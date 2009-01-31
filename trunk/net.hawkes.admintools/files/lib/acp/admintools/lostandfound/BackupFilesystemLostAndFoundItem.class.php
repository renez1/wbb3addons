<?php
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AbstractLostAndFoundFileSystemItem.class.php');

class BackupFilesystemLostAndFoundItem extends AbstractLostAndFoundFileSystemItem {	
	
	public function __construct($backupID) {
		parent::__construct('backupFilesystem', $backupID);	
	}
	
	public static function createVirtualIDSpace() {
		$backups = array();
		chdir(WCF_DIR.'acp/backup');
		$dh = opendir(WCF_DIR.'acp/backup');				
		while($file = readdir ($dh)) {
			if($file != '.' && $file != '..' && $file != '.htaccess' && !is_dir($file)) {				
				$backups[] = $file;				
			}
		}
		closedir($dh);
		self::$virtualFileIDs['backupFilesystem'] = $backups;
		WCF::getSession()->register('virtualLostAndFoundIDs', self::$virtualFileIDs);
	}
	
	public function delete() {		
		if (isset(self::$virtualFileIDs['backupFilesystem'][$this->objectID])) {
			@unlink(WCF_DIR.'acp/backup/'.self::$virtualFileIDs['backupFilesystem'][$this->objectID]);
		}
	}
	
	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('backupFilesystem');		
		foreach($itemIDs as $itemID) {
			$item = new BackupFilesystemLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>