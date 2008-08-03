CREATE TABLE `wcf1_admin_tool_setting` (
  `atse_name` varchar(64) NOT NULL,
  `atse_value` varchar(2000) default NULL,
  PRIMARY KEY  (`atse_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronLastRun', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelLogDays', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelMovedThreadDays', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelPmDays', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelPmDaysExclUgrps', '');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelPmDaysExclUser', '');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelPmDaysShowInfo', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelPmDaysShowExclInfo', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronLogEnabled', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronStatEnabled', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDbAnalyze', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDbOptimize', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDbBackup', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronLogUseAdminEmail', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelInactiveUserDays', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelInactiveUserExclUgrps', '3,4');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelInactiveUserExcl', '1');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveDays', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveSrc', '');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveTgt', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclPolls', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclAnnouncement', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclSticky', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclClosed', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclDeleted', '0');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.width', '1024px;');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.height', '768px;');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.borderWidth', '1px;');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.borderColor', '#808080;');
INSERT INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.borderStyle', 'solid;');

CREATE TABLE `wcf1_admin_tool_spider` (
  `spiderID` int(10) unsigned NOT NULL auto_increment,
  `spiderIdentifier` varchar(255) NOT NULL,
  `spiderName` varchar(255) NOT NULL,
  `spiderUrl` varchar(255) default '',
  PRIMARY KEY  (`spiderID`),
  UNIQUE KEY `spiderIdentifier` (`spiderIdentifier`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `wcf1_admin_tool_moved_threads` (
  `threadID` int(10) unsigned NOT NULL,
  `movedThreadID` int(10) unsigned NOT NULL,
  `checkedTime` int(10) unsigned default 0,
  PRIMARY KEY  (`threadID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
