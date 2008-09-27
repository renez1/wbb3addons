<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * $Id$
 * @package de.mailman.wcf.userWantedPoster
 * @author  MailMan (http://wbb3addons.ump2002.net)
 */

class UserWantedPosterUserProfileMenuListener implements EventListener {
	protected $eventObj;

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
	    if(isset($this->eventObj->menuItems) && $this->eventObj->userID != WCF::getUser()->userID) {
            $sql = "SELECT COUNT(userID) AS cnt"
                ."\n  FROM wcf".WCF_N."_user_wanted_poster"
                ."\n WHERE userID = ".$this->eventObj->userID;
            list($cnt) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
            if(empty($cnt)) {
                foreach($this->eventObj->menuItems as $k => $v) {
                    foreach($this->eventObj->menuItems[$k] as $ik => $iv) {
                        if(isset($iv['menuItem']) && $iv['menuItem'] == 'wcf.user.profile.menu.link.wantedPoster') {
                            unset($this->eventObj->menuItems[$k][$ik]);
                        }
                    }
                }
            }
        }
    }
}

?>
