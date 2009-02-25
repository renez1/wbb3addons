<?php
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/acp/adminTools/AdminTools.class.php');

/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.adminTools
 */

class AdminToolsLinkSettingsForm extends ACPForm {
	public $templateName = 'adminToolsLinkSettings';
	public $menuItemID = 0;
	public $links = array();
	public $linkCur = array();
	public $iFrame = array();
	public $action = '';

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
        // permission
        WCF::getUser()->checkPermission('admin.system.adminTools.canView');

		if(!empty($_REQUEST['menuItemID'])) $this->menuItemID = $_REQUEST['menuItemID'];
        $this->linkCur['menuItem']          = (empty($_POST['menuItem']) ? '' : $_POST['menuItem']);
        $this->linkCur['menuItemLink']      = $this->linkCur['url'] = (empty($_POST['menuItemLink']) ? '' : $_POST['menuItemLink']);
        $this->linkCur['showOrder']         = (!isset($_POST['showOrder']) ? 0 : $_POST['showOrder']);
        $this->linkCur['linkTarget']        = $this->linkCur['target'] = (empty($_POST['linkTarget']) ? '' : $_POST['linkTarget']);
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if(!empty($_POST['linkAction'])) $this->action = $_POST['linkAction'];
        if($this->action == 'select' && !empty($this->menuItemID)) $this->linkCur = AdminTools::getLink($this->menuItemID);
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
        if($this->action == 'modify') {
            if(empty($_POST['menuItem']))           throw new UserInputException('menuItem', 'empty');
            else if(empty($_POST['menuItemLink']))  throw new UserInputException('menuItemLink', 'empty');
            else if(!empty($_POST['showOrder']) && !preg_match('/^\d+$/', $_POST['showOrder']))  throw new UserInputException('showOrder', 'empty');
            else if(!AdminTools::validateLinkExists($this->menuItemID, $this->linkCur['menuItem'])) throw new UserInputException('menuItem', 'exists');
        }
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
        if($this->action == 'modify') {
            $tmp = AdminTools::saveLink($this->menuItemID, $_POST);
            if(!empty($tmp) && !headers_sent()) {
                header('Location: index.php?form=AdminToolsLinkSettings'.SID_ARG_2ND_NOT_ENCODED);
                exit;
            }
        } else if($this->action == 'delete' && !empty($this->menuItemID)) {
            AdminTools::deleteLink($this->menuItemID);
            if(!headers_sent()) {
                header('Location: index.php?form=AdminToolsLinkSettings'.SID_ARG_2ND_NOT_ENCODED);
                exit;
            }
        } else if($this->action == 'iFrame') {
            AdminTools::saveIframeSettings($_POST);
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
		parent::assignVariables();
        $this->links = AdminTools::getLinks();
        $this->iFrame = AdminTools::getIframeSettings();
//        if(!empty($this->menuItemID)) $this->linkCur = AdminTools::getLink($this->menuItemID);
		WCF::getTPL()->assign(array(
    		'wbbExists' => AdminTools::wbbExists(),
		    'menuItemID' => $this->menuItemID,
		    'linkCur' => $this->linkCur,
		    'links' => $this->links,
		    'iFrame' => $this->iFrame
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
