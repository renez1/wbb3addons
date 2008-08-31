<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/acp/adminTools/AdminTools.class.php');

/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wcf.adminTools
 */
class AdminToolsPhpInfoPage extends AbstractPage {
	public $templateName = 'adminToolsPhpInfo';
	public $diskInfo = '';

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

        // disk space in GB
        $di = AdminTools::getDiskInfo();
        if(is_array($di) && count($di)) {
            $totalSpace = StringUtil::formatNumeric($di['TOTAL_SPACE']).' GB';
            $freeSpace = StringUtil::formatNumeric($di['FREE_SPACE']).' GB ('.StringUtil::formatNumeric($di['FREE_QUOTA']).'%)';
            $usedSpace = StringUtil::formatNumeric($di['USED_SPACE']).' GB ('.StringUtil::formatNumeric($di['USED_QUOTA']).'%)';
            $this->diskInfo = WCF::getLanguage()->get('wcf.acp.adminTools.diskInfo', array('$totalSpace' => $totalSpace, '$freeSpace' => $freeSpace, '$usedSpace' => $usedSpace));
        }

		WCF::getTPL()->assign(array(
    		'wbbExists' => AdminTools::wbbExists(),
			'atPHP' => AdminTools::parsePHPConfig(),
			'diskInfo' => $this->diskInfo
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
