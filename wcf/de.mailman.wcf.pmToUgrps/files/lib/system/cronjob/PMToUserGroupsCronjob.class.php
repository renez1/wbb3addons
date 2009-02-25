<?php
require_once (WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');

/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.pmToUgrps
 */

class PMToUserGroupsCronjob implements Cronjob
{
    protected $atSettings = array();
    protected $pmDelCnt = 0;

	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data)
	{
        $pmIDs = '';
        // get all elapsed bulk mailings...
        $sql = "SELECT pmID"
            ."\n  FROM wcf".WCF_N."_pm_bulk_mailing"
            ."\n WHERE elapsedTime > 0"
            ."\n   AND elapsedTime < ".TIME_NOW;
        $result = WCF::getDB()->sendQuery($sql);
        while($row = WCF::getDB()->fetchArray($result)) {
            if(!empty($pmIDs)) $pmIDs .= ',';
            $pmIDs .= $row['pmID'];
        }
        if(!empty($pmIDs)) {
            // start deleting...
            $sql = "DELETE FROM wcf".WCF_N."_pm_to_user"
                ."\n WHERE pmID IN (".$pmIDs.")";
            WCF::getDB()->sendQuery($sql);
            $sql = "DELETE FROM wcf".WCF_N."_pm_hash"
                ."\n WHERE pmID IN (".$pmIDs.")";
            WCF::getDB()->sendQuery($sql);
            $sql = "DELETE FROM wcf".WCF_N."_pm"
                ."\n WHERE pmID IN (".$pmIDs.")";
            WCF::getDB()->sendQuery($sql);
        }
        // clean up self...
        $sql = "DELETE FROM wcf".WCF_N."_pm_bulk_mailing"
            ."\n WHERE pmID NOT IN (SELECT pmID FROM wcf".WCF_N."_pm)";
        WCF::getDB()->registerShutdownUpdate($sql);
	}
}
?>
