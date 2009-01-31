<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Performs AJAX actions
 * 
 */
class AdminToolsLostAndFoundActionPage extends AbstractPage {	
	public $itemID = 0;
	public $item;
	public $items = array();
	public $url = '';
	public $classname = '';
	public $pagename = '';	
	public static $validFunctions = array('mark', 'unmark', 'delete', 'unmarkAll', 'deleteAll');
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['classname'])) $this->classname = $_REQUEST['classname'];
		if (isset($_REQUEST['pagename'])) $this->pagename = $_REQUEST['pagename'];
		if (isset($_REQUEST[$this->pagename.'ID'])) $this->itemID = ArrayUtil::toIntegerArray($_REQUEST[$this->pagename.'ID']);
		if (isset($_REQUEST['itemID'])) $this->itemID = ArrayUtil::toIntegerArray($_REQUEST['itemID']);
		if (isset($_REQUEST['url'])) $this->url = $_REQUEST['url'];						
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();
			
		require_once(WCF_DIR.'lib/acp/admintools/lostandfound/'.$this->classname.'.class.php');
		if(is_array($this->itemID)) {
			foreach($this->itemID as $itemID) {				
				$this->items[] = new $this->classname($itemID);
			}
		}
		else $this->item = new $this->classname($this->itemID);		
		if (in_array($this->action, self::$validFunctions)) {			
			$this->{$this->action}();
		}					
		
	}
	
	public function mark() {
		if(is_array($this->itemID)) {
			foreach($this->items as $item) {
				$item->mark();
			}
		}
		else $this->item->mark();
	}
	
	public function unmark() {
		if(is_array($this->itemID)) {
			foreach($this->items as $item) {
				$item->unmark();
			}
		}
		else $this->item->unmark();
	}
	
	public function unmarkAll() {
		if(version_compare(PHP_VERSION, "5.2.3") < 0) {
			call_user_func($this->classname, 'unmarkAll', $this->pagename);
		}
		else call_user_func($this->classname.'::unmarkAll', $this->pagename);
	}
	
	public function delete() {
		$this->item->delete();
		$this->item->unmark();
		if(!empty($this->url)) {
			HeaderUtil::redirect($this->url);
		}
	}
	
	public function deleteAll() {
		if(version_compare(PHP_VERSION, "5.2.3") < 0) {
			call_user_func($this->classname, 'deleteAll', $this->pagename);
		}
		else call_user_func($this->classname.'::deleteAll', $this->pagename);
		$this->unmarkAll();
		if(!empty($this->url)) {
			HeaderUtil::redirect($this->url);
		}
	}

}
?>