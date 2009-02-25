<?php
class LinkListBox {
	protected $BoxData = array();
	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "linklistbox";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

		if(!defined('LINKLISTBOX_VALUE')) define('LINKLISTBOX_VALUE', '');
		if(!defined('LINKLISTBOX_LOCKED')) define('LINKLISTBOX_LOCKED', false);
		if(!defined('LINKLISTBOX_SPACER')) define('LINKLISTBOX_SPACER', 1);

        $linkList = preg_split("/\r?\n/", LINKLISTBOX_VALUE);
        $links = array();
        $i = 0;
        foreach($linkList as $line) {
            $line = trim($line);
            if(preg_match("/\{SPACER\}/", $line)) {
                $links[$i]['TYPE'] = 'SPACER';
                $links[$i]['SPACER'] = preg_replace("/\{SPACER\}(.*)\{\/SPACER\}/i", "$1", $line);
                $i++;
            } else if(preg_match("/\|/", $line)) {
                list($img,$url,$title,$target,$perm) = preg_split("/\|/", $line,5);
                $img = trim($img);
                $url = trim($url);
                $title = trim($title);
                $target = trim($target);
                $perm = trim($perm);
                if(!empty($url) && !empty($title)) {
                    if(preg_match("/\{\@?RELATIVE_WBB_DIR\}/", $img) && defined('RELATIVE_WBB_DIR')) $img = preg_replace("/{\@?RELATIVE_WBB_DIR\}/", RELATIVE_WBB_DIR, $img);
                    if(preg_match("/\{\@?RELATIVE_WCF_DIR\}/", $img) && defined('RELATIVE_WCF_DIR')) $img = preg_replace("/{\@?RELATIVE_WCF_DIR\}/", RELATIVE_WCF_DIR, $img);
                    if(preg_match("/\{\@?RELATIVE_WBB_DIR\}/", $url) && defined('RELATIVE_WBB_DIR')) $url = preg_replace("/{\@?RELATIVE_WBB_DIR\}/", RELATIVE_WBB_DIR, $url);
                    if(preg_match("/\{\@?RELATIVE_WCF_DIR\}/", $url) && defined('RELATIVE_WCF_DIR')) $url = preg_replace("/{\@?RELATIVE_WCF_DIR\}/", RELATIVE_WCF_DIR, $url);
                    if(preg_match("/\{\@?PACKAGE_ID\}/", $url) && defined('PACKAGE_ID')) $url = preg_replace("/{\@?PACKAGE_ID\}/", PACKAGE_ID, $url);
                    if(preg_match("/\{\@?SID_ARG_2ND\}/", $url) && defined('SID_ARG_2ND')) $url = preg_replace("/{\@?SID_ARG_2ND\}/", SID_ARG_2ND, $url);
                    $links[$i]['TYPE'] = 'LINK';
                    $links[$i]['IMG'] = $img;
                    $links[$i]['URL'] = $url;
                    $links[$i]['TITLE'] = $title;
                    $links[$i]['TARGET'] = $target;
                    $links[$i]['PERM'] = $perm;
                    $i++;
                }
            }
        }

        WCF::getTPL()->assign(array(
            'linkListBoxLinks' => $links,
            'linkListBoxSpacer' => LINKLISTBOX_SPACER,
            'linkListBoxlocked' => LINKLISTBOX_LOCKED
        ));

		if(LINKLISTBOX_LOCKED == true) $this->BoxData['Status'] = 1;
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->linklistbox);
		}
		else {
			if (WBBCore::getSession()->getVar('linklistbox') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('linklistbox');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
