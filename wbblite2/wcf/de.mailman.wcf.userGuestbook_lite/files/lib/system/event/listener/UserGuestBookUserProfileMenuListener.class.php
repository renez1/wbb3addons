<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Hides the user guestbook menu button if the questbook is disabled
 *
 * @author	MailMan http://wbb3addons.ump2002.net/
 * @package	de.mailman.wcf.userGuestBook
*/

class UserGuestBookUserProfileMenuListener implements EventListener {
	protected $eventObj;
	protected $user;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$this->eventObj = $eventObj;

		switch($eventName) {
			case 'buildMenu':
				$this->buildMenu();
				break;
		}
	}

	/**
	 * @see TreeMenu::buildMenu()
	 */
	protected function buildMenu() {
	    if(isset($this->eventObj->menuItems)) {
		    require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
		    $this->user = new UserProfile($this->eventObj->userID, null, null, null);
            if((!$this->user->userGuestbook_enable && $this->eventObj->userID != WCF::getUser()->userID)
            || !$this->user->getPermission('user.guestbook.canUseOwn')) {
                foreach($this->eventObj->menuItems as $k => $v) {
                    foreach($this->eventObj->menuItems[$k] as $ik => $iv) {
                        if(isset($iv['menuItem']) && $iv['menuItem'] == 'wcf.user.profile.menu.link.guestbook') {
                            unset($this->eventObj->menuItems[$k][$ik]);
                        }
                    }
                }
            }
        }
    }
}

?>
