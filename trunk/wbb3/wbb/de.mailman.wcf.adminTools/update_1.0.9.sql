INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronLastRun', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelMovedThreadDays', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveDays', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveSrc', '');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveTgt', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclPolls', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclAnnouncement', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclSticky', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclClosed', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclDeleted', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDbBackup', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelInactiveUserDays', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelInactiveUserExclUgrps', '3,4');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelInactiveUserExcl', '1');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.width', '1024px;');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.height', '768px;');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.borderWidth', '1px;');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.borderColor', '#808080;');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('linkSettings.iframe.borderStyle', 'solid;');

ANALYZE TABLE `wcf1_admin_tool_setting`;
OPTIMIZE TABLE `wcf1_admin_tool_setting`;

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
