<?php
require_once (WCF_DIR . 'lib/data/cronjobs/Cronjob.class.php');

/**
 * $Id$
 * @package de.mailman.wcf.userWantedPoster
 * @author  MailMan (http://wbb3addons.ump2002.net)
 */

class UserWantedPoster implements Cronjob
{
	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data)
	{
		$sql = "DELETE FROM wcf".WCF_N."_user_wanted_poster"
		    ."\n WHERE userID NOT IN (SELECT userID FROM wcf".WCF_N."_user)";
		WCF::getDB()->sendQuery($sql);

		$sql = "SELECT *"
		    ."\n  FROM wcf".WCF_N."_attachment"
		    ."\n WHERE messageType = 'wantedPoster'"
		    ."\n   AND userID NOT IN (SELECT userID FROM wcf".WCF_N."_user_wanted_poster)";
        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            $sql = "DELETE FROM wcf".WCF_N."_attachment"
                ."\n WHERE attachmentID = ".$row['attachmentID'];
            WCF::getDB()->sendQuery($sql);
            // delete attachment file
            if (file_exists(WCF_DIR.'attachments/attachment-'.$row['attachmentID'])) @unlink(WCF_DIR.'attachments/attachment-'.$row['attachmentID']);
            // delete thumbnail, if exists
            if (file_exists(WCF_DIR.'attachments/thumbnail-'.$row['attachmentID'])) @unlink(WCF_DIR.'attachments/thumbnail-'.$row['attachmentID']);
        }
	}
}
?>
