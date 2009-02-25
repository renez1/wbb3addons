<?php
/* $Id$ */
require_once(WCF_DIR.'lib/data/message/bbcode/BBCodeParser.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/BBCode.class.php');

/**
 * Parses the [threadclosed] bbcode Tag.
 *
 * @author	Nendilo	
 * @package	BBCodeThreadClosed
 */
class threadclosedBBCode implements BBCode {
	/**
	 * @see BBCode::getParsedTag()
	 */
	public function getParsedTag($openingTag, $content, $closingTag, BBCodeParser $parser) {
        if (WCF::getUser()->getPermission('user.message.canUseBBCodeThreadClosed')){
		    if ($parser->getOutputType() == 'text/html') {
			    // show template
			    WCF::getTPL()->assign(array(
				    'content' => $content,
			    ));
			    return WCF::getTPL()->fetch('threadclosedBBCodeTag');
		    }
		    else if ($parser->getOutputType() == 'text/plain') {
			    return $content;
		    }
	    }
	    else {
	        return false;
	    }
	}
}
?>
