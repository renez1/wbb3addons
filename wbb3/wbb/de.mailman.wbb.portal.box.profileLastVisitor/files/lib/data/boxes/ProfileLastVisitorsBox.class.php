<?php
/* $Id$ */
class ProfileLastVisitorsBox {
    protected $BoxData = array();
    public $visitors = array();

    public function __construct($data, $boxname = "") {
        $this->BoxData['templatename'] = "profileLastVisitorsBox";
        $this->getBoxStatus($data);
        $this->BoxData['boxID'] = $data['boxID'];

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
        $this->BoxData['Status'] = 1;
        if (WBBCore::getUser()->userID) {
            $this->BoxData['Status'] = intval(WBBCore::getUser()->profileLastVisitorsBox);
        }
        else {
            if (WBBCore::getSession()->getVar('profileLastVisitorsBox') != false) {
                $this->BoxData['Status'] = WBBCore::getSession()->getVar('profileLastVisitorsBox');
            }
        }
    }

    public function getData() {
        return $this->BoxData;
    }
}

?>
