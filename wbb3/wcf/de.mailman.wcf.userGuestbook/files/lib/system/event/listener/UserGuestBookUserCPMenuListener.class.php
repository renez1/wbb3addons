<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Hides the user guestbook button in the cp menu
 *
 * @author	MailMan http://wbb3addons.ump2002.net/
 * @package	de.mailman.wcf.userGuestBook
*/

class UserGuestBookUserCPMenuListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
	    if(!WCF::getUser()->getPermission('user.guestbook.canUseOwn') && isset($eventObj->menuItems['wcf.user.usercp.menu.link.settings'])) {
            foreach($eventObj->menuItems['wcf.user.usercp.menu.link.settings'] as $key => $tab) {
                if(preg_match('/userGuestbook/', $tab['menuItem'])) unset($eventObj->menuItems['wcf.user.usercp.menu.link.settings'][$key]);
            }
        }
	}
}

?>
