<?php
// wbb imports
require_once(WBB_DIR.'lib/data/board/Board.class.php');
require_once(WBB_DIR.'lib/data/board/PortalBoardList.class.php');
require_once(WBB_DIR.'lib/data/boxes/PortalBox.class.php');
require_once(WBB_DIR.'lib/data/boxes/StandardPortalBox.class.php');

/**
 * Shows all boards in a box
 * $Id$
 * @package     de.mailman.wbb.portal.listboards
 * @author      MailMan (http://wbb3addons.ump2002.net), optimized by Oliver Kliebisch
 * @copyright   2009 MailMan
 * @license     GPL
 * @subpackage  data.boxes
 * @category    Portal
 */
class ListBoardsBox extends PortalBox implements StandardPortalBox {
    public $maxHeight = 0;
    /**
     * FIXME: Style related settings should not be treated with the options PiP     
     */
    public $fontSize = '1.2em';
    public $data = array();    
	
    /**     
     * @see StandardPortalBox::readData()
     */
    public function readData() {    	
        // Get user defined box height
        if(WCF::getUser()->userID) {
            if(WCF::getUser()->listboards_maxheight >= 100) $this->maxHeight = WCF::getUser()->listboards_maxheight;
            else if(WCF::getUser()->listboards_maxheight == 0 && LISTBOARDS_MAXHEIGHT >= 100) $this->maxHeight = LISTBOARDS_MAXHEIGHT;
        }
		
        // validate font size
        $this->fontSize = LISTBOARDS_MAINBOARD_FONTSIZE ? '1.2em' : LISTBOARDS_MAINBOARD_FONTSIZE;       

        // Get board list        
        $boardList = new PortalBoardList();
        $boardList->maxDepth = BOARD_LIST_DEPTH;
        $boardData = $boardList->renderBoards();        
                
        if (!count($boardData)) $this->empty = true;
        $this->data = array_merge($this->data, $boardData);
    }
	
    /**     
     * @see StandardPortalBox::getTemplateName()
     */
    public function getTemplateName() {
        return 'listboards';
    }
}
?>