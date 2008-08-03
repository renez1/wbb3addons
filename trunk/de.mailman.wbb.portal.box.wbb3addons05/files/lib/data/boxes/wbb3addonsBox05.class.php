<?php
class wbb3addonsBox05 {
	protected $BoxData = array();
	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "wbb3addonsBox05";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

		if(!defined('WBB3ADDONSBOX05_TITLE'))       define('WBB3ADDONSBOX05_TITLE', '');
		if(!defined('WBB3ADDONSBOX05_BOXOPENED'))   define('WBB3ADDONSBOX05_BOXOPENED', false);
		if(!defined('WBB3ADDONSBOX05_VALUE'))       define('WBB3ADDONSBOX05_VALUE', '');
		if(!defined('WBB3ADDONSBOX05_PHP'))         define('WBB3ADDONSBOX05_PHP', false);

		if(WBB3ADDONSBOX05_BOXOPENED == true) $this->BoxData['Status'] = 1;

		// php?! *******************************************
        if(WBB3ADDONSBOX05_PHP) {
            ob_start();
    		eval(WBB3ADDONSBOX05_VALUE);
            $val = ob_get_clean();
        } else {
    		$val = WBB3ADDONSBOX05_VALUE;
        }

        WCF::getTPL()->assign(array(
            'wbb3addonsBox05Title' => WBB3ADDONSBOX05_TITLE,
            'wbb3addonsBox05Value' => $val,
            'wbb3addonsBox05Opened' => WBB3ADDONSBOX05_BOXOPENED
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->wbb3addonsBox05);
		}
		else {
			if (WBBCore::getSession()->getVar('wbb3addonsBox05') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('wbb3addonsBox05');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
