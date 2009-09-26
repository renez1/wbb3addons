CREATE TABLE `wcf1_ics_holiday_exporter_log` (
  `ihelID` int(10) unsigned NOT NULL auto_increment,
  `ihelTime` int(10) unsigned NOT NULL,
  `ihelCtryCode` varchar(4) NOT NULL,
  `ihelFromYear` int(4) NOT NULL,
  `ihelToYear` int(4) NOT NULL,
  `ihelUsername` varchar(255),
  PRIMARY KEY  (`ihelID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
