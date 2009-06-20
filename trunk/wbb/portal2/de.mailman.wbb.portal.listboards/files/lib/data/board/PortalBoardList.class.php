<?php
// wbb imports
require_once(WBB_DIR.'lib/data/board/BoardList.class.php');

/**
 * This class extends the board list by portal specific functionality
 *
 * @package     de.mailman.wbb.portal.listboards
 * @author      Oliver Kliebisch
 * @copyright   2009 Oliver Kliebisch
 * @license     GPL
 * @subpackage  data.boxes
 * @category    Portal
 */
class PortalBoardList extends BoardList {
	/**
	 * Does nothing
	 */
	public function readParameters() {	}
	
	/**
	 * Does nothing
	 */
	protected function getBoardUsersOnline() { }
	
	/**
	 * @see BoardList::renderBoards()
	 */
	public function renderBoards() {
		// get board structure from cache		
		$this->boardStructure = WCF::getCache()->get('board', 'boardStructure');
		
		if (!isset($this->boardStructure[$this->boardID])) {
			// the board with the given board id has no children			
			return array();
		}
				
		$this->getLastPostTimes();
		if (BOARD_LIST_ENABLE_ONLINE_LIST) {
			$this->getBoardUsersOnline();
		}
		
		// get boards from cache
		$this->boards = WCF::getCache()->get('board', 'boards');						
		
		$this->clearBoardList($this->boardID);
		$this->makeBoardList($this->boardID, $this->boardID);
		return array(
			'boards' => $this->boardList,
			'newPosts' => $this->newPosts,
			'unreadThreadsCount' => $this->unreadThreadsCount
		);
	}
}
?>