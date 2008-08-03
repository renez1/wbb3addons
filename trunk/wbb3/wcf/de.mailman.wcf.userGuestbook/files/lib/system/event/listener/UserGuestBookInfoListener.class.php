<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Shows a info box if new guestbook entries available
 *
 * @author	MailMan http://wbb3addons.ump2002.net/
 * @package	de.mailman.wcf.userGuestBook
*/

class UserGuestBookInfoListener implements EventListener {
	protected $eventObj;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
        $um = WCF::getTPL()->get('userMessages');
        if($um && preg_match('/page=UserGuestbook/', $um)) return;

        if(WCF::getUser()->userID) $userID = WCF::getUser()->userID;
        if(!empty($userID)) {
            $ret = WCF::getTPL()->get('userMessages');
    		require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
    		$user = new UserProfile($userID, null, null, null);
            if($user->userGuestbook_sendInfo) {
                $sql = "SELECT gbh.userLastVisit, gbh.newEntries, gbh.lastEntryUserID, gbh.lastEntry, u.username"
                    ."\n  FROM wcf".WCF_N."_user_guestbook_header gbh"
                    ."\n  LEFT JOIN wcf".WCF_N."_user u ON (u.userID = gbh.lastEntryUserID)"
                    ."\n WHERE gbh.userID = ".$userID
                    ."\n   AND gbh.userID != gbh.lastEntryUserID";
                $row = WCF::getDB()->getFirstRow($sql);
                if(!empty($row['newEntries']) && !empty($row['lastEntry']) && $row['lastEntry'] > $row['userLastVisit']) {
                    if($row['newEntries'] != 1) $msg = WCF::getLanguage()->get('wcf.user.guestbook.infoMessages', array('$newEntries' => $row['newEntries']));
                    else $msg = WCF::getLanguage()->get('wcf.user.guestbook.infoMessage', array('$username' => $row['username']));
                    WCF::getTPL()->append('userMessages', '<p class="info"><a href="index.php?page=UserGuestbook&userID='.$userID.SID_ARG_2ND.'">'.$msg.'</a></p>');
                }
            }
        }
	}
}

?>
