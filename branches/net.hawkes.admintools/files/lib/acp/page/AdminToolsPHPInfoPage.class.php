<?php
/**
 *   This file is part of Admin Tools 2.
 *
 *   Admin Tools 2 is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Admin Tools 2 is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Admin Tools 2.  If not, see <http://www.gnu.org/licenses/>.
 *
 * 
 */
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Displays the PHP info page
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.page
 * @category WCF 
 */
class AdminToolsPHPInfoPage extends AbstractPage {
	public $templateName = 'adminToolsPhpInfo';
	public $diskInformation = array();
	public $phpInfoOutput = '';
	
	/**
	 * @see Page::readData()	 
	 */
	public function readData() {
		parent::readData();
		
		$this->diskInformation = AdminToolsUtil::readDiskInfo();
		$this->readPHPInfo();
	}
	
	/**
	 * Reads the php info by buffering phpinfo()
	 *
	 */
	protected function readPHPInfo() {
		ob_start();
        phpinfo();
        $info = ob_get_clean();
        preg_match ("/.*<body>(.*)<\/body>/s", $info, $matches);
        if(isset($matches[1])) $this->phpInfoOutput = $matches[1];
        $this->phpInfoOutput = str_replace("width=\"600\"", "width=\"795\"", $this->phpInfoOutput);
	}
	
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(    		
			'phpInfoOutput' => $this->phpInfoOutput,
			'diskInformation' => $this->diskInformation
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {    
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.admintools.phpinfo');

		// show page
		parent::show();
	}
}
?>