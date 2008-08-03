INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronDelMovedThreadDays', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveDays', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveSrc', '');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveTgt', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclPolls', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclAnnouncement', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclSticky', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclClosed', '0');
INSERT IGNORE INTO `wcf1_admin_tool_setting` (`atse_name`, `atse_value`) VALUES ('cronThreadArchiveExclDeleted', '0');

ANALYZE TABLE `wcf1_admin_tool_setting`;
OPTIMIZE TABLE `wcf1_admin_tool_setting`;

CREATE TABLE `wcf1_admin_tool_moved_threads` (
  `threadID` int(10) unsigned NOT NULL,
  `movedThreadID` int(10) unsigned NOT NULL,
  `checkedTime` int(10) unsigned default 0,
  PRIMARY KEY  (`threadID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
