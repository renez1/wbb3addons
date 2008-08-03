<?php
class wbb3addonsBox02 {
	protected $BoxData = array();
	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "wbb3addonsBox02";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

		if(!defined('WBB3ADDONSBOX02_TITLE'))       define('WBB3ADDONSBOX02_TITLE', '');
		if(!defined('WBB3ADDONSBOX02_BOXOPENED'))   define('WBB3ADDONSBOX02_BOXOPENED', false);
		if(!defined('WBB3ADDONSBOX02_VALUE'))       define('WBB3ADDONSBOX02_VALUE', '');
		if(!defined('WBB3ADDONSBOX02_PHP'))         define('WBB3ADDONSBOX02_PHP', false);

		if(WBB3ADDONSBOX02_BOXOPENED == true) $this->BoxData['Status'] = 1;

		// php?! *******************************************
        if(WBB3ADDONSBOX02_PHP) {
            ob_start();
    		eval(WBB3ADDONSBOX02_VALUE);
            $val = ob_get_clean();
        } else {
    		$val = WBB3ADDONSBOX02_VALUE;
        }

        WCF::getTPL()->assign(array(
            'wbb3addonsBox02Title' => WBB3ADDONSBOX02_TITLE,
            'wbb3addonsBox02Value' => $val,
            'wbb3addonsBox02Opened' => WBB3ADDONSBOX02_BOXOPENED
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->wbb3addonsBox02);
		}
		else {
			if (WBBCore::getSession()->getVar('wbb3addonsBox02') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('wbb3addonsBox02');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
