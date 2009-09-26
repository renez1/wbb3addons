<?php
require_once (WCF_DIR . 'lib/form/AbstractForm.class.php');

/**
 * $Id$
 * @author      MailMan (http://www.wbb3addons.de)
 * @package     de.mailman.wcf.icsHolidayExporter
 */

define('ICSHE_FILE_PREFIX', 'icshe_');
define('ICSHE_MINYEAR', 1970);
define('ICSHE_MAXYEAR', 2037);

class IcsHolidayExporterForm extends AbstractForm {
    public $templateName = 'icsHolidayExporter';
    public $fromYear = 0;
    public $toYear = 0;
    public $country = '';
    public $years = array();
    public $version = '';
    public $exports = array();
    public $ctryCodes = array();
    private $content = '';

    /**
     * @see Page::readParameters()
     */
    public function readParameters() {
        parent::readParameters();

        $this->version = $this->getVersion();
        for($i=ICSHE_MINYEAR;$i<=ICSHE_MAXYEAR;$i++) $this->years[] = $i;

        if(empty($_POST['fromYear']))   $this->fromYear = date('Y');
        if(empty($_POST['toYear']))     $this->toYear = date('Y');
        if(empty($_POST['country']))    $this->country = 'DE';

/*		
        if (!WCF::getUser()->userID || !WCF::getUser()->getPermission('user.calendar.canUseCalendar')) {
            require_once (WCF_DIR . 'lib/system/exception/PermissionDeniedException.class.php');
            throw new PermissionDeniedException();
        }
*/
    }

    /**
     * @see Form::readFormParameters()
     */
    public function readFormParameters() {
        parent::readFormParameters();
        if(isset($_POST['fromYear']))   $this->fromYear = intval($_POST['fromYear']);
        if(isset($_POST['toYear']))     $this->toYear = intval($_POST['toYear']);
        if(isset($_POST['country']))    $this->country = $_POST['country'];
    }

    /**
     * @see Form::validate()
     */
    public function validate() {
        parent::validate();
        if(empty($this->fromYear) || $this->fromYear > $this->toYear
        || $this->fromYear < ICSHE_MINYEAR || $this->toYear > ICSHE_MAXYEAR) throw new UserInputException('timeFrame');
        if(empty($this->country)) throw new UserInputException('country');
    }

    /**
     * @see Form::save()
     */
    public function save() {
        $username = (WCF::getUser()->username ? WCF::getUser()->username : 'GUEST');
        $sql = "INSERT INTO wcf".WCF_N."_ics_holiday_exporter_log"
               ."\n       (ihelTime, ihelCtryCode, ihelFromYear, ihelToYear, ihelUsername)"
               ."\nVALUES (".TIME_NOW.", '".$this->country."', ".$this->fromYear.", ".$this->toYear.", '".WCF::getDB()->escapeString($username)."')";
        WCF::getDB()->sendQuery($sql);
        $tmp = parse_url(PAGE_URL);
        $parsedUrl = $tmp['host'];
        $fName = ICSHE_FILE_PREFIX.$this->country.'_'.$this->fromYear.'-'.$this->toYear.'.ics';
        $h = $this->getHolidays();
        $this->addLine("BEGIN:VCALENDAR");
        $this->addLine("METHOD:PUBLISH");
        $this->addLine("VERSION:2.0");
        $this->addLine("PRODID:-//WBB3-Add-Ons//ICS Holiday Exporter ".$this->version."//EN");
        $this->addLine("CALSCALE:GREGORIAN");
        foreach($h as $k => $v) {
            $zT = gmdate("Ymd\THis\Z");
            $this->addLine("BEGIN:VEVENT");
            $this->addLine("DTSTART;VALUE=DATE:".$v['DTSTART']);
            $this->addLine("DTEND;VALUE=DATE:".$v['DTEND']);
            if(!empty($v['RRULE'])) $this->addLine("RRULE:".$v['RRULE']);
            $this->addLine("DTSTAMP:".$zT);
            $this->addLine("UID:" . md5(uniqid(rand(), true)) . "@" . $parsedUrl);
            $this->addLine("CREATED:".$zT);
            $this->addLine("LAST-MODIFIED:".$zT);
            $this->addLine("STATUS:CONFIRMED");
            $this->addLine("URL:" . PAGE_URL);
            $this->addLine("SUMMARY:".$this->escLine($v['SUMMARY']));
            if(!empty($v['DESCRIPTION'])) $this->addLine("DESCRIPTION:".$this->escLine($v['DESCRIPTION']));
            $this->addLine("TRANSP:TRANSPARENT");
            $this->addLine("END:VEVENT");
        }
        $this->addLine("END:VCALENDAR");

        // convert encoding
        if (CHARSET != 'UTF-8') {
            $this->content = StringUtil::convertEncoding(CHARSET, 'UTF-8', $this->content);
        }
        // file type
        header('Content-Type: application/octet-stream');
        // file name
        header('Content-Disposition: attachment; filename="'.$fName.'"');
        // no cache headers
        header('Pragma: no-cache');
        header('Expires: 0');
        // send file
        echo $this->content;
        exit;
    }

    /**
     * @see Page::assignVariables()
     */
    public function assignVariables() {
        parent::assignVariables();
        $sql = "SELECT 'Total' AS ctryCode, MAX(ihelID) AS cnt"
            ."\n  FROM wcf".WCF_N."_ics_holiday_exporter_log"
            ."\n UNION"
            ."\nSELECT ihelCtryCode AS ctryCode, COUNT(*) AS cnt"
            ."\n  FROM wcf".WCF_N."_ics_holiday_exporter_log"
            ."\n GROUP BY ihelCtryCode"
            ."\n ORDER BY cnt DESC";
        $this->exports = WCF::getDB()->getResultList($sql);
        $this->ctryCodes = $this->getCountryCodes();

        WCF::getTPL()->assign(array(
            'icsheVersion' => $this->version,
            'fromYear' => $this->fromYear,
            'toYear' => $this->toYear,
            'country' => $this->country,
            'years' => $this->years,
            'ctryCodes' => $this->ctryCodes,
            'exports' => $this->exports
        ));
    }

    /**
     * @see Page::show()
     */
    public function show() {
		// set active menu item
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.icsHolidayExporter');
		
		// check permission
		WCF::getUser()->checkPermission('user.managepages.canUseIHE');
		
        // show form
        parent::show();
    }

    private function escLine($string) {
        $esc = str_replace(array('\\', "\n", ';', ',', '"'), array('\\\\', '\n', '\;', '\,', '\"'), $string);
        return $esc;
    }

    private function addLine($string) {
        // wrap text
        if (StringUtil::length($string) > 75) {
            $string = StringUtil::splitIntoChunks($string, 75, "\r\n ");
        }

        $this->add($string . "\r\n");
    }

    private function add($string) {
        $this->content .= $string;
    }

    private function getHolidays() {
        $ctry = strtolower($this->country);
        if(!is_file(WCF_DIR.'lib/data/icshe/'.$ctry.'.xml')) throw new UserInputException('country');
        $xml = simplexml_load_file(WCF_DIR.'lib/data/icshe/'.$ctry.'.xml');
        $holidays = array();
        $i = 0;
        foreach($xml->annual->holiday as $k => $h) {
            $m = intval($h->month);
            $d = intval($h->day);
            $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $this->fromYear));
            $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $this->fromYear));
            $holidays[$i]['RRULE'] = 'FREQ=YEARLY;INTERVAL=1;BYMONTH='.$m;
            $holidays[$i]['SUMMARY'] = strval($h->summary);
            $holidays[$i]['DESCRIPTION'] = strval($h->description);
            $i++;
        }
        if($xml->eastern || $xml->penanceday || $xml->daydepending) {
            for($y=$this->fromYear;$y<=$this->toYear;$y++) {
                if($xml->eastern) {
                    $ed = self::getEasterDate($y);
                    if($xml->eastern->goodfriday) {
                        $tmp = $ed - (86400 * 2);
                        $m = intval(date('m',$tmp)); $d = intval(date('d',$tmp));
                        $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $y));
                        $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $y));
                        $holidays[$i]['SUMMARY'] = strval($xml->eastern->goodfriday->summary);
                        $holidays[$i]['DESCRIPTION'] = strval($xml->eastern->goodfriday->description);
                        $i++;
                    }
                    if($xml->eastern->eastersunday) {
                        $m = intval(date('m',$ed)); $d = intval(date('d',$ed));
                        $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $y));
                        $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $y));
                        $holidays[$i]['SUMMARY'] = strval($xml->eastern->eastersunday->summary);
                        $holidays[$i]['DESCRIPTION'] = strval($xml->eastern->eastersunday->description);
                        $i++;
                    }
                    if($xml->eastern->eastermonday) {
                        $tmp = $ed + (86400 * 1);
                        $m = intval(date('m',$tmp)); $d = intval(date('d',$tmp));
                        $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $y));
                        $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $y));
                        $holidays[$i]['SUMMARY'] = strval($xml->eastern->eastermonday->summary);
                        $holidays[$i]['DESCRIPTION'] = strval($xml->eastern->eastermonday->description);
                        $i++;
                    }
                    if($xml->eastern->ascension) {
                        $tmp = $ed + (86400 * 39);
                        $m = intval(date('m',$tmp)); $d = intval(date('d',$tmp));
                        $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $y));
                        $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $y));
                        $holidays[$i]['SUMMARY'] = strval($xml->eastern->ascension->summary);
                        $holidays[$i]['DESCRIPTION'] = strval($xml->eastern->ascension->description);
                        $i++;
                    }
                    if($xml->eastern->whitsunday) {
                        $tmp = $ed + (86400 * 49);
                        $m = intval(date('m',$tmp)); $d = intval(date('d',$tmp));
                        $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $y));
                        $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $y));
                        $holidays[$i]['SUMMARY'] = strval($xml->eastern->whitsunday->summary);
                        $holidays[$i]['DESCRIPTION'] = strval($xml->eastern->whitsunday->description);
                        $i++;
                    }
                    if($xml->eastern->whitmonday) {
                        $tmp = $ed + (86400 * 50);
                        $m = intval(date('m',$tmp)); $d = intval(date('d',$tmp));
                        $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $y));
                        $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $y));
                        $holidays[$i]['SUMMARY'] = strval($xml->eastern->whitmonday->summary);
                        $holidays[$i]['DESCRIPTION'] = strval($xml->eastern->whitmonday->description);
                        $i++;
                    }
                    if($xml->eastern->corpuschristi) {
                        $tmp = $ed + (86400 * 60);
                        $m = intval(date('m',$tmp)); $d = intval(date('d',$tmp));
                        $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $y));
                        $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $y));
                        $holidays[$i]['SUMMARY'] = strval($xml->eastern->corpuschristi->summary);
                        $holidays[$i]['DESCRIPTION'] = strval($xml->eastern->corpuschristi->description);
                        $i++;
                    }
                }
                if($xml->penanceday) {
                    $a4 = 25;
                    $bb = 1;
                    while(strftime('%w', mktime(0,0,0,12,$a4,$y)) != 0) {
                        $a4--;
                        $bb = intval(strftime('%j', mktime(0,0,0,12,$a4,$y)) - (5*7) + 3);
                    }
                    $tmp = mktime(0,0,0,1,$bb,$y);
                    $m = intval(date('m',$tmp)); $d = intval(date('d',$tmp));
                    $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $y));
                    $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $y));
                    $holidays[$i]['SUMMARY'] = strval($xml->penanceday->summary);
                    $holidays[$i]['DESCRIPTION'] = strval($xml->penanceday->description);
                    $i++;
                }
                if($xml->daydepending) {
                    foreach($xml->daydepending->holiday as $k => $h) {
                        $m = intval($h->month);
                        list($c,$wD) = preg_split('/:/',$h->day,2);
                        $c = intval($c);
                        $wD = intval($wD);
                        if($c > 0) $start = $c * 7;
                        else $start = date('j', mktime(0,0,0,$m + 1,0,$y)) + (7 * $c) + 7;
                        if(strftime('%w', mktime(0,0,0,$m,$start,$y)) == $wD) {
                            $d = date('j', mktime(0,0,0,$m,$start,$y));
                        } else {
                            while(strftime('%w', mktime(0,0,0,$m,$start,$y)) != $wD) {
                                $start--;
                                $d = date('j', mktime(0,0,0,$m,$start,$y));
                            }
                        }
                        $d = intval($d);
                        $holidays[$i]['DTSTART'] = date('Ymd', mktime(0, 0, 0, $m, $d, $y));
                        $holidays[$i]['DTEND'] = date('Ymd', mktime(0, 0, 0, $m, $d+1, $y));
                        $holidays[$i]['SUMMARY'] = strval($h->summary);
                        $holidays[$i]['DESCRIPTION'] = strval($h->description);
                        $i++;
                    }
                }
            }
        }
        ksort($holidays);
        return $holidays;
    }

    private function getEasterDate($y) {
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
    
    private function getCountryCodes() {
        $ret = array();
        $dh = opendir(WCF_DIR.'lib/data/icshe');
        while(($file = readdir($dh)) !== false) {
            if(preg_match('/\.xml$/', $file)) {
                list($c) = preg_split('/\./', $file, 2);
                $ret[$c] = strtoupper($c);
            }
        }
        closedir($dh);
        ksort($ret);
        return $ret;
    }

    private function getVersion() {
        $ret = '';
        $sql = "SELECT packageVersion FROM wcf".WCF_N."_package WHERE package = 'de.mailman.wcf.icsHolidayExporter'";
        list($ret) = WCF::getDB()->getFirstRow($sql, MYSQL_NUM);
        return $ret;
    }
}
?>
