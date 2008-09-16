<?php
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/acp/adminTools/AdminTools.class.php');

/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.adminTools
 */

class AdminToolsCronForm extends ACPForm {
	public $templateName = 'adminToolsCron';
	public $atSettings = array();
    public $cronDelLogDays = 0;
    public $cronDelMovedThreadDays = 0;
    public $cronDelPmDays = 0;
    public $cronDelPmDaysExclUgrps = '';
    public $cronDelPmDaysExclUser = '';
    public $cronDelPmDaysExclFolder = 0;
    public $cronDelPmDaysShowInfo = 0;
    public $cronDelPmDaysShowExclInfo = 0;
    public $cronDbAnalyze = 0;
    public $cronDbOptimize = 0;
    public $cronDbBackup = 0;
    public $cronLogEnabled = 0;
    public $cronStatEnabled = 0;
    public $cronLogUseAdminEmail = 0;
    public $cronDelInactiveUserDays = 0;
    public $cronDelInactiveUserExclUgrps = '';
    public $cronDelInactiveUserExcl = '';
    public $cronThreadArchiveDays = 0;
    public $cronThreadArchiveSrc = array();
    public $cronThreadArchiveTgt = 0;
    public $cronThreadArchiveExclPolls = 0;
    public $cronThreadArchiveExclAnnouncement = 0;
    public $cronThreadArchiveExclSticky = 0;
    public $cronThreadArchiveExclClosed = 0;
    public $cronThreadArchiveExclDeleted = 0;


	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

        // permission
        WCF::getUser()->checkPermission('admin.system.adminTools.canView');

		$this->atSettings = AdminTools::getSettings();
        foreach($this->atSettings as $k => $val) {
            if(isset($this->$k)) $this->$k = $val;
        }
        // execute cron job
        if(!empty($_REQUEST['cRun'])) {
            if($_REQUEST['cRun'] == 'journal' && (isset($_REQUEST['log']) || isset($_REQUEST['stat']))) {
                AdminTools::cronRunJournal(0
                , (isset($_REQUEST['log']) ? $_REQUEST['log'] : 0)
                , (isset($_REQUEST['stat']) ? $_REQUEST['stat'] : 0)
                , (isset($_REQUEST['adminMail']) ? $_REQUEST['adminMail'] : 0)
                );
            } else if($_REQUEST['cRun'] == 'db' && (isset($_REQUEST['analyze']) || isset($_REQUEST['optimize']) || isset($_REQUEST['backup']))) {
                AdminTools::cronRunDB(
                 (isset($_REQUEST['analyze']) ? $_REQUEST['analyze'] : 0)
                ,(isset($_REQUEST['optimize']) ? $_REQUEST['optimize'] : 0)
                ,(isset($_REQUEST['backup']) ? $_REQUEST['backup'] : 0)
                );
            }
        }
    }

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
        foreach($_POST as $k => $val) {
            if(isset($this->$k)) $this->$k = $val;
        }
    	if($this->cronDelLogDays == '')                         $this->cronDelLogDays = 0;
    	if($this->cronDelMovedThreadDays == '')                 $this->cronDelMovedThreadDays = 0;
		if($this->cronDelPmDays == '')                          $this->cronDelPmDays = 0;
		if($this->cronDelInactiveUserDays == '')                $this->cronDelInactiveUserDays = 0;
		if($this->cronThreadArchiveDays == '')                  $this->cronThreadArchiveDays = 0;
		if(!isset($_POST['cronDbAnalyze']))                     $this->cronDbAnalyze = 0;
		if(!isset($_POST['cronDbOptimize']))                    $this->cronDbOptimize = 0;
		if(!isset($_POST['cronDbBackup']))                      $this->cronDbBackup = 0;
		if(!isset($_POST['cronLogEnabled']))                    $this->cronLogEnabled = 0;
		if(!isset($_POST['cronStatEnabled']))                   $this->cronStatEnabled = 0;
		if(!isset($_POST['cronDelPmDaysExclFolder']))           $this->cronDelPmDaysExclFolder = 0;
		if(!isset($_POST['cronDelPmDaysShowInfo']))             $this->cronDelPmDaysShowInfo = 0;
		if(!isset($_POST['cronDelPmDaysShowExclInfo']))         $this->cronDelPmDaysShowExclInfo = 0;
		if(!isset($_POST['cronLogUseAdminEmail']))              $this->cronLogUseAdminEmail = 0;
		if(!isset($_POST['cronThreadArchiveExclPolls']))        $this->cronThreadArchiveExclPolls = 0;
		if(!isset($_POST['cronThreadArchiveExclAnnouncement'])) $this->cronThreadArchiveExclAnnouncement = 0;
		if(!isset($_POST['cronThreadArchiveExclSticky']))       $this->cronThreadArchiveExclSticky = 0;
		if(!isset($_POST['cronThreadArchiveExclClosed']))       $this->cronThreadArchiveExclClosed = 0;
		if(!isset($_POST['cronThreadArchiveExclDeleted']))      $this->cronThreadArchiveExclDeleted = 0;
    }

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		if(!AdminTools::validateInt($this->cronDelLogDays)) {
			throw new UserInputException('cronDelLogDays', 'notNumeric');
		} else if(!AdminTools::validateInt($this->cronDelMovedThreadDays)) {
			throw new UserInputException('cronDelMovedThreadDays', 'notNumeric');
	    } else if(!AdminTools::validateInt($this->cronDelPmDays)) {
			throw new UserInputException('cronDelPmDays', 'notNumeric');
		} else if($this->cronDelPmDaysExclUgrps != '' && !AdminTools::validateCommaSeparatedIntList($this->cronDelPmDaysExclUgrps)) {
		    throw new UserInputException('cronDelPmDaysExclUgrps', 'commaSeparatedIntList');
		} else if($this->cronDelPmDaysExclUser != '' && !AdminTools::validateCommaSeparatedIntList($this->cronDelPmDaysExclUser)) {
		    throw new UserInputException('cronDelPmDaysExclUser', 'commaSeparatedIntList');
		} else if(!AdminTools::validateInt($this->cronDelInactiveUserDays)) {
			throw new UserInputException('cronDelInactiveUserDays', 'notNumeric');
	    } else if($this->cronDelInactiveUserExclUgrps != '' && !AdminTools::validateCommaSeparatedIntList($this->cronDelInactiveUserExclUgrps)) {
		    throw new UserInputException('cronDelInactiveUserExclUgrps', 'commaSeparatedIntList');
		} else if($this->cronDelInactiveUserExcl != '' && !AdminTools::validateCommaSeparatedIntList($this->cronDelInactiveUserExcl)) {
		    throw new UserInputException('cronDelInactiveUserExcl', 'commaSeparatedIntList');
		} else if(!AdminTools::validateInt($this->cronThreadArchiveDays)) {
			throw new UserInputException('cronThreadArchiveDays', 'notNumeric');
	    } else if($this->cronThreadArchiveTgt > 0 && is_array($this->cronThreadArchiveSrc) && count($this->cronThreadArchiveSrc) && in_array($this->cronThreadArchiveTgt, $this->cronThreadArchiveSrc)) {
			throw new UserInputException('cronThreadArchiveTgt', 'equalTgtSrc');
        }
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		$src = '';
		if(is_array($this->cronThreadArchiveSrc) && count($this->cronThreadArchiveSrc)) {
		    $src = implode(',', $this->cronThreadArchiveSrc);
		}
        AdminTools::saveCron(
            array(
                'cronDelLogDays'                    => $this->cronDelLogDays,
                'cronDelMovedThreadDays'            => $this->cronDelMovedThreadDays,
                'cronDelPmDays'                     => $this->cronDelPmDays,
                'cronDelPmDaysExclUgrps'            => $this->cronDelPmDaysExclUgrps,
                'cronDelPmDaysExclUser'             => $this->cronDelPmDaysExclUser,
                'cronDelPmDaysExclFolder'           => $this->cronDelPmDaysExclFolder,
                'cronDelPmDaysShowInfo'             => $this->cronDelPmDaysShowInfo,
                'cronDelPmDaysShowExclInfo'         => $this->cronDelPmDaysShowExclInfo,
                'cronLogEnabled'                    => $this->cronLogEnabled,
                'cronStatEnabled'                   => $this->cronStatEnabled,
                'cronDbAnalyze'                     => $this->cronDbAnalyze,
                'cronDbOptimize'                    => $this->cronDbOptimize,
                'cronDbBackup'                      => $this->cronDbBackup,
                'cronLogUseAdminEmail'              => $this->cronLogUseAdminEmail,
                'cronDelInactiveUserDays'           => $this->cronDelInactiveUserDays,
                'cronDelInactiveUserExclUgrps'      => $this->cronDelInactiveUserExclUgrps,
                'cronDelInactiveUserExcl'           => $this->cronDelInactiveUserExcl,
                'cronThreadArchiveDays'             => $this->cronThreadArchiveDays,
                'cronThreadArchiveSrc'              => $src,
                'cronThreadArchiveTgt'              => $this->cronThreadArchiveTgt,
                'cronThreadArchiveExclPolls'        => $this->cronThreadArchiveExclPolls,
                'cronThreadArchiveExclAnnouncement' => $this->cronThreadArchiveExclAnnouncement,
                'cronThreadArchiveExclSticky'       => $this->cronThreadArchiveExclSticky,
                'cronThreadArchiveExclClosed'       => $this->cronThreadArchiveExclClosed,
                'cronThreadArchiveExclDeleted'      => $this->cronThreadArchiveExclDeleted
            )
        );
        WCF::getTPL()->assign('success', true);
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		$boards = AdminTools::cronThreadArchiveGetBoards();
		if(isset($_POST['cronThreadArchiveSrc']) && is_array($_POST['cronThreadArchiveSrc'])) {
		    foreach($boards as $k => $v) {
		        if(in_array($boards[$k]['boardID'], $_POST['cronThreadArchiveSrc'])) {
		            $boards[$k]['SRC'] = true;
		        } else {
		            $boards[$k]['SRC'] = false;
		        }
		    }
		}
		WCF::getTPL()->assign(array(
		    'wbbExists'                         => AdminTools::wbbExists(),
		    'cronDelLogDays'                    => $this->cronDelLogDays,
		    'cronDelMovedThreadDays'            => $this->cronDelMovedThreadDays,
		    'cronDelPmDays'                     => $this->cronDelPmDays,
		    'cronDbAnalyze'                     => $this->cronDbAnalyze,
		    'cronDbOptimize'                    => $this->cronDbOptimize,
		    'cronDbBackup'                      => $this->cronDbBackup,
		    'cronDelPmDaysExclUgrps'            => $this->cronDelPmDaysExclUgrps,
		    'cronDelPmDaysExclUser'             => $this->cronDelPmDaysExclUser,
		    'cronDelPmDaysExclFolder'           => $this->cronDelPmDaysExclFolder,
		    'cronDelPmDaysShowInfo'             => $this->cronDelPmDaysShowInfo,
		    'cronDelPmDaysShowExclInfo'         => $this->cronDelPmDaysShowExclInfo,
		    'cronLogEnabled'                    => $this->cronLogEnabled,
		    'cronStatEnabled'                   => $this->cronStatEnabled,
		    'cronActive'                        => AdminTools::cronIsEnabled(),
		    'cronLogUseAdminEmail'              => $this->cronLogUseAdminEmail,
            'cronDelInactiveUserDays'           => $this->cronDelInactiveUserDays,
            'cronDelInactiveUserExclUgrps'      => $this->cronDelInactiveUserExclUgrps,
            'cronDelInactiveUserExcl'           => $this->cronDelInactiveUserExcl,
            'cronThreadArchiveDays'             => $this->cronThreadArchiveDays,
            'cronThreadArchiveBoards'           => $boards,
            'cronThreadArchiveTgt'              => $this->cronThreadArchiveTgt,
            'cronThreadArchiveExclPolls'        => $this->cronThreadArchiveExclPolls,
            'cronThreadArchiveExclAnnouncement' => $this->cronThreadArchiveExclAnnouncement,
            'cronThreadArchiveExclSticky'       => $this->cronThreadArchiveExclSticky,
            'cronThreadArchiveExclClosed'       => $this->cronThreadArchiveExclClosed,
            'cronThreadArchiveExclDeleted'      => $this->cronThreadArchiveExclDeleted
		));
	}

	/**
	 * @see Form::show()
	 */
	public function show() {
        // permission
        WCF::getUser()->checkPermission('admin.system.adminTools.canView');

		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.adminTools');

		// show form
		parent::show();
	}
}
?>
