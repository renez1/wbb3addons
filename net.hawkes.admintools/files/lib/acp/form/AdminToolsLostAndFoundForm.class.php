<?php
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

class AdminToolsLostAndFoundPage extends SortablePage  {
	public $activeMenuItem = 'wcf.acp.menu.link-admintools.lostandfound';
	public $templateName = 'adminToolsLostAndFound';
	public $activeTabMenuItem = 'backup';
	public $activeSubTabMenuItem = 'database';
	
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['activeTabMenuItem'])) $this->activeTabMenuItem = StringUtil::trim($_GET['activeTabMenuItem']);
		if (isset($_GET['activeSubTabMenuItem'])) $this->activeSubTabMenuItem = StringUtil::trim($_GET['activeSubTabMenuItem']);
	}
	
	
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array('activeTabMenuItem' => $this->activeTabMenuItem,
									'activeSubTabMenuItem' => $this->activeSubTabMenuItem));
	}
}

?>