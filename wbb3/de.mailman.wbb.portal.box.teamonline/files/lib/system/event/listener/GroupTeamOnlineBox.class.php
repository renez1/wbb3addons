<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/GroupEditForm.class.php');

class GroupTeamOnlineBox implements EventListener 
{
	protected $showOnTeamOnlineBox = 0;
	protected $teamOnlineMarking = '%s';
	
	/**
	 * @see EventListener::execute()
	 */

	public function execute($eventObj, $className, $eventName) 
	{
		if ($eventName == 'readFormParameters')
		{
			if (isset ($_POST['showOnTeamOnlineBox'])) $this->showOnTeamOnlineBox = intval($_POST['showOnTeamOnlineBox']);
			if (isset ($_POST['teamOnlineMarking'])) $this->teamOnlineMarking = $_POST['teamOnlineMarking'];
		}
		else if ($eventName == 'save')
		{
  			$eventObj->additionalFields['showOnTeamOnlineBox'] = $this->showOnTeamOnlineBox;
    		$eventObj->additionalFields['teamOnlineMarking'] = $this->teamOnlineMarking;
			if (!($eventObj instanceof GroupEditForm)) {
    			$this->showOnTeamOnlineBox = 0;
	    		$this->teamOnlineMarking = '%s';
            }
		}
		else if ($eventName == 'assignVariables')
		{
			if (!count($_POST) && $eventObj instanceof GroupEditForm) {
    			$this->showOnTeamOnlineBox = $eventObj->group->showOnTeamOnlineBox;
	    		$this->teamOnlineMarking = $eventObj->group->teamOnlineMarking;
            }
			WCF::getTPL()->assign(array(
				'showOnTeamOnlineBox' => $this->showOnTeamOnlineBox,
				'teamOnlineMarking' => $this->teamOnlineMarking
			));
			WCF::getTPL()->append('additionalFields', WCF::getTPL()->fetch('groupTeamOnlineBox'));
		} 
	}
}
?>
