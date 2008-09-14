<?php
require_once (WBB_DIR.'lib/data/boxes/MonthlyCalendarBoxHelper.class.php');

/**
 * $Id$
 * @author      MailMan (http://wbb3addons.ump2002.net)
 * @package     de.mailman.wbb.portal.box.monthlyCalendar
 */
class MonthlyCalendarBox {
    protected $BoxData = array();
    public $mcbHelper;

    public function __construct($data, $boxname = "") {
        $this->BoxData['templatename'] = "monthlyCalendarBox";
        $this->getBoxStatus($data);
        $this->BoxData['boxID'] = $data['boxID'];
        $this->mcbHelper = new MonthlyCalendarBoxHelper();

        if(!WBBCore::getUser()->userID || WBBCore::getUser()->monthlyCalendarBox_showCalendarWeeks) $mcbShowCW = true;
        else $mcbShowCW = false;
        if(WBBCore::getUser()->monthlyCalendarBox_showBirthdays) $mcbShowBirthdays = true;
        else $mcbShowBirthdays = false;
        if(WBBCore::getUser()->monthlyCalendarBox_showAppointments) $mcbShowAppointments = true;
        else $mcbShowAppointments = false;
        if(!WBBCore::getUser()->userID || WBBCore::getUser()->monthlyCalendarBox_showHolidays) $mcbShowHolidays = true;
        else $mcbShowHolidays = false;

        $mcDays = $daysBefore = $daysAfter = $calendarWeeks = $birthdays = $dates = $holidays = $months = array();
        $curDay = 0;
        $mcCurY = intval(date('Y'));
        $mcCurM = intval(date('n'));
        if(isset($_REQUEST['mcY'])) $mcY = intval($_REQUEST['mcY']);
        else if(WCF::getSession()->getVar('monthlyCalendarBoxY')) $mcY = intval(WCF::getSession()->getVar('monthlyCalendarBoxY'));
        if(isset($_REQUEST['mcM'])) {
            $mcM = intval($_REQUEST['mcM']);
            if($mcM < 1) {
                $mcM = 12;
                $mcY--;
            } else if($mcM > 12) {
                $mcM = 1;
                $mcY++;
            }
        } else if(WCF::getSession()->getVar('monthlyCalendarBoxM')) {
            $mcM = intval(WCF::getSession()->getVar('monthlyCalendarBoxM'));
        }

        if(empty($mcY)) $mcY = $mcCurY;
        else if($mcY < 1902) $mcY = 1902;
        else if($mcY > 2037) $mcY = 2037;
        if(empty($mcM)) $mcM = $mcCurM;
        else if($mcM < 1) $mcM = 1;
        else if($mcM > 12) $mcM = 12;
        if($mcY == $mcCurY && $mcM == $mcCurM) $curDay = date('j');
        $mcbTitle = WCF::getLanguage()->get('wbb.portal.box.monthlyCalendar.month_'.$mcM).' '.$mcY;
        if($mcbShowBirthdays) $birthdays = $this->mcbHelper->getBirthdays($mcY, $mcM);
        if($mcbShowAppointments && (WCF::getUser()->getPermission('user.calendar.canUseCalendar') || WCF::getUser()->getPermission('user.calendar.canEnter'))) $dates = $this->mcbHelper->getAppointments($mcY, $mcM);
        if($mcbShowHolidays && $mcY >= 1970) $holidays = $this->mcbHelper->getHolidaysDE($mcY, $mcM);

        if(WCF::getUser()->getPermission('user.board.canViewMonthlyCalendarBox')) {
            $cntDays = strftime('%d',gmmktime(0,0,0,$mcM+1,0,$mcY));
            $firstWeekday = strftime('%w', gmmktime(0,0,0,$mcM,1,$mcY));
            $lastWeekday = strftime('%w', gmmktime(0,0,0,$mcM,$cntDays,$mcY));
            if($firstWeekday == 0) $firstWeekday = 7;
            if($lastWeekday == 0) $lastWeekday = 7;
            for($i=1;$i<$firstWeekday;$i++) $daysBefore[] = $i;
            for($i=1;$i<=7-$lastWeekday;$i++) $daysAfter[] = $i;
            for($i=1;$i<=$cntDays;$i++) {
                $mcDays[$i]['day'] = $i;
                $doy = date('z',gmmktime(0,0,0,$mcM,$i,$mcY)) + 1;
                $mcDays[$i]['birthday'] = false;
                $mcDays[$i]['date'] = false;
                $mcDays[$i]['holiday'] = false;
                $mcDays[$i]['title'] = WCF::getLanguage()->get('wbb.portal.box.monthlyCalendar.dayOfTheYear', array('$doy' => $doy));
                if($mcbShowAppointments && isset($dates[$i])) {
                    $mcDays[$i]['title'] .= ' &bull; '.WCF::getLanguage()->get('wbb.portal.box.monthlyCalendar.appointments').': '.$dates[$i];
                    $mcDays[$i]['date'] = true;
                }
                if($mcbShowBirthdays && isset($birthdays[$i])) {
                    $mcDays[$i]['title'] .= ' &bull; '.WCF::getLanguage()->get('wbb.portal.box.monthlyCalendar.birthdays').': '.$birthdays[$i];
                    $mcDays[$i]['birthday'] = true;
                }
                if($mcbShowHolidays && isset($holidays[$i])) {
                    $mcDays[$i]['title'] .= ' &bull; '.$holidays[$i];
                    $mcDays[$i]['holiday'] = true;
                }
            }
            for($i=1;$i<=12;$i++) $months[$i] = WCF::getLanguage()->get('wbb.portal.box.monthlyCalendar.month_'.$i);
        }
        WCF::getSession()->register('monthlyCalendarBoxY', $mcY);
        WCF::getSession()->register('monthlyCalendarBoxM', $mcM);
        WCF::getTPL()->assign(array(
            'mcbHelper' => $this->mcbHelper,
            'mcbTitle' => $mcbTitle,
            'curDay' => $curDay,
            'mcCurY' => $mcCurY,
            'mcCurM' => $mcCurM,
            'mcY' => $mcY,
            'mcM' => $mcM,
            'daysAfter' => $daysAfter,
            'mcDays' => $mcDays,
            'daysBefore' => $daysBefore,
            'months' => $months,
            'mcbShowCW' => $mcbShowCW
        ));
    }

    protected function getBoxStatus($data) {
        // get box status
        $this->BoxData['Status'] = 1;
        if (WBBCore::getUser()->userID) {
            $this->BoxData['Status'] = intval(WBBCore::getUser()->monthlyCalendarBox);
        }
        else {
            if (WBBCore::getSession()->getVar('monthlyCalendarBox') != false) {
                $this->BoxData['Status'] = WBBCore::getSession()->getVar('monthlyCalendarBox');
            }
        }
    }

    public function getData() {
        return $this->BoxData;
    }
}

?>
