<?php
// wbb imports
require_once(WBB_DIR.'lib/data/board/Board.class.php');
require_once(WBB_DIR.'lib/data/boxes/PortalBox.class.php');
require_once(WBB_DIR.'lib/data/boxes/StandardPortalBox.class.php');

/**
 * This box shows all defined boards in a box
 *
 * @package     de.mailman.wbb.portal.listboards
 * @author      MailMan
 * @copyright   2009 MailMan
 * @license     GPL
 * @subpackage  data.boxes
 * @category    Portal
 */

class ListBoardsBox extends PortalBox implements StandardPortalBox {
    public $BoardlistData = array();

    /**
     * Fetch all threads with new posts within
     *
     * @access  public
     * @param   array   $data       Ignored
     * @param   string  $boxname    Boxname
     * @return  void
     */
    public function readData() {
        // DEFAULTS
        $lbLength           = 24;
        $lbLevelCut         = 3;
        $lbMaxHeight        = 0;
        $lbSBColor          = 2;
        $lbFontsize         = '1.2em';
        $lbSpacer           = 5;
        $lbIndent           = '&nbsp;&raquo;&nbsp;';
        $lbIndentNewPosts   = '<span style="font-weight:bold; color:Red;">&nbsp;&raquo;&nbsp;</span>';
        $lbShowNewPosts     = true;

        // ACP Konstanten...
        if(!defined('LISTBOARDS_LENGTH'))               define('LISTBOARDS_LENGTH',             $lbLength);
        if(!defined('LISTBOARDS_LEVELCUT'))             define('LISTBOARDS_LEVELCUT',           $lbLevelCut);
        if(!defined('LISTBOARDS_MAXHEIGHT'))            define('LISTBOARDS_MAXHEIGHT',          $lbMaxHeight);
        if(!defined('LISTBOARDSBOX_SBCOLOR'))           define('LISTBOARDSBOX_SBCOLOR',         $lbSBColor);
        if(!defined('LISTBOARDS_MAINBOARD_FONTSIZE'))   define('LISTBOARDS_MAINBOARD_FONTSIZE', $lbFontsize);
        if(!defined('LISTBOARDS_MAINBOARD_SPACER'))     define('LISTBOARDS_MAINBOARD_SPACER',   $lbSpacer);
        if(!defined('LISTBOARDS_SUBBOARD_INDENT'))      define('LISTBOARDS_SUBBOARD_INDENT',    $lbIndent);
        if(!defined('LISTBOARDS_NEWPOST_INDENT'))       define('LISTBOARDS_NEWPOST_INDENT',     $lbIndentNewPosts);
        if(!defined('LISTBOARDS_SHOW_NEWPOSTS'))        define('LISTBOARDS_SHOW_NEWPOSTS',      $lbShowNewPosts);

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
        $boardList->renderBoards();
    }

    /**
     * @see StandardPortalBox::getTemplateName()
     */
    public function getTemplateName() {
        return 'listboards';
    }
}

?>
