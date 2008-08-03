<?php
class TopPosterBox {
	protected $TopPosterData = array();

	public function __construct($data, $boxname = "") {
		$this->TopPosterData['templatename'] = "topposter";
		$this->getBoxStatus($data);
		$this->TopPosterData['boxID'] = $data['boxID'];

        if(!defined('TOPPOSTER_COUNT'))         define('TOPPOSTER_COUNT', 10);
        if(!defined('TOPPOSTER_SBCOLOR_ACP'))   define('TOPPOSTER_SBCOLOR_ACP', 2);
        WCF::getTPL()->assign('TOPPOSTER_SBCOLOR_ACP', intval(TOPPOSTER_SBCOLOR_ACP));

    	$sql = "SELECT wcu.userid, wcu.username, wbu.posts"
            ."\n  FROM wcf".WCF_N."_user wcu"
            ."\n  LEFT JOIN wbb".WBB_N."_user wbu ON (wbu.userid = wcu.userid)"
            ."\n  ORDER BY wbu.posts DESC, wcu.username"
            ."\n  LIMIT 0, ".TOPPOSTER_COUNT;
   		$result = WBBCore::getDB()->sendQuery($sql);
   	    while ($row = WBBCore::getDB()->fetchArray($result)) {
                $row['username'] = StringUtil::encodeHTML($row['username']);
                $row['posts'] = StringUtil::formatInteger($row['posts']);
                $this->TopPosterData['users'][] = $row;
        }
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->TopPosterData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->TopPosterData['Status'] = intval(WBBCore::getUser()->topposter);
		}
		else {
			if (WBBCore::getSession()->getVar('topposter') != false) {
				$this->TopPosterData['Status'] = WBBCore::getSession()->getVar('topposter');
			}
		}
	}

	public function getData() {
		return $this->TopPosterData;
	}
}

?>
