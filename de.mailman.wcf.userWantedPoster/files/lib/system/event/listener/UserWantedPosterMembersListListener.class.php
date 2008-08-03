<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Shows the user wanted poster tab on members list page.
 * 
 * @author	MailMan
 */
class UserWantedPosterMembersListListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
	    if(WCF::getUser()->getPermission('user.wantedPoster.canViewWantedPoster') && WCF::getUser()->getPermission('user.wantedPoster.canViewMembersListTab')) {
            if($className == 'UserWantedPosterListMembersPage') $style = ' class="activeTabMenu"';
            else $style = '';
    		WCF::getTPL()->append('additionalTabs', '<li'.$style.'><a href="index.php?page=UserWantedPosterListMembers'.SID_ARG_2ND.'"><img src="'.RELATIVE_WCF_DIR.'icon/userWantedPosterListM.png" alt="" /> '.WCF::getLanguage()->get('wcf.header.menu.userWantedPoster').'</a></li>');
        }
	}
}
?>
