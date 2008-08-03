<?php
class TopThreads {
	protected $TopData = array();

	public function __construct($data, $boxname = "") {
		$this->TopData['templatename'] = "topthreads";
		$this->getBoxStatus($data);
		$this->TopData['boxID'] = $data['boxID'];
        if(!defined('TOPTHREADS_COUNT')) define('TOPTHREADS_COUNT', 10);
        if(!defined('TOPTHREADS_TITLELENGTH')) define('TOPTHREADS_TITLELENGTH', 25);
        if(!defined('TOPTHREADS_SBCOLOR_ACP')) define('TOPTHREADS_SBCOLOR_ACP', 2);

		require_once(WBB_DIR.'lib/data/board/Board.class.php');
		$boardIDs = Board::getAccessibleBoards();

        if(!empty($boardIDs)) {
    	    $sql = "SELECT thread.*"
                ."\n  FROM wbb".WBB_N."_thread thread"
                ."\n WHERE thread.boardID IN (0".$boardIDs.")"
                ."\n ORDER BY thread.replies DESC"
                ."\n LIMIT 0, ".TOPTHREADS_COUNT;
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                $row['replies'] = StringUtil::formatInteger($row['replies']);
                $row['title'] = StringUtil::encodeHTML($row['topic']).' - '.$row['replies'];
                if(TOPTHREADS_TITLELENGTH != 0 && strlen($row['topic']) > TOPTHREADS_TITLELENGTH) $row['topic'] = StringUtil::substring($row['topic'],0,(TOPTHREADS_TITLELENGTH-3)).'...';
                $row['topic'] = StringUtil::encodeHTML($row['topic']);
                $this->TopData['threads'][] = $row;
            }
        }
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->TopData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->TopData['Status'] = intval(WBBCore::getUser()->topthreads);
		}
		else {
			if (WBBCore::getSession()->getVar('topthreads') != false) {
				$this->TopData['Status'] = WBBCore::getSession()->getVar('topthreads');
			}
		}
	}

	public function getData() {
		return $this->TopData;
	}
}

?>
