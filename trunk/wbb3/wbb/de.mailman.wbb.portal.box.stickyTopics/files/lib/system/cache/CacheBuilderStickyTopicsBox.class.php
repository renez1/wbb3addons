<?php
/* $Id$ */
require_once(WCF_DIR.'lib/data/user/User.class.php');
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

class CacheBuilderStickyTopicsBox implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data           = array();
		$boardIDs       = STICKYTOPICSBOX_BOARDIDS;
		$limit          = STICKYTOPICSBOX_NUMOFENTRIES;
		$showClosed     = STICKYTOPICSBOX_SHOWCLOSED;
		$sortField      = STICKYTOPICSBOX_SORTFIELD;

        $permBoardIDs = Board::getAccessibleBoards();
		if(empty($boardIDs) || empty($permBoardIDs)) return $data;

		$sql = "SELECT *"
		    ."\n  FROM wbb".WBB_N."_thread"
            ."\n WHERE isSticky = 1"
            ."\n   AND boardID IN(".$boardIDs.")"
            ."\n   AND boardID IN(".$permBoardIDs.")"
            ."\n   AND isDeleted = 0"
            ."\n   AND isDisabled = 0"
            ."\n   AND movedThreadID = 0";
        if(empty($showClosed)) $sql .= "\n   AND isClosed = 0";
        $sql .= " ORDER BY ".$sortField." DESC"
             ."\n LIMIT ".$limit;

		$result = WBBCore::getDB()->sendQuery($sql);
		while ($row = WBBCore::getDB()->fetchArray($result)) $data[] = $row;
        return $data;
	}
}
?>
