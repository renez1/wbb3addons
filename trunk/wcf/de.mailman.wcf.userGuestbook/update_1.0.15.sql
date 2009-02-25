ALTER TABLE `wcf1_user_guestbook` ADD `fromUsername` VARCHAR(255) DEFAULT NULL AFTER `fromUserID`;
ALTER TABLE `wcf1_user_guestbook_header` ADD `lastEntryUsername` VARCHAR(255) DEFAULT NULL AFTER `lastEntryUserID`;
ALTER TABLE `wcf1_user_guestbook_header` ADD `locked` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wcf1_user_guestbook_header` ADD `lockTime` INT(10) UNSIGNED DEFAULT NULL;
ALTER TABLE `wcf1_user_guestbook_header` ADD `lockUserID` INT(10) UNSIGNED DEFAULT NULL;
UPDATE `wcf1_user_guestbook`
   SET `fromUsername` = IFNULL((
        SELECT s.username
          FROM wcf1_user s
         WHERE s.userID = fromUserID), '?');
UPDATE `wcf1_user_guestbook_header`
   SET `lastEntryUsername` = IFNULL((
        SELECT s.username
          FROM wcf1_user s
         WHERE s.userID = lastEntryUserID), '?')
 WHERE lastEntryUserID > 0;
