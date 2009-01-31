<?php
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AbstractLostAndFoundDatabaseItem.class.php');
require_once(WCF_DIR.'lib/data/user/avatar/AvatarEditor.class.php');

class AvatarsDatabaseLostAndFoundItem extends AbstractLostAndFoundDatabaseItem {	
		
	public function __construct($avatarID) {
		parent::__construct('avatarsDatabase', $avatarID);
	}
	
	public function delete() {
		$editor = new AvatarEditor($this->objectID);		
		$editor->delete();
	}
	
	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('avatarsDatabase');		
		foreach($itemIDs as $itemID) {
			$item = new AvatarsDatabaseLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>