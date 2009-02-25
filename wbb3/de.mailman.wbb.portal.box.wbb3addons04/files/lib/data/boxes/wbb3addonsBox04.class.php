<?php
class wbb3addonsBox04 {
	protected $BoxData = array();
	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "wbb3addonsBox04";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

		if(!defined('WBB3ADDONSBOX04_TITLE'))       define('WBB3ADDONSBOX04_TITLE', '');
		if(!defined('WBB3ADDONSBOX04_BOXOPENED'))   define('WBB3ADDONSBOX04_BOXOPENED', false);
		if(!defined('WBB3ADDONSBOX04_VALUE'))       define('WBB3ADDONSBOX04_VALUE', '');
		if(!defined('WBB3ADDONSBOX04_PHP'))         define('WBB3ADDONSBOX04_PHP', false);

		if(WBB3ADDONSBOX04_BOXOPENED == true) $this->BoxData['Status'] = 1;

		// php?! *******************************************
        if(WBB3ADDONSBOX04_PHP) {
            ob_start();
    		eval(WBB3ADDONSBOX04_VALUE);
            $val = ob_get_clean();
        } else {
    		$val = WBB3ADDONSBOX04_VALUE;
        }

        WCF::getTPL()->assign(array(
            'wbb3addonsBox04Title' => WBB3ADDONSBOX04_TITLE,
            'wbb3addonsBox04Value' => $val,
            'wbb3addonsBox04Opened' => WBB3ADDONSBOX04_BOXOPENED
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->wbb3addonsBox04);
		}
		else {
			if (WBBCore::getSession()->getVar('wbb3addonsBox04') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('wbb3addonsBox04');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
