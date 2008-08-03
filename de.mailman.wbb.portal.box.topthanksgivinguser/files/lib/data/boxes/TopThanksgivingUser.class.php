<?php
class TopThanksgivingUser {
	protected $TopData = array();

	public function __construct($data, $boxname = "") {
		$this->TopData['templatename'] = "topthanksgivinguser";
		$this->getBoxStatus($data);
		$this->TopData['boxID'] = $data['boxID'];

        if(!defined('TOPTHANKSGIVINGUSER_COUNT_ACP'))       define('TOPTHANKSGIVINGUSER_COUNT_ACP', 10);
        if(!defined('TOPTHANKSGIVINGUSER_TITLELENGTH_ACP')) define('TOPTHANKSGIVINGUSER_TITLELENGTH_ACP', 28);
        if(!defined('TOPTHANKSGIVINGUSER_SBCOLOR_ACP'))     define('TOPTHANKSGIVINGUSER_SBCOLOR_ACP', 2);
        if(!defined('TOPTHANKSGIVINGUSER_HITS_ACP'))        define('TOPTHANKSGIVINGUSER_HITS_ACP', true);

        $sql = "SELECT wcf.userID, wcf.username, wbb.thanks_got"
            ."\n  FROM wcf".WCF_N."_user wcf"
            ."\n  LEFT JOIN wbb".WBB_N."_user wbb ON (wbb.userid = wcf.userid)"
            ."\n WHERE wbb.thanks_got > 0"
            ."\n ORDER BY wbb.thanks_got DESC, wcf.username"
            ."\n LIMIT 0, ".TOPTHANKSGIVINGUSER_COUNT_ACP;

        $result = WBBCore::getDB()->sendQuery($sql);
        while($row = WBBCore::getDB()->fetchArray($result)) {
            $plainUser = $row['username'];
            $row['thanks_got'] = StringUtil::formatInteger($row['thanks_got']);
            if(TOPTHANKSGIVINGUSER_TITLELENGTH_ACP != 0 && strlen($plainUser) > TOPTHANKSGIVINGUSER_TITLELENGTH_ACP) $row['username'] = StringUtil::substring($plainUser,0,(TOPTHANKSGIVINGUSER_TITLELENGTH_ACP-3)).'...';
            $row['username'] = StringUtil::encodeHTML($row['username']);
            $this->TopData['thanksgivinguser'][] = $row;
        }

        WCF::getTPL()->assign('TOPTHANKSGIVINGUSER_SBCOLOR_ACP', intval(TOPTHANKSGIVINGUSER_SBCOLOR_ACP));
        WCF::getTPL()->assign('TOPTHANKSGIVINGUSER_HITS_ACP', TOPTHANKSGIVINGUSER_HITS_ACP);
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->TopData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->TopData['Status'] = intval(WBBCore::getUser()->topthanksgivinguser);
		}
		else {
			if (WBBCore::getSession()->getVar('topthanksgivinguser') != false) {
				$this->TopData['Status'] = WBBCore::getSession()->getVar('topthanksgivinguser');
			}
		}
	}

	public function getData() {
		return $this->TopData;
	}
}

?>
