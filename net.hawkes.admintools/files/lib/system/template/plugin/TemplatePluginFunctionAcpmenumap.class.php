<?php
require_once(WCF_DIR.'lib/system/exception/SystemException.class.php');
require_once(WCF_DIR.'lib/system/template/TemplatePluginFunction.class.php');
require_once(WCF_DIR.'lib/system/template/Template.class.php');

/**
 * Builds the javascript menu items
 *
 * @package	net.hawkes.advancedheadermenu
 * @author	Oliver Kliebisch
 * @copyright	2008 Oliver Kliebisch
 * @license	Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 Unported <http://creativecommons.org/licenses/by-nc-nd/3.0/>
 */

class TemplatePluginFunctionAcpmenumap implements TemplatePluginFunction {
	protected $output = "";	
	protected $menuItems = array();
	
	/**
	 * @see TemplatePluginFunction::execute()
	 */
	public function execute($tagArgs, Template $tplObj) {
		if(!class_exists('WCFACP')) return;		
		$menuItemData = $tagArgs['menuItemData'];
		
		for ($i = 0; $i < count($menuItemData); $i++) {
			if (!isset($this->menuItems[$menuItemData[$i][0]])) {
				$this->menuItems[$menuItemData[$i][0]] = array();
			}
			
			$menuObject = array();
			$menuObject['menuItem'] = $menuItemData[$i][1];
			$menuObject['menuItemName'] = $menuItemData[$i][2];
			$menuObject['menuItemLink'] = $menuItemData[$i][3];
			$menuObject['menuItemIcon'] = $menuItemData[$i][4];
			
			$this->menuItems[$menuItemData[$i][0]][count($this->menuItems[$menuItemData[$i][0]])] = $menuObject;
		}
		$this->makeSiteMap();
		return $this->output;
	}

	protected function makeSiteMap($parentItem = '', $depth = 0) {
		if (!isset($this->menuItems[$parentItem])) return;
		
		$this->output .= "<ul";
		
		if ($depth == 2) {
			$this->output .= " class=\"sitemapDepth-".$depth." container-1\">\n";
		}
		else {
			$this->output .= " class=\"sitemapDepth-".$depth."\">\n";
		}
		
		for ($i=0; $i < count($this->menuItems[$parentItem]); $i++) {
			if ($depth == 1 && $i > 0 && $i % 3 == 0) {
				$this->output .= "<div class=\"clear\" />\n";
			}			
			$this->makeSiteMapItem($this->menuItems[$parentItem][$i], $depth);			
		}
		
		$this->output .= "</ul>\n";	
	}
	
	protected function makeSiteMapItem($item, $depth = 0) {
		if (!empty($item['menuItemLink']) || $depth < 2) {
			$itemTitle = $item['menuItemName'];
			$this->output .= "<li";
			$headline = "<h".($depth +2).">\n";
			
			if (!empty($item['menuItemIcon'])) {
				$headline .= "<img src=\"".$item['menuItemIcon']."\" alt=\"\"/>\n";
			}
			
			//if (!empty($item['menuItemLink'])) {
				$headline .= "<a href=\"index.php?form=AdminToolsMenuEdit&amp;menuItem=".rawurlencode($item['menuItem'])."&amp;packageID=".PACKAGE_ID.SID_ARG_2ND."\">".$itemTitle."</a>\n";
			//}
			//else {
			//	$headline .= "<span>".$itemTitle."</span>\n";
			//}
			$headline .= "</h".($depth+2).">\n";
			
			if ($depth == 1) {
				$this->output .= " class=\"border\">\n";
				$this->output .= "<div class=\"containerHead\">\n".$headline."</div>\n";
			}
			else {
				$this->output .= ">\n".$headline;
			}
			
			//$this->output .= "</li>\n";			
		}
		
		$this->makeSiteMap($item['menuItem'], $depth + 1);
		$this->output .= "</li>\n";
	}
}
?>