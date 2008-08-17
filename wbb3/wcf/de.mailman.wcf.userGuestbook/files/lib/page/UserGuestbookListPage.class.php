<?php
/* $Id$ */
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/user/UserGuestbookData.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
if(!defined('MEMBERS_LIST_USERS_PER_PAGE')) define('MEMBERS_LIST_USERS_PER_PAGE', 20);

/**
 * @author	MailMan
*/
class UserGuestbookListPage extends MultipleLinkPage {
	public $templateName = 'userGuestbookList';
	public $uwp;
	public $ugbData;
	public $sortField = 'lastEntry';
	public $sortOrder = 'DESC';
	public $itemsPerPage = MEMBERS_LIST_USERS_PER_PAGE;
	protected $cntEntries = 0;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
        $this->cntEntries = UserGuestbookData::countEntries();
		return $this->cntEntries;
	}

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
        if(isset($_REQUEST['sortField']))    $this->sortField = $_REQUEST['sortField'];
        if(isset($_REQUEST['sortOrder']))    $this->sortOrder = $_REQUEST['sortOrder'];
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		$ugb = new UserGuestbookData();
		$this->ugbData = $ugb->getGuestbookList($this->sortField, $this->sortOrder, $this->pageNo, $this->itemsPerPage);
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'ugbData' => $this->ugbData,
			'sortField' => $this->sortField,
			'sortOrder' => $this->sortOrder,
			'cntEntries' => $this->cntEntries
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// check permission
        WCF::getUser()->checkPermission('user.guestbook.canViewList');
   		HeaderMenu::setActiveMenuItem('wcf.header.menu.userGuestbook');
   		parent::show();
	}
}
?>
