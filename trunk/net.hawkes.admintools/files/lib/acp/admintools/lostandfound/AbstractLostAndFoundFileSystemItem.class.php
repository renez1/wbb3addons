<?php
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AbstractLostAndFoundDatabaseItem.class.php');

abstract class AbstractLostAndFoundFileSystemItem extends AbstractLostAndFoundDatabaseItem {
	protected static $virtualFileIDs  = array();		
	public static $sessionCacheUsed = false;
	
	public $filename = '';
	public $filesize = '';
	public $fileLastModTime = 0;	
	
	public function __construct($itemName, $objectID) {
		if(!self::$sessionCacheUsed) {
			self::getVirtualIDsFromSession();
			self::$sessionCacheUsed = true;
		}
		parent::__construct($itemName, $objectID);
	}
	
	public function __destruct() {
		WCF::getSession()->register('virtualLostAndFoundIDs', self::$virtualFileIDs);
	}
	
	public static abstract function createVirtualIDSpace();
	
	public static function getVirtualIDs($type) {
		if(!self::$sessionCacheUsed) {
			self::getVirtualIDsFromSession();
			self::$sessionCacheUsed = true;
		}
		return isset(self::$virtualFileIDs[$type]) ? self::$virtualFileIDs[$type] : null; 
	}
	
	public static function getVirtualID($type, $filename) {
		if(!self::$sessionCacheUsed) {
			self::getVirtualIDsFromSession();
			self::$sessionCacheUsed = true;
		}
		if(isset(self::$virtualFileIDs[$type])) {			
			$fileIDs = array_flip(self::$virtualFileIDs[$type]);
			if(isset($fileIDs[$filename])) {
				return $fileIDs[$filename];
			}
		}
		else return null;
	}
	
	protected static function getVirtualIDsFromSession() {
		$sessionVars = WCF::getSession()->getVars();
		if(isset($sessionVars['virtualLostAndFoundIDs'])) {
			self::$virtualFileIDs = $sessionVars['virtualLostAndFoundIDs'];
		}
	}
}
?>