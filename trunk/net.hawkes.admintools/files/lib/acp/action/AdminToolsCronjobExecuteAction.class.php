<?php
require_once(WCF_DIR.'lib/acp/action/CronjobExecuteAction.class.php');

class AdminToolsCronjobExecuteAction extends CronjobExecuteAction {
	
	protected function executed() {
		parent::executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=AdminToolsCronjobsList&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>