<?php

require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * $Id$
 * @package de.mailman.wcf.userWantedPoster
 * @author  MailMan (http://wbb3addons.ump2002.net)
 */

class UserWantedPosterData {

	protected $userID = 0;
	protected $user;

	/**
	 * @see User::__construct()
	 */
	public function __construct($userID = NULL)
	{
		$this->userID = $userID;
	}

	public function modEntry($text,$enableSmilies,$enableHtml,$enableBBCodes)
	{
		$text = WCF::getDB()->escapeString($text);
		$curDate = time();
        $eExists = WCF::getDB()->getFirstRow("SELECT COUNT(*) AS CNT FROM wcf".WCF_N."_user_wanted_poster WHERE userID = ".$this->userID);
        if(!empty($eExists['CNT'])) {
            $sql = "UPDATE wcf".WCF_N."_user_wanted_poster"
                ."\n   SET text = '".$text."'"
                ."\n      ,updateDate = ".$curDate
                ."\n      ,enableSmilies = ".$enableSmilies
                ."\n      ,enableHtml = ".$enableHtml
                ."\n      ,enableBBCodes = ".$enableBBCodes
                ."\n WHERE userID = ".$this->userID;
        } else {
            $sql = "INSERT INTO wcf".WCF_N."_user_wanted_poster"
                ."\n(userID, text, insertDate, updateDate, enableSmilies, enableHtml, enableBBCodes)"
                ."\nVALUES (".$this->userID.", '".$text."', ".$curDate.", ".$curDate.", ".$enableSmilies.", ".$enableHtml.", ".$enableBBCodes.")";
        }
		WCF::getDB()->sendQuery($sql);

        // admin pm notifier...
        if(USERWANTEDPOSTER_PMNOTIFIERUGRP || USERWANTEDPOSTER_PMNOTIFIERUSER) {
            require_once(WCF_DIR.'lib/data/message/pm/PMEditor.class.php');
            require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
            $notify = array();
            $u = USERWANTEDPOSTER_PMNOTIFIERUSER;
            $g = USERWANTEDPOSTER_PMNOTIFIERUGRP;

            $sql = "SELECT username FROM wcf".WCF_N."_user WHERE userID = ".$this->userID;
            list($username) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);

            if(!empty($u)) {
                $tmp = explode(',', $u);
                foreach($tmp as $k => $v) $notify[] = intval(trim($v));
            }
            if(!empty($g)) {
                $sql = "SELECT DISTINCT userID"
                    ."\n  FROM wcf".WCF_N."_user_to_groups"
                    ."\n WHERE groupID IN (".$g.")";
                $result = WCF::getDB()->sendQuery($sql);
                while($row = WCF::getDB()->fetchArray($result)) {
                    if(!in_array($row['userID'], $notify)) $notify[] = $row['userID'];
                }
            }
            foreach($notify as $k => $userID) {
                if(!empty($userID) && $userID > 0) {
                    $user = new UserProfile($userID,null,null,null);
                   	$lang = ($user->languageID == WCF::getLanguage()->getLanguageID() ? WCF::getLanguage() : new Language($user->languageID));
                   	$subject = $lang->get('wcf.user.wantedPoster.pmAdminNotifier.subject', array('$username' => $username));
                    $msg = $lang->get('wcf.user.wantedPoster.pmAdminNotifier.message', array('$username' => '[url='.PAGE_URL.'/index.php?page=UserWantedPoster&userID='.$this->userID.SID_ARG_2ND.']'.$username.'[/url]', '$moderator' => $user->username));
                    PMEditor::create(false, array(array("username" => $user->username , 'userID' => $user->userID)), array(), $subject, $msg, $this->userID, $username, array('enableBBCodes' => 1, 'showSignature' => 0));
                }
            }
        }

	}

    public function countEntries() {
        $ret = 0;
		$sql = "SELECT COUNT(*) AS count FROM wcf".WCF_N."_user_wanted_poster";
        if(!WCF::getUser()->getPermission('mod.wantedPoster.canLockEntries')) $sql .= "\n WHERE locked != 1";
		list($ret) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
		return $ret;
    }

    public function readEntry()
    {
        if(!empty($this->userID)) {
            $sql = "SELECT uwp.userID, uwp.insertDate, uwp.updateDate, uwp.text, uwp.views, uwp.enableSmilies, uwp.enableHtml, uwp.enableBBCodes, uwp.locked, uwp.lockDate, uwp.lockUser"
                ."\n      ,u.username, IFNULL(SUM(attachmentSize),0) + LENGTH(uwp.text) AS size, COUNT(attachmentID) AS aCnt"
                ."\n  FROM wcf".WCF_N."_user_wanted_poster uwp"
                ."\n  JOIN wcf".WCF_N."_user u ON u.userID = uwp.userID"
                ."\n  LEFT JOIN wcf".WCF_N."_attachment wat ON (wat.userID = uwp.userID AND wat.messageType = 'wantedPoster')"
                ."\n WHERE uwp.userID = ".$this->userID
                ."\n GROUP BY uwp.userID, uwp.insertDate, uwp.updateDate, uwp.text, uwp.views, uwp.enableSmilies, uwp.enableHtml, uwp.enableBBCodes, uwp.locked, uwp.lockDate, uwp.lockUser, u.username";
    		$result = WCF::getDB()->getFirstRow($sql);

    		$result['username'] = StringUtil::encodeHTML($result['username']);
    		if(isset($result['views'])) $result['views'] = StringUtil::formatInteger($result['views']);
            if(isset($result['size'])) $result['size'] = StringUtil::formatInteger($result['size']);
            if(isset($result['aCnt'])) $result['aCnt'] = StringUtil::formatInteger($result['aCnt']);

    		$this->updateViews();
            return $result;
        }
    }

    public function readEntries($sortField='updateDate', $sortOrder='DESC', $pageNo=1, $itemsPerPage=20)
    {
        $ret = array();
        $i = 0;
        if(preg_match('/(userID|avatarID)/',$sortField)) $sortField = 'u.'.$sortField;

        require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
        require_once(WCF_DIR.'lib/data/user/avatar/Avatar.class.php');

        $sql = "SELECT uwp.*, IFNULL(SUM(attachmentSize),0) + LENGTH(uwp.text) AS size, COUNT(attachmentID) AS aCnt, u.*, a.*, rank.*, uo.*"
            ."\n  FROM wcf".WCF_N."_user_wanted_poster uwp"
            ."\n  JOIN wcf".WCF_N."_user u ON u.userID = uwp.userID"
            ."\n  LEFT JOIN wcf".WCF_N."_attachment wat ON (wat.userID = uwp.userID AND wat.messageType = 'wantedPoster')"
            ."\n  LEFT JOIN wcf".WCF_N."_avatar a ON (a.avatarID = u.avatarID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user_rank rank ON (rank.rankID = u.rankID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user_option_value uo ON (uo.userID = u.userID)";
        if(!WCF::getUser()->getPermission('mod.wantedPoster.canLockEntries')) $sql .= "\n WHERE uwp.locked != 1";
        $sql .= "\n GROUP BY uwp.userID, uwp.insertDate, uwp.updateDate, uwp.text, uwp.views, uwp.enableSmilies, uwp.enableHtml, uwp.enableBBCodes, uwp.locked, uwp.lockDate, uwp.lockUser"
               ."\n ORDER BY ".$sortField." ".$sortOrder
               ."\n LIMIT ".$itemsPerPage
               ."\nOFFSET ".(($pageNo - 1) * $itemsPerPage);
        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            $ret[$i]['userID']          = $row['userID'];
            $ret[$i]['insertDate']      = $row['insertDate'];
            $ret[$i]['updateDate']      = $row['updateDate'];
            $ret[$i]['views']           = $row['views'];
            $ret[$i]['locked']          = $row['locked'];
            $ret[$i]['size']            = StringUtil::formatInteger($row['size']);
            $ret[$i]['aCnt']            = StringUtil::formatInteger($row['aCnt']);

            // username
            $user = new UserProfile(null, $row);
            $username = StringUtil::encodeHTML($row['username']);
            $protectedProfile = ($user->protectedProfile && WCF::getUser()->userID != $user->userID);
            $userData = array('user' => $user, 'encodedUsername' => $username, 'protectedProfile' => $protectedProfile);
            $userData['username'] = '<div class="containerIconSmall">';
            if ($user->isOnline()) {
            	$title = WCF::getLanguage()->get('wcf.user.online', array('$username' => $username));
            	$userData['username'] .= '<img src="'.RELATIVE_WCF_DIR.'icon/onlineS.png" alt="'.$title.'" title="'.$title.'" />';
            } else {
            	$title = WCF::getLanguage()->get('wcf.user.offline', array('$username' => $username));
            	$userData['username'] .= '<img src="'.RELATIVE_WCF_DIR.'icon/offlineS.png" alt="'.$title.'" title="'.$title.'" />';
            }
            $userData['username'] .= '</div><div class="containerContentSmall">';

            $title = WCF::getLanguage()->get('wcf.user.viewProfile', array('$username' => $username));
            $userData['username'] .= '<p><a href="index.php?page=User&amp;userID='.$row['userID'].SID_ARG_2ND.'" title="'.$title.'">'.$username.'</a></p>';
            if ($user->getUserTitle()) {
            	$userData['username'] .= '<p class="smallFont">'.$user->getUserTitle().' '.($user->getRank() ? $user->getRank()->getImage() : '').'</p>';
            }
            $userData['username'] .= '</div>';
            $ret[$i]['username'] = $userData['username'];

            // avatar
            if(empty($row['disableAvatar']) && !empty($row['avatarID']) && !empty($row['avatarExtension'])) {
                $avatar = new Avatar(null, $row);
                $avatar->setMaxHeight(50);
                $title = WCF::getLanguage()->get('wcf.user.viewProfile', array('$username' => $row['username']));
                $ret[$i]['avatar'] = '<a href="index.php?page=User&amp;userID='.$row['userID'].SID_ARG_2ND.'" title="'.$title.'">'.$avatar->__toString().'</a>';
            } else {
                $ret[$i]['avatar'] = '&nbsp;';
            }
            $i++;
        }
        return $ret;
    }

    public function deleteEntry($userID)
    {
		$sql = "DELETE FROM wcf".WCF_N."_user_wanted_poster"
		    ."\n WHERE userID = ".$userID;
		$result = WCF::getDB()->sendQuery($sql);

		$sql = "DELETE FROM wcf".WCF_N."_attachment"
		    ."\n WHERE userID = ".$userID
		    ."\n   AND messageType = 'wantedPoster'";
		$result = WCF::getDB()->sendQuery($sql);
    }

    public function lockEntry($userID)
    {
        $lockUser = WCF::getDB()->escapeString(WCF::getUser()->username);
        $sql = "UPDATE wcf".WCF_N."_user_wanted_poster"
            ."\n   SET locked = 1"
            ."\n      ,lockDate = UNIX_TIMESTAMP()"
            ."\n      ,lockUser = '".$lockUser."'"
            ."\n WHERE userID = ".$userID;
		$result = WCF::getDB()->sendQuery($sql);
    }

    public function unlockEntry($userID)
    {
        $sql = "UPDATE wcf".WCF_N."_user_wanted_poster"
            ."\n   SET locked = 0"
            ."\n      ,lockDate = NULL"
            ."\n      ,lockUser = NULL"
            ."\n WHERE userID = ".$userID;
		$result = WCF::getDB()->sendQuery($sql);
    }

    public function updateViews()
    {
        if($this->userID != WCF::getUser()->userID) {
            WCF::getDB()->sendQuery("UPDATE wcf".WCF_N."_user_wanted_poster SET views = views + 1 WHERE userID = ".$this->userID);
        }
    }

    // TEMPLATES *******************************************
    public function templateExists($tplID = 0, $tplName = '') {
        $ret = false;
        if(!empty($tplName)) $tplName = WCF::getDB()->escapeString($tplName);
        $sql = "SELECT COUNT(templateID) AS cnt"
            ."\n  FROM wcf".WCF_N."_user_wanted_poster_template tpl"
            ."\n WHERE 1 = 1";
        if(!empty($tplID)) $sql .= "\n   AND templateID != ".$tplID;
        if(!empty($tplName)) $sql .= "\n   AND LOWER(templateName) = LOWER('".$tplName."')";
        $row = WCF::getDB()->getFirstRow($sql);
        if(!empty($row['cnt'])) $ret = true;
        return $ret;
    }

    public function getUserTemplateList($smilies = 0, $html = 0, $bbcode = 0) {
        $ret = array();
        $i = 0;
        $tmp = self::getTemplateList();
        foreach($tmp as $k=>$t) {
            if(!$t['enabled'] > 0) continue;
            else if($t['enableSmilies'] > 0 && empty($smilies)) continue;
            else if($t['enableHtml'] > 0 && empty($html)) continue;
            else if($t['enableBBCodes'] > 0 && empty($bbcode)) continue;
            else {
                $ret[$i] = $t;
                $i++;
            }
        }
        return $ret;
    }


    public function countTemplates() {
        $ret = 0;
        $row = WCF::getDB()->getFirstRow("SELECT COUNT(*) AS cnt FROM wcf".WCF_N."_user_wanted_poster_template");
        if(isset($row['cnt'])) $ret = $row['cnt'];
        return $ret;
    }

    public function getTemplateList() {
        $ret = array();
        $i = 0;
        $sql = "SELECT tpl.*"
            ."\n  FROM wcf".WCF_N."_user_wanted_poster_template tpl"
            ."\n ORDER BY tpl.templateName";
        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            $ret[$i] = $row;
            $i++;
        }
        return $ret;
    }

    public function getTemplate($tplID) {
        if(empty($tplID)) return array();
        $sql = "SELECT tpl.*, ui.username AS IUser, uu.username AS UUser"
            ."\n  FROM wcf".WCF_N."_user_wanted_poster_template tpl"
            ."\n  LEFT JOIN wcf".WCF_N."_user ui ON (ui.userID = tpl.insertUserID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user uu ON (uu.userID = tpl.updateUserID)"
            ."\n WHERE templateID = ".$tplID;
        $row = WCF::getDB()->getFirstRow($sql);
        if(!empty($row['IUser'])) $row['IUser'] = StringUtil::encodeHTML($row['IUser']);
        else $row['IUser'] = '';
        if(!empty($row['UUser'])) $row['UUser'] = StringUtil::encodeHTML($row['UUser']);
        else $row['UUser'] = '';
        return $row;
    }

    public function saveTemplate($tplID, $tplName, $text, $enableSmilies, $enableHtml, $enableBBCodes, $enabled) {
        $text = WCF::getDB()->escapeString($text);
        $tplName = WCF::getDB()->escapeString($tplName);
        if(!empty($tplID)) {
            $sql = "UPDATE wcf".WCF_N."_user_wanted_poster_template"
                ."\n   SET updateDate = ".TIME_NOW
                ."\n      ,updateUserID = ".$this->userID
                ."\n      ,templateName = '".$tplName."'"
                ."\n      ,text = '".$text."'"
                ."\n      ,enableSmilies = ".$enableSmilies
                ."\n      ,enableHtml = ".$enableHtml
                ."\n      ,enableBBCodes = ".$enableBBCodes
                ."\n      ,enabled = ".$enabled
                ."\n WHERE templateID = ".$tplID;
            WCF::getDB()->sendQuery($sql);
            return WCF::getDB()->getAffectedRows();
        } else {
            $sql = "INSERT INTO wcf".WCF_N."_user_wanted_poster_template"
                ."\n       (insertDate, insertUserID, templateName, text, enableSmilies, enableHtml, enableBBCodes, enabled)"
                ."\nVALUES (".TIME_NOW.", ".$this->userID.", '".$tplName."', '".$text."', ".$enableSmilies.", ".$enableHtml.", ".$enableBBCodes.", ".$enabled.")";
            WCF::getDB()->sendQuery($sql);
            return WCF::getDB()->getInsertID();
        }
    }

    public function deleteTemplate($tplID) {
        $sql = "DELETE FROM wcf".WCF_N."_user_wanted_poster_template"
		    ."\n WHERE templateID = ".$tplID;
		WCF::getDB()->sendQuery($sql);
		return WCF::getDB()->getAffectedRows();
	}
}

?>
