<?php
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/MarkableLostAndFoundItem.class.php');

abstract class AbstractLostAndFoundDatabaseItem implements MarkableLostAndFoundItem {
	public $itemName = '';
	public $editor;
	public $objectID = 0;
	
	public $filename = '';
	public $filesize = '';
	public $fileLastModTime = 0;
	public $user;
	
	public function __construct($itemName, $objectID) {
		$this->itemName = $itemName;		
		$this->objectID = $objectID;
	}

	public function mark() {
		$markedItems = self::getMarkedItems($this->itemName);
		if ($markedItems == null || !is_array($markedItems)) {
			$markedItems = array($this->objectID);				
			WCF::getSession()->register('marked'.ucfirst($this->itemName), $markedItems);
		}
		else {
			if (!in_array($this->objectID, $markedItems)) {				
				array_push($markedItems, $this->objectID);
				WCF::getSession()->register('marked'.ucfirst($this->itemName), $markedItems);
			}
		}
	}

	public function unmark() {
		$markedItems = self::getmarkedItems($this->itemName);
		if (is_array($markedItems) && in_array($this->objectID, $markedItems)) {
			$key = array_search($this->objectID, $markedItems);				
			unset($markedItems[$key]);
			if (count($markedItems) == 0) {
				self::unmarkAll($this->itemName);
			}
			else {
				WCF::getSession()->register('marked'.ucfirst($this->itemName), $markedItems);
			}
		}
	}

	public function isMarked() {
		$sessionVars = WCF::getSession()->getVars();
		if (isset($sessionVars['marked'.ucfirst($this->itemName)])) {
			if (in_array($this->objectID, $sessionVars['marked'.ucfirst($this->itemName)])) return 1;
		}
		
		return 0;
	}
	
	abstract public function delete();
	
	public static abstract function deleteAll();			
	
	public static function unmarkAll($itemName) {
		WCF::getSession()->unregister('marked'.ucfirst($itemName));
	}

	public static function getMarkedItems($itemName) {
		$sessionVars = WCF::getSession()->getVars();
		if (isset($sessionVars['marked'.ucfirst($itemName)])) {
			return $sessionVars['marked'.ucfirst($itemName)];
		}
		return null;
	}
}
?>