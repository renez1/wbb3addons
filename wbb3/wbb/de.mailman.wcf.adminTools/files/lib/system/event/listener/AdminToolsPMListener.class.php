<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * AdminTools Event Listener
 *
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.adminTools
 */
class AdminToolsPMListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventName == 'assignVariables') {
            $showMsg = true;
            $atse = $aUgrps = $aUids = $uUgrps = array();
    		$sql = "SELECT atse_name, atse_value"
	    	    ."\n  FROM wcf".WCF_N."_admin_tool_setting"
	    	    ."\n WHERE atse_name IN ('cronDelPmDays', 'cronDelPmDaysShowInfo', 'cronDelPmDaysShowExclInfo', 'cronDelPmDaysExclUgrps', 'cronDelPmDaysExclUser')";
            $result = WCF::getDB()->sendQuery($sql);
    		while($row = WCF::getDB()->fetchArray($result)) {
                $atse[$row['atse_name']] = $row['atse_value'];
            }
            if(!empty($atse['cronDelPmDays']) && !empty($atse['cronDelPmDaysShowInfo'])) {
                if(empty($atse['cronDelPmDaysShowExclInfo'])) {
                    if($showMsg && WCF::getUser()->groupIDs && !empty($atse['cronDelPmDaysExclUgrps'])) {
                        $uUgrps = explode(',', WCF::getUser()->groupIDs);
                        $aUgrps = explode(',', $atse['cronDelPmDaysExclUgrps']);
                        foreach($uUgrps as $uK => $uV) {
                            $uV = intval($uV);
                            foreach($aUgrps as $aK => $aV) {
                                $aV = intval($aV);
                                if($uV == $aV) {
                                    $showMsg = false;
                                    break 2;
                                }
                            }
                        }
                    }
                    if($showMsg && !empty($atse['cronDelPmDaysExclUser'])) {
                        $uid = intval(WCF::getUser()->userID);
                        $aUids = explode(',', $atse['cronDelPmDaysExclUser']);
                        foreach($aUids as $aK => $aV) {
                            $aV = intval($aV);
                            if($aV == $uid) {
                                $showMsg = false;
                                break;
                            }
                        }
                    }
                }
                if($showMsg) WCF::getTPL()->append('userMessages', '<p class="info">'.WCF::getLanguage()->get('wcf.acp.adminTools.cron.pm.showUserInfo.message', array('$cronDelPmDays' => $atse['cronDelPmDays'])).'</p>');
    	    }
		}
	}
}
?>