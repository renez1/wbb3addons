<?php
require_once(WCF_DIR.'lib/acp/action/CronjobsDeleteAction.class.php');

class AdminToolsCronjobsDeleteAction extends CronjobsDeleteAction {
	
	public function execute() {
		// check permission
		if (!$this->cronjob->canBeEdited) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
		WCF::getUser()->checkPermission('admin.system.cronjobs.canDeleteCronjob');
		
		$sql = "DELETE FROM wcf".WCF_N."_admin_tools_function_to_cronjob
				WHERE cronjobID = ".$this->cronjobID;
		WCF::getDB()->sendQuery($sql);
		
		parent::execute();
	}
	
	protected function executed() {
		parent::executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=AdminToolsCronjobsList&deleteJob='.$this->cronjobID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>