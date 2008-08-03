<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	MailMan http://wbb3addons.ump2002.net/
 * @package	de.mailman.wcf.userGuestBook
*/

class UserGuestBookThreadListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
        if(!USERGUESTBOOK_SHOWINSIDEBAR) return;
        if(!WCF::getUser()->getPermission('user.guestbook.canRead')) return;
        if(empty($eventObj->postList->posts)) return;

        $seen = $ret = array();
        $ret = WCF::getTPL()->get('additionalSidebarUserContacts');
        $link = '<a href="index.php?page=UserGuestbook&amp;userID=%1$d'.SID_ARG_2ND.'"><img src="'.RELATIVE_WCF_DIR.'icon/guestbookS.png" alt="" title="%2$s" /></a>';
        $curUserID = WCF::getUser()->userID;
        $curUserCanUse = WCF::getUser()->getPermission('user.guestbook.canUseOwn');
        $gbEnableOption = 'userOption'.User::getUserOptionID('userGuestbook_enable');

        require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

        foreach($eventObj->postList->posts as $post) {
            if($post->userID) {
                if($curUserID == $post->userID && !$curUserCanUse) {
                    continue;
                } else if(!$post->$gbEnableOption) {
                    continue;
                } else {
                    if(array_key_exists($post->userID, $seen)) {
                        if(!empty($seen[$post->userID])) {
                            if(isset($ret[$post->postID])) $ret[$post->postID] .= ' '.$seen[$post->userID];
                            else $ret[$post->postID] = $seen[$post->userID];
                        }
                    } else {
                        $add = '';
                        $user = new UserProfile($post->userID, null, null, null);
                        if($user->getPermission('user.guestbook.canUseOwn')) {
                            $username = StringUtil::encodeHTML($user->username);
                            $title = WCF::getLanguage()->get('wcf.user.guestbook.title', array('$user->username' => $username));
                            $add = sprintf($link, $post->userID, $title);
                            if(isset($ret[$post->postID])) $ret[$post->postID] .= ' '.$add;
                            else $ret[$post->postID] = $add;
                        }
                        $seen[$post->userID] = $add;
                    }
                }
            }
        }
        if(count($ret)) WCF::getTPL()->assign('additionalSidebarUserContacts',  $ret);
	}
}

?>
