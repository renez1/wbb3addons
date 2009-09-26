<?php
require_once(WCF_DIR.'lib/data/page/location/Location.class.php');

/**
 * $Id$
 * @author      MailMan (http://www.wbb3addons.de)
 * @package     de.mailman.wcf.icsHolidayExporter
 */

class IcsHolidayExporterLocation implements Location {
	public $cachedUserIDs = array();
	public $users = null;

	/**
	 * @see Location::cache()
	 */
	public function cache($location, $requestURI, $requestMethod, $match) {
        if(isset($match[1])) $this->cachedUserIDs[] = $match[1];
	}

	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {
        if(!empty($location['locationName'])) {
            switch($location['locationName']) {
                case 'wcf.usersOnline.location.icsHolidayExporter':
                    return $this->icsHolidayExporter($location);
                    break;
                default:
                    return WCF::getLanguage()->get($location['locationName']);
            }
        } else {
            return '';
        }
	}

    protected function icsHolidayExporter($location) {
        $ret = '';
        $page = 'IcsHolidayExporter';
        $ret = '<a href="index.php?form='.$page.'">'.WCF::getLanguage()->get($location['locationName']).'</a>';
        return $ret;
    }
}
?>