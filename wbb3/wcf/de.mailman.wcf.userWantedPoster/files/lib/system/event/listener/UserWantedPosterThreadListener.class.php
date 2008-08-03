<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	MailMan http://wbb3addons.ump2002.net/
 * @package	de.mailman.wcf.userWantedPoster
*/

class UserWantedPosterThreadListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
        if(!USERWANTEDPOSTER_SHOWINSIDEBAR) return;
        if(!WCF::getUser()->getPermission('user.wantedPoster.canViewWantedPoster')) return;
        if(empty($eventObj->postList->posts)) return;

        $seen = $wpUserIDs = $postUserIDs = array();
        $uwpIDinStr = '';
        $ret = WCF::getTPL()->get('additionalSidebarUserContacts');
        $link = '<a href="index.php?page=UserWantedPoster&amp;userID=%1$d"><img src="'.RELATIVE_WCF_DIR.'icon/userWantedPosterS.png" alt="" title="%2$s" /></a>';
        $curUserID = WCF::getUser()->userID;
        $curUserCanUse = WCF::getUser()->getPermission('user.wantedPoster.canUseWantedPoster');

        require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

        foreach($eventObj->postList->posts as $post) {
            if($post->userID && !in_array($post->userID, $postUserIDs)) $postUserIDs[] = $post->userID;
        }
        $uwpIDinStr = implode(',', $postUserIDs);
        if(empty($uwpIDinStr) || $uwpIDinStr == ',') return;

        $sql = "SELECT userID"
            ."\n  FROM wcf".WCF_N."_user_wanted_poster"
            ."\n WHERE userID IN (".$uwpIDinStr.")";
        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) $wpUserIDs[] = $row['userID'];
        if(!count($wpUserIDs)) return;

        foreach($eventObj->postList->posts as $post) {
            if($post->userID) {
                if($curUserID == $post->userID && !$curUserCanUse) {
                    continue;
                } else if(!in_array($post->userID, $wpUserIDs, true)) {
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
                        if($user->getPermission('user.wantedPoster.canUseWantedPoster')) {
                            $username = StringUtil::encodeHTML($user->username);
                            $title = WCF::getLanguage()->get('wcf.user.wantedPoster.title', array('$user->username' => $username));
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
