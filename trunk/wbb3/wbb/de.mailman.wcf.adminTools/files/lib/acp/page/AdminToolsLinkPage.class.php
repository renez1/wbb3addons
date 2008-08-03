<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/acp/adminTools/AdminTools.class.php');

/**
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.adminTools
 */

class AdminToolsLinkPage extends AbstractPage {
	public $templateName = 'adminToolsLink';
	public $url = '';
	public $target = '';
	public $iFrame = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
        if(isset($_REQUEST['url']))      $this->url = $_REQUEST['url'];
        if(isset($_REQUEST['target']))   $this->target = $_REQUEST['target'];

		if($this->url && !headers_sent() && $this->target == '_self') {
            header('Location: '.$this->url);
            exit;
        }
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

        if($this->target == '_iframe') $this->iFrame = AdminTools::getIframeSettings();
		WCF::getTPL()->assign(array(
		    'url' => $this->url,
		    'target' => $this->target,
		    'iFrame' => $this->iFrame
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
        // permission
        WCF::getUser()->checkPermission('admin.system.adminTools.canView');

		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.adminTools');

		// show page
		parent::show();
	}
}
?>
