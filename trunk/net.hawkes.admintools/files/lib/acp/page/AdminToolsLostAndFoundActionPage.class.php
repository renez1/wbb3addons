<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Performs AJAX actions
 * 
 */
class AdminToolsLostAndFoundActionPage extends AbstractPage {	
	public $itemID = 0;	
	public $url = '';
	public $type = '';
	public $item;
	public static $validFunctions = array('mark', 'unmark', 'delete', 'unmarkAll', 'deleteAll');
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['type'])) $this->type = $_REQUEST['type'];
		if (isset($_REQUEST[$this->type.'ID'])) $this->itemID = ArrayUtil::toIntegerArray($_REQUEST[$this->type.'ID']);
		if (isset($_REQUEST['url'])) $this->url = $_REQUEST['url'];						
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();
				
		if (in_array($this->action, self::$validFunctions)) {			
			$this->{$this->action}();
		}
		
	}
	
	protected function mark() {
		$markedItemsData = WCF::getSession()->getVar('markedItems');
		
		if (!is_array($markedItemsData)) {
			$markedItemsData = array();
		}
		
		if (!isset($markedItemsData[$this->type])) {
			$markedItemsData[$this->type] = array();
		}

		
		if (is_array($this->itemID)) {
			$markedItemsData = array_merge($markedItemsData[$this->type], $this->itemID);
		}
		else $markedItemsData[$this->type][] = $this->itemID;
		fwrite(fopen("temp.tmp", "w"), serialize($this->itemID));		
		//$markedItemsData[$this->type] = array_unique($markedItemsData[$this->type]);
		
		WCF::getSession()->register('markedItems', $markedItemsData);
	}
	
	protected function unmark() {
		$markedItemsData = WCF::getSession()->getVar('markedItems');
		if (!is_array($markedItemsData)) {
			$markedItemsData = array();
		}
		
		if (!is_array($markedItemsData[$this->type])) {
			$markedItemsData[$this->type] = array();
		}
		if (is_array($this->itemID)) {
			$values = array_flip($markedItemsData[$this->type]);
			foreach($this->itemID as $item) {
				unset($values[$item]);
			}
			$markedItemsData[$this->type] = array_flip($values);
		}
		else {
			$values = array_flip($markedItemsData[$this->type]);
			unset($values[$this->item]);
			$markedItemsData[$this->type] = array_flip($values);
		}
		$markedItemsData[$this->type] = array_unique($markedItemsData[$this->type]);		
		WCF::getSession()->register('markedItems', $markedItemsData);
	}
	
	protected function unmarkAll() {
		$markedItemsData = WCF::getSession()->getVar('markedItems');
		if(isset($markedItemsData[$this->type])) {
			unset($markedItemsData[$this->type]);
		}		
		WCF::getSession()->register('markedItems', $markedItemsData);
	}
}
?>