<?php
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

class AdminToolsLostAndFoundPage extends SortablePage  {
	public $activeMenuItem = 'wcf.acp.menu.link.admintools.lostandfound';
	public $templateName = 'adminToolsLostAndFound';
	public $activeTabMenuItem = 'backup';
	public $activeSubTabMenuItem = 'database';
	public $markedItems = array();
	public $markedItemsData = array();		
	public $itemsPerPage = 10;
	public $pageNo = 1;
	
	public $count = 0;


	public $itemData = array();

	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['activeTabMenuItem'])) $this->activeTabMenuItem = StringUtil::trim($_GET['activeTabMenuItem']);
		if (isset($_GET['activeSubTabMenuItem'])) $this->activeSubTabMenuItem = StringUtil::trim($_GET['activeSubTabMenuItem']);
	}

	public function readData() {		

		if(is_array(WCF::getSession()->getVar('markedItems'))) {
			$this->markedItemsData = WCF::getSession()->getVar('markedItems');
			//var_dump($this->markedItemsData); die;
			foreach($this->markedItemsData as $key => $data) {
				$this->markedItems[$key] = count($data);
			}
		}

		$functionName = 'read'.ucfirst($this->activeTabMenuItem);
		if(method_exists($this, $functionName)) {
			$this->{$functionName}();
		}
		
		parent::readData();
	}

	protected function readBackup() {
		chdir(WCF_DIR.'acp/backup');
		$dh= opendir(WCF_DIR.'acp/backup');
		if(!$dh) {
			$this->count = 0;
			return;
		}
		$i=0;
		while($file = readdir ($dh)) {
			if($file != '.' && $file != '..' && $file != '.htaccess' && !is_dir($file)) {
				$this->itemData[$i] = array();
				$this->itemData[$i]['filename'] = urlencode($file);
				$this->itemData[$i]['filePath'] = WCF_DIR.'acp/backup/'.$file;
				$this->itemData[$i]['filesize'] = round((filesize($file) / 1000),2).' kB';
				$this->itemData[$i]['time'] = filemtime($file);
				$i++;
			}
		}
		closedir($dh);
		$this->count = $i;
	}

	public function readAttachments() {
		switch($this->activeSubTabMenuItem) {
			case 'database' :
				// private attachments won't be read
				$sql = "SELECT attachment.*, user.username FROM wcf".WCF_N."_attachment attachment
						LEFT JOIN wcf".WCF_N."_user user
						ON (user.userID = attachment.userID)
						LEFT JOIN wcf".WCF_N."_attachment_container_type type
						ON(type.containerType = attachment.containerType)
						WHERE type.isPrivate = 0";
				$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo-1)*$this->itemsPerPage);
				$i = 0;
				while($row = WCF::getDB()->fetchArray($result)) {
					if(!is_file(WCF_DIR.'attachments/attachment-'.$row['attachmentID'])) {
						if($i <= ($this->pageNo-1)*$this->itemsPerPage) {
							$i++;
							continue;
						}
						else if($i > $this->pageNo*$this->itemsPerPage) break;
						$this->itemData[$i] = array();
						$this->itemData[$i]['filename'] = $row['attachmentName'];
						$this->itemData[$i]['filePath'] = WCF_DIR.'attachments/attachment-'.$row['attachmentID'];
						$this->itemData[$i]['filesize'] = round((($row['attachmentSize']) / 1000),2).' kB';
						$this->itemData[$i]['time'] = $row['uploadTime'];
						$this->itemData[$i]['username'] = $row['username'];
						$this->itemData[$i]['itemID'] = $row['attachmentID'];
						$i++;
					}
				}
				$this->count = $i;
				break;
			case 'filesystem' :
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
					$this->count = count($physicalAttachments);
					$i = 0;
					foreach($physicalAttachments as $attachmentID) {
						if($i <= ($this->pageNo-1)*$this->itemsPerPage) {
							$i++;
							continue;
						}
						else if($i > $this->pageNo*$this->itemsPerPage) break;
						$file = WCF_DIR.'attachments/attachment-'.$attachmentID;
						$this->itemData[$i] = array();
						$this->itemData[$i]['filename'] = $file;
						$this->itemData[$i]['filePath'] = WCF_DIR.'attachments/'.$file;
						$this->itemData[$i]['filesize'] = round((filesize($file) / 1000),2).' kB';
						$this->itemData[$i]['time'] = filemtime($file);
						$this->itemData[$i]['itemID'] = $attachmentID;
						$i++;
					}
				}
				closedir($dh);
				break;
		}
	}
	
	public function readAvatars() {		
		switch($this->activeSubTabMenuItem) {
		case 'database' :				
				$sql = "SELECT avatar.*, user.username FROM wcf".WCF_N."_avatar avatar
						LEFT JOIN wcf".WCF_N."_user user
						ON (user.userID = avatar.userID)";
				$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo-1)*$this->itemsPerPage);
				$i = 0;
				while($row = WCF::getDB()->fetchArray($result)) {
					if(!is_file(WCF_DIR.'images/avatars/avatar-'.$row['avatarID'])) {
						$this->itemData[$i] = array();
						$this->itemData[$i]['filename'] = $row['avatarName'];
						$this->itemData[$i]['filePath'] = WCF_DIR.'images/avatars/'.$row['avatarID'];												
						$this->itemData[$i]['username'] = $row['username'];
						$this->itemData[$i]['itemID'] = $row['avatarID'];
						$i++;
					}
				}
				$this->count = $i;
				break;
			case 'filesystem' :
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
					$this->count = count($physicalAvatars);
					$i = 0;
					foreach($physicalAvatars as $avatarID) {
						if($i <= ($this->pageNo-1)*$this->itemsPerPage) {
							$i++;
							continue;
						}
						else if($i > $this->pageNo*$this->itemsPerPage) break;						
						$file = WCF_DIR.'images/avatars/avatar-'.$avatarID.$avatars[$avatarID];
						$this->itemData[$i] = array();
						$this->itemData[$i]['filename'] = $file;
						$this->itemData[$i]['filePath'] = WCF_DIR.'acp/backup/'.$file;
						$this->itemData[$i]['filesize'] = round((filesize($file) / 1000),2).' kB';
						$this->itemData[$i]['time'] = filemtime($file);
						$this->itemData[$i]['itemID'] = $avatarID;
						$i++;
					}
				}
				closedir($dh);
				break;
		}
	}
	
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array('activeTabMenuItem' => $this->activeTabMenuItem,
									'activeSubTabMenuItem' => $this->activeSubTabMenuItem,
									'markedItems' => $this->markedItems,
									'markedItemsData' => $this->markedItemsData,									
									'defaultSortField' => $this->defaultSortField,
									'defaultSortOrder' => $this->defaultSortOrder,
									'mode' => $this->activeTabMenuItem.ucfirst($this->activeSubTabMenuItem),
									'itemData' => $this->itemData));
	}

	public function show() {

		WCFACP::getMenu()->setActiveMenuItem($this->activeMenuItem);

		parent::show();
	}

	public function countItems() {
		parent::countItems();

		return $this->count;
	}
}

?>