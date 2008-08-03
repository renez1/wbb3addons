<?php
class TeamOnlineBox {
	protected $TeamOnlineBoxData = array();

	public function __construct($data, $boxname = "") {
		$this->TeamOnlineBoxData['templatename'] = "teamonlinebox";
		$this->getBoxStatus($data);
		$this->TeamOnlineBoxData['boxID'] = $data['boxID'];

		if(!defined('TEAMONLINEBOX_SHOWCOUNT_ACP'))     define('TEAMONLINEBOX_SHOWCOUNT_ACP', true);
        if(!defined('TEAMONLINEBOX_SHOWBYLINE_ACP'))    define('TEAMONLINEBOX_SHOWBYLINE_ACP', true);
        if(!defined('TEAMONLINEBOX_SHOWTIME_ACP'))      define('TEAMONLINEBOX_SHOWTIME_ACP', true);
        if(!defined('TEAMONLINEBOX_ORDERBY_ACP'))       define('TEAMONLINEBOX_ORDERBY_ACP', 'lastActivityTime');
        if(!defined('TEAMONLINEBOX_SORTDESC_ACP'))      define('TEAMONLINEBOX_SORTDESC_ACP', true);
        if(!defined('TEAMONLINEBOX_SBCOLOR_ACP'))       define('TEAMONLINEBOX_SBCOLOR_ACP', 2);
        $teamOnline = array();

        if(WCF::getUser()->userID > 0 && WCF::getUser()->getPermission('user.board.canSeeTeamOnlineBox') > 0) {
            require_once(WCF_DIR.'lib/data/user/usersOnline/UsersOnlineList.class.php');
	    	$teamOnlineList = null;
    		$teamOnlineList = new UsersOnlineList('', true);
            $teamOnlineList->renderOnlineList();

            $uOnlineIDs = '0';
            if(isset($teamOnlineList->usersOnline)) {
                foreach($teamOnlineList->usersOnline AS $k=>$v) {
                    if(isset($v['userID'])) $uOnlineIDs .= ','.$v['userID'];
                }
            }
            if($uOnlineIDs != '0') {
                if(TEAMONLINEBOX_SORTDESC_ACP) $sort = 'DESC';
                else $sort = 'ASC';
                $sql = "SELECT DISTINCT wcu.userID, wcu.username, wcu.lastActivityTime, wcuo.userOption".User::getUserOptionID('invisible')." isInvisible, teamOnlineMarking"
                    ."\n  FROM wcf".WCF_N."_user wcu"
                    ."\n  JOIN wcf".WCF_N."_group wcg ON (wcg.groupID = wcu.userOnlineGroupID)"
                    ."\n  JOIN wcf".WCF_N."_user_option_value wcuo ON (wcuo.userID = wcu.userID)"
                    ."\n WHERE wcg.showOnTeamOnlineBox = 1"
                    ."\n   AND wcu.userID IN (".$uOnlineIDs.")"
                    ."\n ORDER BY wcu.".TEAMONLINEBOX_ORDERBY_ACP." ".$sort;
                $result = WBBCore::getDB()->sendQuery($sql);
                $i = 0;
                while($row = WBBCore::getDB()->fetchArray($result)) {
                    if($row['isInvisible'] && !WCF::getUser()->getPermission('admin.general.canViewInvisible')) continue;
                    if ($row['teamOnlineMarking'] != '%s') {
                        if($row['isInvisible']) $row['username'] .= WCF::getLanguage()->get('wcf.usersOnline.invisible');
                        $row['username'] = sprintf($row['teamOnlineMarking'], StringUtil::encodeHTML($row['username']));
                    } else {
                        $row['username'] = StringUtil::encodeHTML($row['username']);
                    }
                    $teamOnline[$i]['userID']            = $row['userID'];
                    $teamOnline[$i]['username']          = $row['username'];
                    $teamOnline[$i]['lastActivityTime']  = $row['lastActivityTime'];
                    $i++;
                }
            }
        }
        WCF::getTPL()->assign('teamOnline', $teamOnline);
        WCF::getTPL()->assign('TEAMONLINEBOX_SHOWCOUNT_ACP', TEAMONLINEBOX_SHOWCOUNT_ACP);
        WCF::getTPL()->assign('TEAMONLINEBOX_SHOWBYLINE_ACP', TEAMONLINEBOX_SHOWBYLINE_ACP);
        WCF::getTPL()->assign('TEAMONLINEBOX_SHOWTIME_ACP', TEAMONLINEBOX_SHOWTIME_ACP);
        WCF::getTPL()->assign('TEAMONLINEBOX_SBCOLOR_ACP', intval(TEAMONLINEBOX_SBCOLOR_ACP));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->TeamOnlineBoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->TeamOnlineBoxData['Status'] = intval(WBBCore::getUser()->teamonlinebox);
		}
		else {
			if (WBBCore::getSession()->getVar('teamonlinebox') != false) {
				$this->TeamOnlineBoxData['Status'] = WBBCore::getSession()->getVar('teamonlinebox');
			}
		}
	}

	public function getData() {
		return $this->TeamOnlineBoxData;
	}
}

?>
