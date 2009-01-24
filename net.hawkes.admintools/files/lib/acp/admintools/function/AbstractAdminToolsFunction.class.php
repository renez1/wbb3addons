<?php
require_once(WCF_DIR.'lib/acp/admintools/function/AdminToolsFunction.class.php');
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');

/**
 * This class is very similar to the abstract action but I don't want that eventlisteners on AbstractAction activate here
 */
abstract class AbstractAdminToolsFunction implements AdminToolsFunction {		
	public $data;
	
	/**
	 * Executes the function
	 * 
	 * @param array $data
	 * @return integer $state
	 */
	public function execute($data) {
		$this->data = &$data;
		EventHandler::fireAction($this, 'execute');
	}
	
	protected function setReturnMessage($type = 'success', $message) {
		$message = array();
		if(is_array(WCF::getSession()->getVar('functionReturnMessage'))) {
			$message = WCF::getSession()->getVar('functionReturnMessage');
		}
		$message[$this->data['functionID']] = array($type => $message);
		WCF::getSession()->register('functionReturnMessage', $message);
	}		
	
	protected function executed() {
		EventHandler::fireAction($this, 'executed');
	}
}
?>