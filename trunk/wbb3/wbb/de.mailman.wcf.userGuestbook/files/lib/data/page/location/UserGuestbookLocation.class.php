<?php
require_once(WCF_DIR.'lib/data/page/location/Location.class.php');

class UserGuestbookLocation implements Location {
	public $cachedUserIDs = array();
	public $users = null;

	/**
	 * @see Location::cache()
	 */
	public function cache($location, $requestURI, $requestMethod, $match) {
        if(isset($match[1])) $this->cachedUserIDs[] = $match[1];
	}

	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {
        if(!empty($location['locationName'])) {
            switch($location['locationName']) {
                case 'wcf.usersOnline.location.userGuestbookList':
                    return $this->userGuestbookList($location);
                    break;
                case 'wcf.usersOnline.location.userGuestbookView':
                    return $this->userGuestbookView($location);
                    break;
                case 'wcf.usersOnline.location.userGuestbookAddEntry':
                    return $this->userGuestbookView($location);
                    break;
                default:
                    return WCF::getLanguage()->get($location['locationName']);
            }
        } else {
            return '';
        }
	}

    protected function userGuestbookList($location) {
        $ret = '';
        if(WCF::getUser()->getPermission('user.guestbook.canViewList')) {
            if(WCF::getUser()->getPermission('user.guestbook.canViewMembersListTab')) $page = 'UserGuestbookListMembers';
            else $page = 'UserGuestbookList';
            $ret = '<a href="index.php?page='.$page.'">'.WCF::getLanguage()->get($location['locationName']).'</a>';
        } else {
            $ret = WCF::getLanguage()->get($location['locationName']);
        }
        return $ret;
    }

    protected function userGuestbookView($location) {
        $ret = '';
		if($this->users == null) $this->readUsers();
        foreach($this->users as $k => $v) {
            $userID = $k;
            $username = $v;
            break;
        }
        if(empty($username)) $username = '?';

        $msg = WCF::getLanguage()->get($location['locationName'], array('$username' => StringUtil::encodeHTML($username)));
        if(!empty($userID) && WCF::getUser()->getPermission('user.guestbook.canRead')) {
            $ret = '<a href="index.php?page=UserGuestbook&amp;userID='.$userID.'">'.$msg.'</a>';
        } else {
            $ret = $msg;
        }
        return $ret;
    }


	/**
	 * Gets users.
	 */
	protected function readUsers() {
		$this->users = array();
		if (!count($this->cachedUserIDs)) return;
		$sql = "SELECT	userID, username
			FROM	wcf".WCF_N."_user
			WHERE	userID IN (".implode(',', $this->cachedUserIDs).")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) $this->users[$row['userID']] = $row['username'];
	}
}
?>