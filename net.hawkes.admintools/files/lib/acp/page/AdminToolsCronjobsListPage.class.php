<?php
require_once(WCF_DIR.'lib/acp/page/CronjobsListPage.class.php');

class AdminToolsCronjobsListPage extends CronjobsListPage  {
	public $templateName = 'adminToolsCronjobs';
	
	
	/**
	 * Gets the list of cronjobs.
	 */
	protected function readCronjobs() {
		$sql = "SELECT payload.cronjobID FROM wcf".WCF_N."_admin_tools_cronjob_payload payload,
									wcf".WCF_N."_package_dependency package_dependency
										WHERE 		payload.packageID = package_dependency.dependency
										AND package_dependency.packageID = ".PACKAGE_ID;
		$result = WCF::getDB()->sendQuery($sql);
		$cronjobIDs = array();
		while($row = WCF::getDB()->fetchArray($result)) {
			$cronjobIDs[] = $row['cronjobID'];
		}
		$sql = "SELECT		cronjobs.*, payload.*
			FROM		wcf".WCF_N."_cronjobs cronjobs
			LEFT JOIN	wcf".WCF_N."_admin_tools_cronjob_payload payload
			ON (payload.cronjobID = cronjobs.cronjobID)
			WHERE 	cronjobs.cronjobID IN(".implode(',', $cronjobIDs).")
			ORDER BY	cronjobs.".$this->sortField." ".$this->sortOrder;		
		$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo - 1) * $this->itemsPerPage);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['editable'] = WCF::getUser()->getPermission('admin.system.cronjobs.canEditCronjob') && $row['canBeEdited'];
			$row['deletable'] = WCF::getUser()->getPermission('admin.system.cronjobs.canDeleteCronjob') && $row['canBeEdited'];
			$row['enableDisable'] = WCF::getUser()->getPermission('admin.system.cronjobs.canEnableDisableCronjob') && $row['canBeDisabled'];
				
			$this->cronjobs[] = $row;
		}		
	}

	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		SortablePage::countItems();

		// count cronjobs
		$sql = "SELECT COUNT(payload.cronjobID) AS count FROM wcf".WCF_N."_admin_tools_cronjob_payload payload,
									wcf".WCF_N."_package_dependency package_dependency
										WHERE 		payload.packageID = package_dependency.dependency
										AND package_dependency.packageID = ".PACKAGE_ID;
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