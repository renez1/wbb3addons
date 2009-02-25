<?php
class TopThreadViews {
	protected $TopData = array();

	public function __construct($data, $boxname = "") {
		$this->TopData['templatename'] = "topthreadviews";
		$this->getBoxStatus($data);
		$this->TopData['boxID'] = $data['boxID'];
        if(!defined('TOPTHREADVIEWS_COUNT')) define('TOPTHREADVIEWS_COUNT', 10);
        if(!defined('TOPTHREADVIEWS_TITLELENGTH')) define('TOPTHREADVIEWS_TITLELENGTH', 25);
        if(!defined('TOPTHREADVIEWS_SBCOLOR_ACP')) define('TOPTHREADVIEWS_SBCOLOR_ACP', 2);

		require_once(WBB_DIR.'lib/data/board/Board.class.php');
		$boardIDs = Board::getAccessibleBoards();
        if(!empty($boardIDs)) {
    	    $sql = "SELECT thread.*"
                ."\n  FROM wbb".WBB_N."_thread thread"
                ."\n WHERE thread.boardID IN (0".$boardIDs.")"
                ."\n ORDER BY thread.views DESC"
                ."\n LIMIT 0, ".TOPTHREADVIEWS_COUNT;
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                $row['views'] = StringUtil::formatInteger($row['views']);
                $row['title'] = StringUtil::encodeHTML($row['topic']).' - '.$row['views'];
                if(TOPTHREADVIEWS_TITLELENGTH != 0 && strlen($row['topic']) > TOPTHREADVIEWS_TITLELENGTH) $row['topic'] = substr($row['topic'],0,TOPTHREADVIEWS_TITLELENGTH-3).'...';
                $row['topic'] = StringUtil::encodeHTML($row['topic']);
                $this->TopData['threads'][] = $row;
            }
        }
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->TopData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->TopData['Status'] = intval(WBBCore::getUser()->topthreadviews);
		}
		else {
			if (WBBCore::getSession()->getVar('topthreadviews') != false) {
				$this->TopData['Status'] = WBBCore::getSession()->getVar('topthreadviews');
			}
		}
	}

	public function getData() {
		return $this->TopData;
	}
}

?>
