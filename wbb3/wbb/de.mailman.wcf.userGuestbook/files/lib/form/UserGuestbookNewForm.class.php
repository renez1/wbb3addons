<?php
require_once(WCF_DIR.'lib/form/MessageForm.class.php');
require_once(WCF_DIR.'lib/data/user/UserGuestbookData.class.php');
require_once(WCF_DIR.'lib/page/util/menu/UserCPMenu.class.php');
require_once(WCF_DIR.'lib/util/StringUtil.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

/**
 * @package	de.mailman.wcf.userGuestbook
 * @author	MailMan
 */
class UserGuestbookNewForm extends MessageForm {
	public $templateName = 'userGuestbookNew';
	public $maxTextLength = USERGUESTBOOK_POST_MAXLENGTH;
	public $showSmilies = true;
	public $showAttachments = false;
	public $showSignatureSetting = false;
	public $showPoll = false;
	public $userID;
	public $user;
	public $newEntry;
	public $text = '';
	public $subject = '*';
	public $preview = false;
	public $guestbookPreview = '';
	public $entryTxt = '';
	public $action = '';
	protected $id;
	protected $entry;
	protected $exception = false;
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		MessageForm::readParameters();

		if(isset($_REQUEST['userID']))  $this->userID = intval($_REQUEST['userID']);
		require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

		$this->user = new UserProfile($this->userID, null, null, null);
		if (!$this->user->userID) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			$this->exception = true;
			throw new IllegalLinkException();
		}

		// Schreibzugriff deaktiviert?
		if(!$this->user->userGuestbook_enable_posting || !$this->user->userGuestbook_enable || !WCF::getUser()->getPermission('user.guestbook.canWrite')
		|| (!WCF::getUser()->getPermission('user.guestbook.canUseOwn') && $this->userID == WCF::getUser()->userID)) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			$this->exception = true;
			throw new PermissionDeniedException();
		}

        $this->locked = UserGuestbookData::getLockInfo($this->user->userID);
		// Gästebuch gesperrt?
		if(!empty($this->locked['locked']) && ($this->user->userID != WCF::getUser()->userID || !WCF::getUser()->getPermission('mod.guestbook.canLock'))) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			$this->exception = true;
			throw new PermissionDeniedException();
		}

        if(!$this->exception && isset($_REQUEST['action']) && !empty($_REQUEST['id']) && ($_REQUEST['action'] == 'edit' || $_REQUEST['action'] == 'comment')) {
            $entry = UserGuestbookData::getEntry(intval($_REQUEST['id']));
            if(!empty($entry['id'])) {
                $this->action   = $_REQUEST['action'];
                $this->id       = $entry['id'];
                $editTime       = intval(WCF::getUser()->getPermission('user.guestbook.canEditOwnEntries'));
                if($this->action == 'edit') {
                    if(!WCF::getUser()->getPermission('mod.guestbook.canEditAll')
                    && !(WCF::getUser()->getPermission('user.guestbook.canEditOwnGuestbook') && $entry['userID'] == WCF::getUser()->userID)
                    && !($editTime != 0 && $entry['fromUserID'] == WCF::getUser()->userID && ($editTime == -1 || $entry['entryTime'] > TIME_NOW - $editTime))) {
            			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
            			$this->exception = true;
            			$this->action = '';
            			throw new PermissionDeniedException();
                    } else {
                        if(count($_POST)) $this->text = (isset($_POST['text']) ? $_POST['text'] : '');
                        else $this->text = $entry['text'];
                    }
                } else if($this->action == 'comment') {
                    if($entry['userID'] != WCF::getUser()->userID || !WCF::getUser()->getPermission('user.guestbook.canComment')) {
            			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
            			$this->exception = true;
            			$this->action = '';
            			throw new PermissionDeniedException();
            	    } else {
                        $this->text = $entry['comment'];
                        $this->maxTextLength    = 2000;
                        $this->entryTxt = MessageParser::getInstance()->parse($entry['text'], $entry['enableSmilies'], $entry['enableHtml'], $entry['enableBBCodes']);
                    }
                }
            }
        }
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if(isset($_POST['text']))       $this->text = ($_POST['text']);
		if(isset($_POST['preview']))    $this->preview = $_POST['preview'];
	}

	/**
	 * @see Form::submit()
	 */
	public function submit() {
		$this->readFormParameters();
		try {
        	// preview
        	if ($this->preview) {
                $this->guestbookPreview = MessageParser::getInstance()->parse($this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes);
                $this->validate();
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
		parent::validate();
		if($this->action == 'comment' && StringUtil::length($this->text) > $this->maxTextLength) {
            throw new UserInputException('text', 'tooLong');
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();

		// insert/update
		$newEntry = new UserGuestbookData($this->userID);
        if($this->action == 'edit') $newEntry->updateEntry($this->id,$this->text,$this->enableSmilies,$this->enableHtml,$this->enableBBCodes);
        else if($this->action == 'comment') $newEntry->addComment($this->id,$this->text);
		else $newEntry->addEntry(WCF::getUser()->userID,$this->text,$this->enableSmilies,$this->enableHtml,$this->enableBBCodes);

		// forward to Guestbook
		HeaderUtil::redirect('index.php?page=UserGuestbook&userID=' . $this->userID . '' . SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
		    'action' => $this->action,
		    'id' => $this->id,
    		'guestbookPreview' => $this->guestbookPreview,
			'user' => $this->user,
			'userID' => $this->userID,
			'text' => $this->text,
			'entryTxt' => $this->entryTxt
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {

		// check permission
		if (!WCF::getUser()->userID) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}

		// set active tab
		require_once(WCF_DIR.'lib/page/util/menu/UserProfileMenu.class.php');
		UserProfileMenu::getInstance()->userID = $this->userID;
		UserCPMenu::getInstance()->setActiveMenuItem('wcf.user.usercp.menu.link.profile.guestbook');

        if(WCF::getUser()->getPermission('user.guestbook.canViewMembersListTab') && WCF::getUser()->getPermission('user.membersList.canView')) HeaderMenu::setActiveMenuItem('wcf.header.menu.memberslist');
        else if(WCF::getUser()->getPermission('user.guestbook.canViewListMenuButton')) HeaderMenu::setActiveMenuItem('wcf.header.menu.userGuestbook');

		require_once(WCF_DIR.'lib/data/message/attachment/AttachmentsEditor.class.php');
		$this->attachmentsEditor = new AttachmentsEditor();

		// show form
		parent::show();
	}
}
?>
