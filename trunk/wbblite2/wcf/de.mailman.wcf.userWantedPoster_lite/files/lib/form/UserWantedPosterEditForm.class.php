<?php
require_once(WCF_DIR.'lib/form/MessageForm.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
require_once(WCF_DIR.'lib/page/util/menu/UserCPMenu.class.php');
require_once(WCF_DIR.'lib/data/user/UserWantedPosterData.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

/**
 * @author	MailMan
 */

class UserWantedPosterEditForm extends MessageForm {
	public $templateName = 'userWantedPosterEdit';
	public $permissionType = 'wantedPoster';

	public $preview = false;
	public $upload = false;
    public $wantedPosterPreview = '';
	public $attachmentsEditor;
	public $showSignatureSetting = false;
	public $showPoll = false;

    public $maxLength = null;
	public $userID;
	public $user;
	public $text = '';
	public $subject = '*';
	public $uwpData;
    public $tplSelect = false;
    public $canUseSmilies = false;
    public $canUseHtml = false;
    public $canUseBBCodes = false;

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if(isset($_REQUEST['userID'])) $this->userID = intval($_REQUEST['userID']);
		else $this->userID = intval(WCF::getUser()->userID);

		require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
		$this->user = new UserProfile($this->userID, null, null, null);

        $this->canUseSmilies = $this->user->getPermission('user.wantedPoster.canUseSmilies');
        $this->canUseHtml = $this->user->getPermission('user.wantedPoster.canUseHtml');
        $this->canUseBBCodes = $this->user->getPermission('user.wantedPoster.canUseBBCodes');
        
		// Permissions
		if (!$this->user->userID) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
		else if(!WCF::getUser()->getPermission('mod.wantedPoster.canModifyEntries')
		     && ($this->user->userID != WCF::getUser()->userID
		     || !WCF::getUser()->getPermission('user.wantedPoster.canViewWantedPoster')
		     || !WCF::getUser()->getPermission('user.wantedPoster.canUseWantedPoster')
		     )) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if(isset($_POST['text'])) $this->text = $_POST['text'];
        if(isset($_POST['preview'])) $this->preview = $_POST['preview'];
        if(isset($_POST['upload'])) $this->upload = $_POST['upload'];
        if(isset($_POST['tplSelect'])) $this->tplSelect = true;
	}

	/**
	 * @see Form::submit()
	 */
	public function submit() {
        EventHandler::fireAction($this, 'submit');
		$this->readFormParameters();
        try {
        	// attachment handling
        	if ($this->showAttachments) {
        		$this->attachmentsEditor->handleRequest();
        	}
        	// preview
        	if ($this->preview || $this->upload) {
        		require_once(WCF_DIR.'lib/data/message/bbcode/AttachmentBBCode.class.php');
        		AttachmentBBCode::setAttachments($this->attachmentsEditor->getSortedAttachments());
                $this->wantedPosterPreview = MessageParser::getInstance()->parse($this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes, false);
                if($this->preview) $this->validate();
            } else {
            	$this->validate();
            	$this->save();
            }
        }
        catch (UserInputException $e) {
        	$this->errorField = $e->getField();
        	$this->errorType = $e->getType();
        }
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
	    if($this->tplSelect) return;
		parent::validate();
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		$fields = array(
			'enableWantedPosterSmilies' => $this->enableSmilies,
			'enableWantedPosterHtml' => $this->enableHtml,
			'enableWantedPosterBBCodes' => $this->enableBBCodes
		);
		$editor = WCF::getUser()->getEditor();
		$editor->updateFields($fields);
		$editor->updateOptions(array('wysiwygEditorMode' => $this->wysiwygEditorMode, 'wysiwygEditorHeight' => $this->wysiwygEditorHeight));

		parent::save();
        if($this->tplSelect) return;
		$modEntry = new UserWantedPosterData($this->userID);
		$modEntry->modEntry($this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes);

   		// forward
   		header('Location: '.FileUtil::addTrailingSlash(dirname(WCF::getSession()->requestURI)).'index.php?page=UserWantedPoster&userID=' . $this->userID . '' . SID_ARG_2ND_NOT_ENCODED);
   		exit;
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		if(!count($_POST)) {
			// wysiwyg
			$this->wysiwygEditorMode = WCF::getUser()->wysiwygEditorMode;
			$this->wysiwygEditorHeight = WCF::getUser()->wysiwygEditorHeight;
			// default settings
			$this->parseURL = 1;
			$this->enableSmilies = $this->user->enableWantedPosterSmilies;
			$this->enableHtml = $this->user->enableWantedPosterHtml;
			$this->enableBBCodes = $this->user->enableWantedPosterBBCodes;
		}
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

        if($this->tplSelect) {
            $tmp = UserWantedPosterData::getTemplate($_POST['tplID']);
            if(isset($tmp['text'])) {
                WCF::getTPL()->assign(array(
                    'text' => $tmp['text']
                ));
            }
        }
		WCF::getTPL()->assign(array(
            'wantedPosterPreview' => $this->wantedPosterPreview,
			'user' => $this->user,
			'userID' => $this->userID,
            'canUseBBCodes' => $this->canUseBBCodes,
            'canUseSmilies' => $this->canUseSmilies,
            'canUseHtml' => $this->canUseHtml,
			'uwpData' => $this->uwpData,
            'tplList' => UserWantedPosterData::getUserTemplateList($this->canUseSmilies, $this->canUseHtml, $this->canUseBBCodes)
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// check permission
		if (!WCF::getUser()->getPermission('mod.wantedPoster.canModifyEntries')
		&& ($this->userID != WCF::getUser()->userID
		|| !WCF::getUser()->getPermission('user.wantedPoster.canViewWantedPoster')
		|| !WCF::getUser()->getPermission('user.wantedPoster.canUseWantedPoster')
        )) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}
		// get max text length
        $this->maxTextLength = WCF::getUser()->getPermission('user.wantedPoster.maxLength');

        if(WCF::getUser()->getPermission('user.wantedPoster.canViewMembersListTab') && WCF::getUser()->getPermission('user.membersList.canView')) HeaderMenu::setActiveMenuItem('wcf.header.menu.memberslist');
        else if(WCF::getUser()->getPermission('user.wantedPoster.canViewHeaderMenu')) HeaderMenu::setActiveMenuItem('wcf.header.menu.userWantedPoster');

		// set active tab
		UserCPMenu::getInstance()->setActiveMenuItem('wcf.user.usercp.menu.link.profile.wantedPoster');

		// Lade Content
		$uwp = new UserWantedPosterData($this->userID);
		$this->uwpData = $uwp->readEntry();
		if(isset($this->uwpData['text'])) $this->text = $this->uwpData['text'];

		// check upload permission
		if (!WCF::getUser()->getPermission('user.wantedPoster.canUploadAttachment')) {
			$this->showAttachments = false;
		}

		// get attachments editor
		require_once(WCF_DIR.'lib/data/message/attachment/AttachmentsEditor.class.php');
		$this->attachmentsEditor = new AttachmentsEditor($this->userID, 'wantedPoster', WCF::getUser()->getPermission('user.wantedPoster.maxAttachmentSize'), WCF::getUser()->getPermission('user.wantedPoster.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.wantedPoster.maxAttachmentCount'));

		// show form
		parent::show();
	}
}
?>
