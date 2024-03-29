<?php
/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wbb.portal.box.buddiesbox
 */
class BuddiesBox {
	protected $BuddiesData = array();

	public function __construct($data, $boxname = "") {
        if(!defined('BUDDIESBOX_SBCOLOR_ACP')) define('BUDDIESBOX_SBCOLOR_ACP', 2);
        if(!defined('BUDDIESBOX_SHOWDEL_ACP')) define('BUDDIESBOX_SHOWDEL_ACP', false);
        if(!defined('BUDDIESBOX_SHOWUSERMARKING_ACP')) define('BUDDIESBOX_SHOWUSERMARKING_ACP', false);
        if(!defined('BUDDIESBOX_SHOWONLYONLINE_ACP')) define('BUDDIESBOX_SHOWONLYONLINE_ACP', true);
        if(!defined('BUDDIESBOX_HIDEIFEMPTY_ACP')) define('BUDDIESBOX_HIDEIFEMPTY_ACP', true);
		$this->BuddiesData['templatename'] = "buddiesbox";
		$this->getBoxStatus($data);
		$this->BuddiesData['boxID'] = $data['boxID'];
		$this->BuddiesData['showBuddiesBox'] = false;

//        $buddies = WCF::getUser()->buddies;
		if (WCF::getUser()->userID != 0) {

            require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

   		    $cnt = 0;
		    $sql = "SELECT u.*, uo.*, wcg.*"
		        ."\n  FROM wcf".WCF_N."_user_whitelist wcu"
		        ."\n  JOIN wcf".WCF_N."_user u ON (u.userID = wcu.whiteUserID)"
		        ."\n  LEFT JOIN wcf".WCF_N."_user_option_value uo ON (uo.userID = u.userID)"
		        ."\n  LEFT JOIN wcf".WCF_N."_group wcg ON (wcg.groupID = u.userOnlineGroupID)"
		        ."\n WHERE wcu.userID = ".WCF::getUser()->userID
		        ."\n ORDER BY u.username";
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                $user = new UserProfile(null, $row);
                if(BUDDIESBOX_SHOWONLYONLINE_ACP && !$user->isOnline()) continue;
                if($user->isOnline()) {
                	$this->BuddiesData['buddies'][$cnt]['imgTitle'] = StringUtil::decodeHTML(WCF::getLanguage()->get('wcf.user.online', array('$username' => $row['username'])));
                	$this->BuddiesData['buddies'][$cnt]['img'] = 'onlineS.png';
                } else {
                	$this->BuddiesData['buddies'][$cnt]['imgTitle'] = StringUtil::decodeHTML(WCF::getLanguage()->get('wcf.user.offline', array('$username' => $row['username'])));
                	$this->BuddiesData['buddies'][$cnt]['img'] = 'offlineS.png';
                }
                if($user->acceptPm) $this->BuddiesData['buddies'][$cnt]['pm'] = '1';
                else $this->BuddiesData['buddies'][$cnt]['pm'] = '';
                $this->BuddiesData['buddies'][$cnt]['userID'] = $row['userID'];
                $this->BuddiesData['buddies'][$cnt]['username'] = StringUtil::encodeHTML($row['username']);

                // userOnlineMarking...
                if(BUDDIESBOX_SHOWUSERMARKING_ACP && !empty($row['userOnlineMarking']) && $row['userOnlineMarking'] != '%s') {
                    $this->BuddiesData['buddies'][$cnt]['username'] = sprintf($row['userOnlineMarking'], StringUtil::encodeHTML($row['username']));
                }
                $cnt++;
    		}
    		if($cnt > 0 || !BUDDIESBOX_HIDEIFEMPTY_ACP) $this->BuddiesData['showBuddiesBox'] = true;
    	}
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BuddiesData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BuddiesData['Status'] = intval(WBBCore::getUser()->buddiesbox);
		}
		else {
			if (WBBCore::getSession()->getVar('buddiesbox') != false) {
				$this->BuddiesData['Status'] = WBBCore::getSession()->getVar('buddiesbox');
			}
		}
	}

	public function getData() {
		return $this->BuddiesData;
	}
}

?>
