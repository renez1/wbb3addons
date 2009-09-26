<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * $Id$
 * @author      MailMan (http://www.wbb3addons.de)
 * @package     de.mailman.wbb.versionChecker
 */

class VersionCheckerPage extends AbstractPage {
	public $templateName = 'versionChecker';
	public $logFile = '';
	public $logMaxEntries = 100;
	public $verFirst = '1.0.0';
	public $verSecond = '1.0.0 PL1';
	public $verResult = -2;

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if(!empty($_POST['action']) && $_POST['action'] == 'compare') {
		    if(!empty($_POST['version1']) && !empty($_POST['version2'])) {
                require_once(WCF_DIR.'lib/acp/package/Package.class.php');
		        $this->verResult = Package::compareVersion($_POST['version1'], $_POST['version2']);
//		        $this->verResult = version_compare($_POST['version1'], $_POST['version2']);
		        $this->verFirst = $_POST['version1'];
		        $this->verSecond = $_POST['version2'];
		        if($this->logFile) {
		            $entries = array();
                    $u = (WCF::getUser()->username ? WCF::getUser()->username : 'Guest');
                    $t = TIME_NOW;
		            if(is_file(WCF_DIR.'/'.$this->logFile)) $entries = file(WCF_DIR.'/'.$this->logFile);
		            array_push($entries, $t.'||'.date('d.m.Y H:i:s',$t).'||'.$u.'||'.$this->verFirst.'||'.$this->verSecond);
		            rsort($entries);
		            if(!empty($this->logMaxEntries) && $this->logMaxEntries > 0) $output = array_slice($entries, 0, $this->logMaxEntries);
		            else $output = $entries;
		            if(count($output) && $fh = @fopen(WCF_DIR.'/'.$this->logFile, 'w')) {
		                foreach($output as $k => $line) fwrite($fh, trim($line)."\n");
		                fclose($fh);
		            }
		        }
		    }
		}
	}

	/**
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array(
			'verResult' => $this->verResult,
			'version1' => $this->verFirst,
			'version2' => $this->verSecond
		));
	}

    /**
     * @see Page::show()
     */
    public function show() {
		// set active menu item
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.versionChecker');
		
		// check permission
		WCF::getUser()->checkPermission('user.managepages.canViewversionChecker');
		
        // show form
        parent::show();
    }
}
?>
