<?php
class wbb3addonsBox01 {
	protected $BoxData = array();
	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "wbb3addonsBox01";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

		if(!defined('WBB3ADDONSBOX01_TITLE'))       define('WBB3ADDONSBOX01_TITLE', '');
		if(!defined('WBB3ADDONSBOX01_BOXOPENED'))   define('WBB3ADDONSBOX01_BOXOPENED', false);
		if(!defined('WBB3ADDONSBOX01_VALUE'))       define('WBB3ADDONSBOX01_VALUE', '');
		if(!defined('WBB3ADDONSBOX01_PHP'))         define('WBB3ADDONSBOX01_PHP', false);

		if(WBB3ADDONSBOX01_BOXOPENED == true) $this->BoxData['Status'] = 1;

		// php?! *******************************************
        if(WBB3ADDONSBOX01_PHP) {
            ob_start();
    		eval(WBB3ADDONSBOX01_VALUE);
            $val = ob_get_clean();
        } else {
    		$val = WBB3ADDONSBOX01_VALUE;
        }

        WCF::getTPL()->assign(array(
            'wbb3addonsBox01Title' => WBB3ADDONSBOX01_TITLE,
            'wbb3addonsBox01Value' => $val,
            'wbb3addonsBox01Opened' => WBB3ADDONSBOX01_BOXOPENED
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->wbb3addonsBox01);
		}
		else {
			if (WBBCore::getSession()->getVar('wbb3addonsBox01') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('wbb3addonsBox01');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
