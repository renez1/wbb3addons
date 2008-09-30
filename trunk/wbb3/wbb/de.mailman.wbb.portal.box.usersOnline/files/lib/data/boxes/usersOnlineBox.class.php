<?php
/* $Id$ */
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/data/user/usersOnline/UsersOnlineSortedList.class.php');

if(!defined('USERSONLINEBOX_BOXOPENED'))    define('USERSONLINEBOX_BOXOPENED', false);
if(!defined('USERSONLINEBOX_HIDEGUESTS'))   define('USERSONLINEBOX_HIDEGUESTS', true);
if(!defined('USERSONLINEBOX_HIDEROBOTS'))   define('USERSONLINEBOX_HIDEROBOTS', true);
if(!defined('USERSONLINEBOX_SHOWNUMOFUSERNEXTTITLE'))   define('USERSONLINEBOX_SHOWNUMOFUSERNEXTTITLE', true);
if(!defined('USERSONLINEBOX_SHOWLEGEND'))   define('USERSONLINEBOX_SHOWLEGEND', true);
if(!defined('USERSONLINEBOX_SHOWLEGENDBOTTOM'))   define('USERSONLINEBOX_SHOWLEGENDBOTTOM', false);
if(!defined('USERSONLINEBOX_MAXHEIGHT'))    define('USERSONLINEBOX_MAXHEIGHT', 300);

class usersOnlineBox extends SortablePage {
	protected $BoxData = array();
	public $defaultSortField = 'lastActivityTime';
	public $defaultSortOrder = 'DESC';
	public $templateName = '';
	public $usersOnlineSortedList;

	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "usersOnlineBox";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

		if(USERSONLINEBOX_BOXOPENED == true) $this->BoxData['Status'] = 1;

		$this->usersOnlineSortedList = new UsersOnlineSortedList();
		parent::__construct();
	}

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
        if(!WCF::getUser()->getPermission('user.board.canViewUsersOnlineBox')) return;
		parent::readParameters();
		
		if (isset($_REQUEST['detailedSpiderList'])) {
			$this->usersOnlineSortedList->detailedSpiderList = intval($_REQUEST['detailedSpiderList']);
		}
	}

	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
	    if(!WCF::getUser()->getPermission('user.board.canViewUsersOnlineBox')) return;
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'username':
			case 'lastActivityTime':
			case 'requestURI':
				break;
				
			case 'ipAddress':
			case 'userAgent':
				if (WCF::getUser()->getPermission('admin.general.canViewIpAddress')) break;
			default: $this->sortField = $this->defaultSortField;
		}
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
        if(!WCF::getUser()->getPermission('user.board.canViewUsersOnlineBox')) return;
		parent::readData();
		
		$this->usersOnlineSortedList->sqlOrderBy = 'session.'.$this->sortField.' '.$this->sortOrder;
		$this->usersOnlineSortedList->getUsersOnline();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
        if(!WCF::getUser()->getPermission('user.board.canViewUsersOnlineBox')) return;
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'users' => $this->usersOnlineSortedList->users,
			'guests' => (USERSONLINEBOX_HIDEGUESTS == true ? array() : $this->usersOnlineSortedList->guests),
			'spiders' => (USERSONLINEBOX_HIDEROBOTS == true ? array() : $this->usersOnlineSortedList->spiders),
			'canViewIpAddress' => WCF::getUser()->getPermission('admin.general.canViewIpAddress'),
			'detailedSpiderList' => $this->usersOnlineSortedList->detailedSpiderList,
			'usersOnlineMarkings' => $this->usersOnlineSortedList->getUsersOnlineMarkings()
		));
	}


	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->usersOnlineBox);
		}
		else {
			if (WBBCore::getSession()->getVar('usersOnlineBox') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('usersOnlineBox');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
