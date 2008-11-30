<?php
/* $Id$ */
class xmasBox {
	protected $BoxData = array();
	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "xmasBox";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

		if(!defined('XMASBOX_TITLE'))       define('XMASBOX_TITLE', 'Xmas-Box');
		if(!defined('XMASBOX_BG'))          define('XMASBOX_BG', 1);
		if(!defined('XMASBOX_SPEAKER'))     define('XMASBOX_SPEAKER', 1);

		if(XMASBOX_BOXOPENED == true || XMASBOX_HEADER == true) $this->BoxData['Status'] = 1;
		
		# Länderkürzel nach ISO 639-1
		$xmasbox_value = array(
                                'af',
                                'al',
                                'am',
                                'ar',
                                'ba',
                                'bg',
                                'br',
                                'by',
                                'ca',
                                'cs',
                                'da',
                                'de',
                                'el',
                                'en',
                                'eo',
                                'es',
                                'et',
                                'fi',
                                'fo',
                                'fr',
                                'ga',
                                'he',
                                'hr',
                                'hu',
                                'id',
                                'is',
                                'it',
                                'ja',
                                'ko',
                                'lu',
                                'nl',
                                'pl',
                                'pt',
                                'ro',
                                'ru',
                                'sk',
                                'sr',
                                'sv',
                                'tl',
                                'tr',
                                'uk',
                                'ur',
                                'vi',
                                'zh-guoyu',
                                'zh'
		);
	    $rand = array_rand($xmasbox_value, 1);
	    $value = $xmasbox_value[$rand];
	    define('XMASBOX_VALUE', $value);
	    $lang = WCF::getLanguage()->get('wbb.portal.box.xmasbox.lang_'.$xmasbox_value[$rand]);
	    define('XMASBOX_LANG', $lang);
	    
		if(XMASBOX_BG != 5){
		    switch(XMASBOX_BG){
		        case 1: define('XMASBOX_BACKGROUND', 'winterland.jpg');
		            break;
		        case 2: define('XMASBOX_BACKGROUND', 'kugeln.jpg');
		            break;
		        case 3: define('XMASBOX_BACKGROUND', 'dekoration.jpg');
		            break;
		        case 4: define('XMASBOX_BACKGROUND', 'eiskristall.jpg');
		            break;
		    }
		}
		else{
		    $bg = array(
		                'winterland.jpg',
		                'kugeln.jpg',
		                'dekoration.jpg',
		                'eiskristall.jpg'
		    );
		    shuffle($bg);
		    define('XMASBOX_BACKGROUND', $bg[0]);
		}
				
		if(XMASBOX_SPEAKER != 4){
		    switch(XMASBOX_SPEAKER){
		        case 1: define('XMASBOX_ANSAGER', 'weihnachtsmann.png');
		            break;
		        case 2: define('XMASBOX_ANSAGER', 'schneemann.png');
		            break;
		        case 3: define('XMASBOX_ANSAGER', 'rudolph.png');
		            break;
		    }
		}
		else{
		    $speaker = array(
		                'weihnachtsmann.png',
		                'schneemann.png',
		                'rudolph.png'
		    );
		    shuffle($speaker);
		    define('XMASBOX_ANSAGER', $speaker[0]);
		}

        WCF::getTPL()->assign(array(
            'xmasBoxTitle' => XMASBOX_TITLE,
            'xmasBoxOpened' => XMASBOX_BOXOPENED
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->xmasBox);
		}
		else {
			if (WBBCore::getSession()->getVar('xmasBox') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('xmasBox');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
