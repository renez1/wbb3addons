/* $Id$ */
CREATE TABLE `wcf1_pm_bulk_mailing` (
    `pmID` int(10) unsigned NOT NULL auto_increment,
    `elapsedTime` int(10) unsigned NOT NULL default '0',
    `time` int(10) unsigned NOT NULL default '0',
    `userID` int(10) unsigned NOT NULL default '0',
    PRIMARY KEY (`pmID`),
    KEY `elapsedTime` (`elapsedTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
