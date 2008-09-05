<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * $Id$
 * @package de.mailman.wcf.userWantedPoster
 * @author  MailMan (http://wbb3addons.ump2002.net)
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
