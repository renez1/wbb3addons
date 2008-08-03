<?php
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/user/AttachmentManager.class.php');
require_once(WCF_DIR.'lib/page/util/menu/UserCPMenu.class.php');

class AttachmentManagerPage extends MultipleLinkPage {
	public $templateName = 'attachmentManager';
	public $attachments = array();
	public $thisPage = 'index.php?page=AttachmentManager';
	public $tplError = '';
	public $tplWarning = '';
	public $tplInfo = '';
	public $itemsPerPage = ATTACHMENTMANAGER_ITEMSPERPAGE;
	public $sortField = ATTACHMENTMANAGER_SORTFIELD;
	public $sortOrder = ATTACHMENTMANAGER_SORTORDER;
	public $am;
	public $userID = 0;
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
        $this->userID = WCF::getUser()->userID;
        if(isset($_REQUEST['sortField']))   $this->sortField    = $_REQUEST['sortField'];
        if(isset($_REQUEST['sortOrder']))   $this->sortOrder    = $_REQUEST['sortOrder'];

        if(isset($_REQUEST['showOnlyMessageType']))   $this->showOnlyMessageType = $_REQUEST['showOnlyMessageType'];
        else if(WCF::getSession()->getVar('showOnlyMessageType'))     $this->showOnlyMessageType = WCF::getSession()->getVar('showOnlyMessageType');
        if(isset($_REQUEST['showOnlyFileType']))   $this->showOnlyFileType = $_REQUEST['showOnlyFileType'];
        else if(WCF::getSession()->getVar('showOnlyFileType'))     $this->showOnlyFileType = WCF::getSession()->getVar('showOnlyFileType');
        if(isset($_REQUEST['showOnlyImages']))   $this->showOnlyImages = $_REQUEST['showOnlyImages'];
        else if(WCF::getSession()->getVar('showOnlyImages'))     $this->showOnlyImages = WCF::getSession()->getVar('showOnlyImages');
        if(isset($_REQUEST['showThumbnails']))   $this->showThumbnails    = $_REQUEST['showThumbnails'];
        else if(WCF::getSession()->getVar('showThumbnails'))     $this->showThumbnails = WCF::getSession()->getVar('showThumbnails');

		if(isset($_POST['fDo'])) {
            if (!$this->userID) {
            	require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
            	throw new PermissionDeniedException();
            } else {
                if($_POST['fDo'] == 'delete' && isset($_POST['delAttachment']) && is_array($_POST['delAttachment'])) {
                    WCF::getUser()->checkPermission('user.profile.attachmentManager.canDelete');
                    $this->am = new AttachmentManager();
                    $ret = $this->am->deleteAttachments($this->userID, $_POST['delAttachment']);
                    if($ret['CODE'] == RET_ERROR) $this->tplError = '<p class="error">'.$ret['MSG'].'</p>';
                    else if($ret['CODE'] == RET_WARNING) $this->tplWarning = '<p class="warning">'.$ret['MSG'].'</p>';
                    else if($ret['CODE'] == RET_INFO) $this->tplInfo = '<p class="success">'.$ret['MSG'].'</p>';
                } else if($_POST['fDo'] == 'switchThumbnails') {
                    if(empty($_POST['showThumbnails'])) $this->showThumbnails = 0;
                    else $this->showThumbnails = 1;
                } else if($_POST['fDo'] == 'setFilter') {
                    if(empty($_POST['showOnlyImages'])) $this->showOnlyImages = 0;
                    else $this->showOnlyImages = 1;
                }
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
		$this->attachments = $this->am->getAttachments($this->userID, $this->sortField, $this->sortOrder, $this->itemsPerPage, $this->pageNo, false, $this->showThumbnails, $this->showOnlyImages, $this->showOnlyMessageType, $this->showOnlyFileType);
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
		    'showOnlyImages' => $this->showOnlyImages,
		    'showOnlyMessageType' => $this->showOnlyMessageType,
		    'showOnlyFileType' => $this->showOnlyFileType,
		    'thisPage' => $this->thisPage,
		    'tplError' => $this->tplError,
		    'tplWarning' => $this->tplWarning,
		    'tplInfo' => $this->tplInfo,
		    'attachmentInfo' => $this->am->getInfo($this->userID, $this->showOnlyImages, $this->showOnlyMessageType, $this->showOnlyFileType),
		    'attachmentTotalInfo' => $this->am->getTotalInfo($this->userID),
		    'usage' => $this->am->getPmUsage(),
		    'showThumbnails' => $this->showThumbnails,
		    'messageTypes' => $this->am->getMessageTypes($this->userID, $this->showOnlyFileType, $this->showOnlyImages),
		    'fileTypes' => $this->am->getFileTypes($this->userID, $this->showOnlyMessageType, $this->showOnlyImages)
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!$this->userID) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}

        WCF::getUser()->checkPermission('user.profile.attachmentManager.canView');
		// set active tab
		UserCPMenu::getInstance()->setActiveMenuItem('wcf.user.usercp.menu.link.profile.attachmentManager');

        WCF::getSession()->register('showThumbnails', $this->showThumbnails);
        WCF::getSession()->register('showOnlyMessageType', $this->showOnlyMessageType);
        WCF::getSession()->register('showOnlyFileType', $this->showOnlyFileType);
        WCF::getSession()->register('showOnlyImages', $this->showOnlyImages);

		// show form
		parent::show();
	}
}
?>
