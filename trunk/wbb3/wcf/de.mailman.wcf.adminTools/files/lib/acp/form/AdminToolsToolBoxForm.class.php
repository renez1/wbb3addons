<?php
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/acp/adminTools/AdminTools.class.php');

/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.adminTools
 */

class AdminToolsToolBoxForm extends ACPForm {
	public $templateName = 'adminToolsToolBox';
    public $cacheDel = 0;
    public $cacheTpl = 0;
    public $cacheLang = 0;
    public $cacheOpt = 0;
    public $cacheRSS = 0;
    public $spRssExists = false;
    public $spiders = array();
    public $spiderCur = array();
    public $spiderCntOwn = 0;
    public $spiderCntSyn = 0;
    public $spiderCntAll = 0;
    public $spiderID = 0;
    public $errMsg = '';
    public $sucMsg = '';
    public $action = '';
    public $userOptions = array();
    public $languages = array();
    public $setLanguageMsg = '';


	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

        // permission
        WCF::getUser()->checkPermission('admin.system.adminTools.canView');

        if(isset($_REQUEST['cacheDel']))    $this->cacheDel = $_REQUEST['cacheDel'];
        if(isset($_REQUEST['cacheTpl']))    $this->cacheTpl = $_REQUEST['cacheTpl'];
        if(isset($_REQUEST['cacheLang']))   $this->cacheLang = $_REQUEST['cacheLang'];
        if(isset($_REQUEST['cacheOpt']))    $this->cacheOpt = $_REQUEST['cacheOpt'];
        if(isset($_REQUEST['cacheRSS']))    $this->cacheRSS = $_REQUEST['cacheRSS'];

        $this->spiderCur['spiderIdentifier']    = (empty($_POST['spiderIdentifier']) ? '' : $_POST['spiderIdentifier']);
        $this->spiderCur['spiderName']          = (empty($_POST['spiderName']) ? '' : $_POST['spiderName']);
        $this->spiderCur['spiderUrl']           = (empty($_POST['spiderUrl']) ? '' : $_POST['spiderUrl']);
        $this->spiderID                         = (empty($_POST['spiderID']) ? 0 : $_POST['spiderID']);

        // execute
        if(!empty($_REQUEST['cRun'])) {
            if($_REQUEST['cRun'] == 'cache' &&
            (!empty($this->cacheDel) || !empty($this->cacheTpl) || !empty($this->cacheLang) || !empty($this->cacheOpt) || !empty($this->cacheRSS))) {
                $ret = AdminTools::cacheDel($this->cacheDel, $this->cacheTpl, $this->cacheLang, $this->cacheOpt, $this->cacheRSS);
                $cntCache = $cntTpl = $cntLang = $cntOpt = $cntRSS = 0;
                if(isset($ret['cacheDel'])) $cntCache = $ret['cacheDel'];
                if(isset($ret['cacheTpl'])) $cntTpl = $ret['cacheTpl'];
                if(isset($ret['cacheLang'])) $cntLang = $ret['cacheLang'];
                if(isset($ret['cacheOpt'])) $cntOpt = $ret['cacheOpt'];
                if(isset($ret['cacheRSS'])) $cntRSS = $ret['cacheRSS'];
                if(empty($cntCache) && empty($cntTpl) && empty($cntLang) && empty($cntOpt) && empty($cntRSS)) $this->errMsg = WCF::getLanguage()->get('wcf.acp.adminTools.error.cache');
                else $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.cache', array('$cacheDel' => $cntCache, '$cacheTpl' => $cntTpl, '$cacheLang' => $cntLang, '$cacheOpt' => $cntOpt, '$cacheRSS' => $cntRSS));
            }
        }
    }

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
	    parent::readFormParameters();
        // spiders
        if(!empty($_POST['spiderAction'])) {
            $this->action = 'spider_'.$_POST['spiderAction'];
            if($this->action == 'spider_select') {
                $this->spiderCur = array();
                $this->spiderID = 0;
                if(!empty($_POST['spiderID'])) {
                    $tmp = AdminTools::getSpider($_POST['spiderID']);
                    if(!empty($tmp['spiderID'])) {
                        $this->spiderCur = $tmp;
                        $this->spiderID = $tmp['spiderID'];
                    }
                }
            } else if($this->action == 'spider_export') {
                $content = 'SPIDERIDENTIFIER;SPIDERNAME;SPIDERURL'."\n";
                $fileName = 'wbb_admin_tools_spider_export_'.date('YmdHis').'.csv';
        		$sql = "SELECT spiderIdentifier, spiderName, spiderUrl"
        		    ."\n  FROM wcf".WCF_N."_admin_tool_spider";
                $result = WCF::getDB()->sendQuery($sql);
        		while($row = WCF::getDB()->fetchArray($result)) {
                    $content .= '"'.$row['spiderIdentifier'].'";"'.$row['spiderName'].'";"'.$row['spiderUrl'].'"'."\n";
                }
                // file type
                header('Content-Type: application/octet-stream');
                // file name
                header('Content-Disposition: attachment; filename="'.$fileName.'"');
                // no cache headers
                header('Pragma: no-cache');
                header('Expires: 0');
                // send file
                echo $content;
                exit;
            }
        } else if(!empty($_POST['boardAction'])) {
            $this->action = 'board';
        } else if(!empty($_POST['prefixAction'])) {
            $this->action = 'prefix';
        } else if(!empty($_POST['ugrpAction'])) {
            $this->action = 'ugrps';
        } else if(!empty($_POST['userOptionAction'])) {
            $this->action = 'userOptions';
        } else if(!empty($_POST['userDefaultOptionAction'])) {
            $this->action = 'userDefaultOptions';
        } else if(!empty($_POST['setLanguageAction'])) {
            $this->action = 'setLanguage';
        }
    }

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		if($this->action == 'spider_save') {
		    if(empty($_POST['spiderIdentifier']))   throw new UserInputException('spiderIdentifier', 'empty');
		    else if(empty($_POST['spiderName']))    throw new UserInputException('spiderName', 'empty');
		    else if(AdminTools::validateSpiderExists($this->spiderID, $this->spiderCur)) throw new UserInputException('spiderIdentifier', 'exists');
		} else if($this->action == 'board') {
		    if(empty($_POST['boardSrcID']))         throw new UserInputException('boardSrc', 'empty');
		    else if(empty($_POST['boardTgtID']))    throw new UserInputException('boardTgt', 'empty');
		    else if($_POST['boardTgtID'] == $_POST['boardSrcID'])    throw new UserInputException('boardTgt', 'equal');
		    else if(!isset($_POST['boardUser']) && !isset($_POST['boardGroups']) && !isset($_POST['boardMods']))   throw new UserInputException('boardRights', 'empty');
		} else if($this->action == 'prefix') {
		    if(empty($_POST['boardPrefSrcID']))         throw new UserInputException('boardPrefSrc', 'empty');
		    else if(empty($_POST['boardPrefTgtID']))    throw new UserInputException('boardPrefTgt', 'empty');
		    else if($_POST['boardPrefSrcID'] == $_POST['boardPrefTgtID']) throw new UserInputException('boardPrefTgt', 'equal');
		} else if($this->action == 'ugrps') {
		    if(empty($_POST['ugrpSrcID']))         throw new UserInputException('ugrpSrc', 'empty');
		    else if(empty($_POST['ugrpTgtID']))    throw new UserInputException('ugrpTgt', 'empty');
		    else if($_POST['ugrpTgtID'] == $_POST['ugrpSrcID'])    throw new UserInputException('ugrpTgt', 'equal');
		} else if($this->action == 'userOptions') {
		    if(empty($_POST['optionID']))          throw new UserInputException('optionID', 'empty');
		    else if(!empty($_POST['userOptionExclUgrps']) && !AdminTools::validateCommaSeparatedIntList($_POST['userOptionExclUgrps']))    throw new UserInputException('userOptionExclUgrps', 'commaSeparatedIntList');
		} else if($this->action == 'userDefaultOptions') {
		    if(empty($_POST['optionDefaultID']))    throw new UserInputException('optionDefaultID', 'empty');
		} else if($this->action == 'setLanguage') {
		    if(empty($_POST['languageID']))         throw new UserInputException('languageID', 'empty');
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		if($this->action == 'spider_save') {
		    AdminTools::saveSpider($this->spiderID, $this->spiderCur);
		    $this->spiderCur = AdminTools::getSpider($this->spiderID);
		    $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.saved');
		} else if($this->action == 'spider_delete') {
		    AdminTools::deleteSpider($this->spiderID);
		    $this->spiderCur = array();
		    $this->spiderID = 0;
		    $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.deleted');
		} else if($this->action == 'spider_sync') {
		    AdminTools::syncSpider(true);
		} else if($this->action == 'spider_import') {
            self::importSpider();
        } else if($this->action == 'board') {
            AdminTools::syncBoard($_POST);
            $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.saved');
    	} else if($this->action == 'prefix') {
            AdminTools::syncPrefBoard($_POST);
            $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.saved');
    	} else if($this->action == 'ugrps') {
            AdminTools::syncUgrps($_POST);
            $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.saved');
    	} else if($this->action == 'userOptions') {
            AdminTools::saveUserOptions($_POST);
            $this->userOptions = AdminTools::getUserOptions();
            $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.saved');
    	} else if($this->action == 'userDefaultOptions') {
            AdminTools::saveUserDefaultOptions($_POST);
            $this->userOptions = AdminTools::getUserOptions();
            $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.saved');
    	} else if($this->action == 'setLanguage') {
            $cnt = AdminTools::setLanguage($_POST);
            $this->languages = AdminTools::getLanguages();
            $this->setLanguageMsg = WCF::getLanguage()->get('wcf.acp.adminTools.toolBox.setLanguage.msg', array('$cnt' => $cnt));
    	}
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
	    $tmp = AdminTools::spiderCnt();
	    if(isset($tmp['cntOwn'])) {
    	    $this->spiderCntOwn = $tmp['cntOwn'];
    	    $this->spiderCntSyn = $tmp['cntSyn'];
       	    $this->spiderCntAll = $tmp['cntAll'];
        }
        if(AdminTools::wbbExists() && is_dir(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/cache')) $this->spRssExists = true;
        if(!count($this->userOptions)) $this->userOptions = AdminTools::getUserOptions();
        if(!count($this->languages)) $this->languages = AdminTools::getLanguages();
		parent::assignVariables();
		WCF::getTPL()->assign(array(
    		'wbbExists' => AdminTools::wbbExists(),
		    'cacheDel' => $this->cacheDel,
		    'cacheTpl' => $this->cacheTpl,
		    'cacheLang' => $this->cacheLang,
		    'cacheOpt' => $this->cacheOpt,
		    'cacheRSS' => $this->cacheRSS,
		    'spRssExists' => $this->spRssExists,
		    'spiders' => AdminTools::getSpiders(),
		    'spiderCur' => $this->spiderCur,
		    'spiderID' => $this->spiderID,
		    'spiderCntOwn' => $this->spiderCntOwn,
		    'spiderCntSyn' => $this->spiderCntSyn,
		    'spiderCntAll' => $this->spiderCntAll,
		    'boards' => AdminTools::getBoards(),
		    'prefBoards' => AdminTools::getPrefBoards(),
		    'ugrps' => AdminTools::getUgrps(),
		    'userOptions' => $this->userOptions,
		    'userOptionExclUgrps' => AdminTools::getSetting('userOptionExclUgrps'),
		    'languages' => $this->languages,
		    'setLanguageMsg' => $this->setLanguageMsg,
		    'errMsg' => $this->errMsg,
		    'sucMsg' => $this->sucMsg
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
	
	private function importSpider() {
        if(!empty($_FILES['importSpider']['tmp_name']) && is_uploaded_file($_FILES['importSpider']['tmp_name'])) {
        	$csv = file($_FILES['importSpider']['tmp_name']);
        	$spiders = array();
        	$i = 0;
        	if(count($csv)) {
        	    foreach($csv as $line) {
        	        $line = trim($line);
        	        if(preg_match('/^"/', $line)) {
        	            $spiderIdentifier = $spiderName = $spiderURL = '';
        	            list($spiderIdentifier, $spiderName, $spiderURL) = preg_split('/";"/', $line, 3);
        	            $spiderIdentifier = preg_replace('/^"/', '', $spiderIdentifier);
        	            if($spiderURL) $spiderURL = preg_replace('/"$/', '', $spiderURL);
        	            if(!empty($spiderIdentifier) && !empty($spiderName)) {
        	                $spiders[$i]['spiderIdentifier'] = $spiderIdentifier;
        	                $spiders[$i]['spiderName'] = $spiderName;
        	                $spiders[$i]['spiderURL'] = $spiderURL;
        	                $i++;
        	            }
        	        }
        	    }
        	}
        	if(count($spiders)) {
        	    $sql = "TRUNCATE TABLE wcf".WCF_N."_admin_tool_spider";
        	    WCF::getDB()->sendQuery($sql);
        	    foreach($spiders as $k => $v) {
        	        $sql = "INSERT INTO wcf".WCF_N."_admin_tool_spider"
        	            ."\n       (spiderIdentifier, spiderName, spiderURL)"
        	            ."\nVALUES ('".WCF::getDB()->escapeString($v['spiderIdentifier'])."', '".WCF::getDB()->escapeString($v['spiderName'])."', '".WCF::getDB()->escapeString($v['spiderURL'])."')";
        	        WCF::getDB()->sendQuery($sql);
        	    }
        	}
        	AdminTools::syncSpider(true);
        }
	}
}
?>
