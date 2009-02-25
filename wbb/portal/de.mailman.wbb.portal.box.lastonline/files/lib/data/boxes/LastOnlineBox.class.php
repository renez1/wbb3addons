<?php
class LastOnlineBox {
	protected $LastOnlineBoxData = array();
    protected $lastOnline = array();
    protected $lastOnlineByName = array();

	public function __construct($data, $boxname = "") {
		$this->LastOnlineBoxData['templatename'] = "lastonlinebox";
		$this->getBoxStatus($data);
		$this->LastOnlineBoxData['boxID'] = $data['boxID'];
        $cntTodayOnline = 0;
        $showAllOU = 0;
        $curPage = '';
        if(!defined('LASTONLINEBOX_NUMOFUSER_ACP')) define('LASTONLINEBOX_NUMOFUSER_ACP', 5);
        if(!defined('LASTONLINEBOX_SBCOLOR_ACP'))   define('LASTONLINEBOX_SBCOLOR_ACP', 2);
        if(!defined('LASTONLINEBOX_MAXHEIGHT_ACP')) define('LASTONLINEBOX_MAXHEIGHT_ACP', 300);
        if(!defined('LASTONLINEBOX_SHOWTIME_ACP'))  define('LASTONLINEBOX_SHOWTIME_ACP', true);
        if(!defined('LASTONLINEBOX_SHOWUSERMARKING_ACP'))  define('LASTONLINEBOX_SHOWUSERMARKING_ACP', true);

        if(WCF::getUser()->getPermission('user.board.canSeeLastOnlineBox') > 0) {
            $itstamp    = time();
            $todayStartTime = mktime(0, 0, 0, (int) date("m",$itstamp), (int) date("d",$itstamp), (int) date("Y",$itstamp));
            $i = 0;

            if(!empty($_GET['showAllOU'])) $showAllOU = 1;
            if(!empty($_GET['page'])) $curPage = $_GET['page'];

            if(WCF::getUser()->userID > 0 && WCF::getUser()->getPermission('admin.general.canViewInvisible')) {
                $sql = "SELECT COUNT(*) AS cntTodayOnline"
                    ."\n  FROM wcf".WCF_N."_user wcu"
                    ."\n WHERE wcu.lastActivityTime >= ".$todayStartTime;
                $result = WBBCore::getDB()->getFirstRow($sql);
                $cntTodayOnline = $result['cntTodayOnline'];
            } else {
                $sql = "SELECT COUNT(*) AS cntTodayOnline"
                    ."\n  FROM wcf".WCF_N."_user wcu"
                    ."\n  LEFT JOIN wcf".WCF_N."_user_option_value wcuo ON (wcuo.userID = wcu.userID)"
                    ."\n WHERE wcu.lastActivityTime >= ".$todayStartTime
                    ."\n AND wcuo.userOption".User::getUserOptionID('invisible')." = 0";
                $result = WBBCore::getDB()->getFirstRow($sql);
                $cntTodayOnline = $result['cntTodayOnline'];
            }

            if(LASTONLINEBOX_SHOWUSERMARKING_ACP) {
                $sql = "SELECT wcu.userID, wcu.username, wcu.lastActivityTime, wcuo.userOption".User::getUserOptionID('invisible')." isInvisible, wcg.userOnlineMarking"
                        ."\n  FROM wcf".WCF_N."_user wcu"
                        ."\n  LEFT JOIN wcf".WCF_N."_group wcg ON (wcg.groupID = wcu.userOnlineGroupID)";
            } else {
                $sql = "SELECT wcu.userID, wcu.username, wcu.lastActivityTime, wcuo.userOption".User::getUserOptionID('invisible')." isInvisible"
                        ."\n  FROM wcf".WCF_N."_user wcu";
            }
            $sql .= "\n  LEFT JOIN wcf".WCF_N."_user_option_value wcuo ON (wcuo.userID = wcu.userID)"
                   ."\n WHERE wcu.lastActivityTime >= ".$todayStartTime
                   ."\n ORDER BY wcu.lastActivityTime DESC";
            if(LASTONLINEBOX_NUMOFUSER_ACP > 0 && empty($showAllOU)) $sql .= "\n  LIMIT 0, ".LASTONLINEBOX_NUMOFUSER_ACP;
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                if($row['isInvisible'] && !WCF::getUser()->getPermission('admin.general.canViewInvisible')) continue;
                if(LASTONLINEBOX_SHOWUSERMARKING_ACP && $row['userOnlineMarking'] != '%s') {
                    $row['username'] = sprintf($row['userOnlineMarking'], StringUtil::encodeHTML($row['username']));
                    if($row['isInvisible']) $row['username'] .= WCF::getLanguage()->get('wcf.usersOnline.invisible');
                } else {
                    $row['username'] = StringUtil::encodeHTML($row['username']);
                }
                $this->lastOnline[$i]['userID']            = $row['userID'];
                $this->lastOnline[$i]['username']          = $row['username'];
                $this->lastOnline[$i]['plainname']         = $row['username'];
                $this->lastOnline[$i]['lastActivityTime']  = $row['lastActivityTime'];
                $i++;
            }
            $this->getSortedByNames();
        }
        WCF::getTPL()->assign('LASTONLINEBOX_SBCOLOR_ACP', intval(LASTONLINEBOX_SBCOLOR_ACP));
        WCF::getTPL()->assign('LASTONLINEBOX_MAXHEIGHT_ACP', intval(LASTONLINEBOX_MAXHEIGHT_ACP));
        WCF::getTPL()->assign('LASTONLINEBOX_SHOWTIME_ACP', LASTONLINEBOX_SHOWTIME_ACP);
        WCF::getTPL()->assign('lastOnline', $this->lastOnline);
        WCF::getTPL()->assign('lastOnlineByName', $this->lastOnlineByName);
        WCF::getTPL()->assign('canSeeLastOnlineBox', WCF::getUser()->getPermission('user.board.canSeeLastOnlineBox'));
        WCF::getTPL()->assign('cntTodayOnline', $cntTodayOnline);
        WCF::getTPL()->assign('showAllOU', $showAllOU);
        WCF::getTPL()->assign('curPage', $curPage);
    }

    protected function getSortedByNames() {
    	$names = $this->lastOnlineByName = array();
    	foreach($this->lastOnline as $key => $row) {
    		$names[$key] = $row['plainname'];
    	}
    	StringUtil::sort($names);
    	foreach($names as $key => $row) {
    		$this->lastOnlineByName[] = $this->lastOnline[$key];
    	}
    }

	protected function getBoxStatus($data) {
		// get box status
		$this->LastOnlineBoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->LastOnlineBoxData['Status'] = intval(WBBCore::getUser()->lastonlinebox);
		}
		else {
			if (WBBCore::getSession()->getVar('lastonlinebox') != false) {
				$this->LastOnlineBoxData['Status'] = WBBCore::getSession()->getVar('lastonlinebox');
			}
		}
	}

	public function getData() {
		return $this->LastOnlineBoxData;
	}
}

?>
