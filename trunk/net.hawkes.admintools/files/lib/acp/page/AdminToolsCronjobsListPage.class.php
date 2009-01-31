<?php
require_once(WCF_DIR.'lib/acp/page/CronjobsListPage.class.php');

class AdminToolsCronjobsListPage extends CronjobsListPage  {
	public $templateName = 'adminToolsCronjobs';
	
	
	/**
	 * Gets the list of cronjobs.
	 */
	protected function readCronjobs() {
		parent::readCronjobs();

		// filter non admin tools cronjobs
		$cronjobIDs = array();
		foreach($this->cronjobs as $cronjob) {
			$cronjobIDs[]  = $cronjob['cronjobID'];
		}
		
		$sql = "SELECT DISTINCT cronjobID FROM wcf".WCF_N."_admin_tools_function_to_cronjob
				WHERE cronjobID IN (".implode(',', $cronjobIDs).")";
		$result = WCF::getDB()->sendQuery($sql);
		
		$adminToolsCronjobIDs = array();
		while($row = WCF::getDB()->fetchArray($result)) {
			$adminToolsCronjobIDs[] = $row['cronjobID'];
		}
				
		foreach($this->cronjobs as $key => $cronjob) {
			if(!in_array($cronjob['cronjobID'], $adminToolsCronjobIDs)) {
				unset($this->cronjobs[$key]);
			}
		}
	}

	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		SortablePage::countItems();
				
		$sql = "SELECT COUNT(DISTINCT cronjobID) AS count FROM wcf".WCF_N."_admin_tools_function_to_cronjob";
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// set active menu item.
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.admintools.cronjobs');

		// check permission
		WCF::getUser()->checkPermission(array('admin.system.cronjobs.canEditCronjob', 'admin.system.cronjobs.canDeleteCronjob', 'admin.system.cronjobs.canEnableDisableCronjob'));		
		SortablePage::show();
	}
}
?>