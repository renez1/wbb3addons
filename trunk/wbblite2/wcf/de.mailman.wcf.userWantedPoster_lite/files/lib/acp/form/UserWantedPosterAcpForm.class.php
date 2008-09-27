<?php
require_once(WCF_DIR.'lib/acp/form/WysiwygCacheloaderForm.class.php');
require_once(WCF_DIR.'lib/data/user/UserWantedPosterData.class.php');

/**
 * $Id$
 * @package de.mailman.wcf.userWantedPoster
 * @author  MailMan (http://wbb3addons.ump2002.net)
 */

class UserWantedPosterAcpForm extends WysiwygCacheloaderForm {
	public $templateName = 'userWantedPosterAcp';
	public $thisPage = 'index.php?form=UserWantedPosterAcp';
    public $userID = 0;
	public $uwp;
	public $tplID;
	public $subject;
	public $text;
    public $enableSmilies;
    public $enableHtml;
    public $enableBBCodes;
    public $enabled;
    public $preview, $send;

    protected function setDefaults() {
        $this->tplID = 0;
        $this->subject = '';
        $this->text = '';
        $this->enableSmilies = 0;
        $this->enableHtml = 0;
        $this->enableBBCodes = 0;
        $this->enabled = 0;
    }

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
        $this->setDefaults();
		parent::readParameters();
        if (!WCF::getUser()->userID || !WCF::getUser()->getPermission('admin.general.wantedPoster.canEdit')) {
        	require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
        	throw new PermissionDeniedException();
        }
        $this->userID = WCF::getUser()->userID;
        $uwp = new UserWantedPosterData($this->userID);
    }

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if(isset($_POST['tplID'])) $this->tplID = $_POST['tplID'];
		if(isset($_POST['subject'])) $this->subject = $_POST['subject'];
		if(isset($_POST['text'])) $this->text = $_POST['text'];
		if(isset($_POST['enableSmilies'])) $this->enableSmilies = $_POST['enableSmilies'];
		if(isset($_POST['enableHtml'])) $this->enableHtml = $_POST['enableHtml'];
		if(isset($_POST['enableBBCodes'])) $this->enableBBCodes = $_POST['enableBBCodes'];
		if(isset($_POST['enabled'])) $this->enabled = $_POST['enabled'];
        if(isset($_POST['preview'])) $this->preview = (boolean) $_POST['preview'];
        if(isset($_POST['send'])) $this->send = (boolean) $_POST['send'];
	}

	/**
	 * @see Form::submit()
  
	public function submit() {
        EventHandler::fireAction($this, 'submit');
		$this->readFormParameters();
        try {
            // preview
            if ($this->preview) {
                require_once(WCF_DIR.'lib/data/message/pm/PMEditor.class.php');
                WCF::getTPL()->assign('preview', PMEditor::createPreview($this->subject, $this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes));
                $this->validate();
            }
            // send message
            if ($this->send) {
                $this->validate();
                $this->save();
            }
        }
        catch (UserInputException $e) {
        	$this->errorField = $e->getField();
        	$this->errorType = $e->getType();
        }
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
	    if(empty($_POST['fDo']) || $_POST['fDo'] != 'mod' || !empty($_POST['deleteTemplate'])) return;
        if(empty($this->subject)) throw new UserInputException('subject', 'empty');
        if(empty($this->text)) throw new UserInputException('text', 'empty');
        if(UserWantedPosterData::templateExists($this->tplID, $this->subject)) throw new UserInputException('subject', 'exists');
		parent::validate();
	}


	/**
	 * @see Form::save()
	 */
	public function save() {
	    if(empty($_POST['fDo']) || $_POST['fDo'] != 'mod') return;
		parent::save();
		if(!empty($this->tplID) && !empty($_POST['deleteTemplate'])) {
		    $ret = UserWantedPosterData::deleteTemplate($this->tplID);
            if(!empty($ret)) WCF::getTPL()->assign('success', WCF::getLanguage()->get('wcf.acp.wantedPoster.msgTplDeleted'));
            else WCF::getTPL()->assign('error', WCF::getLanguage()->get('wcf.acp.wantedPoster.errTplDeleted'));
            $this->setDefaults();
		} else {
		    $ret = UserWantedPosterData::saveTemplate($this->tplID, $this->subject, $this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes, $this->enabled);
		    if(!empty($ret)) {
    		    if(empty($this->tplID)) $this->tplID = $ret;
    		    WCF::getTPL()->assign('success', WCF::getLanguage()->get('wcf.acp.wantedPoster.msgTplSaved'));
    		} else {
    		    WCF::getTPL()->assign('error', WCF::getLanguage()->get('wcf.acp.wantedPoster.errTplSaved'));
    		}
		}
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
    }

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
        $tplData = UserWantedPosterData::getTemplate($this->tplID);
		if(!empty($tplData['templateID']) && empty($_POST['fDo'])) {
            $this->subject = $tplData['templateName'];
            $this->text = $tplData['text'];
            $this->enableSmilies = $tplData['enableSmilies'];
            $this->enableHtml = $tplData['enableHtml'];
            $this->enableBBCodes = $tplData['enableBBCodes'];
            $this->enabled = $tplData['enabled'];
        }

		WCF::getTPL()->assign(array(
		    'thisPage' => $this->thisPage,
		    'tplID' => $this->tplID,
		    'subject' => $this->subject,
		    'text' => $this->text,
		    'enableSmilies' => $this->enableSmilies,
		    'enableHtml' => $this->enableHtml,
		    'enableBBCodes' => $this->enableBBCodes,
		    'enabled' => $this->enabled,
		    'tplCount' => UserWantedPosterData::countTemplates(),
		    'tplList' => UserWantedPosterData::getTemplateList(),
		    'tplData' => $tplData
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.wantedPoster');

		// show form
		parent::show();
	}
}
?>
