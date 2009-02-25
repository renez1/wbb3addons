<?php
/* $Id$ */
require_once(WCF_DIR.'lib/data/message/bbcode/BBCodeParser.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/BBCode.class.php');

/**
 * Parses the [threadmoved] bbcode Tag.
 *
 * @author	Nendilo	
 * @package	BBCodeThreadMoved
 */
class threadmovedBBCode implements BBCode {
	/**
	 * @see BBCode::getParsedTag()
	 */
	public function getParsedTag($openingTag, $content, $closingTag, BBCodeParser $parser) {
        if (WCF::getUser()->getPermission('user.message.canUseBBCodeThreadMoved')){
		    if ($parser->getOutputType() == 'text/html') {
			    // show template
			    WCF::getTPL()->assign(array(
				    'content' => $content,
			    ));
			    return WCF::getTPL()->fetch('threadmovedBBCodeTag');
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
