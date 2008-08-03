<?php
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/acp/adminTools/AdminTools.class.php');

/**
 * AdminTools ToolBox Class
 *
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
            }
        } else if(!empty($_POST['boardAction'])) {
            $this->action = 'board';
        } else if(!empty($_POST['prefixAction'])) {
            $this->action = 'prefix';
        } else if(!empty($_POST['ugrpAction'])) {
            $this->action = 'ugrps';
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
		} else if($this->action == 'board') {
            AdminTools::syncBoard($_POST);
            $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.saved');
    	} else if($this->action == 'prefix') {
            AdminTools::syncPrefBoard($_POST);
            $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.saved');
    	} else if($this->action == 'ugrps') {
            AdminTools::syncUgrps($_POST);
            $this->sucMsg = WCF::getLanguage()->get('wcf.acp.adminTools.success.saved');
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
        if(is_dir(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/cache')) $this->spRssExists = true;
		parent::assignVariables();
		WCF::getTPL()->assign(array(
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
}
?>
