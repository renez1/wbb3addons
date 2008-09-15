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

    public function getAppointments($y, $m) {
        $month = intval($m);
        $ret = array();
        $sTimestamp = mktime(0, 0, 0, $m, 1, $y);
        $eTimestamp = mktime(0, 0, 0, $m+1, 0, $y);
        $userID = WCF::getUser()->userID;
        if(empty($userID)) return $ret;

        if(WCF::getUser()->getPermission('user.calendar.canUseCalendar')) {
            $sql = "SELECT cem.subject AS subject, ced.startTime AS startTime"
                ."\n  FROM wcf".WCF_N."_calendar_event_date ced"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ced.eventID)"
                ."\n WHERE ced.startTime >= ".$sTimestamp
                ."\n   AND ced.startTime <= ".$eTimestamp
                ."\n   AND cem.userID = ".$userID;
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                $dd = date('j', $row['startTime']);
                if(isset($ret[$dd])) $ret[$dd] .= " - ";
                else $ret[$dd] = '';
                $ret[$dd] .= StringUtil::encodeHTML($row['subject']);
            }
        } else if(WCF::getUser()->getPermission('user.calendar.canEnter')) {
            $sql = "SELECT cem.subject AS subject, ce.eventTime AS startTime"
                ."\n  FROM wcf".WCF_N."_calendar_event ce"
                ."\n  LEFT JOIN wcf".WCF_N."_calendar_event_message cem ON (cem.eventID = ce.eventID)"
                ."\n WHERE ce.eventTime >= ".$sTimestamp
                ."\n   AND ce.eventTime <= ".$eTimestamp
                ."\n   AND cem.userID = ".$userID;
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                $dd = date('j', $row['startTime']);
                if(isset($ret[$dd])) $ret[$dd] .= " - ";
                else $ret[$dd] = '';
                $ret[$dd] .= StringUtil::encodeHTML($row['subject']);
            }
        }
        return $ret;
    }

    public function getBirthdays($y, $m) {
        $month = intval($m);
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
                ."\n WHERE uov.userOption".$optionID." LIKE '____-".$month."-__'";
            $result = WBBCore::getDB()->sendQuery($sql);
            while($row = WBBCore::getDB()->fetchArray($result)) {
                list($by, $bm, $bd) = preg_split('/\-/', $row['BD'], 3);
                $by = intval($by);
                $bm = intval($bm);
                $bd = intval($bd);
                if($y >= $by) {
                    $age = $y - $by;
                    if($age == 0) $age = 1;
                    if(isset($ret[$bd])) $ret[$bd] .= " - ";
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
