<?php
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/user/UserWantedPosterData.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
if(!defined('MEMBERS_LIST_USERS_PER_PAGE')) define('MEMBERS_LIST_USERS_PER_PAGE', 20);

/**
 * @author	MailMan
*/
class UserWantedPosterListPage extends MultipleLinkPage {
	public $templateName = 'userWantedPosterList';
	public $uwp;
	public $uwpData;
	public $sortField = 'updateDate';
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
		// count number of wanted poster
		$this->cntEntries = UserWantedPosterData::countEntries();
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
		$uwp = new UserWantedPosterData();
		$this->uwpData = $uwp->readEntries($this->sortField, $this->sortOrder, $this->pageNo, $this->itemsPerPage);
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'uwpData' => $this->uwpData,
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
        WCF::getUser()->checkPermission('user.wantedPoster.canViewWantedPoster');
   		HeaderMenu::setActiveMenuItem('wcf.header.menu.userWantedPoster');
   		parent::show();
	}
}
?>
