<?php
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AbstractLostAndFoundDatabaseItem.class.php');
require_once(WCF_DIR.'lib/data/attachment/AttachmentEditor.class.php');

class AttachmentsDatabaseLostAndFoundItem extends AbstractLostAndFoundDatabaseItem {	
		
	public function __construct($attachmentID) {
		parent::__construct('attachmentsDatabase', $attachmentID);
	}
	
	public function delete() {
		$editor = new AttachmentEditor($this->objectID);
		$editor->delete();
	}
	
	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('attachmentsDatabase');		
		foreach($itemIDs as $itemID) {
			$item = new AttachmentsDatabaseLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>