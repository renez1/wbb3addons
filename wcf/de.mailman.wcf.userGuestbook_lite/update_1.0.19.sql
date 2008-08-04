ALTER TABLE `wcf1_user_guestbook_header` ADD `locked` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wcf1_user_guestbook_header` ADD `lockTime` INT(10) UNSIGNED DEFAULT NULL;
ALTER TABLE `wcf1_user_guestbook_header` ADD `lockUserID` INT(10) UNSIGNED DEFAULT NULL;
