<?php
/* $Id$ */
class ProfileLastVisitorsBox {
	protected $ProfileLastVisitorsData = array();
	public $visitors = array();

	public function __construct($data, $boxname = "") {
		$this->ProfileLastVisitorsData['templatename'] = "ProfileLastVisitorsBox";
		$this->getBoxStatus($data);
		$this->ProfileLastVisitorsData['boxID'] = $data['boxID'];

        $sql = "SELECT profile.userID, profile.username, profile.time, user.lastActivityTime"
            ."\n  FROM wcf".WCF_N."_profile_lastvisitors profile"
            ."\n  LEFT OUTER JOIN wcf".WCF_N."_user user ON (user.userID = profile.userID)"
            ."\n WHERE profileID = ".WBBCore::getUser()->userID
            ."\n ORDER BY time DESC"
            ."\n LIMIT 0 , ".SHOW_LASTVISITOR_AMOUNT;
        $result = WCF::getDB()->sendQuery($sql);
        while ($row = WCF::getDB()->fetchArray($result)) {
        	$this->visitors[] = $row;
        }

		WCF::getTPL()->assign(array(
			'visitors' => $this->visitors
		));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->ProfileLastVisitorsData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->ProfileLastVisitorsData['Status'] = intval(WBBCore::getUser()->ProfileLastVisitorsBox);
		}
		else {
			if (WBBCore::getSession()->getVar('ProfileLastVisitorsBox') != false) {
				$this->ProfileLastVisitorsData['Status'] = WBBCore::getSession()->getVar('ProfileLastVisitorsBox');
			}
		}
	}

	public function getData() {
		return $this->ProfileLastVisitorsData;
	}
}

?>
