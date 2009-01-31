<?php
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AbstractLostAndFoundFileSystemItem.class.php');
require_once(WCF_DIR.'lib/data/user/avatar/AvatarEditor.class.php');

class AvatarsFilesystemLostAndFoundItem extends AbstractLostAndFoundFileSystemItem {

	public function __construct($avatarID) {
		parent::__construct('avatarsFilesystem', $avatarID);
	}

	public static function createVirtualIDSpace() {
		$theAvatars = array();
		chdir(WCF_DIR.'images/avatars');
		$dh=opendir(WCF_DIR.'images/avatars');
		$avatarIDs = array();
		$avatars = array();
		while($file = readdir ($dh)) {
			if(preg_match("/^(avatar).*/",$file) && $file != '.' && $file != '..' && $file != '.htaccess' && !preg_match("/^.*\.php$/",$file)) {
				$avatarID = (int) preg_replace("/.*\-(\d+).*/", "$1", $file);
				$avatars[$avatarID] = preg_replace("/.*\-(\d+)(.*)/", "$2", $file);
				if($avatarID > 0) {
					$avatarIDs[] = $avatarID;
				}
			}
		}
		if (count($avatarIDs)) {
			$sql = "SELECT avatarID, avatarExtension FROM wcf".WCF_N."_avatar WHERE avatarID IN (".implode(',', $avatarIDs).")";
			$result = WCF::getDB()->sendQuery($sql);
			$physicalAvatars = array_flip($avatarIDs);
			while($row = WCF::getDB()->fetchArray($result)) {
				unset($physicalAvatars[$row['avatarID']]);
			}
			$physicalAvatars = array_keys($physicalAvatars);
			foreach($physicalAvatars as $avatarID) {				
				$file = WCF_DIR.'images/avatars/avatar-'.$avatarID.$avatars[$avatarID];
				$theAvatars[] = $file;
			}
		}
		closedir($dh);
		self::$virtualFileIDs['avatarsFilesystem'] = $theAvatars;
		WCF::getSession()->register('virtualLostAndFoundIDs', self::$virtualFileIDs);
	}

	public function delete() {
		if (isset(self::$virtualFileIDs['avatarsFilesystem'][$this->objectID])) {
			$editor = new AvatarEditor(null, array('avatarID' => $this->objectID));
			$editor->delete();
		}
	}

	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('avatarsFilesystem');
		foreach($itemIDs as $itemID) {
			$item = new AvatarsFilesystemLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>