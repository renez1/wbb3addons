/* $Id$ */
CREATE TABLE `wcf1_user_wanted_poster` (
  `userID` int(10) unsigned NOT NULL,
  `insertDate` int(10) unsigned NOT NULL default '0',
  `updateDate` int(10) unsigned default NULL,
  `text` text,
  `views` int(10) NOT NULL default '0',
  `enableSmilies` tinyint(1) NOT NULL default '1',
  `enableHtml` tinyint(1) NOT NULL default '0',
  `enableBBCodes` tinyint(1) NOT NULL default '1',
  `locked` tinyint(1) NOT NULL default '0',
  `lockDate` int(10) unsigned NOT NULL default '0',
  `lockUser` varchar(255) default NULL,
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `wcf1_user_wanted_poster_template` (
  `templateID` int(10) unsigned NOT NULL auto_increment,
  `insertDate` int(10) unsigned NOT NULL default '0',
  `insertUserID` int(10) unsigned NOT NULL,
  `updateDate` int(10) unsigned default NULL,
  `updateUserID` int(10) unsigned default NULL,
  `templateName` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `enableSmilies` tinyint(1) NOT NULL default '1',
  `enableHtml` tinyint(1) NOT NULL default '0',
  `enableBBCodes` tinyint(1) NOT NULL default '1',
  `enabled` tinyint(1) NOT NULL default '1',
  PRIMARY KEY (`templateID`),
  UNIQUE KEY `templateName` (`templateName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `wcf1_user` 
ADD (`enableWantedPosterSmilies` TINYINT(1) NOT NULL DEFAULT 1
    ,`enableWantedPosterHtml` TINYINT(1) NOT NULL DEFAULT 0
    ,`enableWantedPosterBBCodes` TINYINT(1) NOT NULL DEFAULT 1);
