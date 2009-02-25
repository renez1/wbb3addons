<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

class InactiveTopicInfoListener implements EventListener {

	public function execute($eventObj, $className, $eventName) {
	    if(!defined('THREAD_SHOW_CLOSED_MESSAGE_ALL')) define('THREAD_SHOW_CLOSED_MESSAGE_ALL', false);
        if(isset($eventObj->thread)         && $eventObj->thread->isDeleted == 1)   WCF::getTPL()->append('userMessages', '<p class="error">'.WCF::getLanguage()->get('wbb.thread.inactiveTopic.deleted').'</p>');
        else if(isset($eventObj->thread)    && $eventObj->thread->isDisabled == 1)  WCF::getTPL()->append('userMessages', '<p class="warning">'.WCF::getLanguage()->get('wbb.thread.inactiveTopic.disabled').'</p>');
        else if(isset($eventObj->thread)    && $eventObj->thread->isClosed == 1 && (WCF::getUser()->getPermission('mod.board.canReplyClosedThread') || THREAD_SHOW_CLOSED_MESSAGE_ALL)) WCF::getTPL()->append('userMessages', '<p class="warning">'.WCF::getLanguage()->get('wbb.thread.inactiveTopic.closed').'</p>');
        else if(isset($eventObj->post)      && $eventObj->post->isDeleted == 1)     WCF::getTPL()->append('userMessages', '<p class="error">'.WCF::getLanguage()->get('wbb.thread.inactiveTopic.deleted').'</p>');
        else if(isset($eventObj->post)      && $eventObj->post->isDisabled == 1)    WCF::getTPL()->append('userMessages', '<p class="warning">'.WCF::getLanguage()->get('wbb.thread.inactiveTopic.disabled').'</p>');
        else if(isset($eventObj->post)      && $eventObj->post->isClosed == 1 && (WCF::getUser()->getPermission('mod.board.canReplyClosedThread') || THREAD_SHOW_CLOSED_MESSAGE_ALL)) WCF::getTPL()->append('userMessages', '<p class="warning">'.WCF::getLanguage()->get('wbb.thread.inactiveTopic.closed').'</p>');
	}
}
?>
