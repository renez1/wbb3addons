<?php
require_once(WCF_DIR.'lib/acp/form/WysiwygCacheloaderForm.class.php');
require_once(WCF_DIR.'lib/data/user/group/Group.class.php');

/**
 * $Id$
 * @package de.mailman.wcf.pmToUgrps
 * @author  MailMan (http://wbb3addons.ump2002.net)
 */
class PMToUserGroupsForm extends WysiwygCacheloaderForm {
    public $templateName = 'pmToUserGroups';
    public $groups = array();
    public $groupIDs = array();
    public $subject = '';
    public $text = '';
    public $enableSmilies = false;
    public $enableHtml = false;
    public $enableBBCodes = false;
    public $showSignature = false;
    public $canUseSmilies = false;
    public $canUseHtml = false;
    public $canUseBBCodes = false;
    public $maxTextLength = 10000;
    public $preview, $send, $user;
    /**
     * @see Page::readParameters()
     */
    public function readParameters() {
        parent::readParameters();
        $this->canUseSmilies = WCF::getUser()->getPermission('user.message.canUseSmilies');
        $this->canUseHtml = WCF::getUser()->getPermission('user.message.canUseHtml');
        $this->canUseBBCodes = WCF::getUser()->getPermission('user.message.canUseBBCodes');
        if(empty($_POST)) {
            if($this->canUseSmilies)    $this->enableSmilies = true;
            if($this->canUseBBCodes)    $this->enableBBCodes = true;
            $this->showSignature = true;
        }
        $this->maxTextLength = WCF::getUser()->getPermission('user.pm.maxLength');
    }

    /**
     * @see Form::readFormParameters()
     */
    public function readFormParameters() {
        parent::readFormParameters();
        if(isset($_POST['subject'])) $this->subject = $_POST['subject'];
        if(isset($_POST['text'])) $this->text = $_POST['text'];
        if(isset($_POST['enableSmilies'])) $this->enableSmilies = $_POST['enableSmilies'];
        if(isset($_POST['enableHtml'])) $this->enableHtml = $_POST['enableHtml'];
        if(isset($_POST['enableBBCodes'])) $this->enableBBCodes = $_POST['enableBBCodes'];
        if(isset($_POST['showSignature'])) $this->showSignature = $_POST['showSignature'];
        if(isset($_POST['groupIDs']) && is_array($_POST['groupIDs'])) $this->groupIDs = ArrayUtil::toIntegerArray($_POST['groupIDs']);
        if(isset($_POST['preview'])) $this->preview = (boolean) $_POST['preview'];
        if(isset($_POST['send'])) $this->send = (boolean) $_POST['send'];
    }

    /**
     * @see Form::submit()
     */
    public function submit() {
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
                // no errors
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
        if(!count($this->groupIDs)) throw new UserInputException('groupIDs', 'empty');
        if(empty($this->subject)) throw new UserInputException('subject', 'empty');
        if(empty($this->text)) {
            throw new UserInputException('text', 'empty');
        } else if(StringUtil::length($this->text) > $this->maxTextLength) {
            throw new UserInputException('text', 'tooLong');
        } else if(defined('ENABLE_CENSORSHIP') && ENABLE_CENSORSHIP) {
            require_once(WCF_DIR.'lib/data/message/censorship/Censorship.class.php');
            $result = Censorship::test($this->text);
            if($result) {
                WCF::getTPL()->assign('censoredWords', $result);
                throw new UserInputException('text', 'censoredWordsFound');
            }
        }

        parent::validate();
    }

    /**
     * @see Form::save()
     */
    public function save() {
        parent::save();
        // save config in session
        $pmData = WCF::getSession()->getVar('pmData');
        if($pmData === null) $pmData = array();
        $pmSessionID = count($pmData);
        $pmData[$pmSessionID] = array(
            'groupIDs'  => implode(',', $this->groupIDs),
            'subject'   => $this->subject,
            'text'      => $this->text,
            'enableSmilies' => $this->enableSmilies,
            'enableHtml'    => $this->enableHtml,
            'enableBBCodes' => $this->enableBBCodes,
            'showSignature' => $this->showSignature,
            'startTime'     => TIME_NOW
        );
        WCF::getSession()->register('pmData', $pmData);
        $this->saved();

        // show worker template
        WCF::getTPL()->assign(array(
            'pageTitle' => WCF::getLanguage()->get('wcf.pmToUgrps.pageTitle'),
            'url' => 'index.php?action=PMToUserGroups&pmSessionID='.$pmSessionID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED
        ));
        WCF::getTPL()->display('worker');
        exit;
    }

    /**
     * @see Page::readData()
     */
    public function readData() {
        parent::readData();
        $this->groups = Group::getAccessibleGroups(array(), array(Group::GUESTS, Group::EVERYONE));
    }

    /**
     * @see Page::assignVariables()
     */
    public function assignVariables() {
        parent::assignVariables();

        WCF::getTPL()->assign(array(
            'maxTextLength' => $this->maxTextLength,
            'groupIDs'      => $this->groupIDs,
            'groups'        => $this->groups,
            'subject'       => $this->subject,
            'text'          => $this->text,
            'enableSmilies' => $this->enableSmilies,
            'enableHtml'    => $this->enableHtml,
            'enableBBCodes' => $this->enableBBCodes,
            'canUseSmilies' => $this->canUseSmilies,
            'canUseHtml'    => $this->canUseHtml,
            'canUseBBCodes' => $this->canUseBBCodes,
            'showSignature' => $this->showSignature
        ));
    }

    /**
     * @see Page::show()
     */
    public function show() {
        // enable menu item
        WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.group');
        Session::resetSessions();

        WCF::getUser()->checkPermission('admin.user.canPMToUserGroups');

        // show form
        parent::show();
    }

}
?>
