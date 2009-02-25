<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/acp/adminTools/AdminTools.class.php');

/**
 * $Id$
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
        if(isset($_GET['url'])) {
            $urlFound = false;
            if(preg_match('/^(ht|f)tp:/', $_GET['url']) && !empty($_SERVER['HTTP_HOST']) && !preg_match('/'.$_SERVER['HTTP_HOST'].'/', $_GET['url'])) {
                $ext = true;
            } else {
                $ext = false;
            }
            foreach($_GET as $k => $v) {
                if($k == 'url' || $urlFound) {
                    if(!$urlFound) {
                        $urlFound = true;
                        $this->url = $v;
                    } else if(!empty($v)) {
                        if($ext && preg_match('/^(packageid|s)$/i', $k)) continue;
                        if(!preg_match('/\?/', $this->url)) $p = '?';
                        else $p = '&';
                        $this->url .= $p.$k.'='.$v;
                    }
                }
            }
        }
        if(isset($_GET['target']))   $this->target = $_GET['target'];

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
            'wbbExists' => AdminTools::wbbExists(),
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
