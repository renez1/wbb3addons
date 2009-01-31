<?php
require_once(WCF_DIR.'lib/acp/admintools/lostandfound/AbstractLostAndFoundFileSystemItem.class.php');
require_once(WCF_DIR.'lib/data/attachment/AttachmentEditor.class.php');

class AttachmentsFilesystemLostAndFoundItem extends AbstractLostAndFoundFileSystemItem {

	public function __construct($attachmentID) {
		parent::__construct('attachmentsFilesystem', $attachmentID);
	}

	public static function createVirtualIDSpace() {
		$attachments = array();
		chdir(WCF_DIR.'attachments');
		$dh=opendir(WCF_DIR.'attachments');
		$attachmentIDs = array();
		while($file = readdir ($dh)) {
			if(preg_match("/^(attachment|thumbnail).*/",$file) && $file != '.' && $file != '..' && $file != '.htaccess' && !preg_match("/^.*\.php$/",$file)) {
				$attachmentID = (int) preg_replace("/.*\-(\d+)$/", "$1", $file);
				if($attachmentID > 0) {
					$attachmentIDs[] = $attachmentID;
				}
			}
		}
		if (count($attachmentIDs)) {
			$sql = "SELECT attachmentID FROM wcf".WCF_N."_attachment WHERE attachmentID IN (".implode(',', $attachmentIDs).")";
			$result = WCF::getDB()->sendQuery($sql);
			$physicalAttachments = array_flip($attachmentIDs);
			while($row = WCF::getDB()->fetchArray($result)) {
				unset($physicalAttachments[$row['attachmentID']]);
			}
			$physicalAttachments = array_keys($physicalAttachments);			
			foreach($physicalAttachments as $attachmentID) {
				$file = WCF_DIR.'attachments/attachment-'.$attachmentID;
				$attachments[] = $file;
			}
		}
		closedir($dh);
		self::$virtualFileIDs['attachmentsFilesystem'] = $attachments;
		WCF::getSession()->register('virtualLostAndFoundIDs', self::$virtualFileIDs);
	}

	public function delete() {
		if (isset(self::$virtualFileIDs['attachmentsFilesystem'][$this->objectID])) {
			$editor = new AttachmentEditor(null, array('avatarID' => $this->objectID));
			$editor->delete();
		}
	}

	public static function deleteAll() {
		$itemIDs = self::getMarkedItems('attachmentsFilesystem');
		foreach($itemIDs as $itemID) {
			$item = new AttachmentsFilesystemLostAndFoundItem($itemID);
			$item->delete();
		}
	}
}
?>