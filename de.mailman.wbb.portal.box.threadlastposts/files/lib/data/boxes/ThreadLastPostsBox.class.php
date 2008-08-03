<?php
class ThreadLastPostsBox {
	protected $threadLastPostsBoxData = array();

	public function __construct($data, $boxname = "") {
		$this->threadLastPostsBoxData['templatename'] = "threadlastpostsbox";
		$this->getBoxStatus($data);
		$this->threadLastPostsBoxData['boxID'] = $data['boxID'];
		$cntPosts = 0;

        if(!defined('THREADLASTPOSTSBOX_THREADID'))       define('THREADLASTPOSTSBOX_THREADID', 0);
        if(!defined('THREADLASTPOSTSBOX_LIMIT'))          define('THREADLASTPOSTSBOX_LIMIT', 10);
        if(!defined('THREADLASTPOSTSBOX_TITLELENGTH'))    define('THREADLASTPOSTSBOX_TITLELENGTH', 28);
        if(!defined('THREADLASTPOSTSBOX_SBCOLOR'))        define('THREADLASTPOSTSBOX_SBCOLOR', 2);

		require_once(WBB_DIR.'lib/data/board/Board.class.php');
		$boardIDs = Board::getAccessibleBoards();

        if(!empty($boardIDs) && THREADLASTPOSTSBOX_THREADID) {
            $sql = "SELECT wp.postID, wp.threadID, wp.userID, wp.subject, wp.message, wp.time"
                ."\n  FROM wbb1_1_post wp"
                ."\n  JOIN wbb1_1_thread wt ON (wt.threadID = wp.threadID)"
                ."\n WHERE wp.threadID = ".THREADLASTPOSTSBOX_THREADID
                ."\n   AND wp.isDeleted = 0"
                ."\n   AND wp.isDisabled = 0"
                ."\n   AND wt.isDeleted = 0"
                ."\n   AND wt.isDisabled = 0"
                ."\n   AND wt.boardID IN (".$boardIDs.")"
                ."\n ORDER BY wp.postID DESC"
                ."\n  LIMIT 0, ".THREADLASTPOSTSBOX_LIMIT;
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                if(!empty($row['subject'])) $title = $row['subject'];
                else {
                    $title = preg_replace('/\[/','<',$row['message']);
                    $title = preg_replace('/\]/','>',$title);
                    $title = strip_tags($title); //StringUtil::stripHTML($title);
                }
                if(THREADLASTPOSTSBOX_TITLELENGTH != 0 && StringUtil::length($title) > THREADLASTPOSTSBOX_TITLELENGTH) $title = StringUtil::substring($title,0,(THREADLASTPOSTSBOX_TITLELENGTH-3)).'...';
                $row['title'] = StringUtil::encodeHTML($title);
                $this->threadLastPostsBoxData['box'][] = $row;
                $cntPosts++;
            }
        }
        WCF::getTPL()->assign(array(
            'THREADLASTPOSTSBOX_SBCOLOR' => intval(THREADLASTPOSTSBOX_SBCOLOR),
            'threadLastPostBoxCnt' => $cntPosts
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->threadLastPostsBoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->threadLastPostsBoxData['Status'] = intval(WBBCore::getUser()->threadlastpostsbox);
		}
		else {
			if (WBBCore::getSession()->getVar('threadlastpostsbox') != false) {
				$this->threadLastPostsBoxData['Status'] = WBBCore::getSession()->getVar('threadlastpostsbox');
			}
		}
	}

	public function getData() {
		return $this->threadLastPostsBoxData;
	}
}

?>
