<?php
require_once (WCF_DIR . 'lib/data/cronjobs/Cronjob.class.php');

/**
 * @author	MailMan
 */
class UserGuestBook implements Cronjob
{
	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data)
	{
		$sql = "DELETE FROM wcf".WCF_N."_user_guestbook"
		    ."\n WHERE userID NOT IN (SELECT userID FROM wcf".WCF_N."_user)";
		WCF::getDB()->sendQuery($sql);

		$sql = "DELETE FROM wcf".WCF_N."_user_guestbook_header"
		    ."\n WHERE userID NOT IN (SELECT userID FROM wcf".WCF_N."_user)";
		WCF::getDB()->sendQuery($sql);
	}
}
?>
