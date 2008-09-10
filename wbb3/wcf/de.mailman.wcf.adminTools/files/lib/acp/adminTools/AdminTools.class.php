<?php
/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.adminTools
 */

class AdminTools {

    public function wbbExists() {
        if(!defined('WBB_EXISTS')) {
            if(!defined('WBB_N') || !defined('WBB_DIR')) define('WBB_EXISTS', false);
            else define('WBB_EXISTS', true);
        }
        return WBB_EXISTS;
    }

	public function getSettings($atse_name='') {
	    $ret = array();
		$sql = "SELECT atse_name, atse_value"
		    ."\n  FROM wcf".WCF_N."_admin_tool_setting";
		if(!empty($atse_name)) $sql .= "\n   WHERE atse_name = '".$atse_name."'";
        $result = WCF::getDB()->sendQuery($sql);
		while($row = WCF::getDB()->fetchArray($result)) {
            $ret[$row['atse_name']] = $row['atse_value'];
        }
        return $ret;
	}

    public function getSetting($atse_name) {
        $tmp = self::getSettings($atse_name);
        if(isset($tmp[$atse_name])) $ret = $tmp[$atse_name];
        else $ret = '';
        return $ret;
    }

    public function saveSetting($name, $value) {
        $name = trim($name);
        $value = WCF::getDB()->escapeString($value);
        $sql = "INSERT INTO wcf".WCF_N."_admin_tool_setting"
               ."\n       (atse_name, atse_value)"
               ."\nVALUES ('".$name."', '".$value."')"
               ."\n    ON DUPLICATE KEY UPDATE atse_value = '".$value."'";
        WCF::getDB()->sendQuery($sql);
    }

	public function getAcpPackageID($menuItem) {
	    $ret = 0;
	    $sql = "SELECT packageID FROM wcf".WCF_N."_acp_menu_item WHERE menuItem = '".$menuItem."'";
	    list($ret) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
	    return $ret;
	}

    // delete pms ******************************************
    public function deletePMs($pmIDs) {
        require_once(WCF_DIR.'lib/data/message/attachment/AttachmentsEditor.class.php');

		// delete recipients
		$sql = "DELETE FROM	wcf".WCF_N."_pm_to_user
			WHERE		pmID IN (".$pmIDs.")";
		WCF::getDB()->sendQuery($sql);

		// delete messages
		$sql = "DELETE FROM	wcf".WCF_N."_pm
			WHERE		pmID IN (".$pmIDs.")";
		WCF::getDB()->sendQuery($sql);

		// delete pm hashes
		$sql = "DELETE FROM	wcf".WCF_N."_pm_hash
			WHERE		pmID IN (".$pmIDs.")";
		WCF::getDB()->registerShutdownUpdate($sql);

		// delete attachments
		$attachments = new AttachmentsEditor($pmIDs, 'pm');
		$attachments->deleteAll();
    }

    // delete inactive user ********************************
    public function deleteInactiveUser($days, $exclUser=0, $exclGrp=0) {
        if($days > 0) {
            $userIDs = array();
            $sql = "SELECT userID, username, registrationDate, lastActivityTime"
                ."\n  FROM wcf".WCF_N."_user"
                ."\n WHERE activationCode > 0"
                ."\n   AND lastActivityTime < ".(TIME_NOW - $days * 86400);
            if(!empty($exclUser)) $sql .= "\n   AND userID NOT IN (".$exclUser.")";
            if(!empty($exclGrp)) $sql .= "\n   AND userID NOT IN (SELECT userID FROM wcf".WCF_N."_user_to_groups WHERE groupID IN (".$exclGrp."))";
            $result = WCF::getDB()->sendQuery($sql);
            while($row = WCF::getDB()->fetchArray($result)) {
                $userIDs[] = $row['userID'];
            }
            if(count($userIDs) > 0) {
                require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');
                UserEditor::deleteUsers($userIDs);
            }
        }
    }

    // cron jobs *******************************************
    public function cronIsEnabled() {
        $sql = "SELECT active"
            ."\n  FROM wcf".WCF_N."_cronjobs"
            ."\n WHERE classPath = 'lib/system/cronjob/AdminToolsCronjob.class.php'";
        $cronActive = WCF::getDB()->getFirstRow($sql);
        if(empty($cronActive['active'])) return false;
        else return true;
    }

    public function saveCron($settings) {
        if($settings['cronLogEnabled'] != '1')  $settings['cronLogEnabled'] = '0';
        if($settings['cronStatEnabled'] != '1') $settings['cronStatEnabled'] = '0';
        if($settings['cronDbAnalyze'] != '1')   $settings['cronDbAnalyze'] = '0';
        if($settings['cronDbOptimize'] != '1')  $settings['cronDbOptimize'] = '0';
        if($settings['cronDbBackup'] != '1')    $settings['cronDbBackup'] = '0';

        foreach($settings as $k => $v) {
            self::saveSetting($k, $v);
        }
    }

    public function cronRunDB($analyze=0, $optimize=0, $backup=0) {
    	if(!empty($analyze) || !empty($optimize)) {
            $tables = WCF::getDB()->getTableNames();
            foreach($tables AS $table) {
                if(!empty($analyze))  WCF::getDB()->sendQuery('ANALYZE TABLE '.$table);
                if(!empty($optimize)) WCF::getDB()->sendQuery('OPTIMIZE TABLE '.$table);
            }
    	}
    	if(!empty($backup)) self::cronDbBackup();
    }
    public function cronDbBackup() {
        require_once(WCF_DIR.'lib/system/database/DatabaseDumper.class.php');
        $allTables = WCF::getDB()->getTableNames();
        $tablesEx = DatabaseDumper::getTableStates($allTables);
        $rowCount = 0;
        $tables = array();
        foreach($tablesEx as $table) {
        	$rowCount += $table['Rows'];
        	$tables[] = $table['Name'];
        }
        // comment buffer
        $limit = $rowCount + 10000;

        $offset = -1;
        $backupFile = WCF_DIR.'acp/backup/'.date('YmdHis').'.sql.gz';
        $file = new ZipFile($backupFile, 'wb');
        $loopTimeLimit = 3600;
        $loopStart = time();
        $tableName = '';

        // write header info
        $head = "-- WoltLab Community Framework\n";
        $head .= "-- database: ".WCF::getDB()->getDatabaseName()."\n";
        $head .= "-- generated at ".date('r')."\n\n";
        $head .= "-- DO NOT EDIT THIS FILE\n\n";
        $head .= "-- WCF DATABASE CHARSET\n";
        $head .= "SET NAMES  ".WCF::getDB()->getCharset().";\n\n";
        $file->write($head);
        @set_time_limit(3600);
        DatabaseDumper::export($file, $tables, $limit, $loopTimeLimit, $loopStart, 0, $tableName);
    }

    public function cronCheckMovedThreads($delDays=0) {
        if(!self::wbbExists()) return;
        $sql = "INSERT IGNORE INTO wcf".WCF_N."_admin_tool_moved_threads"
            ."\n       (threadID, movedThreadID, checkedTime)"
            ."\nSELECT threadID, movedThreadID, ".TIME_NOW
            ."\n  FROM wbb".WBB_N."_thread"
            ."\n WHERE movedThreadID > 0";
        WCF::getDB()->sendQuery($sql);

        if($delDays > 0) {
            $delTime = TIME_NOW - $delDays * 86400;
            $sql = "DELETE FROM wbb".WBB_N."_thread"
                ."\n WHERE movedThreadID > 0"
                ."\n   AND threadID IN ("
                ."\n       SELECT threadID"
                ."\n         FROM wcf".WCF_N."_admin_tool_moved_threads"
                ."\n        WHERE checkedTime > 0"
                ."\n          AND checkedTime < ".$delTime
                ."\n       )";
            WCF::getDB()->sendQuery($sql);
        }

        $sql = "DELETE FROM wcf".WCF_N."_admin_tool_moved_threads"
            ."\n WHERE threadID NOT IN (SELECT threadID FROM wbb".WBB_N."_thread)";
        WCF::getDB()->registerShutdownUpdate($sql);
    }

    public function cronThreadArchiveGetBoards() {
        if(!self::wbbExists()) return;
        $ret = $arcBoards = array();
        $boards = self::getSettings('cronThreadArchiveSrc');
        require_once(WBB_DIR.'/lib/data/board/Board.class.php');
        $boardSelect = Board::getBoardSelect(array(), true, true);
        if(!empty($boards['cronThreadArchiveSrc'])) $arcBoards = explode(',', $boards['cronThreadArchiveSrc']);

        $sql = "SELECT boardID, title"
            ."\n  FROM wbb".WBB_N."_board"
            ."\n WHERE boardType != 0";
        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            if(isset($boardSelect[$row['boardID']])) unset($boardSelect[$row['boardID']]);
        }
        $i = 0;
        foreach($boardSelect as $k => $v) {
            $ret[$i]['boardID'] = $k;
            $ret[$i]['title'] = $v;
            if(in_array($k, $arcBoards)) $ret[$i]['SRC'] = true;
            else $ret[$i]['SRC'] = false;
            $i++;
        }
        return $ret;
    }

    public function cronThreadArchive($settings) {
        if(!self::wbbExists()) return;
        if(!is_array($settings) || !count($settings)) return;
        if(!empty($settings['cronThreadArchiveDays']) && !empty($settings['cronThreadArchiveSrc']) && !empty($settings['cronThreadArchiveTgt'])) {
            if(!intval($settings['cronThreadArchiveDays']) > 0) return;
            $arcTime = TIME_NOW - intval($settings['cronThreadArchiveDays']) * 86400;
            $src = $settings['cronThreadArchiveSrc'];
            $tgt = intval($settings['cronThreadArchiveTgt']);
            $tmp = explode(',', $src);
            if(in_array($tgt, $tmp)) return;
            $sql = "UPDATE wbb".WBB_N."_thread"
                ."\n   SET boardID = ".$tgt
                ."\n WHERE lastPostTime < ".$arcTime
                ."\n   AND movedThreadID = 0"
                ."\n   AND boardID IN (".$src.")";
            if($settings['cronThreadArchiveExclPolls'] != '0') $sql .= "\n   AND polls = 0";
            if($settings['cronThreadArchiveExclAnnouncement'] != '0') $sql .= "\n   AND isAnnouncement = 0";
            if($settings['cronThreadArchiveExclSticky'] != '0') $sql .= "\n   AND isSticky = 0";
            if($settings['cronThreadArchiveExclClosed'] != '0') $sql .= "\n   AND isClosed = 0";
            if($settings['cronThreadArchiveExclDeleted'] != '0') $sql .= "\n   AND isDeleted = 0";
            WCF::getDB()->registerShutdownUpdate($sql);
        }
    }

    public function cronRunJournal($pmDelCnt=0, $cLog=0, $cStat=0, $cAdminMail=0) {
        if(!empty($cLog) || !empty($cStat)) {
            require_once(WCF_DIR.'lib/data/mail/Mail.class.php');
            require_once(WCF_DIR.'lib/data/user/User.class.php');
            require_once(WCF_DIR.'lib/system/language/Language.class.php');
            $pR = 45;
            $pL = 35;

            // get default language
            $sql = "SELECT languageID"
                ."\n  FROM wcf".WCF_N."_language"
                ."\n WHERE isDefault = 1";
            $tmp = WCF::getDB()->getFirstRow($sql);
            $lang = ($tmp['languageID'] == WCF::getLanguage()->getLanguageID() ? WCF::getLanguage() : new Language($tmp['languageID']));
            $useStrftime = DateUtil::$useStrftime;
            DateUtil::$useStrftime = ($lang->get('wcf.global.dateMethod') == 'strftime');

            $currentDate        = DateUtil::formatDate($lang->get('wcf.global.dateFormat'), TIME_NOW);
            $previousDayStart   = mktime(0, 0, 0, (int) date("m",TIME_NOW), (int) date("d",TIME_NOW) - 1, (int) date("Y",TIME_NOW));
            $previousDayEnd     = mktime(0, 0, -1, (int) date("m",TIME_NOW), (int) date("d",TIME_NOW), (int) date("Y",TIME_NOW));
            $logDate            = DateUtil::formatDate($lang->get('wcf.global.dateFormat'), $previousDayStart);
            $spacer             = str_repeat('-', 80);
            $mailUserHeader     = "\n".self::str_pad("USER", 26, " ").self::str_pad("USERID", 12, " ", STR_PAD_LEFT)."    ".self::str_pad("REG-DATE", 20, " ")."LAST-ACTIVE"."\n".$spacer;

            $subject = $lang->get('wcf.acp.adminTools.cron.mail.subject', array('PAGE_TITLE' => PAGE_TITLE, '$currentDate' => $currentDate));
            $message = $lang->get('wcf.acp.adminTools.cron.mail.header', array('PAGE_TITLE' => PAGE_TITLE));

            // log -----------------------------------------

            if(!empty($cLog)) {
                $message .= "\n\n".$spacer;
                $message .= "\n".$lang->get('wcf.acp.adminTools.cron.mail.logHeader', array('$logDate' => $logDate));
                $message .= "\n".$spacer;

                // deleted PMs -----------------------------
                if(!empty($pmDelCnt)) {
                    $message .= "\n\n".$lang->get('wcf.acp.adminTools.cron.mail.statsCntDeletedPMs').' '.StringUtil::decodeHTML(StringUtil::formatInteger($pmDelCnt));
                }

                // registrations ---------------------------
                $mailMsg = '';
                $cnt = 0;
                $sql = "SELECT userID"
                    ."\n  FROM wcf".WCF_N."_user"
                    ."\n WHERE registrationDate >= ".$previousDayStart
                    ."\n   AND registrationDate <= ".$previousDayEnd
                    ."\n ORDER BY LOWER(username)";
                $result = WCF::getDB()->sendQuery($sql);
                while($row = WCF::getDB()->fetchArray($result)) {
                    $user = new User($row['userID']);
                    $cnt++;
                    $mailMsg .= "\n".self::str_pad(StringUtil::encodeHTML($user->username), 26, " ")
                                       .self::str_pad($user->userID, 12, " ", STR_PAD_LEFT)
                                       ."    "
                                       .self::str_pad(DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->registrationDate), 20, " ")
                                       .DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->lastActivityTime);
                }

                $message .= "\n\n";
                $message .= $lang->get('wcf.acp.adminTools.cron.mail.registrations').' '.$cnt.$mailUserHeader;
                if(!empty($cnt)) $message .= $mailMsg;
                else $message .= "\n-";

                // user quits ------------------------------
                $mailMsg = '';
                $cnt = 0;
                $sql = "SELECT userID"
                    ."\n  FROM wcf".WCF_N."_user"
                    ."\n WHERE quitStarted > 0"
                    ."\n ORDER BY LOWER(username)";
                $result = WCF::getDB()->sendQuery($sql);
                while($row = WCF::getDB()->fetchArray($result)) {
                    $user = new User($row['userID']);
                    $cnt++;
                    $mailMsg .= "\n".self::str_pad(StringUtil::encodeHTML($user->username), 26, " ")
                                       .self::str_pad($user->userID, 12, " ", STR_PAD_LEFT)
                                       ."    "
                                       .self::str_pad(DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->registrationDate), 20, " ")
                                       .DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->lastActivityTime);
                    $quitStarted = DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->quitStarted);
                    $quitExec = DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->quitStarted + 7 * 86400);
                    $mailMsg .= "\n".self::str_pad(StringUtil::encodeHTML($lang->get('wcf.acp.adminTools.cron.mail.quitInfo', array('$quitStarted' => $quitStarted, '$quitExec' => $quitExec))), $pL + $pR, " ", STR_PAD_LEFT);
                }

                $message .= "\n\n";
                $message .= $lang->get('wcf.acp.adminTools.cron.mail.quit').' '.$cnt.$mailUserHeader;
                if(!empty($cnt)) $message .= $mailMsg;
                else $message .= "\n-";

                // inactive ------------------------------------
                $mailMsg = '';
                $cnt = 0;
                $sql = "SELECT userID"
                    ."\n  FROM wcf".WCF_N."_user"
                    ."\n WHERE activationCode > 0"
                    ."\n ORDER BY LOWER(username)";
                $result = WCF::getDB()->sendQuery($sql);
                while($row = WCF::getDB()->fetchArray($result)) {
                    $user = new User($row['userID']);
                    $cnt++;
                    $mailMsg .= "\n".self::str_pad(StringUtil::encodeHTML($user->username), 26, " ")
                                       .self::str_pad($user->userID, 12, " ", STR_PAD_LEFT)
                                       ."    "
                                       .self::str_pad(DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->registrationDate), 20, " ")
                                       .DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->lastActivityTime);
                }

                $message .= "\n\n";
                $message .= $lang->get('wcf.acp.adminTools.cron.mail.inactives').' '.$cnt.$mailUserHeader;
                if(!empty($cnt)) $message .= $mailMsg;
                else $message .= "\n-";

                // banned --------------------------------------
                $mailMsg = '';
                $cnt = 0;
                $sql = "SELECT userID"
                    ."\n  FROM wcf".WCF_N."_user"
                    ."\n WHERE banned > 0"
                    ."\n ORDER BY LOWER(username)";
                $result = WCF::getDB()->sendQuery($sql);
                while($row = WCF::getDB()->fetchArray($result)) {
                    $user = new User($row['userID']);
                    $cnt++;
                    $mailMsg .= "\n".self::str_pad(StringUtil::encodeHTML($user->username), 26, " ")
                                       .self::str_pad($user->userID, 12, " ", STR_PAD_LEFT)
                                       ."    "
                                       .self::str_pad(DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->registrationDate), 20, " ")
                                       .DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $user->lastActivityTime);
                }

                $message .= "\n\n";
                $message .= $lang->get('wcf.acp.adminTools.cron.mail.banned').' '.$cnt.$mailUserHeader;
                if(!empty($cnt)) $message .= $mailMsg;
                else $message .= "\n-";
            }

            if(!empty($cStat)) {
                // stats -----------------------------------
        		$nStats = array();

                // user
                $sql = "SELECT COUNT(userID) AS user, MAX(userID) AS userMax FROM wcf".WCF_N."_user";
                $tmp = WCF::getDB()->getFirstRow($sql); foreach($tmp AS $k => $v) { $nStats[$k] = $v; }
                $sql = "SELECT SUM(banned) AS userLocked, SUM(disableSignature) AS signLocked FROM wcf".WCF_N."_user";
                $tmp = WCF::getDB()->getFirstRow($sql); foreach($tmp AS $k => $v) { $nStats[$k] = $v; }
                // threads
                if(self::wbbExists()) {
                    $sql = "SELECT COUNT(threadID) AS threads, MAX(threadID) AS threadsMax, SUM(views) AS threadViews FROM wbb".WBB_N."_thread";
                    $tmp = WCF::getDB()->getFirstRow($sql); foreach($tmp AS $k => $v) { $nStats[$k] = $v; }
                    $sql = "SELECT COUNT(threadID) AS threadClosed FROM wbb".WBB_N."_thread WHERE isClosed = 1";
                    $tmp = WCF::getDB()->getFirstRow($sql); foreach($tmp AS $k => $v) { $nStats[$k] = $v; }
                    // posts
                    $sql = "SELECT COUNT(postID) AS posts, MAX(postID) AS postsMax FROM wbb".WBB_N."_post";
                    $tmp = WCF::getDB()->getFirstRow($sql); foreach($tmp AS $k => $v) { $nStats[$k] = $v; }
                    // pm
                }
                $sql = "SELECT COUNT(pmID) AS pms, MAX(pmID) AS pmsMax FROM wcf".WCF_N."_pm";
                $tmp = WCF::getDB()->getFirstRow($sql); foreach($tmp AS $k => $v) { $nStats[$k] = $v; }
                // polls
                $sql = "SELECT COUNT(pollID) AS polls FROM wcf".WCF_N."_poll";
                $tmp = WCF::getDB()->getFirstRow($sql); foreach($tmp AS $k => $v) { $nStats[$k] = $v; }
                // attachments *****************************
                // get decimal point
                $dp = StringUtil::decodeHTML($lang->get('wcf.global.decimalPoint'));
                if(!preg_match('/^[\,\.]{1}$/',$dp)) $dp = ',';
                // get thousands separator
                $tp = StringUtil::decodeHTML($lang->get('wcf.global.thousandsSeparator'));
                if(!preg_match('/^[\,\.\s]{1}$/',$tp)) $tp = ' ';
                $sql = "SELECT COUNT(attachmentID) AS atCnt, SUM(attachmentSize) AS atSize FROM wcf".WCF_N."_attachment WHERE messageID != 0";
                $tmp = WCF::getDB()->getFirstRow($sql); foreach($tmp AS $k => $v) { $nStats[$k] = $v; }
                if(!empty($nStats['atSize'])) $atSize = number_format($nStats['atSize'] / pow(1024, 2), 2, $dp, $tp); // MB
                else $atSize = 0;
                // avatars
                $sql = "SELECT COUNT(avatarID) AS avatars FROM wcf".WCF_N."_avatar WHERE userID > 0";
                $tmp = WCF::getDB()->getFirstRow($sql); foreach($tmp AS $k => $v) { $nStats[$k] = $v; }
                // record
                $nStats['record'] = USERS_ONLINE_RECORD;
                $nStats['recordTime'] = USERS_ONLINE_RECORD_TIME;

                // mail message
                $message .= "\n\n".$spacer;
                $message .= "\n".$lang->get('wcf.acp.adminTools.cron.mail.statsHeader');
                $message .= "\n".$spacer;

                $di = self::getDiskInfo();
                if(is_array($di) && count($di)) {
                    $totalSpace = StringUtil::decodeHTML(StringUtil::formatNumeric($di['TOTAL_SPACE'])).' GB';
                    $freeSpace = StringUtil::decodeHTML(StringUtil::formatNumeric($di['FREE_SPACE'])).' GB ('.StringUtil::decodeHTML(StringUtil::formatNumeric($di['FREE_QUOTA'])).'%)';
                    $usedSpace = StringUtil::decodeHTML(StringUtil::formatNumeric($di['USED_SPACE'])).' GB ('.StringUtil::decodeHTML(StringUtil::formatNumeric($di['USED_QUOTA'])).'%)';
                    $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.diskTotalSpace'), $pR, " ").self::str_pad($totalSpace, $pL, " ", STR_PAD_LEFT);
                    $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.diskFreeSpace'), $pR, " ").self::str_pad($freeSpace, $pL, " ", STR_PAD_LEFT);
                    $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.diskUsedSpace'), $pR, " ").self::str_pad($usedSpace, $pL, " ", STR_PAD_LEFT);
                }

                if(isset($nStats['user']))          $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntMembers'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['user'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['userMax']))       $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntMembersMax'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['userMax'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['userLocked']))    $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntMembersLocked'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['userLocked'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['signLocked']))    $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntSignLocked'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['signLocked'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['threads']))       $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntThreads'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['threads'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['threadsMax']))    $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntThreadsMax'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['threadsMax'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['threadClosed']))  $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntThreadsLocked'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['threadClosed'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['threadViews']))   $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntThreadsView'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['threadViews'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['posts']))         $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntPosts'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['posts'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['postsMax']))      $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntPostsMax'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['postsMax'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['pms']))           $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntPMs'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['pms'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['pmsMax']))        $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntPMsMax'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['pmsMax'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['polls']))         $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntPolls'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['polls'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['atCnt']))         $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntAttachments'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['atCnt'])).' ('.$atSize.' MB)', $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['avatars']))       $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntAvatars'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['avatars'])), $pL, " ", STR_PAD_LEFT);
                if(isset($nStats['record']))        $message .= "\n".self::str_pad($lang->get('wcf.acp.adminTools.cron.mail.statsCntRecord'), $pR, " ").self::str_pad(StringUtil::decodeHTML(StringUtil::formatInteger($nStats['record'])).' ('.DateUtil::formatDate($lang->get('wcf.global.timeFormat'), $nStats['recordTime']).')', $pL, " ", STR_PAD_LEFT);
            }

            // sendmail ------------------------------------
            if(empty($cAdminMail)) $sendTo = array(MAIL_FROM_NAME => MAIL_FROM_ADDRESS);
            else $sendTo = MAIL_ADMIN_ADDRESS;
            $mail = new Mail($sendTo, $subject, $message);
            $mail->send();
            // reset datetime
            DateUtil::$useStrftime = $useStrftime;
        }
    }


    // boards **********************************************
    public function getBoards() {
        if(!self::wbbExists()) return array();
        require_once(WBB_DIR.'/lib/data/board/Board.class.php');
        return Board::getBoardSelect(array(), true, true);
    }
    public function syncBoard($arg=array()) {
        if(!self::wbbExists()) return;
        if(!empty($arg['boardSrcID']) && !empty($arg['boardTgtID']) && $arg['boardSrcID'] != $arg['boardTgtID']) {
            if(!empty($arg['boardUser'])) {
                $sql = "DELETE FROM wbb".WBB_N."_board_to_user"
                    ."\n WHERE boardID = ".$arg['boardTgtID'];
                WCF::getDB()->sendQuery($sql);
                $sql = "INSERT INTO wbb".WBB_N."_board_to_user"
                    ."\n       (boardID, userID, canViewBoard, canEnterBoard, canReadThread, canStartThread, canReplyThread, canStartPoll"
                    ."\n       ,canReplyOwnThread, canStartThreadWithoutModeration, canReplyThreadWithoutModeration, canVotePoll"
                    ."\n       ,canRateThread, canUsePrefix, canUploadAttachment, canDownloadAttachment, canDeleteOwnPost, canEditOwnPost)"
                    ."\nSELECT ".$arg['boardTgtID'].", userID, canViewBoard, canEnterBoard, canReadThread, canStartThread, canReplyThread, canStartPoll"
                    ."\n       ,canReplyOwnThread, canStartThreadWithoutModeration, canReplyThreadWithoutModeration, canVotePoll"
                    ."\n       ,canRateThread, canUsePrefix, canUploadAttachment, canDownloadAttachment, canDeleteOwnPost, canEditOwnPost"
                    ."\n  FROM wbb".WBB_N."_board_to_user"
                    ."\n WHERE boardID = ".$arg['boardSrcID'];
                WCF::getDB()->sendQuery($sql);
            }
            if(!empty($arg['boardGroups'])) {
                $sql = "DELETE FROM wbb".WBB_N."_board_to_group"
                    ."\n WHERE boardID = ".$arg['boardTgtID'];
                WCF::getDB()->sendQuery($sql);
                $sql = "INSERT INTO wbb".WBB_N."_board_to_group"
                    ."\n       (boardID, groupID, canViewBoard, canEnterBoard, canReadThread, canStartThread, canReplyThread, canStartPoll"
                    ."\n       ,canReplyOwnThread, canStartThreadWithoutModeration, canReplyThreadWithoutModeration, canVotePoll"
                    ."\n       ,canRateThread, canUsePrefix, canUploadAttachment, canDownloadAttachment, canDeleteOwnPost, canEditOwnPost)"
                    ."\nSELECT ".$arg['boardTgtID'].", groupID, canViewBoard, canEnterBoard, canReadThread, canStartThread, canReplyThread, canStartPoll"
                    ."\n       ,canReplyOwnThread, canStartThreadWithoutModeration, canReplyThreadWithoutModeration, canVotePoll"
                    ."\n       ,canRateThread, canUsePrefix, canUploadAttachment, canDownloadAttachment, canDeleteOwnPost, canEditOwnPost"
                    ."\n  FROM wbb".WBB_N."_board_to_group"
                    ."\n WHERE boardID = ".$arg['boardSrcID'];
                WCF::getDB()->sendQuery($sql);
            }
            if(!empty($arg['boardMods'])) {
                $sql = "DELETE FROM wbb".WBB_N."_board_moderator"
                    ."\n WHERE boardID = ".$arg['boardTgtID'];
                WCF::getDB()->sendQuery($sql);
                $sql = "INSERT INTO wbb".WBB_N."_board_moderator"
                    ."\n       (boardID, userID, groupID, canDeleteThread, canDeleteThreadCompletely, canCloseThread, canEnableThread, canMoveThread"
                    ."\n       ,canCopyThread, canMergeThread, canEditPost, canDeletePost, canDeletePostCompletely, canClosePost"
                    ."\n       ,canEnablePost, canMovePost, canCopyPost, canReplyClosedThread, canPinThread, canStartAnnouncement)"
                    ."\nSELECT ".$arg['boardTgtID'].", userID, groupID, canDeleteThread, canDeleteThreadCompletely, canCloseThread, canEnableThread, canMoveThread"
                    ."\n       ,canCopyThread, canMergeThread, canEditPost, canDeletePost, canDeletePostCompletely, canClosePost"
                    ."\n       ,canEnablePost, canMovePost, canCopyPost, canReplyClosedThread, canPinThread, canStartAnnouncement"
                    ."\n  FROM wbb".WBB_N."_board_moderator"
                    ."\n WHERE boardID = ".$arg['boardSrcID'];
                WCF::getDB()->sendQuery($sql);
            }
            // reset cache
            WCF::getCache()->clear(WBB_DIR.'cache/', 'cache.board*', true);
            // reset all sessions
            Session::resetSessions();
        }
    }

    // user groups *****************************************
    public function getUgrps($skipAdmins=true) {
        $sql = "SELECT *"
            ."\n  FROM wcf".WCF_N."_group"
            ."\n WHERE 1 = 1";
        if($skipAdmins) $sql .= "\n   AND groupID != 4";
        $sql .= "\n ORDER BY LOWER(groupName)";
        return WCF::getDB()->getResultList($sql);
    }
    public function syncUgrps($arg=array()) {
        if(!empty($arg['ugrpSrcID']) && !empty($arg['ugrpTgtID']) && $arg['ugrpSrcID'] != $arg['ugrpTgtID']) {
            $sql = "DELETE FROM wcf".WCF_N."_group_option_value"
                ."\n WHERE groupID = ".$arg['ugrpTgtID'];
            WCF::getDB()->sendQuery($sql);
            $sql = "INSERT INTO wcf".WCF_N."_group_option_value"
                ."\n       (groupID, optionID, optionValue)"
                ."\nSELECT ".$arg['ugrpTgtID'].", optionID, optionValue"
                ."\n  FROM wcf".WCF_N."_group_option_value"
                ."\n WHERE groupID = ".$arg['ugrpSrcID'];
            WCF::getDB()->sendQuery($sql);

            if(!empty($arg['ugrpUser'])) {
                $sql = "DELETE FROM wcf".WCF_N."_user_to_groups"
                    ."\n WHERE groupID = ".$arg['ugrpTgtID'];
                WCF::getDB()->sendQuery($sql);
                $sql = "INSERT INTO wcf".WCF_N."_user_to_groups"
                    ."\n       (userID, groupID)"
                    ."\nSELECT userID, ".$arg['ugrpTgtID']
                    ."\n  FROM wcf".WCF_N."_user_to_groups"
                    ."\n WHERE groupID = ".$arg['ugrpSrcID'];
                WCF::getDB()->sendQuery($sql);
            }

            if(self::wbbExists() && !empty($arg['ugrpBoards'])) {
                $sql = "DELETE FROM wbb".WBB_N."_board_to_group"
                    ."\n WHERE groupID = ".$arg['ugrpTgtID'];
                WCF::getDB()->sendQuery($sql);
                $sql = "INSERT INTO wbb".WBB_N."_board_to_group"
                    ."\n       (boardID, groupID, canViewBoard, canEnterBoard, canReadThread, canStartThread, canReplyThread, canStartPoll"
                    ."\n       ,canReplyOwnThread, canStartThreadWithoutModeration, canReplyThreadWithoutModeration, canVotePoll"
                    ."\n       ,canRateThread, canUsePrefix, canUploadAttachment, canDownloadAttachment, canDeleteOwnPost, canEditOwnPost)"
                    ."\nSELECT boardID, ".$arg['ugrpTgtID'].", canViewBoard, canEnterBoard, canReadThread, canStartThread, canReplyThread, canStartPoll"
                    ."\n       ,canReplyOwnThread, canStartThreadWithoutModeration, canReplyThreadWithoutModeration, canVotePoll"
                    ."\n       ,canRateThread, canUsePrefix, canUploadAttachment, canDownloadAttachment, canDeleteOwnPost, canEditOwnPost"
                    ."\n  FROM wbb".WBB_N."_board_to_group"
                    ."\n WHERE groupID = ".$arg['ugrpSrcID'];
                WCF::getDB()->sendQuery($sql);
            }
        }
    }

    // prefixes ********************************************
    public function getPrefBoards() {
        if(!self::wbbExists()) return array();
        $ret = array();
        require_once(WBB_DIR.'/lib/data/board/Board.class.php');
        $boardSelect = Board::getBoardSelect(array(), true, true);

        $sql = "SELECT boardID, title"
            ."\n  FROM wbb".WBB_N."_board"
            ."\n WHERE prefixes IS NULL"
            ."\n    OR prefixes = ''";
        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            if(isset($boardSelect[$row['boardID']])) unset($boardSelect[$row['boardID']]);
        }
        foreach($boardSelect as $k => $v) $ret[$k] = $v;
        return $ret;
    }

    public function syncPrefBoard($arg=array()) {
        if(!self::wbbExists()) return;
        if(!empty($arg['boardPrefSrcID']) && !empty($arg['boardPrefTgtID']) && $arg['boardPrefSrcID'] != $arg['boardPrefTgtID']) {
            $sql = "SELECT prefixes FROM wbb".WBB_N."_board WHERE boardID = ".$arg['boardPrefSrcID'];
            list($prefixes) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
            if(!empty($prefixes)) {
                $prefixes = WCF::getDB()->escapeString($prefixes);
                $sql = "UPDATE wbb".WBB_N."_board"
                    ."\n   SET prefixes = '".$prefixes."'"
                    ."\n WHERE boardID = ".$arg['boardPrefTgtID'];
                WCF::getDB()->sendQuery($sql);
            }
        }
    }

    // cache ***********************************************
    public function cacheDel($cacheDel, $cacheTpl, $cacheLang, $cacheOpt, $cacheRSS) {
        $ret = array();
        $cnt = 0;
        if($cacheDel) {
            if(self::wbbExists()) {
                $dir = WBB_DIR.'cache';
                if(is_dir($dir)) {
                    chdir($dir);
                    if($dh = opendir($dir)) {
                        while($file = readdir($dh)) {
                            if(preg_match("/\.php$/i",$file) && @unlink($file)) $cnt++;
                        }
                        closedir($dh);
                    }
                }
            }
            $dir = WCF_DIR.'cache';
            if(is_dir($dir)) {
                chdir($dir);
                if($dh = opendir($dir)) {
                    while($file = readdir($dh)) {
                        if(preg_match("/\.php$/i",$file) && @unlink($file)) $cnt++;
                    }
                    closedir($dh);
                }
            }
            $ret['cacheDel'] = $cnt;
        }

        if($cacheTpl) {
            $cnt = 0;
            $dir = WCF_DIR.'acp/templates/compiled';
            if(is_dir($dir)) {
                chdir($dir);
                if($dh = opendir($dir)) {
                    while($file = readdir($dh)) {
                        if(preg_match("/\.php$/i",$file) && @unlink($file)) $cnt++;
                    }
                    closedir($dh);
                }
            }
            $dir = WCF_DIR.'templates/compiled';
            if(is_dir($dir)) {
                chdir($dir);
                if($dh = opendir($dir)) {
                    while($file = readdir($dh)) {
                        if(preg_match("/\.php$/i",$file) && @unlink($file)) $cnt++;
                    }
                    closedir($dh);
                }
            }
            $ret['cacheTpl'] = $cnt;
        }

        if($cacheLang) {
            $cnt = 0;
            $dir = WCF_DIR.'language';
            if(is_dir($dir)) {
                chdir($dir);
                if($dh = opendir($dir)) {
                    while($file = readdir($dh)) {
                        if(preg_match("/\d.*\.php$/i",$file) && @unlink($file)) $cnt++;
                    }
                    closedir($dh);
                }
            }
            $ret['cacheLang'] = $cnt;
        }

        if(self::wbbExists() && $cacheRSS) {
            $cnt = 0;
            $dir = WBB_DIR.'lib/data/boxes/SimplePieNewsReader/cache';
            if(is_dir($dir)) {
                chdir($dir);
                if($dh = opendir($dir)) {
                    while($file = readdir($dh)) {
                        if(@unlink($file)) $cnt++;
                    }
                    closedir($dh);
                }
            }
            $ret['cacheRSS'] = $cnt;
        }

        if(self::wbbExists() && $cacheOpt) {
            $cnt = 0;
            if(is_file(WBB_DIR.'options.inc.php') && @unlink(WBB_DIR.'options.inc.php')) {
                $cnt++;
            }
            $ret['cacheOpt'] = $cnt;
        }
        return $ret;
    }

    // lost and found **************************************
    public function getLostAndFound($type) {
        $LOST = array();
        $i = 0;
        if($type == 'lostAndFoundWbbF') {
            chdir(WCF_DIR.'attachments');
            $dh=opendir(WCF_DIR.'attachments');
            $sql = "SELECT COUNT(*) cnt FROM wcf".WCF_N."_attachment WHERE attachmentID = %d";
            while($file = readdir ($dh)) {
            	if(preg_match("/^(attachment|thumbnail).*/",$file) && $file != '.' && $file != '..' && $file != '.htaccess' && !preg_match("/^.*\.php$/",$file)) {
            		$cnt = 0;
            		$attachmentID = (int) preg_replace("/.*\-(\d+)$/", "$1", $file);
            		if($attachmentID > 0) {
                        $row = WCF::getDB()->getFirstRow(sprintf($sql,$attachmentID));
                        if($row['cnt'] == 0) {
                            $LOST[$i]['DELVAL'] = urlencode($file);
                        	$LOST[$i]['FILE'] = $file;
                        	$LOST[$i]['SIZE'] = round((filesize($file) / 1024),2).' kB';
                        	$LOST[$i]['TIME'] = filemtime($file);
                        	$LOST[$i]['USER'] = '&raquo;Unbekannt&laquo;';
                        	$i++;
                        }
                    }
            	}
            }
            closedir($dh);
        }
        else if($type == 'lostAndFoundWbbD') {
            $sql = "SELECT a.*, b.username FROM wcf".WCF_N."_attachment a JOIN wcf".WCF_N."_user b ON (b.userID = a.userID) ORDER BY a.attachmentID";
            $result = WCF::getDB()->sendQuery($sql);
            while($row = WCF::getDB()->fetchArray($result)) {
			    if(!is_file(WCF_DIR.'attachments/attachment-'.$row['attachmentID'])) {
                    $LOST[$i]['DELVAL'] = $row['attachmentID'];
                    $LOST[$i]['FILE'] = 'attachment-'.$row["attachmentID"];
                    $LOST[$i]['SIZE'] = round(($row['attachmentSize'] / 1024),2).' kB';
                    $LOST[$i]['TIME'] = $row['uploadTime'];
			        if(empty($row['username'])) $LOST[$i]['USER'] = '&raquo;Unbekannt&laquo;';
                    else $LOST[$i]['USER'] = StringUtil::encodeHTML($row['username']);
				    $i++;
			    }
            }
        }
        else if($type == 'lostAndFoundWbbB') {
            chdir(WCF_DIR.'acp/backup');
            $dh=opendir(WCF_DIR.'acp/backup');
            while($file = readdir ($dh)) {
            	if($file != '.' && $file != '..' && $file != '.htaccess' && !is_dir($file)) {
                    $LOST[$i]['DELVAL'] = urlencode($file);
                    $LOST[$i]['FILE'] = '<a href="index.php?form=AdminToolsLostAndFound&amp;show=downloadFile&amp;fileName='.$file.'&amp;packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED.'">'.$file.'</a>';
                    $LOST[$i]['SIZE'] = round((filesize($file) / 1024),2).' kB';
                    $LOST[$i]['TIME'] = filemtime($file);
                    $LOST[$i]['USER'] = '&raquo;Unbekannt&laquo;';
                    $i++;
            	}
            }
            closedir($dh);
        }
        return $LOST;
    }
    public function getLostAndFoundDelete($type, $del=array()) {
        if($type == 'lostAndFoundWbbF') {
            chdir(WCF_DIR.'attachments');
            $dh=opendir(WCF_DIR.'attachments');
            foreach($del as $file) {
            	$file = urldecode($file);
            	unlink($file);
            }
            rewinddir($dh);
            closedir($dh);
        }
        else if($type == 'lostAndFoundWbbD') {
            foreach($del as $file) {
                WCF::getDB()->sendQuery("DELETE FROM wcf".WCF_N."_attachment WHERE attachmentID =".$file);
            }
        }
        else if($type == 'lostAndFoundWbbB') {
            chdir(WCF_DIR.'acp/backup');
            $dh=opendir(WCF_DIR.'acp/backup');
            foreach($del as $file) {
            	$file = urldecode($file);
            	unlink($file);
            }
            rewinddir($dh);
            closedir($dh);
        }
    }

    // user options ****************************************
    public function getUserOptions() {
        $sql = "SELECT *"
            ."\n  FROM wcf".WCF_N."_user_option"
            ."\n WHERE optionType = 'boolean'"
            ."\n   AND categoryName LIKE 'settings.%'"
            ."\n ORDER BY optionName";
        return WCF::getDB()->getResultList($sql);
    }

    public function saveUserOptions($arg=array()) {
        if(isset($arg['userOptionSet']) && preg_match('/^[0|1]$/',$arg['userOptionSet']) && !empty($arg['optionID'])) {
            $set = intval($arg['userOptionSet']);
            $sql = "UPDATE wcf".WCF_N."_user_option_value"
                ."\n   SET userOption".$arg['optionID']." = ".$set;
            if(!empty($arg['userOptionExclUgrps'])) {
                $sql .= "\n WHERE userID NOT IN (SELECT userID FROM wcf".WCF_N."_user_to_groups WHERE groupID IN (".$arg['userOptionExclUgrps']."))";
            }
            if(isset($arg['userOptionExclUgrps'])) self::saveSetting('userOptionExclUgrps', $arg['userOptionExclUgrps']);
            WCF::getDB()->registerShutdownUpdate($sql);
            require_once(WCF_DIR.'lib/system/session/UserSession.class.php');
            Session::resetSessions();
        }
    }

    // spiders *********************************************
    public function getSpiders() {
        $sql = "SELECT *"
            ."\n  FROM wcf".WCF_N."_admin_tool_spider"
            ."\n ORDER BY spiderName";
        return WCF::getDB()->getResultList($sql);
    }
    public function getSpider($spiderID) {
        $sql = "SELECT *"
            ."\n  FROM wcf".WCF_N."_admin_tool_spider"
            ."\n WHERE spiderID = ".$spiderID;
        return WCF::getDB()->getFirstRow($sql);
    }
    public function saveSpider($spiderID, $spiderCur) {
        $spiderIdentifier   = (!empty($spiderCur['spiderIdentifier'])   ? "'".WCF::getDB()->escapeString(StringUtil::toLowerCase($spiderCur['spiderIdentifier']))."'"    : 'NULL');
        $spiderName         = (!empty($spiderCur['spiderName'])         ? "'".WCF::getDB()->escapeString($spiderCur['spiderName'])."'"          : 'NULL');
        $spiderUrl          = (!empty($spiderCur['spiderUrl'])          ? "'".WCF::getDB()->escapeString($spiderCur['spiderUrl'])."'"           : "''");

        if(!empty($spiderID)) {
            $sql = "UPDATE wcf".WCF_N."_admin_tool_spider"
                ."\n   SET spiderIdentifier = ".$spiderIdentifier
                ."\n      ,spiderName = ".$spiderName
                ."\n      ,spiderUrl = ".$spiderUrl
                ."\n WHERE spiderID = ".$spiderID;
        } else {
            $sql = "INSERT INTO wcf".WCF_N."_admin_tool_spider"
                ."\n       (spiderIdentifier, spiderName, spiderUrl)"
                ."\nVALUES (".$spiderIdentifier.", ".$spiderName.", ".$spiderUrl.")";
        }
        WCF::getDB()->sendQuery($sql);
    }
    public function deleteSpider($spiderID) {
        $sql = "DELETE FROM wcf".WCF_N."_admin_tool_spider"
            ."\n WHERE spiderID = ".$spiderID;
        WCF::getDB()->sendQuery($sql);
    }
    public function syncSpider($manSync=false) {
        if($manSync) {
            require_once(WCF_DIR.'lib/system/cronjob/RefreshSearchRobotsCronjob.class.php');
            RefreshSearchRobotsCronjob::execute(null);
        }
        $sql = "INSERT IGNORE INTO wcf".WCF_N."_spider"
            ."\n       (spiderIdentifier, spiderName, spiderURL)"
            ."\nSELECT a.spiderIdentifier, a.spiderName, a.spiderUrl"
            ."\n  FROM wcf".WCF_N."_admin_tool_spider a";
        WCF::getDB()->sendQuery($sql);
        WCF::getCache()->clear(WCF_DIR.'cache', 'cache.spiders.php');
    }
    public function spiderCnt() {
        $sql = "SELECT"
            ."\n        (SELECT COUNT(spiderID) FROM wcf".WCF_N."_admin_tool_spider) cntOwn"
            ."\n       ,(SELECT COUNT(o.spiderID) FROM wcf".WCF_N."_admin_tool_spider o, wcf".WCF_N."_spider a WHERE o.spiderIdentifier = a.spiderIdentifier) cntSyn"
            ."\n       ,(SELECT COUNT(spiderID) FROM wcf".WCF_N."_spider) cntAll";
        return WCF::getDB()->getFirstRow($sql);
    }

    public function validateSpiderExists($spiderID, $spiderCur) {
        $ret = true;
        $spiderIdentifier = (!empty($spiderCur['spiderIdentifier']) ? "'".WCF::getDB()->escapeString(StringUtil::toLowerCase($spiderCur['spiderIdentifier']))."'"  : 'NULL');
        $sql = "SELECT COUNT(spiderID) AS cnt"
            ."\n  FROM wcf".WCF_N."_admin_tool_spider"
            ."\n WHERE spiderIdentifier = ".$spiderIdentifier;
        if(!empty($spiderID)) {
            $sql .= "\n   AND spiderID != ".$spiderID;
        }
        $tmp = WCF::getDB()->getFirstRow($sql);
        if(empty($tmp['cnt'])) $ret = false;
        return $ret;
    }

    // phpinfo *********************************************
    public function parsePHPConfig() {
        $matches = array();
        $ret = '';
        ob_start();
        phpinfo();
        $info = ob_get_clean();
        preg_match ("/.*<body>(.*)<\/body>/s", $info, $matches);
        if(isset($matches[1])) $ret = $matches[1];
        return $ret;
    }


    // links ***********************************************
    public function getLinks() {
        $sql = "SELECT *"
            ."\n  FROM wcf".WCF_N."_acp_menu_item"
            ."\n WHERE parentMenuItem = 'wcf.acp.menu.link.adminTools.userLinks'"
            ."\n ORDER BY showOrder, menuItem";
        return WCF::getDB()->getResultList($sql);
    }
    public function getLink($menuItemID) {
        $ret = array();
        $sql = "SELECT *"
            ."\n  FROM wcf".WCF_N."_acp_menu_item"
            ."\n WHERE menuItemID = ".$menuItemID;
        $ret = WCF::getDB()->getFirstRow($sql);
        if(!empty($ret['menuItemLink'])) {
            $tmp = array();
            parse_str($ret['menuItemLink'], $tmp);
            if(isset($tmp['url']))      $ret['url'] = $tmp['url'];
            if(isset($tmp['target']))   $ret['target'] = $tmp['target'];
        }
        return $ret;
    }
    public function validateLinkExists($menuItemID, $menuItem) {
        $ret = true;
        $menuItem = (!empty($menuItem) ? "'".WCF::getDB()->escapeString($menuItem)."'"  : 'NULL');
        $sql = "SELECT COUNT(menuItemID) AS cnt"
            ."\n  FROM wcf".WCF_N."_acp_menu_item"
            ."\n WHERE menuItem = ".$menuItem;
        if(!empty($menuItemID)) {
            $sql .= "\n   AND menuItemID != ".$menuItemID;
        }
        $tmp = WCF::getDB()->getFirstRow($sql);
        if(!empty($tmp['cnt'])) $ret = false;
        return $ret;
    }
    public function saveLink($menuItemID=0, $data=array()) {
        $ret = 0;
        $menuItem       = (!empty($data['menuItem']) ? "'".WCF::getDB()->escapeString($data['menuItem'])."'" : 'NULL');
        $showOrder      = (!isset($data['showOrder']) ? 0 : $data['showOrder']);
        $linkTarget     = (empty($data['linkTarget']) ? '_iframe' : $data['linkTarget']);
        $menuItemLink   = (!empty($data['menuItemLink']) ? $data['menuItemLink'] : '');
        $url = 'index.php?page=AdminToolsLink&url='.$menuItemLink.'&target='.$linkTarget;
        $url = "'".WCF::getDB()->escapeString($url)."'";
        if($menuItemID > 0) {
            $sql = "UPDATE wcf".WCF_N."_acp_menu_item"
                ."\n   SET menuItem = ".$menuItem
                ."\n      ,menuItemLink = ".$url
                ."\n      ,showOrder = ".$showOrder
                ."\n WHERE menuItemID = ".$menuItemID;
        } else {
            $packageID = self::getAcpPackageID('wcf.acp.menu.link.adminTools');
            $sql = "INSERT INTO wcf".WCF_N."_acp_menu_item"
                ."\n       (packageID, menuItem, parentMenuItem, menuItemLink, menuItemIcon, showOrder)"
                ."\nVALUES (".$packageID.", ".$menuItem.", 'wcf.acp.menu.link.adminTools.userLinks', ".$url.", 'icon/adminToolsLinkM.png', ".$showOrder.")";
        }
        WCF::getDB()->sendQuery($sql);
        if($menuItemID > 0) $ret = $menuItemID;
        else $ret = WCF::getDB()->getInsertID();
        WCF::getCache()->clear(WCF_DIR.'cache', 'cache.menu-*.php', true);
        return $ret;
    }
    public function deleteLink($menuItemID) {
        if(!empty($menuItemID)) {
            $sql = "DELETE FROM wcf".WCF_N."_acp_menu_item"
                ."\n WHERE menuItemID = ".$menuItemID
                ."\n   AND parentMenuItem = 'wcf.acp.menu.link.adminTools.userLinks'";
            WCF::getDB()->sendQuery($sql);
            WCF::getCache()->clear(WCF_DIR.'cache', 'cache.menu-*.php', true);
        }
    }
    public function getIframeSettings() {
        $ret = array();
        $sql = "SELECT * FROM wcf".WCF_N."_admin_tool_setting WHERE atse_name LIKE 'linkSettings.iframe.%'";
        $result = WCF::getDB()->sendQuery($sql);
		while($row = WCF::getDB()->fetchArray($result)) {
            list($d1, $d2, $idx) = preg_split('/\./', $row['atse_name'], 3);
            $ret[$idx] = $row['atse_value'];
        }
        return $ret;
    }
    public function saveIframeSettings($data) {
        $atse = self::getIframeSettings();
        foreach($atse as $k => $v) {
            if(isset($data[$k])) {
                $val = trim($data[$k]);
                if($val == ';') $val = '';
                if($val != '' && substr($val, -1) != ';') $val .= ';';
                self::saveSetting('linkSettings.iframe.'.$k, $val);
            }
        }
    }

    // disk info *******************************************
    // $pow = 1 == KB; $pow = 2 == MB; $pow = 3 == GB
    public function getDiskInfo($pow=3, $dec=2) {
        $ret = array();
        if(function_exists('disk_free_space') && function_exists('disk_total_space')) {
            $root = '';
            if($tmp = @disk_total_space($_SERVER["DOCUMENT_ROOT"])) $root = $_SERVER["DOCUMENT_ROOT"];
            else if(self::wbbExists() && $tmp = @disk_total_space(WBB_DIR)) $root = WBB_DIR;
            if($root) {
                $ret['TOTAL_SPACE'] = round(disk_total_space($root) / pow(1024, $pow), $dec);
                $ret['FREE_SPACE']  = round(disk_free_space($root) / pow(1024, $pow), $dec);
                $ret['USED_SPACE']  = round($ret['TOTAL_SPACE'] - $ret['FREE_SPACE'], $dec);
                if($ret['TOTAL_SPACE'] > 0) {
                    $ret['FREE_QUOTA'] = round($ret['FREE_SPACE'] * 100 / $ret['TOTAL_SPACE'], $dec);
                    $ret['USED_QUOTA'] = round($ret['USED_SPACE'] * 100 / $ret['TOTAL_SPACE'], $dec);
                } else {
                    $ret['FREE_QUOTA'] = $ret['USED_QUOTA'] = 0;
                }
            }
        }
        return $ret;
    }

    // *****************************************************
    // validate functions **********************************
    public function validateCommaSeparatedIntList($val) {
        $ret = true;
        $vArr = explode(',', $val);
        foreach($vArr as $k => $int) {
            $int = trim($int);
            if(!preg_match('/^[0-9]+$/', $int)) {
                $ret = false;
                break;
            }
        }
        return $ret;
    }
    public function validateInt($val) {
        if(preg_match('/^[0-9]+$/', $val)) return true;
        else return false;
    }

    // *****************************************************
    // converters

    // UTF-8 considered
    public static function str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT) {
        $str = '';
        $length = 0;
        if(preg_match('/./u', $input)) $length = intval($pad_length - preg_match_all('/./u', $input, $dummy));
        else $str = str_pad($input, $pad_length, $pad_string, $pad_type);
        if($length > 0) {
            switch($pad_type) {
                case STR_PAD_LEFT:
                    $str = str_repeat($pad_string, $length).$input;
                    break;
                case STR_PAD_BOTH:
                    $str = str_repeat($pad_string, floor($length / 2));
                    $str .= $input;
                    $str .= str_repeat($pad_string, ceil($length / 2));
                    break;
                default:
                    $str = $input.str_repeat($pad_string, $length);
            }
        }
        return $str;
    }
}
?>
