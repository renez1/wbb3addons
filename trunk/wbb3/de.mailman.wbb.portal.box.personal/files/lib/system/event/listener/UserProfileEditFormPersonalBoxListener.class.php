<?php
/* $Id$ */
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

class UserProfileEditFormPersonalBoxListener implements EventListener {
    protected $personalbox_maxheight = 0;

	public function execute($eventObj, $className, $eventName) {
		if($eventObj->activeCategory == 'settings.display') {
            if($eventName == 'assignVariables') {
				foreach($eventObj->options as $key1 => $category1) {
					if($category1['categoryName'] == 'settings.display.personalbox') {
					    if(isset($eventObj->options[$key1]) && !WCF::getUser()->getPermission('user.profile.personalbox.canView')) {
					        unset($eventObj->options[$key1]);
					        break;
					    } else {
                            foreach ($category1['options'] as $key2 => $category2) {
                                if(isset($category2['optionName']) && isset($eventObj->options[$key1]['options'][$key2])) {
                                    $doUnset = false;
                                    switch($category2['optionName']) {
                                        case 'personalbox_maxheight':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetMaxheight')) $doUnset = true;
                                            break;
                                        case 'personalbox_show_avatar':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetAvatar')) $doUnset = true;
                                            break;
                                        case 'personalbox_show_personal':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetPersonal')) $doUnset = true;
                                            break;
                                        case 'personalbox_show_search':
                                        case 'personalbox_search_days':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetCurPosts')) $doUnset = true;
                                            break;
                                        case 'personalbox_show_pm':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetPM')) $doUnset = true;
                                            break;
                                        case 'personalbox_show_im':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetIM')) $doUnset = true;
                                            break;
                                        case 'personalbox_show_usercp_acp':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetUserCP')) $doUnset = true;
                                            break;
                                        case 'personalbox_show_styles':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetStyle')) $doUnset = true;
                                            break;
                                        case 'personalbox_show_misc':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetMisc')) $doUnset = true;
                                            break;
                                        case 'personalbox_weathercom_enabled':
                                        case 'personalbox_weather_enabled':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetWeather')) $doUnset = true;
                                            break;
                                        case 'personalbox_weathercom_zipcode':
                                        case 'personalbox_weather_zipcode':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetWeather') || !WCF::getUser()->getPermission('user.profile.personalbox.canSetWeatherZip')) $doUnset = true;
                                            break;
                                        case 'personalbox_weathercom_style':
                                        case 'personalbox_weathercom_day':
                                        case 'personalbox_weather_style':
                                            if(!WCF::getUser()->getPermission('user.profile.personalbox.canSetWeather') || !WCF::getUser()->getPermission('user.profile.personalbox.canSetWeatherStyle')) $doUnset = true;
                                            break;
                                    }
                                    if($doUnset) unset($eventObj->options[$key1]['options'][$key2]);
                            	}
                            }
						    break;
						}
                    }
                }
            }
		}
	}
}

?>
