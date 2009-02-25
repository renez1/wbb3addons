ALTER TABLE `wcf1_group` 
ADD (`teamOnlineMarking` VARCHAR(255) NOT NULL DEFAULT '%s'
    ,`showOnTeamOnlineBox` TINYINT(1) NOT NULL DEFAULT 0);
