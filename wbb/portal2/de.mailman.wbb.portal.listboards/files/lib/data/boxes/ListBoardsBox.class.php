<?php
// wbb imports
require_once(WBB_DIR.'lib/data/board/Board.class.php');
require_once(WBB_DIR.'lib/data/boxes/PortalBox.class.php');
require_once(WBB_DIR.'lib/data/boxes/StandardPortalBox.class.php');

/**
 * Shows all boards in a box
 * $Id$
 * @package     de.mailman.wbb.portal.listboards
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @copyright   2009 MailMan
 * @license     GPL
 * @subpackage  data.boxes
 * @category    Portal
 */

class ListBoardsBox extends PortalBox implements StandardPortalBox {
    public $BoardlistData = array();

    public function readData() {
        // Boxen Hoehe
        if(WCF::getUser()->userID) {
            if(WCF::getUser()->listboards_maxheight >= 100) $lbMaxHeight = intval(WCF::getUser()->listboards_maxheight);
            else if(WCF::getUser()->listboards_maxheight == 0 && LISTBOARDS_MAXHEIGHT >= 100) $lbMaxHeight = LISTBOARDS_MAXHEIGHT;
        }

        // Template Variablen zuordnen...
        WCF::getTPL()->assign(array(
            'lbFontsize'        => (LISTBOARDS_MAINBOARD_FONTSIZE == '' ? $lbFontsize : LISTBOARDS_MAINBOARD_FONTSIZE),
            'lbSpacer'          => intval(LISTBOARDS_MAINBOARD_SPACER),
            'lbIndent'          => LISTBOARDS_SUBBOARD_INDENT,
            'lbIndentNewPosts'  => LISTBOARDS_NEWPOST_INDENT,
            'lbSBColor'         => intval(LISTBOARDSBOX_SBCOLOR),
            'lbLength'          => intval(LISTBOARDS_LENGTH),
            'lbLevelCut'        => intval(LISTBOARDS_LEVELCUT),
            'lbShowNewPosts'    => LISTBOARDS_SHOW_NEWPOSTS,
            'lbMaxHeight'       => $lbMaxHeight,
        ));

        // Forenliste
        require_once(WBB_DIR.'lib/data/board/BoardList.class.php');
        $boardList = new BoardList();
        $boardList->maxDepth = BOARD_LIST_DEPTH;
        $boardList->renderBoards();
    }

    public function getTemplateName() {
        return 'listboards';
    }
}

?>
