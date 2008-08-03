<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/user/UserWantedPosterData.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

/**
 * @author	MailMan
 */
class UserWantedPosterPage extends AbstractPage {
	public $templateName = 'userWantedPoster';
	public $userID = 0;
	public $user;
	public $uwp;
	public $uwpData;
    public $attachmentList = array();

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_REQUEST['userID'])) $this->userID = intval($_REQUEST['userID']);
		require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

		$this->user = new UserProfile($this->userID, null, null, null);

		if (!$this->user->userID) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}

		// Löschen
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
			if ($this->user->userID != WCF::getUser()->userID && !WCF::getUser()->getPermission('mod.wantedPoster.canDeleteEntries')) {
				require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
				throw new PermissionDeniedException();
			} else {
    			$uwp = new UserWantedPosterData($this->userID);
	    		$uwp->deleteEntry($_REQUEST['userID']);
        		// forward
	        	header('Location: '.FileUtil::addTrailingSlash(dirname(WCF::getSession()->requestURI)).'index.php?page=UserWantedPosterList'.SID_ARG_2ND_NOT_ENCODED);
		        exit;
	    	}
		}
		else if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'lock' || $_REQUEST['action'] == 'unlock')) {
			if (!WCF::getUser()->getPermission('mod.wantedPoster.canLockEntries')) {
				require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
				throw new PermissionDeniedException();
			} else {
    			$uwp = new UserWantedPosterData($this->userID);
    			if($_REQUEST['action'] == 'lock') $uwp->lockEntry($_REQUEST['userID']);
    			else $uwp->unlockEntry($_REQUEST['userID']);
        		// forward
	        	header('Location: '.FileUtil::addTrailingSlash(dirname(WCF::getSession()->requestURI)).'index.php?page=UserWantedPosterList'.SID_ARG_2ND_NOT_ENCODED);
		        exit;
    		}
		}
	}


	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		// Lade Content
		$uwp = new UserWantedPosterData($this->userID);
		$this->uwpData = $uwp->readEntry();

		// Wandle BBCode um und newlines
		if(isset($this->uwpData['text'])) {
    		$bbcode = new MessageParser();
            $this->uwpData['text'] = $bbcode->parse($this->uwpData['text'], $this->uwpData['enableSmilies'], $this->uwpData['enableHtml'], $this->uwpData['enableBBCodes']);
        }
	}

	/**
	 * @see Page::assignVariables(),
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array(
			'user' => $this->user,
			'uwpData' => $this->uwpData
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// check permission
		if (!WCF::getUser()->getPermission('user.wantedPoster.canViewWantedPoster'))  {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}
		else if ($this->user->ignoredUser) {
			require_once(WCF_DIR.'lib/system/exception/NamedUserException.class.php');
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.profile.error.ignoredUser', array('$username' => StringUtil::encodeHTML($this->user->username))));
		}

        if(WCF::getUser()->getPermission('user.wantedPoster.canViewMembersListTab') && WCF::getUser()->getPermission('user.membersList.canView')) HeaderMenu::setActiveMenuItem('wcf.header.menu.memberslist');
        else if(WCF::getUser()->getPermission('user.wantedPoster.canViewHeaderMenu')) HeaderMenu::setActiveMenuItem('wcf.header.menu.userWantedPoster');

		require_once(WCF_DIR.'lib/page/util/menu/UserProfileMenu.class.php');
		UserProfileMenu::getInstance()->userID = $this->userID;
		UserProfileMenu::getInstance()->setActiveMenuItem('wcf.user.profile.menu.link.wantedPoster');


		// get attachments
        require_once(WCF_DIR.'lib/data/message/attachment/Attachments.class.php');
        $attachments = new Attachments($this->userID, 'wantedPoster');
        $this->attachmentList = $attachments->getSortedAttachments();
        require_once(WCF_DIR.'lib/data/message/bbcode/AttachmentBBCode.class.php');
        AttachmentBBCode::setAttachments($this->attachmentList);

    	parent::show();
	}
}
?>
