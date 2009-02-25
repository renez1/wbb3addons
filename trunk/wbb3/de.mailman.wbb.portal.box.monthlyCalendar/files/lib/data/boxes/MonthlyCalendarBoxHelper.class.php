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
        $maxDays = intval(WBBCore::getUser()->monthlyCalendarBox_maxAppointmentDays);
        $userID = intval(WCF::getUser()->userID);
        if(!$limit > 0) $limit = 10;
        if(empty($maxDays)) $maxDays = 30;
        if(empty($userID)) {
            $showPublic = 1;
            $showBirthdays = 1;
        }
        $m = intval(date('n'));
        $y = intval(date('Y'));
        $d = intval(date('j'));
        $sTimestamp = mktime(0, 0, 0, $m, $d, $y);
        $eTimestamp = $sTimestamp + 86400;

        // WoltLab Calendar...
        if(WBBCore::getUser()->getPermission('user.calendar.canUseCalendar')) {
            require_once (WCF_DIR . 'lib/util/CalendarUtil.class.php');
            require_once (WCF_DIR . 'lib/data/calendar/event/EventList.class.php');
            $cals = Calendar::getEnabledCalendars();
            if(empty($showBirthdays)) {
                foreach($cals as $k => $v) {
                    if($v->className == 'BirthdayEvent') {
                        unset($cals[$k]);
                        break;
                    }
                }
            }
            if(!empty($userID) && empty($showPublic)) {
                foreach($cals as $k => $v) {
                    if($v->ownerID != $userID && $v->className != 'BirthdayEvent') {
                        unset($cals[$k]);
                    }
                }
            }
            $events = new EventList($sTimestamp, $sTimestamp + 86400 * $maxDays, $cals);
            $events->readEvents();
            $myEvents = $events->getEvents($limit);
//            file_put_contents('/tmp/debug.txt', print_r($myEvents, true));
            foreach($myEvents as $event) {
                if($showBirthdays && $event->calendar->className == 'BirthdayEvent' && $event->user && !$event->eventID) {
                    $ret[$i]['birthday'] = true;
                    $ret[$i]['userID'] = $event->userID;
                    $ret[$i]['username'] = $event->user->username;
                    $ret[$i]['age'] = $event->user->age;
                    $ret[$i]['time'] = $event->startTime;
                    $ret[$i]['eventID'] = null;
                } else {
                    $ret[$i]['birthday'] = false;
                    $ret[$i]['eventID'] = $event->eventID;
                }
                if($event->isFullDay) {
                    $ret[$i]['startTime'] = DateUtil::getUTC($event->startTime);
                    $ret[$i]['endTime'] = DateUtil::getUTC($event->endTime);
                } else {
                    $ret[$i]['startTime'] = $event->startTime;
                    $ret[$i]['endTime'] = $event->endTime;
                }
                $ret[$i]['fullDay'] = $event->isFullDay;
                $ret[$i]['subject'] = $event->subject;
                $ret[$i]['severalDays'] = false;
                $ret[$i]['curYear'] = true;
                $ret[$i]['sameDay'] = false;
                $ret[$i]['color'] = $event->color;
                $ret[$i]['today'] = false;
                $ret[$i]['title'] = '';

                if($ret[$i]['startTime'] >= $sTimestamp && $ret[$i]['endTime'] < $eTimestamp) $ret[$i]['today'] = true;
                else {
                    $ret[$i]['today'] = false;
                    if(date('j', $ret[$i]['startTime']) != date('j', $ret[$i]['endTime'])) $ret[$i]['severalDays'] = true;
                    if(date('Y', $ret[$i]['startTime']) != date('Y')) $ret[$i]['curYear'] = false;
                }
                if($ret[$i]['severalDays']) {
                    $ret[$i]['title'] = DateUtil::formatDate('%d.%m. %H:%M', $ret[$i]['startTime'])
                                . ' - '.DateUtil::formatDate('%d.%m. %H:%M', $ret[$i]['endTime']);
                } else {
                    $ret[$i]['title'] = DateUtil::formatDate('%H:%M', $ret[$i]['startTime'])
                                  . '-'.DateUtil::formatDate('%H:%M', $ret[$i]['endTime']);
                }
                $ret[$i]['title'] .= ': '.$ret[$i]['subject'];
                $i++;
            }
        } else if(WBBCore::getUser()->getPermission('user.calendar.canEnter')) {
            if(!empty($showBirthdays)) {
                $birthdays = self::getBirthdayList($y, $m, $d);
                $color = '';
                $isEnabled = 1;
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

            $sql = "SELECT ce.eventID, cem.subject AS subject, ce.eventTime AS startTime, ce.eventEndTime AS endTime, ce.isFullDay AS fullDay"
                ."\n  FROM wcf".WCF_N."_calendar_event ce"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ce.eventID)"
                ."\n WHERE (ce.eventTime >= ".TIME_NOW
                ."\n    OR (ce.isFullDay = 1 AND ce.eventTime >= ".$sTimestamp.")"
                ."\n    OR (ce.eventEndTime > ce.eventTime AND ce.eventEndTime > ".$sTimestamp."))"
                ."\n   AND cem.isDeleted != 1";
            if(!empty($userID) && empty($showPublic)) $sql .= "\n   AND cem.userID = ".$userID;
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
        $i = 0;
        $month = intval($m);
        $sTimestamp = gmmktime(0, 0, 0, $m, 1, $y);
        $eTimestamp = gmmktime(0, 0, 0, $m+1, 0, $y);
        $userID = intval(WCF::getUser()->userID);
        $showPublic = intval(WBBCore::getUser()->monthlyCalendarBox_showPublicAppointments);
        if(empty($userID)) {
            $showPublic = 1;
        }
        if(WBBCore::getUser()->getPermission('user.calendar.canUseCalendar')) {
            require_once (WCF_DIR . 'lib/util/CalendarUtil.class.php');
            require_once (WCF_DIR . 'lib/data/calendar/event/EventList.class.php');
            $cals = Calendar::getEnabledCalendars();
            $events = new EventList($sTimestamp, $eTimestamp, $cals);
            $events->readEvents();
            $myEvents = $events->getEvents(1000);
            foreach($myEvents as $event) {
                if(!$event->eventID) continue;
                $dd = date('j', $event->startTime);
                if(isset($ret[$dd])) $ret[$dd] .= ", ";
                else $ret[$dd] = '';
                $ret[$dd] .= StringUtil::encodeHTML($event->subject);
                $i++;
            }
        } else if(WBBCore::getUser()->getPermission('user.calendar.canEnter')) {
            $sql = "SELECT cem.subject AS subject, ce.eventTime AS startTime"
                ."\n  FROM wcf".WCF_N."_calendar_event ce"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ce.eventID)"
                ."\n WHERE ce.eventTime >= ".$sTimestamp
                ."\n   AND ce.eventTime <= ".$eTimestamp;
            if(!empty($userID) && empty($showPublic)) $sql .= "\n   AND cem.userID = ".$userID;
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
        $optionID = intval(User::getUserOptionID('birthday'));
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
                    if(!$by > 0) $age = null;
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
        $optionID = intval(User::getUserOptionID('birthday'));
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
                    if(!$by > 0) $age = null;
                    else $age = $y - $by;
                    if(isset($ret[$bd])) $ret[$bd] .= ", ";
                    else $ret[$bd] = '';
                    $ret[$bd] .= StringUtil::encodeHTML($row['username']).($age ? ' ('.$age.')' : '');
                }
            }
        }
        return $ret;
    }

    public function getHolidays($ctryCode, $y, $m) {
        switch($ctryCode) {
        case 'AT':
            return self::getHolidaysAT($y, $m);
        case 'CH':
            return self::getHolidaysCH($y, $m);
        case 'FR':
            return self::getHolidaysFR($y, $m);
        case 'IT':
            return self::getHolidaysIT($y, $m);
        case 'NL':
            return self::getHolidaysNL($y, $m);
        default:
            return self::getHolidaysDE($y, $m);
        }
    }

    public function getHolidaysDE($y, $m) {
        $ret = array();
        $y = intval($y);
        $m = intval($m);
        switch($m) {
        case 1:
            $ret[1] = 'Neujahr';
            $ret[6] = 'Dreik&ouml;nigstag *BW, BY, ST';
            break;
        case 5:
            $ret[1] = 'Tag der Arbeit (Maifeiertag)';
            break;
        case 8:
            $ret[15] = 'Mari&auml; Himmelfahrt *SL, (BY)';
            break;
        case 10:
            $ret[3] = 'Tag der Deutschen Einheit';
            $ret[31] = 'Reformationstag *BB, MV, SN, ST, (TH)';
            break;
        case 11:
            $ret[1] = 'Allerheiligen *BW, BY, NW, RP, SL, (TH)';
            break;
        case 12:
            $ret[25] = '1. Weihnachtsfeiertag';
            $ret[26] = '2. Weihnachtsfeiertag';
            break;
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

    public function getHolidaysAT($y, $m) {
        $ret = array();
        $y = intval($y);
        $m = intval($m);
        switch($m) {
        case 1:
            $ret[1] = 'Neujahr';
            $ret[6] = 'Heilige Drei K&ouml;nige';
            break;
        case 3:
            $ret[19] = 'Josef *K&auml;rnten, Steiermark, Tirol, Vorarlberg';
            break;
        case 5:
            $ret[1] = 'Staatsfeiertag (Tag der Arbeit)';
            $ret[4] = 'Florian *Ober&ouml;sterreich';
            break;
        case 8:
            $ret[15] = 'Mari&auml; Himmelfahrt';
            break;
        case 9:
            $ret[24] = 'Rupert *Salzburg';
            break;
        case 10:
            $ret[10] = 'Tag der Volksabstimmung *K&auml;rnten';
            $ret[26] = 'Nationalfeiertag';
            break;
        case 11:
            $ret[1] = 'Allerheiligen';
            $ret[11] = 'Martin *Burgenland';
            $ret[15] = 'Leopold *Nieder&ouml;sterreich, Wien';
            break;
        case 12:
            $ret[8] = 'Mari&auml; Empf&auml;ngnis';
            $ret[24] = 'Heiliger Abend';
            $ret[25] = 'Christtag';
            $ret[26] = 'Stefanitag';
            $ret[31] = 'Silvester';
            break;
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
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Fronleichnam';
        return $ret;
    }

    public function getHolidaysCH($y, $m) {
        $ret = array();
        $y = intval($y);
        $m = intval($m);
        switch($m) {
        case 1:
            $ret[1] = 'Neujahrstag';
            $ret[2] = 'Berchtoldstag';
            $ret[6] = 'Heilige Drei K&ouml;nige';
            break;
        case 3:
            $ret[19] = 'Josefstag';
            break;
        case 5:
            $ret[1] = 'Tag der Arbeit';
            break;
        case 8:
            $ret[1] = 'Bundesfeier';
            $ret[15] = 'Mari&auml; Himmelfahrt';
            break;
        case 11:
            $ret[1] = 'Allerheiligen';
            break;
        case 12:
            $ret[8] = 'Mari&auml; Empf&auml;ngnis';
            $ret[25] = 'Weihnachtstag';
            $ret[26] = 'Stephanstag';
            break;
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
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Auffahrt';
        $tmp = $ed + (86400 * 49);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Pfingstsonntag';
        $tmp = $ed + (86400 * 50);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Pfingstmontag';
        $tmp = $ed + (86400 * 60);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Fronleichnam';
        return $ret;
    }

    public function getHolidaysFR($y, $m) {
        $ret = array();
        $y = intval($y);
        $m = intval($m);
        switch($m) {
        case 1:
            $ret[1] = '	Jour de l\'An';
            break;
        case 5:
            $ret[1] = 'F&ecirc;te du travail';
            $ret[8] = 'F&ecirc;te de la Victoire';
            $ret[22] = 'Abolition de l&acute;esclavage *Martinique';
            $ret[27] = 'Abolition de l&acute;esclavage *Guadeloupe';
            break;
        case 6:
            $ret[10] = 'Abolition de l&acute;esclavage *Guyane';
            break;
        case 7:
            $ret[14] = 'F&ecirc;te Nationale de la France';
            break;
        case 8:
            $ret[15] = 'Assomption';
            break;
        case 11:
            $ret[1] = 'Toussaint';
            $ret[11] = 'Armistice 1918';
            break;
        case 12:
            $ret[20] = 'Abolition de l&acute;esclavage *La R&eacute;union';
            $ret[25] = 'No&euml;l';
            $ret[26] = 'Lendemain de No&euml;l';
            break;
        }
        $ed = self::getEasterDate($y);
        $edY = date('Y', $ed);
        $edM = date('n', $ed);
        $edD = intval(date('d', $ed));
        if($edM == $m) $ret[$edD] = 'Dimanche de P&acirc;ques';
        $tmp = $ed - (86400 * 2);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Vendredi Saint';
        $tmp = $ed + (86400 * 1);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Lundi de P&acirc;ques';
        $tmp = $ed + (86400 * 39);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Ascension';
        $tmp = $ed + (86400 * 49);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Pentec&acirc;te';
        $tmp = $ed + (86400 * 50);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Lundi de Pentec&acirc;te';
        return $ret;
    }

    public function getHolidaysIT($y, $m) {
        $ret = array();
        $y = intval($y);
        $m = intval($m);
        switch($m) {
        case 1:
            $ret[1] = 'Capodanno';
            $ret[6] = 'Epifania';
            break;
        case 4:
            $ret[25] = 'Liberazione Italia';
            break;
        case 5:
            $ret[1] = 'Festa del lavoro';
            break;
        case 6:
            $ret[2] = 'Festa della Repubblica Italia';
            break;
        case 8:
            $ret[15] = 'Ferragosto';
            break;
        case 11:
            $ret[1] = 'Ognissanti';
            break;
        case 12:
            $ret[8] = 'Immacolata Concezione';
            $ret[25] = 'Natale';
            $ret[26] = 'Santo Stefano';
            $ret[31] = 'San Silvestro';
            break;
        }
        $ed = self::getEasterDate($y);
        $edY = date('Y', $ed);
        $edM = date('n', $ed);
        $edD = intval(date('d', $ed));
        if($edM == $m) $ret[$edD] = 'Domenica di Pasqua';
        $tmp = $ed + (86400 * 1);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Luned&igrave; di Pasqua';
        $tmp = $ed + (86400 * 49);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Domenica di Pentecoste';
        $tmp = $ed + (86400 * 50);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Luned&igrave; di Pentecoste';
        return $ret;
    }

    public function getHolidaysNL($y, $m) {
        $ret = array();
        $y = intval($y);
        $m = intval($m);
        switch($m) {
        case 1:
            $ret[1] = 'Nieuwjaar';
            break;
        case 4:
            $ret[30] = 'Koninginnedag';
            break;
        case 5:
            $ret[4] = 'Dodenherdenking';
            $ret[5] = 'Bevrijdingsdag';
            break;
        case 12:
            $ret[5] = 'Sinterklaasavond';
            $ret[25] = 'Kerstmis';
            $ret[26] = 'Kerstmis';
            $ret[31] = 'Oudejaar';
            break;
        }
        $ed = self::getEasterDate($y);
        $edY = date('Y', $ed);
        $edM = date('n', $ed);
        $edD = intval(date('d', $ed));
        if($edM == $m) $ret[$edD] = 'Pasen';
        $tmp = $ed - (86400 * 2);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Goede Vrijdag';
        $tmp = $ed + (86400 * 1);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Pasen';
        $tmp = $ed + (86400 * 39);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Hemelvaartsdag';
        $tmp = $ed + (86400 * 49);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Pinksteren';
        $tmp = $ed + (86400 * 50);
        if(date('n', $tmp) == $m) $ret[intval(date('d', $tmp))] = 'Pinksteren';
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
