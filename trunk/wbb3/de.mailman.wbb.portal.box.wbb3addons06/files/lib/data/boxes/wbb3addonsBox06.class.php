<?php
class wbb3addonsBox06 {
	protected $BoxData = array();
	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "wbb3addonsBox06";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

		if(!defined('WBB3ADDONSBOX06_TITLE'))       define('WBB3ADDONSBOX06_TITLE', '');
		if(!defined('WBB3ADDONSBOX06_BOXOPENED'))   define('WBB3ADDONSBOX06_BOXOPENED', false);
		if(!defined('WBB3ADDONSBOX06_VALUE'))       define('WBB3ADDONSBOX06_VALUE', '');
		if(!defined('WBB3ADDONSBOX06_PHP'))         define('WBB3ADDONSBOX06_PHP', false);

		if(WBB3ADDONSBOX06_BOXOPENED == true) $this->BoxData['Status'] = 1;

		// php?! *******************************************
        if(WBB3ADDONSBOX06_PHP) {
            ob_start();
    		eval(WBB3ADDONSBOX06_VALUE);
            $val = ob_get_clean();
        } else {
    		$val = WBB3ADDONSBOX06_VALUE;
        }

        WCF::getTPL()->assign(array(
            'wbb3addonsBox06Title' => WBB3ADDONSBOX06_TITLE,
            'wbb3addonsBox06Value' => $val,
            'wbb3addonsBox06Opened' => WBB3ADDONSBOX06_BOXOPENED
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->wbb3addonsBox06);
		}
		else {
			if (WBBCore::getSession()->getVar('wbb3addonsBox06') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('wbb3addonsBox06');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
