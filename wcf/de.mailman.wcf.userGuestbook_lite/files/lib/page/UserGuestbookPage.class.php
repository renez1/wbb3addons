<?php
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/user/UserGuestbookData.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
/**
 * @package	de.mailman.wcf.userGuestbook
 * @author	MailMan
 */
class UserGuestbookPage extends MultipleLinkPage {
	public $templateName = 'userGuestbook';
	public $userID = 0;
	public $userGB;
	public $gbData;
	public $sortField = 'entryTime';
	public $sortOrder = 'DESC';
	public $itemsPerPage = USERGUESTBOOK_ITEMSPERPAGE;
	public $cntEntries = 0;
	public $cntViews = 0;
	public $lastVisitor = '';
	public $visitorLastVisit = 0;
	public $locked;

	/**
	 * Creates a new UserGuestbookPage object.
	 */
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

		// Gästebuch deaktiviert?
		if(($this->user->userID != WCF::getUser()->userID && !$this->user->userGuestbook_enable)
		|| !WCF::getUser()->getPermission('user.guestbook.canRead')
		|| ($this->user->userID == WCF::getUser()->userID && !WCF::getUser()->getPermission('user.guestbook.canUseOwn'))) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
        $sql = "SELECT COUNT(userID) AS count"
            ."\n  FROM wcf".WCF_N."_user_guestbook"
            ."\n WHERE userID = ".$this->user->userID;
        list($this->cntEntries) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
        return $this->cntEntries;
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		// Löschen
		if(isset($_REQUEST['action'])) {
            if($_REQUEST['action'] == 'delete' && !empty($_REQUEST['id'])) {
            	if($this->user->userID != WCF::getUser()->userID && !WCF::getUser()->getPermission('mod.guestbook.canDeleteEntrys')) {
            		require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
            		throw new PermissionDeniedException();
            	} else {
            		$userGB = new UserGuestbookData($this->userID);
            		$userGB->deleteEntry($_REQUEST['id']);
                }
            } else if($_REQUEST['action'] == 'deleteComment' && !empty($_REQUEST['id'])) {
            	if($this->user->userID != WCF::getUser()->userID || !WCF::getUser()->getPermission('user.guestbook.canComment')) {
            		require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
            		throw new PermissionDeniedException();
            	} else {
            		$userGB = new UserGuestbookData($this->userID);
            		$userGB->deleteComment($_REQUEST['id']);
                }
            } else if(($_REQUEST['action'] == 'lock' || $_REQUEST['action'] == 'unlock') && !empty($_REQUEST['userID'])) {
            	if(!WCF::getUser()->getPermission('mod.guestbook.canLock')) {
            		require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
            		throw new PermissionDeniedException();
            	} else {
            		$userGB = new UserGuestbookData($this->userID);
            		$userGB->lockEntry($_REQUEST['userID'], $_REQUEST['action']);
                }
            }
        }
        // Statistiken
        if($this->user->userID && WCF::getUser()->userID && (empty($this->pageNo) || $this->pageNo < 2)) {
            if($this->user->userID != WCF::getUser()->userID) {
                UserGuestbookData::updateStatsVisitor(WCF::getUser()->userID);
            } else {
                UserGuestbookData::updateStatsUser();
            }
        }
        $stats = UserGuestbookData::getStats();
        $this->cntEntries       = $stats['entries'];
        $this->cntViews         = $stats['views'];
        $this->lastVisitor      = $stats['lastVisitor'];
        $this->visitorLastVisit = $stats['visitorLastVisit'];

		// Lade Gästebuchdaten
		$userGB = new UserGuestbookData($this->userID);
		$this->gbData = $userGB->getEntries($this->pageNo, $this->itemsPerPage);

		// Wandle BBCode um und newlines
		$bbcode = new MessageParser();

		foreach($this->gbData as $p => $v) {
			$this->gbData[$p]['text'] = $bbcode->parse($v['text'],$v['enableSmilies'],$v['enableHtml'],$v['enableBBCodes']);
			if(!empty($v['comment'])) $this->gbData[$p]['comment'] = $bbcode->parse($v['comment'],$v['enableSmilies'],$v['enableHtml'],$v['enableBBCodes']);

			// permissions
            $editTime = intval(WCF::getUser()->getPermission('user.guestbook.canEditOwnEntries'));
			if(WCF::getUser()->getPermission('mod.guestbook.canDeleteEntrys') || $v['userID'] == WCF::getUser()->userID) {
			    $this->gbData[$p]['permDelete'] = true;
			} else {
			    $this->gbData[$p]['permDelete'] = false;
            }
            if(WCF::getUser()->getPermission('mod.guestbook.canEditAll')
            || (WCF::getUser()->getPermission('user.guestbook.canEditOwnGuestbook') && $v['userID'] == WCF::getUser()->userID)
            || ($editTime != 0 && $v['fromUserID'] == WCF::getUser()->userID && ($editTime == -1 || $v['entryTime'] > TIME_NOW - $editTime))) {
                $this->gbData[$p]['permEdit'] = true;
            } else {
                $this->gbData[$p]['permEdit'] = false;
            }
            if(WCF::getUser()->getPermission('user.guestbook.canComment') && $v['userID'] == WCF::getUser()->userID) {
                $this->gbData[$p]['permComment'] = true;
            } else {
                $this->gbData[$p]['permComment'] = false;
            }
		}
	}

	/**
	 * @see Page::assignVariables(),
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array(
			'user' => $this->user,
			'gbData' => $this->gbData,
			'cntEntries' => $this->cntEntries,
			'cntViews' => $this->cntViews,
			'userID' => $this->userID,
			'lastVisitor' => $this->lastVisitor,
			'visitorLastVisit' => $this->visitorLastVisit,
			'locked' => UserGuestbookData::getLockInfo($this->userID)
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		require_once(WCF_DIR.'lib/page/util/menu/UserProfileMenu.class.php');
		UserProfileMenu::getInstance()->userID = $this->userID;
		UserProfileMenu::getInstance()->setActiveMenuItem('wcf.user.profile.menu.link.guestbook');

		// check permission
		WCF::getUser()->checkPermission('user.guestbook.canRead');
		if ($this->user->ignoredUser) {
			require_once(WCF_DIR.'lib/system/exception/NamedUserException.class.php');
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.profile.error.ignoredUser', array('$username' => StringUtil::encodeHTML($this->user->username))));
		}

        if(WCF::getUser()->getPermission('user.guestbook.canViewMembersListTab') && WCF::getUser()->getPermission('user.membersList.canView')) HeaderMenu::setActiveMenuItem('wcf.header.menu.memberslist');
        else if(WCF::getUser()->getPermission('user.guestbook.canViewListMenuButton')) HeaderMenu::setActiveMenuItem('wcf.header.menu.userGuestbook');

        parent::show();
	}
}
?>
