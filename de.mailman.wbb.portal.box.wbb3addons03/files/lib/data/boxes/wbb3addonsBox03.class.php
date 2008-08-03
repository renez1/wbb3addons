<?php
class wbb3addonsBox03 {
	protected $BoxData = array();
	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "wbb3addonsBox03";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

		if(!defined('WBB3ADDONSBOX03_TITLE'))       define('WBB3ADDONSBOX03_TITLE', '');
		if(!defined('WBB3ADDONSBOX03_BOXOPENED'))   define('WBB3ADDONSBOX03_BOXOPENED', false);
		if(!defined('WBB3ADDONSBOX03_VALUE'))       define('WBB3ADDONSBOX03_VALUE', '');
		if(!defined('WBB3ADDONSBOX03_PHP'))         define('WBB3ADDONSBOX03_PHP', false);

		if(WBB3ADDONSBOX03_BOXOPENED == true) $this->BoxData['Status'] = 1;

		// php?! *******************************************
        if(WBB3ADDONSBOX03_PHP) {
            ob_start();
    		eval(WBB3ADDONSBOX03_VALUE);
            $val = ob_get_clean();
        } else {
    		$val = WBB3ADDONSBOX03_VALUE;
        }

        WCF::getTPL()->assign(array(
            'wbb3addonsBox03Title' => WBB3ADDONSBOX03_TITLE,
            'wbb3addonsBox03Value' => $val,
            'wbb3addonsBox03Opened' => WBB3ADDONSBOX03_BOXOPENED
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->wbb3addonsBox03);
		}
		else {
			if (WBBCore::getSession()->getVar('wbb3addonsBox03') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('wbb3addonsBox03');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
