<?php
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/data/user/usersOnline/UsersOnlineSortedList.class.php');

/**
 * Shows the team online page.
 */
class TeamOnlinePage extends SortablePage {
	public $defaultSortField = 'lastActivityTime';
	public $defaultSortOrder = 'DESC';
	public $templateName = 'teamOnline';
	public $usersOnlineSortedList;
	
	/**
	 * Creates a new UsersOnlinePage object.
	 */
	public function __construct() {
		$this->usersOnlineSortedList = new UsersOnlineSortedList();
		parent::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['detailedSpiderList'])) {
			$this->usersOnlineSortedList->detailedSpiderList = intval($_REQUEST['detailedSpiderList']);
		}
        if (isset($this->usersOnlineSortedList->sqlConditions)) {
            $this->usersOnlineSortedList->sqlConditions = ' AND groups.showOnTeamOnlineBox = 1 ';
        }
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
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
		parent::readData();
		
		$this->usersOnlineSortedList->sqlOrderBy = 'session.'.$this->sortField.' '.$this->sortOrder;
		$this->usersOnlineSortedList->getUsersOnline();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'users' => $this->usersOnlineSortedList->users,
			'guests' => $this->usersOnlineSortedList->guests,
			'spiders' => $this->usersOnlineSortedList->spiders,
			'canViewIpAddress' => WCF::getUser()->getPermission('admin.general.canViewIpAddress'),
			'canSeeTeamOnlineBox' => WCF::getUser()->getPermission('user.board.canSeeTeamOnlineBox'),
			'detailedSpiderList' => $this->usersOnlineSortedList->detailedSpiderList,
			'usersOnlineMarkings' => $this->usersOnlineSortedList->getUsersOnlineMarkings()
		));
	}
}
?>