<?php

class ListBoardsBox {
	protected $BoardlistData = array();

	public function __construct($data, $boxname = "") {
		$this->BoardlistData['templatename'] = "listboards";
		$this->getBoxStatus($data);
		$this->BoardlistData['boxID'] = $data['boxID'];

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
        if(!defined('LISTBOARDS_LENGTH_ACP'))               define('LISTBOARDS_LENGTH_ACP',             $lbLength);
        if(!defined('LISTBOARDS_LEVELCUT_ACP'))             define('LISTBOARDS_LEVELCUT_ACP',           $lbLevelCut);
        if(!defined('LISTBOARDS_MAXHEIGHT_ACP'))            define('LISTBOARDS_MAXHEIGHT_ACP',          $lbMaxHeight);
        if(!defined('LISTBOARDSBOX_SBCOLOR_ACP'))           define('LISTBOARDSBOX_SBCOLOR_ACP',         $lbSBColor);
        if(!defined('LISTBOARDS_MAINBOARD_FONTSIZE_ACP'))   define('LISTBOARDS_MAINBOARD_FONTSIZE_ACP', $lbFontsize);
        if(!defined('LISTBOARDS_MAINBOARD_SPACER_ACP'))     define('LISTBOARDS_MAINBOARD_SPACER_ACP',   $lbSpacer);
        if(!defined('LISTBOARDS_SUBBOARD_INDENT_ACP'))      define('LISTBOARDS_SUBBOARD_INDENT_ACP',    $lbIndent);
        if(!defined('LISTBOARDS_NEWPOST_INDENT_ACP'))       define('LISTBOARDS_NEWPOST_INDENT_ACP',     $lbIndentNewPosts);
        if(!defined('LISTBOARDS_SHOW_NEWPOSTS_ACP'))        define('LISTBOARDS_SHOW_NEWPOSTS_ACP',      $lbShowNewPosts);

        // Boxen Hoehe
        if(WCF::getUser()->userID) {
            if(WCF::getUser()->listboards_maxheight >= 100) $lbMaxHeight = intval(WCF::getUser()->listboards_maxheight);
            else if(WCF::getUser()->listboards_maxheight == 0 && LISTBOARDS_MAXHEIGHT_ACP >= 100) $lbMaxHeight = LISTBOARDS_MAXHEIGHT_ACP;
        }

        // Template Variablen zuordnen...
   		WCF::getTPL()->assign(array(
   		    'lbFontsize'        => (LISTBOARDS_MAINBOARD_FONTSIZE_ACP == '' ? $lbFontsize : LISTBOARDS_MAINBOARD_FONTSIZE_ACP),
   		    'lbSpacer'          => intval(LISTBOARDS_MAINBOARD_SPACER_ACP),
   		    'lbIndent'          => LISTBOARDS_SUBBOARD_INDENT_ACP,
   		    'lbIndentNewPosts'  => LISTBOARDS_NEWPOST_INDENT_ACP,
   		    'lbSBColor'         => intval(LISTBOARDSBOX_SBCOLOR_ACP),
   		    'lbLength'          => intval(LISTBOARDS_LENGTH_ACP),
   		    'lbLevelCut'        => intval(LISTBOARDS_LEVELCUT_ACP),
   		    'lbShowNewPosts'    => LISTBOARDS_SHOW_NEWPOSTS_ACP,
   		    'lbMaxHeight'       => $lbMaxHeight,
		));

        // Forenliste
        require_once(WBB_DIR.'lib/data/board/BoardList.class.php');
        $boardList = new BoardList();
        $boardList->renderBoards();
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoardlistData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoardlistData['Status'] = intval(WBBCore::getUser()->listboards);
		}
		else {
			if (WBBCore::getSession()->getVar('listboards') != false) {
				$this->BoardlistData['Status'] = WBBCore::getSession()->getVar('listboards');
			}
		}
	}

	public function getData() {
		return $this->BoardlistData;
	}
}

?>
