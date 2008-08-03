<?php
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/user/AttachmentManager.class.php');

class AttachmentManagerAcpPage extends MultipleLinkPage {
	public $templateName = 'attachmentManagerAcp';
	public $attachments = array();
	public $thisPage = 'index.php?page=AttachmentManagerAcp';
	public $tplError = '';
	public $tplWarning = '';
	public $tplInfo = '';
	public $itemsPerPage = ATTACHMENTMANAGER_ITEMSPERPAGE;
	public $sortField = ATTACHMENTMANAGER_SORTFIELD;
	public $sortOrder = ATTACHMENTMANAGER_SORTORDER;
	public $am;
	public $userID = 0;
	public $username = '';
	public $showOnlyImages = 0;
	public $showOnlyMessageType = '';
	public $showOnlyFileType = '';
	public $showThumbnails = 0;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
        if (!WCF::getUser()->userID) {
        	require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
        	throw new PermissionDeniedException();
        }

        $this->am = new AttachmentManager();

        if(isset($_REQUEST['sortField']))   $this->sortField    = $_REQUEST['sortField'];
        if(isset($_REQUEST['sortOrder']))   $this->sortOrder    = $_REQUEST['sortOrder'];
        if(isset($_REQUEST['userID']))      $this->userID       = $_REQUEST['userID'];
        if(isset($_POST['username']))       $this->username     = $_POST['username'];

        if(isset($_REQUEST['showOnlyMessageType']))   $this->showOnlyMessageType = $_REQUEST['showOnlyMessageType'];
        else if(WCF::getSession()->getVar('showOnlyMessageType'))     $this->showOnlyMessageType = WCF::getSession()->getVar('showOnlyMessageType');
        if(isset($_REQUEST['showOnlyFileType']))   $this->showOnlyFileType = $_REQUEST['showOnlyFileType'];
        else if(WCF::getSession()->getVar('showOnlyFileType'))     $this->showOnlyFileType = WCF::getSession()->getVar('showOnlyFileType');
        if(isset($_REQUEST['showOnlyImages']))   $this->showOnlyImages = $_REQUEST['showOnlyImages'];
        else if(WCF::getSession()->getVar('showOnlyImages'))     $this->showOnlyImages = WCF::getSession()->getVar('showOnlyImages');
        if(isset($_REQUEST['showThumbnails']))   $this->showThumbnails    = $_REQUEST['showThumbnails'];
        else if(WCF::getSession()->getVar('showThumbnails'))     $this->showThumbnails = WCF::getSession()->getVar('showThumbnails');

		if(isset($_POST['fDo'])) {
            if($_POST['fDo'] == 'delete' && isset($_POST['delAttachment']) && is_array($_POST['delAttachment'])) {
                WCF::getUser()->checkPermission('admin.general.attachmentManager.canDelete');
                $ret = $this->am->deleteAttachments($this->userID, $_POST['delAttachment']);
                if($ret['CODE'] == RET_ERROR) $this->tplError = '<p class="error">'.$ret['MSG'].'</p>';
                else if($ret['CODE'] == RET_WARNING) $this->tplWarning = '<p class="warning">'.$ret['MSG'].'</p>';
                else if($ret['CODE'] == RET_INFO) $this->tplInfo = '<p class="success">'.$ret['MSG'].'</p>';
            } else if($_POST['fDo'] == 'setFilter') {
                if(empty($_POST['showOnlyImages'])) $this->showOnlyImages = 0;
                else $this->showOnlyImages = 1;
                if(!empty($this->username)) {
                    $tmp = $this->am->getUserByName($this->username);
                    if(!empty($tmp['userID'])) {
                        $this->userID = $tmp['userID'];
                        $this->username = $tmp['username'];
                    } else {
                        $this->userID = 0;
                        $this->username = '';
                    }
                } else {
                    $this->userID = 0;
                    $this->username = '';
                }
            } else if($_POST['fDo'] == 'switchThumbnails') {
                if(empty($_POST['showThumbnails'])) $this->showThumbnails = 0;
                else $this->showThumbnails = 1;
            }
		}

        if(!empty($this->userID)) {
            $tmp = $this->am->getUserById($this->userID);
            if(!empty($tmp['userID'])) {
                $this->userID = $tmp['userID'];
                $this->username = $tmp['username'];
            }
        }
    }

	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		$this->am = new AttachmentManager();
        return $this->am->getCount($this->userID, $this->showOnlyImages, $this->showOnlyMessageType, $this->showOnlyFileType);
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		$this->am = new AttachmentManager();
		$this->attachments = $this->am->getAttachments($this->userID, $this->sortField, $this->sortOrder, $this->itemsPerPage, $this->pageNo, true, $this->showThumbnails, $this->showOnlyImages, $this->showOnlyMessageType, $this->showOnlyFileType);
    }

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array(
		    'attachments' => $this->attachments,
		    'sortField' => $this->sortField,
		    'sortOrder' => $this->sortOrder,
		    'userID' => $this->userID,
		    'username' => $this->username,
		    'showOnlyImages' => $this->showOnlyImages,
		    'showOnlyMessageType' => $this->showOnlyMessageType,
		    'showOnlyFileType' => $this->showOnlyFileType,
		    'thisPage' => $this->thisPage.'&userID='.$this->userID,
		    'tplError' => $this->tplError,
		    'tplWarning' => $this->tplWarning,
		    'tplInfo' => $this->tplInfo,
		    'attachmentInfo' => $this->am->getInfo($this->userID, $this->showOnlyImages, $this->showOnlyMessageType, $this->showOnlyFileType),
		    'attachmentTotalInfo' => $this->am->getTotalInfo(0),
		    'showThumbnails' => $this->showThumbnails,
		    'messageTypes' => $this->am->getMessageTypes($this->userID, $this->showOnlyFileType, $this->showOnlyImages),
		    'fileTypes' => $this->am->getFileTypes($this->userID, $this->showOnlyMessageType, $this->showOnlyImages)
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
        WCF::getUser()->checkPermission('admin.general.attachmentManager.canView');
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.attachmentManager');

        WCF::getSession()->register('showThumbnails', $this->showThumbnails);
        WCF::getSession()->register('showOnlyMessageType', $this->showOnlyMessageType);
        WCF::getSession()->register('showOnlyFileType', $this->showOnlyFileType);
        WCF::getSession()->register('showOnlyImages', $this->showOnlyImages);

		// show form
		parent::show();
	}
}
?>
