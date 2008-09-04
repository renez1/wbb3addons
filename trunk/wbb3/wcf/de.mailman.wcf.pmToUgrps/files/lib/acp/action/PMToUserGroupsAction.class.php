<?php
require_once(WCF_DIR.'lib/acp/action/WorkerAction.class.php');
require_once(WCF_DIR.'lib/data/message/pm/PMEditor.class.php');

/**
 * $Id$
 * @package de.mailman.wcf.pmToUgrps
 * @author  MailMan (http://wbb3addons.ump2002.net)
 */
class PMToUserGroupsAction extends WorkerAction {
    public $limit = 25;
    public $pmSessionID = 0;
    public $pmData = array();
    public $showPoll = false;
    public $blindCopyArray = array();
    public $recipientArray = array();
    public $attachmentsEditor;
    public $preview, $send, $draft;
	public $action = 'PMToUserGroups';
	public $username, $userID, $pmID;


    /**
     * @see Action::readParameters()
     */
    public function readParameters() {
        parent::readParameters();

        // parameters
        if(isset($_REQUEST['pmSessionID'])) $this->pmSessionID = intval($_REQUEST['pmSessionID']);

        // get pm data
        $pmData = WCF::getSession()->getVar('pmData');
        if(!isset($pmData[$this->pmSessionID])) {
            throw new SystemException('could not find pm data');
        }

        $this->pmData = $pmData[$this->pmSessionID];
        if(!empty($this->pmData['pmID'])) $this->pmID = intval($this->pmData['pmID']);
        $this->limit = $this->pmData['limit'];
        $this->username = WCF::getUser()->username;
        $this->userID = intval(WCF::getUser()->userID);
    }

    /**
     * @see Action::execute()
     */
    public function execute() {
        parent::execute();

        // check permission
        WCF::getUser()->checkPermission('admin.user.canPMToUserGroups');

        $sql = "SELECT COUNT(DISTINCT u.userID) AS cnt"
            ."\n  FROM wcf".WCF_N."_user u"
            ."\n  LEFT JOIN wcf".WCF_N."_user_to_groups g ON (g.userID = u.userID)"
            ."\n  LEFT JOIN wcf".WCF_N."_group_option_value v ON (v.groupID = g.groupID)"
            ."\n  LEFT JOIN wcf".WCF_N."_group_option o ON (o.optionID = v.optionID)"
            ."\n WHERE o.optionName = 'user.pm.canUsePm'"
            ."\n   AND v.optionValue = '1'"
            ."\n   AND u.userID != ".$this->userID
            ."\n   AND g.groupID IN (".$this->pmData['groupIDs'].")";
        $row = WCF::getDB()->getFirstRow($sql);
        $count = $row['cnt'];

        if(!$count > 0) {
            // clear session
            if(isset($this->pmData)) {
                $pmData = WCF::getSession()->getVar('pmData');
                unset($pmData[$this->pmSessionID]);
                WCF::getSession()->register('pmData', $pmData);
            }
			$this->finish('wcf.pmToUgrps.error.noRecipients', 'index.php?form=PMToUserGroups&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
        }

        if($count <= ($this->limit * $this->loop)) {
            $endTime = TIME_NOW;
            $lf = "\n";
            // remove from outbox
            $sql = "UPDATE wcf".WCF_N."_pm"
                ."\n   SET saveInOutbox = 0"
                ."\n WHERE pmID = ".$this->pmID;
            WCF::getDB()->sendQuery($sql);

            // groups...
            $groups = '';
            $sql = "SELECT groupName"
                ."\n  FROM wcf".WCF_N."_group"
                ."\n WHERE groupID IN (".$this->pmData['groupIDs'].")"
                ."\n ORDER BY groupName";
            $result = WCF::getDB()->sendQuery($sql);
            while($row = WCF::getDB()->fetchArray($result)) {
                if(!empty($groups)) $groups .= ', ';
                $groups .= StringUtil::decodeHTML($row['groupName']);
            }


            // log...
            $log = '';
            $subject = WCF::getLanguage()->get('wcf.pmToUgrps.log.subject', array('$pmID' => $this->pmID)).' '.$this->pmData['subject'];
            if($this->pmData['enableHtml']) $log .= '<pre>';
            $log .= WCF::getLanguage()->get('wcf.pmToUgrps.log.started', array('$startTime' => DateUtil::formatDate('%d.%m.%Y %H:%M:%S', $this->pmData['startTime']))).$lf;
            $log .= WCF::getLanguage()->get('wcf.pmToUgrps.log.finished', array('$endTime' => DateUtil::formatDate('%d.%m.%Y %H:%M:%S', $endTime))).$lf;
            $log .= WCF::getLanguage()->get('wcf.pmToUgrps.log.recipients', array('$groups' => $groups, '$count' => StringUtil::decodeHTML(StringUtil::formatInteger($count)))).$lf;
            $log .= str_repeat('-', 60).$lf;
            if($this->pmData['enableHtml']) $log .= '</pre>'.$lf;
            $log .= $this->pmData['text'];
            $this->recipientArray = $this->blindCopyArray = array();
            $this->recipientArray[0]['userID'] = $this->userID;
            $this->recipientArray[0]['username'] = $this->username;
            PMEditor::create($this->draft, $this->recipientArray, $this->blindCopyArray, $subject, $log, $this->userID, $this->username, array('enableSmilies' => $this->pmData['enableSmilies'], 'enableHtml' => $this->pmData['enableHtml'], 'enableBBCodes' => $this->pmData['enableBBCodes'], 'showSignature' => false));

            // clear session
            $pmData = WCF::getSession()->getVar('pmData');
            unset($pmData[$this->pmSessionID]);
            WCF::getSession()->register('pmData', $pmData);

            $this->calcProgress();
            $msg = WCF::getLanguage()->get('wcf.pmToUgrps.finish', array('$count' => StringUtil::decodeHTML(StringUtil::formatInteger($count)), '$startTime' => DateUtil::formatShortTime('%H:%M:%S', $this->pmData['startTime']), '$endTime' => DateUtil::formatShortTime('%H:%M:%S', $endTime)));
			$this->finish($msg, 'index.php?form=PMToUserGroups&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
        }

        // get users
        $sql = "SELECT DISTINCT u.userID, u.username"
            ."\n  FROM wcf".WCF_N."_user u"
            ."\n  LEFT JOIN wcf".WCF_N."_user_to_groups g ON (g.userID = u.userID)"
            ."\n  LEFT JOIN wcf".WCF_N."_group_option_value v ON (v.groupID = g.groupID)"
            ."\n  LEFT JOIN wcf".WCF_N."_group_option o ON (o.optionID = v.optionID)"
            ."\n WHERE o.optionName = 'user.pm.canUsePm'"
            ."\n   AND v.optionValue = '1'"
            ."\n   AND u.userID != ".$this->userID
            ."\n   AND g.groupID IN (".$this->pmData['groupIDs'].")"
            ."\n ORDER BY u.userID";
        $this->blindCopyArray = array();
        $i = 0;
        $result = WCF::getDB()->sendQuery($sql, $this->limit, ($this->limit * $this->loop));
        while($row = WCF::getDB()->fetchArray($result)) {
            $this->blindCopyArray[$i]['userID'] = $row['userID'];
            $this->blindCopyArray[$i]['username'] = $row['username'];
            $i++;
        }

        if(count($this->blindCopyArray)) {
            if(empty($this->pmID)) {
                $tmp = PMEditor::create($this->draft, $this->recipientArray, $this->blindCopyArray, $this->pmData['subject'], $this->pmData['text'], $this->userID, $this->username, array('enableSmilies' => $this->pmData['enableSmilies'], 'enableHtml' => $this->pmData['enableHtml'], 'enableBBCodes' => $this->pmData['enableBBCodes'], 'showSignature' => $this->pmData['showSignature']));
                if($tmp->pmID) {
                    $this->pmID = intval($tmp->pmID);
                    $pmData = WCF::getSession()->getVar('pmData');
                    $pmData[$this->pmSessionID]['pmID'] = $this->pmID;
                    WCF::getSession()->register('pmData', $pmData);
                }
            } else {
                $recipientIDs = $inserts = '';
                foreach($this->blindCopyArray as $k => $v) {
                    $username = WCF::getDB()->escapeString($this->blindCopyArray[$k]['username']);
                    if(!empty($recipientIDs)) $recipientIDs .= ',';
                    $recipientIDs .= $this->blindCopyArray[$k]['userID'];
        			if(!empty($inserts)) $inserts .= ',';
		        	$inserts .= "\n       (".$this->pmID.", ".intval($this->blindCopyArray[$k]['userID']).", '".$username."', 1)";
                }
                if(!empty($recipientIDs) && !empty($inserts)) {
                    $sql = "INSERT IGNORE INTO wcf".WCF_N."_pm_to_user"
                        ."\n       (pmID, recipientID, recipient, isBlindCopy)"
                        ."\nVALUES ".$inserts;
                    WCF::getDB()->sendQuery($sql);
                    PMEditor::updateUnreadMessageCount($recipientIDs);
                    PMEditor::updateTotalMessageCount($recipientIDs);
                    Session::resetSessions($recipientIDs, true, false);
                }
            }
        }
        $this->executed();
        $this->calcProgress(($this->limit * $this->loop), $count);
        $msg = WCF::getLanguage()->get('wcf.pmToUgrps.progress', array('$loop' => StringUtil::decodeHTML(StringUtil::formatInteger(($this->limit * $this->loop))), '$count' => StringUtil::decodeHTML(StringUtil::formatInteger($count))));
        $this->nextLoop($msg, 'index.php?action='.$this->action.'&pmSessionID='.$this->pmSessionID.'&limit='.$this->limit.'&loop='.($this->loop + 1).'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
    }
}
?>
