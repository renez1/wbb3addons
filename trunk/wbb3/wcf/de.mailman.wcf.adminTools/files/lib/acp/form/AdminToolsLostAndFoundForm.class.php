<?php
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/acp/adminTools/AdminTools.class.php');

/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.adminTools
 */

class AdminToolsLostAndFoundForm extends ACPForm {
	public $templateName = 'adminToolsLostAndFound';
    public $show = 'lostAndFoundWbbD';
    public $fDo = '';

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
        if(!empty($_REQUEST['show'])) $this->show = $_REQUEST['show'];
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if(!empty($_POST['fDo'])) $this->fDo = $_POST['fDo'];
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
        // delete
        if($this->show && $this->fDo == 'delete' && isset($_POST['lostAndFoundDel']) && is_array($_POST['lostAndFoundDel']) && count($_POST['lostAndFoundDel']) > 0) {
            AdminTools::getLostAndFoundDelete($this->show, $_POST['lostAndFoundDel']);
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

		WCF::getTPL()->assign(array(
    		'wbbExists' => AdminTools::wbbExists(),
            'show' => $this->show,
            'lostAndFoundDel' => AdminTools::getLostAndFound($this->show)
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