<?php
// wbb imports
require_once(WBB_DIR.'lib/data/boxes/PortalBox.class.php');
require_once(WBB_DIR.'lib/data/boxes/StandardPortalBox.class.php');

/**
 * Shows sticky topics in a box
 * $Id$
 * @package     de.mailman.wbb.portal.stickyTopics
 * @author      MailMan (http://www.wbb3addons.de)
 * @copyright   2009 MailMan
 * @license     GPL
 * @subpackage  data.boxes
 * @category    Portal
 */
class StickyTopicsBox extends PortalBox implements StandardPortalBox {
	public $data = array();

    /**
     * @see StandardPortalBox::readData()
     */
    public function readData() {
        if(WCF::getUser()->getPermission('user.board.canViewStickyTopicsBox')) {
    		foreach($this->cacheData as $key => $item) {
    		    $this->data['topics'][] = $item;
    		}
        }
	}

    /**
     * @see StandardPortalBox::getTemplateName()
     */
    public function getTemplateName() {
        return 'stickyTopicsBox';
    }
}
?>
