<?php
require_once(WCF_DIR.'lib/acp/action/CronjobsEnableAction.class.php');

class AdminToolsCronjobsEnableAction extends CronjobsEnableAction {
	
	protected function executed() {
		parent::executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=AdminToolsCronjobsList&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>