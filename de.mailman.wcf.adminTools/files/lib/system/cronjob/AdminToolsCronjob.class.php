<?php
require_once (WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');
require_once(WCF_DIR.'lib/acp/adminTools/AdminTools.class.php');

/**
 * AdminTools Cronjob Class
 *
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.adminTools
 */

class AdminToolsCronjob implements Cronjob
{
    protected $atSettings = array();
    protected $pmDelCnt = 0;

	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data)
	{

        // read settings ***********************************
        $this->atSettings = AdminTools::getSettings();
        
        // check last execution time
        $this->atSettings['cronLastRun'] = intval($this->atSettings['cronLastRun']);
        if($this->atSettings['cronLastRun'] < TIME_NOW - 3600) {
            $sql = "UPDATE wcf".WCF_N."_admin_tool_setting"
                ."\n   SET atse_value = '".TIME_NOW."'"
                ."\n WHERE atse_name = 'cronLastRun'";
            WCF::getDB()->registerShutdownUpdate($sql);
        } else {
            return;
        }

        // WBB-LOG *****************************************
    	if(!empty($this->atSettings['cronDelLogDays'])) {
    	    WCF::getDB()->sendQuery("DELETE FROM wcf".WCF_N."_cronjobs_log WHERE execTime < ".(time() - ($this->atSettings['cronDelLogDays'] * 86400)));
        }

        // delete PNs **************************************
    	if(!empty($this->atSettings['cronDelPmDays'])) {
            $dPMs = $cnt = 0;
            $sql = "SELECT pmID"
                ."\n  FROM wcf".WCF_N."_pm"
                ."\n WHERE time < ".(time() - ($this->atSettings['cronDelPmDays'] * 86400));
            if(!empty($this->atSettings['cronDelPmDaysExclUgrps'])) $sql .= "\n   AND userID NOT IN (SELECT userID FROM wcf".WCF_N."_user_to_groups WHERE groupID IN (".$this->atSettings['cronDelPmDaysExclUgrps']."))";
            if(!empty($this->atSettings['cronDelPmDaysExclUser'])) $sql .= "\n   AND userID NOT IN (".$this->atSettings['cronDelPmDaysExclUser'].")";
            $result = WCF::getDB()->sendQuery($sql);
            while($row = WCF::getDB()->fetchArray($result)) {
                $cnt++;
                $this->pmDelCnt++;
                $dPMs .= ','.$row['pmID'];
                if(($cnt % 50) == 0) {
                    AdminTools::deletePMs($dPMs);
                    $cnt = 0;
                    $dPMs = 0;
                }
            }
            if(!empty($dPMs)) AdminTools::deletePMs($dPMs);
        }
        
        // delete inactive user ****************************
        if(!empty($this->atSettings['cronDelInactiveUserDays']) && $this->atSettings['cronDelInactiveUserDays'] > 0) {
            AdminTools::deleteInactiveUser($this->atSettings['cronDelInactiveUserDays'], $this->atSettings['cronDelInactiveUserExcl'], $this->atSettings['cronDelInactiveUserExclUgrps']);
        }

        // check moved threads
        AdminTools::cronCheckMovedThreads(intval($this->atSettings['cronDelMovedThreadDays']));

        // archive
        AdminTools::cronThreadArchive($this->atSettings);

        // spider ******************************************
        AdminTools::syncSpider();

        // journal *****************************************
        AdminTools::cronRunJournal($this->pmDelCnt, $this->atSettings['cronLogEnabled'], $this->atSettings['cronStatEnabled'], $this->atSettings['cronLogUseAdminEmail']);

        // DB **********************************************
        AdminTools::cronRunDB($this->atSettings['cronDbAnalyze'], $this->atSettings['cronDbOptimize'], $this->atSettings['cronDbBackup']);

	}
}
?>
