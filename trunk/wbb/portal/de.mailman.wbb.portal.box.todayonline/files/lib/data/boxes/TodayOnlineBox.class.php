<?php
class TodayOnlineBox {
	protected $WwoData = array();

	public function __construct($data, $boxname = "") {
		$this->WwoData['templatename'] = "todayonlinebox";
		$this->getBoxStatus($data);
		$this->WwoData['boxID'] = $data['boxID'];

        // defaults
        if(!defined('TODAYONLINEBOX_SHOWONLINEUSER_ACP'))   define('TODAYONLINEBOX_SHOWONLINEUSER_ACP', true);
        if(!defined('TODAYONLINEBOX_SHOWSTATS_ACP'))        define('TODAYONLINEBOX_SHOWSTATS_ACP', true);
        if(!defined('TODAYONLINEBOX_SHOWLEGEND_ACP'))       define('TODAYONLINEBOX_SHOWLEGEND_ACP', 'wio');

        WCF::getTPL()->assign('TODAYONLINEBOX_SHOWONLINEUSER_ACP', TODAYONLINEBOX_SHOWONLINEUSER_ACP);
        WCF::getTPL()->assign('TODAYONLINEBOX_SHOWSTATS_ACP', TODAYONLINEBOX_SHOWSTATS_ACP);
        WCF::getTPL()->assign('TODAYONLINEBOX_SHOWLEGEND_ACP', TODAYONLINEBOX_SHOWLEGEND_ACP);
        $stats = array();
		if(TODAYONLINEBOX_SHOWSTATS_ACP) $stats = WCF::getCache()->get('stat');
		WCF::getTPL()->assign('stats', $stats);

        if(TODAYONLINEBOX_SHOWONLINEUSER_ACP) {
    		require_once(WCF_DIR.'lib/data/user/usersOnline/UsersOnlineList.class.php');
    		$usersOnlineListObj = null;
    		$usersOnlineListObj = new UsersOnlineList('', true);
    		$usersOnlineListObj->renderOnlineList();
        }

		if(WCF::getUser()->getPermission('user.board.canSeeWWO')) {
            require_once(WCF_DIR.'lib/data/user/usersOnline/UsersWasOnlineList.class.php');
            if(INDEX_LIMIT_WASONLINE_LIST && INDEX_LIMIT_WASONLINE_LIST_AMOUNT > 0) $usersWasOnlineList = new UsersWasOnlineList('', true, INDEX_LIMIT_WASONLINE_LIST_AMOUNT);
            else $usersWasOnlineList = new UsersWasOnlineList('', true);
            $usersWasOnlineList->renderWasOnlineList();
    	}
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->WwoData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->WwoData['Status'] = intval(WBBCore::getUser()->todayonlinebox);
		}
		else {
			if (WBBCore::getSession()->getVar('todayonlinebox') != false) {
				$this->WwoData['Status'] = WBBCore::getSession()->getVar('todayonlinebox');
			}
		}
	}

	public function getData() {
		return $this->WwoData;
	}
}

?>
