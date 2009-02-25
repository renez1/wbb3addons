<?php
/* $Id$ */
class StickyTopicsBox {
	protected $StickyTopicsData = array();

	public function __construct($data, $boxname = "") {
		$this->StickyTopicsData['templatename'] = "stickyTopicsBox";
		$this->getBoxStatus($data);
		$this->StickyTopicsData['boxID'] = $data['boxID'];
		$this->StickyTopicsData['topics'] = array();
        if(STICKYTOPICSBOX_BOXOPENED == true) $this->StickyTopicsData['Status'] = 1;

        if(WBBCore::getUser()->getPermission('user.board.canViewStickyTopicsBox')) {
    		$this->StickyTopicsData['data'] = WBBCore::getCache()->get('box-'.$data['boxID']);
    		foreach($this->StickyTopicsData['data'] as $k => $v) {
    		    $this->StickyTopicsData['topics'][] = $v;
    		}
        }
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->StickyTopicsData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->StickyTopicsData['Status'] = intval(WBBCore::getUser()->stickyTopicsBox);
		}
		else {
			if (WBBCore::getSession()->getVar('stickyTopicsBox') != false) {
				$this->StickyTopicsData['Status'] = WBBCore::getSession()->getVar('stickyTopicsBox');
			}
		}
	}

	public function getData() {
		return $this->StickyTopicsData;
	}
}

?>
