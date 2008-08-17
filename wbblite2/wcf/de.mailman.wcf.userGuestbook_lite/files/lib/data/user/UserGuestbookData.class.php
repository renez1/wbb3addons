<?php
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

/**
 * @package	de.mailman.wcf.userGuestbook
 * @author	MailMan
 */
class UserGuestbookData {

	protected $userID = 0;
	protected $user;

	/**
	 * Creates a new UserGuestbookEntry
	 *
	 * @see User::__construct()
	 */
	public function __construct($userID = NULL)
	{
		$this->userID = $userID;
		$this->user = new UserProfile($this->userID, null, null, null);
	}

    public function getOptionID($optionName) {
        $optID = User::getUserOptionID($optionName);
        if(empty($optID)) list($optID) = WCF::getDB()->getFirstRow("SELECT optionID FROM wcf".WCF_N."_user_option WHERE optionName = '".$optionName."'", MYSQL_NUM);
        return $optID;
    }
    public function getGroupOptionID($optionName) {
        list($optID) = WCF::getDB()->getFirstRow("SELECT optionID FROM wcf".WCF_N."_group_option WHERE optionName = '".$optionName."'", MYSQL_NUM);
        return $optID;
    }


    public function getLockInfo($userID) {
        if(empty($userID)) return;
        $sql = "SELECT ugh.locked, ugh.lockTime, ugh.lockUserID, u.username"
            ."\n  FROM wcf".WCF_N."_user_guestbook_header ugh"
            ."\n  LEFT JOIN wcf".WCF_N."_user u ON (u.userID = ugh.lockUserID)"
            ."\n WHERE ugh.userID = ".$userID;
        return WCF::getDB()->getFirstRow($sql);
    }

    public function lockEntry($userID, $lock) {
        if(empty($userID) || !WCF::getUser()->userID) return;
        $cTime = TIME_NOW;
        if($lock == 'lock' || $lock == 1) $locked = 1;
        else $locked = 0;
        $sql = "INSERT INTO wcf".WCF_N."_user_guestbook_header"
            ."\n       (userID, locked, lockTime, lockUserID)"
            ."\nVALUES (".$userID.", ".$locked.", ".$cTime.", ".WCF::getUser()->userID.")"
            ."\n    ON DUPLICATE KEY UPDATE"
            ."\n       userID = ".$userID
            ."\n      ,locked = ".$locked
            ."\n      ,lockTime = ".$cTime
            ."\n      ,lockUserID = ".WCF::getUser()->userID;
        WCF::getDB()->sendQuery($sql);
    }

    public function getEntry($id) {
        if(empty($id)) return;
        $sql = "SELECT *"
            ."\n  FROM wcf".WCF_N."_user_guestbook"
            ."\n WHERE id = ".$id;
        return WCF::getDB()->getFirstRow($sql);
    }

    public function updateEntry($id,$text,$enableSmilies,$enableHtml,$enableBBCodes) {
        if(empty($id)) return;
        $text = WCF::getDB()->escapeString($text);
        $sql = "UPDATE wcf".WCF_N."_user_guestbook"
            ."\n   SET text = '".$text."'"
            ."\n      ,enableSmilies = ".$enableSmilies
            ."\n      ,enableHtml = ".$enableHtml
            ."\n      ,enableBBCodes = ".$enableBBCodes
            ."\n      ,updateTime = UNIX_TIMESTAMP(NOW())"
            ."\n      ,updateUserID = ".WCF::getUser()->userID
            ."\n WHERE id = ".$id;
        WCF::getDB()->sendQuery($sql);
    }

    public function addComment($id,$text) {
        if(empty($id)) return;
        $cTime = TIME_NOW;
        $text = WCF::getDB()->escapeString($text);
        $sql = "UPDATE wcf".WCF_N."_user_guestbook"
            ."\n   SET comment = '".$text."'"
            ."\n      ,commentTime = ".$cTime
            ."\n WHERE id = ".$id;
        WCF::getDB()->sendQuery($sql);

        $sql = "UPDATE wcf".WCF_N."_user_guestbook_header"
            ."\n   SET userLastCommentTime = ".$cTime
            ."\n WHERE userID = ".$this->userID;
        WCF::getDB()->sendQuery($sql);
    }

    public function deleteComment($id) {
        if(empty($id)) return;
        $sql = "UPDATE wcf".WCF_N."_user_guestbook"
            ."\n   SET comment = NULL"
            ."\n      ,commentTime = 0"
            ."\n WHERE id = ".$id;
        WCF::getDB()->sendQuery($sql);

        $sql = "SELECT MAX(commentTime)"
            ."\n  FROM wcf".WCF_N."_user_guestbook"
            ."\n WHERE id = ".$id;
        list($cTime) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
        if(empty($cTime)) $cTime = 0;

        $sql = "UPDATE wcf".WCF_N."_user_guestbook_header"
            ."\n   SET userLastCommentTime = ".$cTime
            ."\n WHERE userID = ".$this->userID;
        WCF::getDB()->sendQuery($sql);
    }

	public function addEntry($fromUserID,$text,$enableSmilies,$enableHtml,$enableBBCodes)
	{
        $cTime = TIME_NOW;
		$text = WCF::getDB()->escapeString($text);
		$fromUsername = WCF::getUser()->username;
		$fromUsername = WCF::getDB()->escapeString($fromUsername);
		$sql = "INSERT INTO wcf".WCF_N."_user_guestbook"
		    ."\n       (userID, fromUserID, fromUsername, text, enableSmilies, enableHtml, enableBBCodes, entryTime)"
		    ."\nVALUES (".$this->userID.", ".$fromUserID.", '".$fromUsername."', '".$text."', ".$enableSmilies.", ".$enableHtml.", ".$enableBBCodes.", ".$cTime.")";
		WCF::getDB()->sendQuery($sql);

		$sql = "SELECT COUNT(*) AS count"
		    ."\n  FROM wcf".WCF_N."_user_guestbook"
		    ."\n WHERE userID = ".$this->userID;
		list($cntEntries) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);

        if($this->userID == $fromUserID) {
            $sql = "INSERT INTO wcf".WCF_N."_user_guestbook_header"
                ."\n       (userID, userLastVisit, lastEntryUserID, lastEntryUsername, lastEntry, entries, newEntries)"
                ."\nVALUES (".$this->userID.", ".$cTime.", ".$fromUserID.", '".$fromUsername."', ".$cTime.", ".$cntEntries.", 0)"
                ."\n    ON DUPLICATE KEY UPDATE"
                ."\n       userID = ".$this->userID
                ."\n      ,userLastVisit = ".$cTime
                ."\n      ,lastEntryUserID = ".$fromUserID
                ."\n      ,lastEntryUsername = '".$fromUsername."'"
                ."\n      ,lastEntry = ".$cTime
                ."\n      ,entries = ".$cntEntries
                ."\n      ,newEntries = 0";
        } else {
            $sql = "SELECT COUNT(*) AS count"
                ."\n  FROM wcf".WCF_N."_user_guestbook gb"
                ."\n  LEFT JOIN wcf".WCF_N."_user_guestbook_header gbh ON (gbh.userID = gb.userID)"
                ."\n WHERE gb.userID = ".$this->userID
                ."\n   AND gbh.userLastVisit < gb.entryTime";
            list($cntNewEntries) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
            $sql = "INSERT INTO wcf".WCF_N."_user_guestbook_header"
                ."\n       (userID, lastEntryUserID, lastEntryUsername, lastEntry, entries, newEntries, visitorID, visitorLastVisit)"
                ."\nVALUES (".$this->userID.", ".$fromUserID.", '".$fromUsername."', ".$cTime.", ".$cntEntries.", ".$cntNewEntries.", ".$fromUserID.", ".$cTime.")"
                ."\n    ON DUPLICATE KEY UPDATE"
                ."\n       userID = ".$this->userID
                ."\n      ,lastEntryUserID = ".$fromUserID
                ."\n      ,lastEntryUsername = '".$fromUsername."'"
                ."\n      ,lastEntry = ".$cTime
                ."\n      ,entries = ".$cntEntries
                ."\n      ,newEntries = ".$cntNewEntries
                ."\n      ,visitorID = ".$fromUserID
                ."\n      ,visitorLastVisit = ".$cTime;
        }
        WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Löscht einen Eintrag
	 */
    public function deleteEntry($id) {
        if(empty($id)) return;

        $sql = "DELETE FROM wcf".WCF_N."_user_guestbook"
            ."\n WHERE id = ".$id;
        WCF::getDB()->sendQuery($sql);

        $sql = "SELECT COUNT(*) AS count, IFNULL(MAX(entryTime), 0) AS lastEntry, IFNULL(MAX(id), 0) AS ID, IFNULL(MAX(commentTime), 0) AS commentTime"
            ."\n  FROM wcf".WCF_N."_user_guestbook"
            ."\n WHERE userID = ".$this->userID;
        $row = WCF::getDB()->getFirstRow($sql);

        $sql = "SELECT fromUserID, fromUsername"
            ."\n  FROM wcf".WCF_N."_user_guestbook"
            ."\n WHERE id = ".$row['ID'];
        list($lastEntryUserID, $lastEntryUsername) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
        if(empty($lastEntryUserID)) $lastEntryUserID = 0;
        if(empty($lastEntryUsername)) $lastEntryUsername = 'NULL';
        else $lastEntryUsername = "'".$lastEntryUsername."'";

        $sql = "SELECT COUNT(*) AS count"
            ."\n  FROM wcf".WCF_N."_user_guestbook gb"
            ."\n  LEFT JOIN wcf".WCF_N."_user_guestbook_header gbh ON (gbh.userID = gb.userID)"
            ."\n WHERE gb.userID = ".$this->userID
            ."\n   AND gbh.userLastVisit < gb.entryTime";
        list($cntNewEntries) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);

        $sql = "UPDATE wcf".WCF_N."_user_guestbook_header"
            ."\n   SET entries = ".$row['count']
            ."\n      ,newEntries = ".$cntNewEntries
            ."\n      ,lastEntryUserID = ".$lastEntryUserID
            ."\n      ,lastEntryUsername = ".$lastEntryUsername
            ."\n      ,lastEntry = ".$row['lastEntry']
            ."\n      ,userLastCommentTime = ".$row['commentTime']
            ."\n WHERE userID = ".$this->userID;
        WCF::getDB()->sendQuery($sql);
    }


	/**
	 * Lädt alle Einträge eines Benutzers
	 */
	 public function getEntries($pageNo=1, $itemsPerPage=10)
	 {
	    $userOptID = self::getOptionID('userGuestbook_enable');
		$sql = "SELECT gb.*, gbh.*, u.username, v.username AS lastVisitor, upd.username AS updateUser"
		    ."\n      ,fuov.userOption".$userOptID." AS fuIsEnabled, fugbh.locked AS fuIsLocked"
		    ."\n      ,IFNULL((SELECT MAX(fugov.optionValue)"
		    ."\n                 FROM wcf".WCF_N."_user_to_groups fuutg, wcf".WCF_N."_group_option_value fugov, wcf".WCF_N."_group_option fugo"
		    ."\n                WHERE fuutg.userID = gb.fromUserID"
		    ."\n                  AND fugov.groupID = fuutg.groupID"
		    ."\n                  AND fugo.optionID = fugov.optionID"
		    ."\n                  AND fugo.optionName = 'user.guestbook.canUseOwn'), 0) AS fuCanUseOwn"
		    ."\n  FROM wcf".WCF_N."_user_guestbook_header gbh"
		    ."\n  LEFT JOIN wcf".WCF_N."_user_guestbook gb ON (gb.userID = gbh.userID)"
		    ."\n  LEFT JOIN wcf".WCF_N."_user u ON (u.userID = gb.fromUserID)"
		    ."\n  LEFT JOIN wcf".WCF_N."_user v ON (v.userID = gbh.visitorID)"
		    ."\n  LEFT JOIN wcf".WCF_N."_user upd ON (upd.userID = gb.updateUserID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user_option_value fuov ON (fuov.userID = gb.fromUserID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user_guestbook_header fugbh ON (fugbh.userID = gb.fromUserID)"
		    ."\n WHERE gbh.userID = ".$this->userID
		    ."\n ORDER BY gb.entryTime DESC"
            ."\n LIMIT ".$itemsPerPage
            ."\nOFFSET ".(($pageNo - 1) * $itemsPerPage);
		$result = WCF::getDB()->getResultList($sql);
		return $result;
	 }

    public function countEntries() {
        $ret = 0;
        $userOptID = self::getOptionID('userGuestbook_enable');
        if(!empty($userOptID)) {
            $sql = "SELECT COUNT(DISTINCT gb.userID) AS count"
                ."\n  FROM wcf".WCF_N."_user_guestbook_header gb"
                ."\n  LEFT JOIN wcf".WCF_N."_user_option_value ov ON (ov.userID = gb.userID)"
                ."\n  LEFT JOIN wcf".WCF_N."_user_to_groups ug ON (ug.userID = gb.userID)"
                ."\n  LEFT JOIN wcf".WCF_N."_group_option_value gv ON (gv.groupID = ug.groupID)"
                ."\n  LEFT JOIN wcf".WCF_N."_group_option go ON (go.optionID = gv.optionID)"
                ."\n WHERE ov.userOption".$userOptID." = '1'"
                ."\n   AND gv.optionValue = '1'"
                ."\n   AND go.optionName = 'user.guestbook.canUseOwn'";
            if(!WCF::getUser()->getPermission('mod.guestbook.canLock')) {
                $sql .= "\n   AND gb.locked != 1";
                $sql .= "\n   AND gb.entries > 0";
            } else {
                $sql .= "\n   AND (gb.locked = 1 OR gb.entries > 0)";
            }
            list($ret) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
        }
        return $ret;
    }

    public function getGuestbookList($sortField='lastEntry', $sortOrder='DESC', $pageNo=1, $itemsPerPage=20)
    {
        $ret = array();
        $i = 0;
        $optID = self::getOptionID('userGuestbook_enable');
        if(preg_match('/(userID|avatarID)/',$sortField)) $sortField = 'u.'.$sortField;
        require_once(WCF_DIR.'lib/data/user/avatar/Avatar.class.php');
        $gbUserIDs = 0;

        $sql = "SELECT ugb.*, u.*, rank.*, a.*, uo.*, leu.username AS lastEntryUser, lv.username AS lastVisitor"
            ."\n  FROM wcf".WCF_N."_user_guestbook_header ugb"
            ."\n  JOIN wcf".WCF_N."_user u ON (u.userID = ugb.userID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user leu ON (leu.userID = ugb.lastEntryUserID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user lv ON (lv.userID = ugb.visitorID)"
            ."\n  LEFT JOIN wcf".WCF_N."_avatar a ON (a.avatarID = u.avatarID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user_rank rank ON (rank.rankID = u.rankID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user_option_value uo ON (uo.userID = u.userID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user_option_value ov ON (ov.userID = ugb.userID)"
            ."\n  LEFT JOIN wcf".WCF_N."_user_to_groups ug ON (ug.userID = ugb.userID)"
            ."\n  LEFT JOIN wcf".WCF_N."_group_option_value gv ON (gv.groupID = ug.groupID)"
            ."\n  LEFT JOIN wcf".WCF_N."_group_option go ON (go.optionID = gv.optionID)"
            ."\n WHERE ov.userOption".$optID." = '1'"
            ."\n   AND gv.optionValue = '1'"
            ."\n   AND go.optionName = 'user.guestbook.canUseOwn'";
        if(!WCF::getUser()->getPermission('mod.guestbook.canLock')) {
            $sql .= "\n   AND ugb.locked != 1";
            $sql .= "\n   AND ugb.entries > 0";
        } else {
                $sql .= "\n   AND (ugb.locked = 1 OR ugb.entries > 0)";
        }
        $sql .= "\n GROUP BY ugb.userID, ugb.userLastVisit, ugb.lastEntryUserID, ugb.lastEntry, ugb.entries, ugb.newEntries, ugb.views, ugb.visitorID, ugb.visitorLastVisit, ugb.locked, ugb.lockTime, ugb.lockUserID"
            ."\n ORDER BY ".$sortField." ".$sortOrder
            ."\n LIMIT ".$itemsPerPage
            ."\nOFFSET ".(($pageNo - 1) * $itemsPerPage);

        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            $user = new UserProfile(null, $row);
            if(!$user->userGuestbook_enable) continue;
            $gbUserIDs .= ','.$row['userID'];
            $ret[$i]['curUserEntry']        = false;
            $ret[$i]['userID']              = $row['userID'];
            $ret[$i]['userLastVisit']       = $row['userLastVisit'];
            $ret[$i]['userLastCommentTime'] = $row['userLastCommentTime'];
            $ret[$i]['lastEntry']           = $row['lastEntry'];
            $ret[$i]['visitorLastVisit']    = $row['visitorLastVisit'];
            $ret[$i]['entries']             = StringUtil::formatInteger($row['entries']);
            $ret[$i]['newEntries']          = StringUtil::formatInteger($row['newEntries']);
            $ret[$i]['views']               = StringUtil::formatInteger($row['views']);
            $ret[$i]['locked']              = $row['locked'];
            if(empty($row['lastEntryUser'])) {
                $ret[$i]['lastEntryUser']   = StringUtil::encodeHTML($row['lastEntryUsername']);
                $ret[$i]['lastEntryUserID'] = 0;
            } else {
                $ret[$i]['lastEntryUser']       = StringUtil::encodeHTML($row['lastEntryUser']);
                $ret[$i]['lastEntryUserID']     = $row['lastEntryUserID'];
            }
            $ret[$i]['lastVisitor']         = StringUtil::encodeHTML($row['lastVisitor']);
            $ret[$i]['lastVisitorID']       = $row['visitorID'];

            // username
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

        if(!empty($gbUserIDs) && WCF::getUser()->userID) {
            $sql = "SELECT DISTINCT userID"
                ."\n  FROM wcf".WCF_N."_user_guestbook"
                ."\n WHERE userID IN (".$gbUserIDs.")"
                ."\n   AND fromUserID = ".WCF::getUser()->userID;
            $result = WCF::getDB()->sendQuery($sql);
            while($row = WCF::getDB()->fetchArray($result)) {
                foreach($ret as $k => $v) {
                    if($ret[$k]['userID'] == $row['userID']) $ret[$k]['curUserEntry'] = true;
                }
            }
        }
        return $ret;
    }

    public function getStats() {
		$sql = "SELECT gbh.*, u.username AS lastVisitor"
		    ."\n  FROM wcf".WCF_N."_user_guestbook_header gbh"
            ."\n  LEFT JOIN wcf".WCF_N."_user u ON (u.userID = gbh.visitorID)"
		    ."\n WHERE gbh.userID = ".$this->userID
		    ."\n LIMIT 1";
		return WCF::getDB()->getFirstRow($sql);
    }

    public function updateStatsVisitor($visitorID) {
        $sql = "INSERT INTO wcf".WCF_N."_user_guestbook_header"
            ."\n       (userID, views, visitorID, visitorLastVisit)"
            ."\nVALUES (".$this->userID.", views+1, ".$visitorID.", UNIX_TIMESTAMP(NOW()))"
            ."\n    ON DUPLICATE KEY UPDATE"
            ."\n       userID = ".$this->userID
            ."\n      ,views = views + 1"
            ."\n      ,visitorID = ".$visitorID
            ."\n      ,visitorLastVisit = UNIX_TIMESTAMP(NOW())";
        WCF::getDB()->sendQuery($sql);
    }

    public function updateStatsUser() {
        $sql = "INSERT INTO wcf".WCF_N."_user_guestbook_header"
            ."\n       (userID, userLastVisit, newEntries)"
            ."\nVALUES (".$this->userID.", UNIX_TIMESTAMP(NOW()), 0)"
            ."\n    ON DUPLICATE KEY UPDATE"
            ."\n       userID = ".$this->userID
            ."\n      ,userLastVisit = UNIX_TIMESTAMP(NOW())"
            ."\n      ,newEntries = 0";
        WCF::getDB()->sendQuery($sql);
    }
}
?>
