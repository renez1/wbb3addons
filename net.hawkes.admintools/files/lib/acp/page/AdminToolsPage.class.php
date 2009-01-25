<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');


class AdminToolsPage extends AbstractPage {
	public $templateName = 'adminTools';
	
	public function show() {
		// set active menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.admintools.index');
		
		parent::show();
	}
}
?>