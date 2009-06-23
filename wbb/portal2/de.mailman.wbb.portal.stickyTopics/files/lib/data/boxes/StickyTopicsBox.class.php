<?php
// wbb imports
require_once(WBB_DIR.'lib/data/boxes/PortalBox.class.php');
require_once(WBB_DIR.'lib/data/boxes/StandardPortalBox.class.php');
require_once(WBB_DIR.'lib/data/board/Board.class.php');

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
        $this->data['topics'] = array();
        $boardIDs = Board::getAccessibleBoards();

        if(!empty($boardIDs)) {
            $boardIDs = explode(',', $boardIDs);
            foreach($this->cacheData as $key => $item) {
                if(isset($item['boardID']) && in_array($item['boardID'], $boardIDs)) $this->data['topics'][] = $item;
            }
        }

        // save memory
        $this->cacheData = array();
        unset($boardIDs);

        if(!count($this->data['topics'])) $this->empty = true;
    }

    /**
     * @see StandardPortalBox::getTemplateName()
     */
    public function getTemplateName() {
        return 'stickyTopicsBox';
    }
}
?>
