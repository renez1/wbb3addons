<?php
/* $Id$ */
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
            if($className == 'PostEditForm' && isset($eventObj->thread) && $eventObj->thread->isSticky) {
            	$box = PortalBox::getBoxByName('stickyTopics');
				$box->clearDataCache();                                      
            }
        }
	}
}
?>
