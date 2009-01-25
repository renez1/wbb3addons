<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Lists menu items
 *
 * @package	net.hawkes.advancedheadermenu
 * @author	Oliver Kliebisch
 * @copyright	2008 Oliver Kliebisch
 * @license	Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 Unported <http://creativecommons.org/licenses/by-nc-nd/3.0/>
 */


class AdminToolsMenuPage extends AbstractPage {
	public $templateName = 'adminToolsAcpMenuList';
	public $acpMenu;
	public $itemStructure;
	public $deletedItemID = 0;


	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['deletedItemID'])) $this->deletedItemID = intval($_REQUEST['deletedItemID']);
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$this->renderItemStructure();
	}

	public function renderItemStructure() {
		$this->acpMenu = WCFACP::getMenu();

		$this->makeItemStructure();
	}

	public function makeItemStructure() {
		if(!count($this->acpMenu->getMenuItems())) return;

		$menuItemData = array();
		$i = 0;
		foreach($this->acpMenu->getMenuItems() as $items) {
			foreach($items as $item) {
				$menuItemName = WCF::getLanguage()->get($item['menuItem']);
				$menuItemData[$i] = array($item['parentMenuItem'], $item['menuItem'], $menuItemName, $item['menuItemLink'], $item['menuItemIcon']);
					
				$i++;
			}
		}
		$this->itemStructure = $menuItemData;
	}


	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		//var_dump($this->itemStructure[1]); die;
		WCF::getTPL()->assign(array(
			'items' => $this->itemStructure,
			'deletedItemID' => $this->deletedItemID	
		));
	}


	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.admintools.menu');

		//WCF::getUser()->checkPermission(array('admin.headermenu.canAddItem', 'admin.headermenu.canEditItem', 'admin.headermenu.canDeleteItem'));

		parent::show();
	}

}

?>