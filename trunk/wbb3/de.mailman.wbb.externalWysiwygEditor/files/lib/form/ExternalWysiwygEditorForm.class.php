<?php
require_once(WCF_DIR.'lib/form/MessageForm.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

/**
 * $Id$
 * @package de.mailman.wbb.externalWysiwygEditor
 * @author  MailMan (http://wbb3addons.ump2002.net)
 * example: {@RELATIVE_WBB_DIR}index.php?form=ExternalWysiwygEditor&amp;permissionType=message{@SID_ARG_2ND}
 */

class ExternalWysiwygEditorForm extends MessageForm {
	public $templateName = 'externalWysiwygEditor';

    public $userID;
	public $upload = false;
    public $permissionType = 'message';
	public $showSignatureSetting = false;
	public $showPoll = false;
    public $maxLength = null;
	public $text = '';
	public $preview = '';
	public $subject = '*';

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		// Permissions
		if (!WCF::getUser()->userID) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		} else {
		    $this->userID = intval(WCF::getUser()->userID);
        }
        if(isset($_REQUEST['permissionType'])) $this->permissionType = $_REQUEST['permissionType'];
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
	}

	/**
	 * @see Form::submit()
	 */
	public function submit() {
        EventHandler::fireAction($this, 'submit');
		$this->readFormParameters();
        try {
            $this->preview = MessageParser::getInstance()->parse($this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes, false);
            $this->validate();
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
		parent::validate();
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
        return;
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		if(!count($_POST)) {
    		// default settings
            // wysiwyg
            $this->wysiwygEditorMode = WCF::getUser()->wysiwygEditorMode;
            $this->wysiwygEditorHeight = WCF::getUser()->wysiwygEditorHeight;
            // options
            $this->parseURL = WCF::getUser()->{$this->permissionType.'ParseURL'};
            $this->enableSmilies = WCF::getUser()->{$this->permissionType.'EnableSmilies'};
            $this->enableHtml = WCF::getUser()->{$this->permissionType.'EnableHtml'};
            $this->enableBBCodes = WCF::getUser()->{$this->permissionType.'EnableBBCodes'};
        }
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array(
			'showSignature' => false,
			'showSmilies' => true,
			'showSettings' => true,
			'showAttachments' => false,
			'showPoll' => false,
			'showSignatureSetting' => false,
		    'enableSmilies' => $this->enableSmilies,
		    'enableHtml' => $this->enableHtml,
		    'enableBBCodes' => $this->enableBBCodes,
		    'maxUploadFields' => 0,
		    'text' => $this->text,
		    'preview' => $this->preview,
		    'permissionType' => $this->permissionType
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// show form
		parent::show();
	}
}
?>
