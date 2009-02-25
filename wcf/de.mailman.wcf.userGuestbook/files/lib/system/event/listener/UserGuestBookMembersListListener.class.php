<?php
/* $Id$ */
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Shows the guestbook tab on members list page.
 * 
 * @author	MailMan
 */
class UserGuestBookMembersListListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
	    if(WCF::getUser()->getPermission('user.guestbook.canViewList') && WCF::getUser()->getPermission('user.guestbook.canViewMembersListTab')) {
            if($className == 'UserGuestbookListMembersPage') $style = ' class="activeTabMenu"';
            else $style = '';
    		WCF::getTPL()->append('additionalTabs', '<li'.$style.'><a href="index.php?page=UserGuestbookListMembers'.SID_ARG_2ND.'"><img src="'.RELATIVE_WCF_DIR.'icon/guestbookListM.png" alt="" /> '.WCF::getLanguage()->get('wcf.header.menu.userGuestbook').'</a></li>');
        }
	}
}
?>