<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');


class AdminToolsiFramePage extends AbstractPage {
	public $templateName = 'adminToolsiFrame';
	public $iFrameID = 0;
	public $iFrameData = array();
	
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['iFrameID'])) $this->iFrameID = intval($_GET['iFrameID']);
		else {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
	}
	
	public function readData() {
		parent::readData();
		
		$sql = "SELECT item.menuItem, iframe.* FROM wcf".WCF_N."_admin_tools_iframe iframe
				LEFT JOIN			wcf".WCF_N."_acp_menu_item item
				ON (item.menuItemID = iframe.menuItemID)
				WHERE iframeID = ".$this->iFrameID;
		$this->iFrameData = WCF::getDB()->getFirstRow($sql);
	}
	
	public function assignVariables() {
		parent::assignVariables();
		
		WCFACP::getMenu()->setActiveMenuItem($this->iFrameData['menuItem']);
		
		WCF::getTPL()->assign(array('iFrameData' => $this->iFrameData));
	}
}
?>