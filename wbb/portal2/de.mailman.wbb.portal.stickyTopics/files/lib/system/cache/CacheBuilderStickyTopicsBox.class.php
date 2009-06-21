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
        $permBoardIDs   = Board::getAccessibleBoards();
		if(!STICKYTOPICSBOX_BOARDIDS || empty($permBoardIDs)) return $data;

		$sql = "SELECT *"
		    ."\n  FROM wbb".WBB_N."_thread"
            ."\n WHERE isSticky = 1"
            ."\n   AND boardID IN(".STICKYTOPICSBOX_BOARDIDS.")"
            ."\n   AND boardID IN(".$permBoardIDs.")"
            ."\n   AND isDeleted = 0"
            ."\n   AND isDisabled = 0"
            ."\n   AND movedThreadID = 0";
        if(!STICKYTOPICSBOX_SHOWCLOSED) $sql .= "\n   AND isClosed = 0";
        $sql .= " ORDER BY ".STICKYTOPICSBOX_SORTFIELD." DESC"
             ."\n LIMIT ".STICKYTOPICSBOX_NUMOFENTRIES;

		$result = WBBCore::getDB()->sendQuery($sql);
		while ($row = WBBCore::getDB()->fetchArray($result)) $data[] = $row;
        return $data;
	}
}
?>
