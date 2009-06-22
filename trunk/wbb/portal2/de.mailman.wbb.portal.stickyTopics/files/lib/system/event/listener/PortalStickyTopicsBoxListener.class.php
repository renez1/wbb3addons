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
		$thread = null;
		if ($className == 'ThreadAddForm') {
			$thread = $eventObj->newThread;
		}
		else {
			$thread = $eventObj->thread;
		}
		if ($thread && $eventObj->isImportant == 1) {
			$box = PortalBox::getBoxByName('stickyTopics');
			$box->clearDataCache();
		}

	}
}
?>
