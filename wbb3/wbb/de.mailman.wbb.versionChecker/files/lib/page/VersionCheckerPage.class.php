<?php
/* $Id$ */
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

class VersionCheckerPage extends AbstractPage {
	public $templateName = 'versionChecker';
	public $logFile = '';
	public $verFirst = '1.0.0';
	public $verSecond = '1.0.0 PL1';
	public $verResult = -2;

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if(!empty($_POST['action']) && $_POST['action'] == 'compare') {
		    if(!empty($_POST['version1']) && !empty($_POST['version2'])) {
                require_once(WCF_DIR.'lib/acp/package/Package.class.php');
		        $this->verResult = Package::compareVersion($_POST['version1'], $_POST['version2']);
//		        $this->verResult = version_compare($_POST['version1'], $_POST['version2']);
		        $this->verFirst = $_POST['version1'];
		        $this->verSecond = $_POST['version2'];
		        if($this->logFile) {
		            if($fh = @fopen(WBB_DIR.'/'.$this->logFile, 'a')) {
		                $u = (WCF::getUser()->username ? WCF::getUser()->username : 'Guest');
		                fwrite($fh, TIME_NOW.'||'.$u.'||'.$this->verFirst.'||'.$this->verSecond."\n");
		                fclose($fh);
		            }
		        }
		    }
		}
	}

	/**
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array(
			'verResult' => $this->verResult,
			'version1' => $this->verFirst,
			'version2' => $this->verSecond
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();
	}
}
?>
