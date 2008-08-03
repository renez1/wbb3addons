<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Clears the sticky box cache
 * 
 * @author	MailMan
 */
class PortalStickyTopicsBoxListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
        if($eventName == 'saved') {
            $refresh = false;
            if($className == 'PostEditForm' && isset($eventObj->thread) && $eventObj->thread->isSticky) $refresh = true;
            else if(isset($eventObj->activeCategory) && $eventObj->activeCategory == 'portal') $refresh = true;
            if($refresh) {
                $sql = "SELECT boxID"
                    ."\n  FROM wbb".WBB_N."_portalboxes"
                    ."\n WHERE boxName = 'stickyTopics'";
                list($boxID) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
                if(!empty($boxID)) WCF::getCache()->clear(WBB_DIR.'cache/','cache.box-'.$boxID.'.php');
            }
        }
	}
}
?>
