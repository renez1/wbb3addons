<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

class AdminToolsPHPInfoPage extends AbstractPage {
	public $templateName = 'adminToolsPhpInfo';
	public $diskInformation = array();
	public $phpInfoOutput = '';

	public function readData() {
		parent::readData();
		
		$this->diskInformation = AdminToolsUtil::readDiskInfo();
		$this->readPHPInfo();
	}
	
	protected function readPHPInfo() {
		ob_start();
        phpinfo();
        $info = ob_get_clean();
        preg_match ("/.*<body>(.*)<\/body>/s", $info, $matches);
        if(isset($matches[1])) $this->phpInfoOutput = $matches[1];
        $this->phpInfoOutput = str_replace("width=\"600\"", "width=\"795\"", $this->phpInfoOutput);
	}
	
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(    		
			'phpInfoOutput' => $this->phpInfoOutput,
			'diskInformation' => $this->diskInformation
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {    
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.admintools.phpinfo');

		// show page
		parent::show();
	}
}
?>