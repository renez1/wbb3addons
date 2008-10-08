<?php
/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wbb.portal.box.monthlyCalendar
 */
class MonthlyCalendarBoxHelper {

	public function getCW($y, $m, $d) {
        $cw = date("W",mktime(1,1,1,$m,$d,$y));
		if($cw < 1) $cw = date("W",mktime(1,1,1,12,31,$y-1));
		return intval($cw);
	}

    public function getAppointmentList() {
        $ret = array();
        $i = 0;
        $limit = intval(WBBCore::getUser()->monthlyCalendarBox_maxAppointments);
        $showPublic = intval(WBBCore::getUser()->monthlyCalendarBox_showPublicAppointments);
        $showBirthdays = intval(WBBCore::getUser()->monthlyCalendarBox_showBirthdaysInAppointments);
        $userID = intval(WCF::getUser()->userID);
        if(!$limit > 0 || !$userID > 0) return $ret;
        $m = intval(date('n'));
        $y = intval(date('Y'));
        $d = intval(date('j'));
        $sTimestamp = mktime(0, 0, 0, $m, $d, $y);
        $eTimestamp = $sTimestamp + 86400;

        if(!empty($showBirthdays)) {
            $birthdays = self::getBirthdayList($y, $m, $d);
            $color = '';
            $isEnabled = 1;
            if(count($birthdays) && WBBCore::getUser()->getPermission('user.calendar.canUseCalendar')) {
                $sql = "SELECT IFNULL(cstu.color, cal.color) AS color"
                    ."\n  FROM wcf".WCF_N."_calendar cal"
                    ."\n  LEFT JOIN wcf".WCF_N."_calendar_settings_to_user cstu ON (cstu.userID = ".$userID." AND cstu.calendarID = cal.calendarID)"
                    ."\n WHERE cal.title = 'wcf.calendar.birthdays'";
                $result = WBBCore::getDB()->getFirstRow($sql);
                $color = $result['color'];
            }
            foreach($birthdays as $k => $v) {
                $ret[$i]['birthday'] = true;
                $ret[$i]['color'] = $color;
                $ret[$i]['userID'] = $v['userID'];
                $ret[$i]['username'] = $v['username'];
                $ret[$i]['age'] = $v['age'];
                $ret[$i]['time'] = $v['time'];
                $i++;
            }
        }

        // WoltLab Calendar...
        if(WBBCore::getUser()->getPermission('user.calendar.canUseCalendar')) {
            $sql = "SELECT cem.eventID, cem.subject AS subject, ced.startTime AS startTime, ced.endTime AS endTime, ced.isFullDay AS fullDay, IFNULL(cstu.color, cal.color) AS color, IFNULL(cstu.isEnabled,1) AS isEnabled"
                ."\n  FROM wcf".WCF_N."_calendar_event_date ced"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar cal ON (cal.calendarID = ced.calendarID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event ce ON (ce.eventID = ced.eventID AND ce.userID = ".$userID." AND ce.calendarID = cal.calendarID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ced.eventID AND cem.userID = ".$userID." AND cem.messageID = ce.messageID)"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_settings_to_user cstu ON (cstu.userID = ".$userID." AND cstu.calendarID = ced.calendarID)"
                ."\n WHERE (ced.startTime >= ".TIME_NOW
                ."\n    OR (ced.isFullDay = 1 AND ced.startTime >= ".$sTimestamp.")"
                ."\n    OR (ced.endTime > ced.startTime AND ced.endTime > ".$sTimestamp."))"
                ."\n   AND IFNULL(cstu.isEnabled,1) = 1"
                ."\n   AND cem.eventID IS NOT NULL"
                ."\n UNION"
                ."\nSELECT cem.eventID, cem.subject AS subject, ced.startTime AS startTime, ced.endTime AS endTime, ced.isFullDay AS fullDay, IFNULL(cstu.color, cal.color) AS color, IFNULL(cstu.isEnabled,1) AS isEnabled"
                ."\n  FROM wcf".WCF_N."_calendar_event_date ced"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar cal ON (cal.calendarID = ced.calendarID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event ce ON (ce.eventID = ced.eventID AND ce.calendarID = cal.calendarID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ced.eventID AND cem.messageID = ce.messageID AND cem.userID = ce.userID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event_participation cep ON (cep.eventID = ced.eventID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event_participation_to_user ceptu ON (ceptu.participationID = cep.participationID AND ceptu.userID = ".$userID.")"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_settings_to_user cstu ON (cstu.userID = ".$userID." AND cstu.calendarID = ced.calendarID)"
                ."\n WHERE (ced.startTime >= ".TIME_NOW
                ."\n    OR (ced.isFullDay = 1 AND ced.startTime >= ".$sTimestamp.")"
                ."\n    OR (ced.endTime > ced.startTime AND ced.endTime > ".$sTimestamp."))"
                ."\n   AND cem.eventID IS NOT NULL"
                ."\n   AND IFNULL(cstu.isEnabled,1) = 1";
            if(!empty($showPublic)) {
                $sql .= "\n UNION"
                       ."\nSELECT cem.eventID, cem.subject AS subject, ced.startTime AS startTime, ced.endTime AS endTime, ced.isFullDay AS fullDay, IFNULL(cstu.color, cal.color) AS color, IFNULL(cstu.isEnabled,1) AS isEnabled"
                       ."\n  FROM wcf".WCF_N."_calendar_event_date ced"
                       ."\n RIGHT JOIN wcf".WCF_N."_calendar cal ON (cal.calendarID = ced.calendarID)"
                       ."\n RIGHT JOIN wcf".WCF_N."_calendar_event ce ON (ce.eventID = ced.eventID AND ce.calendarID = cal.calendarID)"
                       ."\n RIGHT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ced.eventID AND cem.messageID = ce.messageID AND cem.userID = ce.userID)"
                       ."\n RIGHT JOIN wcf".WCF_N."_calendar_to_group ctg ON (ctg.calendarID = cal.calendarID)"
                       ."\n RIGHT JOIN wcf".WCF_N."_user_to_groups utg ON (utg.groupID = ctg.groupID AND utg.userID = ".$userID.")"
                       ."\n  LEFT JOIN wcf".WCF_N."_calendar_settings_to_user cstu ON (cstu.userID = ".$userID." AND cstu.calendarID = ced.calendarID)"
                       ."\n WHERE (ced.startTime >= ".TIME_NOW
                       ."\n    OR (ced.isFullDay = 1 AND ced.startTime >= ".$sTimestamp.")"
                       ."\n    OR (ced.endTime > ced.startTime AND ced.endTime > ".$sTimestamp."))"
                       ."\n   AND cem.eventID IS NOT NULL"
                       ."\n   AND IFNULL(cstu.isEnabled,1) = 1";
            }
            $sql .= "\n ORDER BY startTime"
                   ."\n LIMIT ".$limit;
            $result = WBBCore::getDB()->sendQuery($sql);
//            $ret['sql'] = $sql;
            while($row = WBBCore::getDB()->fetchArray($result)) {
                if(empty($row['isEnabled'])) continue;
                $ret[$i]['birthday'] = false;
                if(!empty($row['fullDay'])) {
                    $row['startTime'] = DateUtil::getUTC($row['startTime']);
                    $row['endTime'] = DateUtil::getUTC($row['endTime']);
                }
                $ret[$i]['fullDay'] = $row['fullDay'];
                $ret[$i]['eventID'] = $row['eventID'];
                $ret[$i]['subject'] = $row['subject'];
                $ret[$i]['startTime'] = $row['startTime'];
                $ret[$i]['endTime'] = $row['endTime'];
                $ret[$i]['severalDays'] = false;
                $ret[$i]['curYear'] = true;
                $ret[$i]['sameDay'] = false;
                $ret[$i]['color'] = $row['color'];
                if($row['startTime'] >= $sTimestamp && $row['endTime'] <= $eTimestamp) $ret[$i]['today'] = true;
                else {
                    $ret[$i]['today'] = false;
                    if(date('j', $row['startTime']) != date('j', $row['endTime'])) $ret[$i]['severalDays'] = true;
                    if(date('Y', $row['startTime']) != date('Y')) $ret[$i]['curYear'] = false;
                }

                if($ret[$i]['severalDays']) {
                    $ret[$i]['title'] = DateUtil::formatShortTime('%d.%m. %H:%M', $row['startTime'])
                                . ' - '.DateUtil::formatShortTime('%d.%m. %H:%M', $row['endTime']);
                } else {
                    $ret[$i]['title'] = DateUtil::formatShortTime('%H:%M', $row['startTime'])
                                  . '-'.DateUtil::formatShortTime('%H:%M', $row['endTime']);
                }
                $ret[$i]['title'] .= ': '.$row['subject'];
                $i++;
            }

        } else if(WBBCore::getUser()->getPermission('user.calendar.canEnter')) {
            $sql = "SELECT ce.eventID, cem.subject AS subject, ce.eventTime AS startTime, ce.eventEndTime AS endTime, ce.isFullDay AS fullDay"
                ."\n  FROM wcf".WCF_N."_calendar_event ce"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ce.eventID)"
                ."\n WHERE (ce.eventTime >= ".TIME_NOW
                ."\n    OR (ce.isFullDay = 1 AND ce.eventTime >= ".$sTimestamp.")"
                ."\n    OR (ce.eventEndTime > ce.eventTime AND ce.eventEndTime > ".$sTimestamp."))"
                ."\n   AND cem.isDeleted != 1";
            if(empty($showPublic)) $sql .= "\n   AND cem.userID = ".$userID;
            $sql .= "\n ORDER BY ce.eventTime"
                ."\n LIMIT ".$limit;
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                if(!empty($row['fullDay'])) {
                    $tM = intval(date('n', $row['startTime']));
                    $tY = intval(date('Y', $row['startTime']));
                    $tD = intval(date('j', $row['startTime']));
                    $row['startTime'] = mktime(0, 0, 0, $tM, $tD, $tY);
                    if(empty($row['endTime'])) {
                        $row['endTime'] = mktime(0, 0, 0, $tM, $tD, $tY);
                    } else {
                        $tM = intval(date('n', $row['endTime']));
                        $tY = intval(date('Y', $row['endTime']));
                        $tD = intval(date('j', $row['endTime']));
                        $row['endTime'] = mktime(0, 0, 0, $tM, $tD, $tY);
                    }
                } 
                if(empty($row['endTime'])) $row['endTime'] = $row['startTime'];
                $ret[$i]['fullDay'] = $row['fullDay'];
                $ret[$i]['birthday'] = false;
                $ret[$i]['color'] = '';
                $ret[$i]['eventID'] = $row['eventID'];
                $ret[$i]['subject'] = $row['subject'];
                $ret[$i]['startTime'] = $row['startTime'];
                $ret[$i]['endTime'] = $row['endTime'];
                $ret[$i]['severalDays'] = false;
                $ret[$i]['curYear'] = true;
                $ret[$i]['sameDay'] = false;
                if($row['startTime'] >= $sTimestamp && $row['endTime'] <= $eTimestamp) $ret[$i]['today'] = true;
                else {
                    $ret[$i]['today'] = false;
                    if(date('j', $row['startTime']) != date('j', $row['endTime'])) $ret[$i]['severalDays'] = true;
                    if(date('Y', $row['startTime']) != date('Y')) $ret[$i]['curYear'] = false;
                }

                if($ret[$i]['severalDays']) {
                    $ret[$i]['title'] = DateUtil::formatShortTime('%d.%m. %H:%M', $row['startTime'])
                                . ' - '.DateUtil::formatShortTime('%d.%m. %H:%M', $row['endTime']);
                } else {
                    $ret[$i]['title'] = DateUtil::formatShortTime('%H:%M', $row['startTime'])
                                  . '-'.DateUtil::formatShortTime('%H:%M', $row['endTime']);
                }
                $ret[$i]['title'] .= ': '.$row['subject'];
                $i++;
            }
        }
        return $ret;
    }

    public function getAppointments($y, $m) {
        $ret = array();
        $month = intval($m);
        $sTimestamp = gmmktime(0, 0, 0, $m, 1, $y);
        $eTimestamp = gmmktime(0, 0, 0, $m+1, 0, $y);
        $userID = intval(WCF::getUser()->userID);
        $showPublic = intval(WBBCore::getUser()->monthlyCalendarBox_showPublicAppointments);
        if(empty($userID)) return $ret;

        if(WBBCore::getUser()->getPermission('user.calendar.canUseCalendar')) {
            $sql = "SELECT cem.subject AS subject, ced.startTime AS startTime"
                ."\n  FROM wcf".WCF_N."_calendar_event_date ced"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar cal ON (cal.calendarID = ced.calendarID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event ce ON (ce.eventID = ced.eventID AND ce.userID = ".$userID." AND ce.calendarID = cal.calendarID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ced.eventID AND cem.userID = ".$userID." AND cem.messageID = ce.messageID)"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_settings_to_user cstu ON (cstu.userID = ".$userID." AND cstu.calendarID = ced.calendarID)"
                ."\n WHERE ced.startTime >= ".$sTimestamp
                ."\n   AND ced.startTime <= ".$eTimestamp
                ."\n   AND cem.eventID IS NOT NULL"
                ."\n UNION"
                ."\nSELECT cem.subject AS subject, ced.startTime AS startTime"
                ."\n  FROM wcf".WCF_N."_calendar_event_date ced"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar cal ON (cal.calendarID = ced.calendarID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event ce ON (ce.eventID = ced.eventID AND ce.calendarID = cal.calendarID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ced.eventID AND cem.messageID = ce.messageID AND cem.userID = ce.userID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event_participation cep ON (cep.eventID = ced.eventID)"
                ."\n RIGHT JOIN wcf".WCF_N."_calendar_event_participation_to_user ceptu ON (ceptu.participationID = cep.participationID AND ceptu.userID = ".$userID.")"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_settings_to_user cstu ON (cstu.userID = ".$userID." AND cstu.calendarID = ced.calendarID)"
                ."\n WHERE ced.startTime >= ".$sTimestamp
                ."\n   AND ced.startTime <= ".$eTimestamp
                ."\n   AND cem.eventID IS NOT NULL";
            if(!empty($showPublic)) {
                $sql .= "\n UNION"
                       ."\nSELECT cem.subject AS subject, ced.startTime AS startTime"
                       ."\n  FROM wcf".WCF_N."_calendar_event_date ced"
                       ."\n  RIGHT JOIN wcf".WCF_N."_calendar cal ON (cal.calendarID = ced.calendarID)"
                       ."\n  RIGHT JOIN wcf".WCF_N."_calendar_event ce ON (ce.eventID = ced.eventID AND ce.calendarID = cal.calendarID)"
                       ."\n  RIGHT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ced.eventID AND cem.messageID = ce.messageID AND cem.userID = ce.userID)"
                       ."\n  RIGHT JOIN wcf".WCF_N."_calendar_to_group ctg ON (ctg.calendarID = cal.calendarID)"
                       ."\n  RIGHT JOIN wcf".WCF_N."_user_to_groups utg ON (utg.groupID = ctg.groupID AND utg.userID = ".$userID.")"
                       ."\n  LEFT JOIN wcf".WCF_N."_calendar_settings_to_user cstu ON (cstu.userID = ".$userID." AND cstu.calendarID = ced.calendarID)"
                       ."\n WHERE ced.startTime >= ".$sTimestamp
                       ."\n   AND ced.startTime <= ".$eTimestamp
                       ."\n   AND cem.eventID IS NOT NULL";
            }
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                $dd = date('j', $row['startTime']);
                if(isset($ret[$dd])) $ret[$dd] .= ", ";
                else $ret[$dd] = '';
                $ret[$dd] .= StringUtil::encodeHTML($row['subject']);
            }
        } else if(WBBCore::getUser()->getPermission('user.calendar.canEnter')) {
            $sql = "SELECT cem.subject AS subject, ce.eventTime AS startTime"
                ."\n  FROM wcf".WCF_N."_calendar_event ce"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ce.eventID)"
                ."\n WHERE ce.eventTime >= ".$sTimestamp
                ."\n   AND ce.eventTime <= ".$eTimestamp;
            if(empty($showPublic)) $sql .= "\n   AND cem.userID = ".$userID;
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                $dd = date('j', $row['startTime']);
                if(isset($ret[$dd])) $ret[$dd] .= ", ";
                else $ret[$dd] = '';
                $ret[$dd] .= StringUtil::encodeHTML($row['subject']);
            }
        }
        return $ret;
    }

    public function getBirthdayList($y, $m, $d) {
        $month = intval($m);
        $day = intval($d);
        if($day < 10) $day = '0'.$day;
        $ret = array();
        $i = 0;
        if($month < 10) $month = '0'.$month;
        $sql = "SELECT optionID"
            ."\n  FROM wcf".WCF_N."_user_option"
            ."\n WHERE optionName = 'birthday'"
            ."\n   AND categoryName = 'profile.personal'"
            ."\n   AND optionType = 'birthday'";
        $result = WBBCore::getDB()->getFirstRow($sql);
        $optionID = $result['optionID'];
        if(!empty($optionID)) {
            $sql = "SELECT u.userID, u.username, uov.userOption".$optionID." AS BD"
                ."\n  FROM wcf".WCF_N."_user_option_value uov"
                ."\n  LEFT JOIN wcf".WCF_N."_user u ON (u.userID = uov.userID)"
                ."\n WHERE uov.userOption".$optionID." LIKE '____-".$month."-".$day."'"
                ."\n ORDER BY LOWER(u.username)";
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                list($by, $bm, $bd) = preg_split('/\-/', $row['BD'], 3);
                $by = intval($by);
                $bm = intval($bm);
                $bd = intval($bd);
                if($y >= $by) {
                    if(!$by > 0) $age = '?';
                    else $age = $y - $by;
                    $ret[$i]['username'] = StringUtil::encodeHTML($row['username']);
                    $ret[$i]['userID'] = $row['userID'];
                    $ret[$i]['age'] = $age;
                    $ret[$i]['time'] = mktime(0, 0, 0, $m, $d, $y);
                    $i++;
                }
            }
        }
        return $ret;
    }

    public function getBirthdays($y, $m, $d = 0) {
        $month = intval($m);
        $day = intval($d);
        if($day != 0 && $day < 10) $day = '0'.$day;
        $ret = array();
        if($month < 10) $month = '0'.$month;
        $sql = "SELECT optionID"
            ."\n  FROM wcf".WCF_N."_user_option"
            ."\n WHERE optionName = 'birthday'"
            ."\n   AND categoryName = 'profile.personal'"
            ."\n   AND optionType = 'birthday'";
        $result = WBBCore::getDB()->getFirstRow($sql);
        $optionID = $result['optionID'];
        if(!empty($optionID)) {
            $sql = "SELECT u.userID, u.username, uov.userOption".$optionID." AS BD"
                ."\n  FROM wcf".WCF_N."_user_option_value uov"
                ."\n  LEFT JOIN wcf".WCF_N."_user u ON (u.userID = uov.userID)"
                ."\n WHERE 1 = 1";
            if($day == 0) $sql .= "\n   AND uov.userOption".$optionID." LIKE '____-".$month."-__'";
            else $sql .= "\n   AND uov.userOption".$optionID." LIKE '____-".$month."-".$day."'";
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                list($by, $bm, $bd) = preg_split('/\-/', $row['BD'], 3);
                $by = intval($by);
                $bm = intval($bm);
                $bd = intval($bd);
                if($y >= $by) {
                    if(!$by > 0) $age = '?';
                    else $age = $y - $by;
                    if(isset($ret[$bd])) $ret[$bd] .= ", ";
                    else $ret[$bd] = '';
                    $ret[$bd] .= StringUtil::encodeHTML($row['username']).' ('.$age.')';
                }
            }
        }
        return $ret;
    }

    public function getHolidaysDE($y, $m) {
        $ret = array();
        $y = intval($y);
        $m = intval($m);
        if($m == 1) {
            $ret[1] = 'Neujahr';
            $ret[6] = 'Dreik&ouml;nigstag *BW, BY, ST';
        } else if($m == 5) {
            $ret[1] = 'Tag der Arbeit (Maifeiertag)';
        } else if($m == 8) {
            $ret[15] = 'Mari&auml; Himmelfahrt *SL, (BY)';
        } else if($m == 10) {
            $ret[3] = 'Tag der Deutschen Einheit';
            $ret[31] = 'Reformationstag *BB, MV, SN, ST, (TH)';
        } else if($m == 11) {
            $ret[1] = 'Allerheiligen *BW, BY, NW, RP, SL, (TH)';
        } else if($m == 12) {
            $ret[25] = '1. Weihnachtsfeiertag';
            $ret[26] = '2. Weihnachtsfeiertag';
        }
        $ed = self::getEasterDate($y);
        $edY = date('Y', $ed);
        $edM = date('n', $ed);
        $edD = intval(date('d', $ed));
        if($edM == $m) $ret[$edD] = 'Ostersonntag';
        $tmp = $ed - (86400 * 2);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Karfreitag';
        $tmp = $ed + (86400 * 1);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Ostermontag';
        $tmp = $ed + (86400 * 39);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Christi Himmelfahrt';
        $tmp = $ed + (86400 * 49);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Pfingstsonntag';
        $tmp = $ed + (86400 * 50);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Pfingstmontag';
        $tmp = $ed + (86400 * 60);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Fronleichnam *BW, BY, HE, NW, RP, SL, (SN, TH)';

        if($m >= 11) {
            $a4 = 25;
            $bb = 1;
            while(strftime('%w', mktime(0,0,0,12,$a4,$y)) != 0) {
                $a4--;
                $bb = intval(strftime('%j', mktime(0,0,0,12,$a4,$y)) - (5*7) + 3);
            }
            $tmp = mktime(0,0,0,1,$bb,$y);
            if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Bu&szlig;- und Bettag *SN';
        }
        return $ret;
    }

    public function getEasterDate($y) {
        if(function_exists('easter_date')) $ret = easter_date($y);
        else {
            // modifizierte Gauss-Fassung nach Lichtenberg...
            // http://de.wikipedia.org/wiki/Gau%C3%9Fsche_Osterformel
            // 1. die Saekularzahl:
            $K = (int) ($y / 100);
            // 2. die saekulare Mondschaltung:
            $M = 15 + (int) ((3 * $K + 3) / 4) - (int) ((8 * $K + 13) / 25);
            // 3. die saekulare Sonnenschaltung:
            $S = 2 - (int) ((3 * $K + 3) / 4);
            // 4. den Mondparameter:
            $A = $y % 19;
            // 5. den Keim für den ersten Vollmond im Fruehling:
            $D = (19 * $A + $M) % 30;
            // 6. die kalendarische Korrekturgroesse:
            $R = (int) ($D / 29) + ((int) ($D / 28) - (int) ($D / 29)) * (int) ($A / 11);
            // 7. die Ostergrenze:
            $OG = 21 + $D - $R;
            // 8. den ersten Sonntag im Maerz:
            $SZ = 7 - (int) ($y + ($y / 4) + $S) % 7;
            // 9. die Entfernung in Tagen, die der Ostersonntag von der Ostergrenze hat (Osterentfernung):
            $OE = 7 - ($OG - $SZ) % 7;
            // 10. das Datum des Ostersonntags als Maerzdatum (32. Maerz = 1. April etc.):
            $OS = $OG + $OE;
            // nachfolgende Variable entspricht 100%ig der PHP-Funktion easter_date() ;)
            $ret = mktime(0,0,0,3,$OS,$y,-1);
        }
        return $ret;
    }
        
}
?>
