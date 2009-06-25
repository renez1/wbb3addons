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
		$clearCache = false;
		if($className == 'ThreadActionPage' && $eventName == 'show' && isset($eventObj->action) && isset($eventObj->thread)) {
		    if($eventObj->thread->isSticky == 1
		    &&($eventObj->action == 'trash'
		    || $eventObj->action == 'recover'
		    || $eventObj->action == 'unstick')) {
		        $clearCache = true;
            } else if($eventObj->action == 'stick') {
                $clearCache = true;
            }
        } else if($className == 'ThreadAddForm' && $eventName == 'saved' && $eventObj->isImportant == 1) {
			$clearCache = true;
        } else if($className == 'PostEditForm' && $eventName == 'saved' && $eventObj->isImportant == 1) {
			$clearCache = true;
		}

		if($clearCache) {
			$box = PortalBox::getBoxByName('stickyTopics');
			$box->clearDataCache();
		}
	}
}
?>
