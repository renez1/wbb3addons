<?php
class TopThanksgivingPosts {
	protected $TopData = array();

	public function __construct($data, $boxname = "") {
		$this->TopData['templatename'] = "topthanksgivingposts";
		$this->getBoxStatus($data);
		$this->TopData['boxID'] = $data['boxID'];

        if(!defined('TOPTHANKSGIVING_COUNT_ACP'))       define('TOPTHANKSGIVING_COUNT_ACP', 10);
        if(!defined('TOPTHANKSGIVING_TITLELENGTH_ACP')) define('TOPTHANKSGIVING_TITLELENGTH_ACP', 28);
        if(!defined('TOPTHANKSGIVING_SBCOLOR_ACP'))     define('TOPTHANKSGIVING_SBCOLOR_ACP', 2);
        if(!defined('TOPTHANKSGIVING_HITS_ACP'))        define('TOPTHANKSGIVING_HITS_ACP', true);

		require_once(WBB_DIR.'lib/data/board/Board.class.php');
		$boardIDs = Board::getAccessibleBoards();

        if(!empty($boardIDs)) {
            $sql = "SELECT thread.topic AS subject, MIN(post.postID) AS postID, COUNT(*) AS cnt"
                ."\n  FROM wbb".WBB_N."_thread thread"
                ."\n  LEFT JOIN (wbb".WBB_N."_post post, wbb".WBB_N."_thank_guests tg, wbb".WBB_N."_thank_user tu)"
                ."\n  ON (post.threadID = thread.threadID AND (post.postID = tu.postID OR post.postID = tg.postID))"
                ."\n  WHERE thread.isDisabled = 0"
                ."\n  AND thread.isDeleted = 0"
                ."\n  AND thread.boardID IN (".$boardIDs.")"
                ."\n  AND post.isDeleted = 0"
                ."\n  AND post.isDisabled = 0"
                ."\n  GROUP BY thread.threadID"
                ."\n  ORDER BY cnt DESC"
                ."\n  LIMIT 0, ".TOPTHANKSGIVING_COUNT_ACP;
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                $plainSubject = $row['subject'];
                $row['thanks'] = StringUtil::formatInteger($row['cnt']);
                $row['title'] = StringUtil::encodeHTML($plainSubject).' - '.$row['thanks'];
                if(TOPTHANKSGIVING_TITLELENGTH_ACP != 0 && strlen($plainSubject) > TOPTHANKSGIVING_TITLELENGTH_ACP) $row['subject'] = StringUtil::substring($plainSubject,0,(TOPTHANKSGIVING_TITLELENGTH_ACP-3)).'...';
                $row['subject'] = StringUtil::encodeHTML($row['subject']);
                $this->TopData['thanksgiving'][] = $row;
            }
        }
        WCF::getTPL()->assign('TOPTHANKSGIVING_SBCOLOR_ACP', intval(TOPTHANKSGIVING_SBCOLOR_ACP));
        WCF::getTPL()->assign('TOPTHANKSGIVING_HITS_ACP', TOPTHANKSGIVING_HITS_ACP);
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->TopData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->TopData['Status'] = intval(WBBCore::getUser()->topthanksgivingposts);
		}
		else {
			if (WBBCore::getSession()->getVar('topthanksgivingposts') != false) {
				$this->TopData['Status'] = WBBCore::getSession()->getVar('topthanksgivingposts');
			}
		}
	}

	public function getData() {
		return $this->TopData;
	}
}

?>
