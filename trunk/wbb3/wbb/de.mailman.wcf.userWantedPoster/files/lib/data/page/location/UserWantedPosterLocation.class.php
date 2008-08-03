<?php
require_once(WCF_DIR.'lib/data/page/location/Location.class.php');

class UserWantedPosterLocation implements Location {
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
                case 'wcf.usersOnline.location.userWantedPosterList':
                    return $this->userWantedPosterList($location);
                    break;
                case 'wcf.usersOnline.location.userWantedPosterEdit':
                case 'wcf.usersOnline.location.userWantedPoster':
                    return $this->userWantedPoster($location);
                    break;
                default:
                    return WCF::getLanguage()->get($location['locationName']);
            }
        } else {
            return '';
        }
	}

    protected function userWantedPoster($location) {
        $ret = '';
		if($this->users == null) $this->readUsers();
        foreach($this->users as $k => $v) {
            $userID = $k;
            $username = $v;
            break;
        }
        if(empty($username)) $username = '?';

        $msg = WCF::getLanguage()->get($location['locationName'], array('$username' => StringUtil::encodeHTML($username)));
        if(!empty($userID) && WCF::getUser()->getPermission('user.wantedPoster.canViewWantedPoster')) {
            $ret = '<a href="index.php?page=UserWantedPoster&amp;userID='.$userID.'">'.$msg.'</a>';
        } else {
            $ret = $msg;
        }
        return $ret;
    }

    protected function userWantedPosterList($location) {
        $ret = '';
        $msg = WCF::getLanguage()->get($location['locationName']);
        if(WCF::getUser()->getPermission('user.wantedPoster.canViewWantedPoster')) {
            if(WCF::getUser()->getPermission('user.wantedPoster.canViewMembersListTab')) $page = 'UserWantedPosterListMembers';
            else $page = 'UserWantedPosterList';
            $ret = '<a href="index.php?page='.$page.'">'.$msg.'</a>';
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
