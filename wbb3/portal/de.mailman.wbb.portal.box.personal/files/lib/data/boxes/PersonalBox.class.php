<?php
/* $Id$ */
class PersonalBox {
	protected $BoxData = array();

	public function __construct($data, $boxname = "") {
		$this->BoxData['templatename'] = "personalbox";
		$this->getBoxStatus($data);
		$this->BoxData['boxID'] = $data['boxID'];

        // Instant Messenger by Tatzelwurm
        if(!defined('INSTANTMESSENGER_AKTIV')) define('INSTANTMESSENGER_AKTIV', false);
        $imcount = 0;
        $pbShowIM = false;
        if(!empty($_REQUEST['page'])) $boxCurPage = $_REQUEST['page'];
        else $boxCurPage = 'Portal';

        // DEFAULTS
        $pbCatVertOffset    = 4;
        $pbLargeRankImages  = false;
        $pbRepeatRankImage  = true;
        $pbRankImage        = '<img src="'.RELATIVE_WCF_DIR.'icon/userRank1S.png" alt="" title="'.WCF::getLanguage()->get('wcf.user.rank').'" />';
        $pbLineFeedRank     = false;
        $pbFBColor          = 1;
        $pbSBColor          = 2;
        $pbShowUserMarking  = true;
        $pbStyleWidth       = 140;
        $pbShowProfileLink  = false;
        $pbShowDisplayLink  = false;
        $pbFirstColWidth    = 20;
        $pbTableWidth       = '99%';
        $pbCellPadding      = 0;
        $pbWeatherZipCode   = '60329';
        $pbWeatherComZipCode    = 'DEPLZ,60329';
        $pbWeatherStyle     = 1;
        $pbWeatherComStyle  = 4;
        $pbWeatherWidth     = 140;
        $pbWeatherComDay    = 'C';
        $pbMaxHeight        = 0;
        $pbShowAvatar       = true;
        $pbAvatarMaxWidth   = 150;
        $pbAvatarMaxHeight  = 150;
        $pbShowPersonal     = false;
        $pbShowSearch       = true;
        $pbSearchDays       = 0;
        $pbShowPM           = true;
        $pbShowUserCP       = true;
        $pbShowStyles       = false;
        $pbShowMisc         = false;
        $pbShowWeather      = false;
        $pbShowWeatherCom   = false;
        $pbShowProfileHits  = false;

        // ACP Konstanten...
        if(!defined('PERSONALBOX_CATSPACER_ACP'))       define('PERSONALBOX_CATSPACER_ACP',         $pbCatVertOffset);
        if(!defined('PERSONALBOX_LARGERANKIMAGES_ACP')) define('PERSONALBOX_LARGERANKIMAGES_ACP',   $pbLargeRankImages);
        if(!defined('PERSONALBOX_REPEATRANKIMAGE_ACP')) define('PERSONALBOX_REPEATRANKIMAGE_ACP',   $pbRepeatRankImage);
        if(!defined('PERSONALBOX_STYLEBOXWIDTH_ACP'))   define('PERSONALBOX_STYLEBOXWIDTH_ACP',     $pbStyleWidth);
        if(!defined('PERSONALBOX_WEATHER_ZIPCODE_ACP')) define('PERSONALBOX_WEATHER_ZIPCODE_ACP',   $pbWeatherZipCode);
        if(!defined('PERSONALBOX_WEATHERCOM_ZIPCODE_ACP'))  define('PERSONALBOX_WEATHERCOM_ZIPCODE_ACP',    $pbWeatherComZipCode);
        if(!defined('PERSONALBOX_WEATHER_STYLE_ACP'))   define('PERSONALBOX_WEATHER_STYLE_ACP',     $pbWeatherStyle);
        if(!defined('PERSONALBOX_WEATHERCOM_STYLE_ACP'))    define('PERSONALBOX_WEATHERCOM_STYLE_ACP',  $pbWeatherComStyle);
        if(!defined('PERSONALBOX_WEATHERCOM_DAY_ACP'))  define('PERSONALBOX_WEATHERCOM_DAY_ACP',    $pbWeatherComDay);
        if(!defined('PERSONALBOX_WEATHER_WIDTH_ACP'))   define('PERSONALBOX_WEATHER_WIDTH_ACP',     $pbWeatherWidth);
        if(!defined('PERSONALBOX_LINEFEEDRANK_ACP'))    define('PERSONALBOX_LINEFEEDRANK_ACP',      $pbLineFeedRank);
        if(!defined('PERSONALBOX_FBCOLOR_ACP'))         define('PERSONALBOX_FBCOLOR_ACP',           $pbFBColor);
        if(!defined('PERSONALBOX_SBCOLOR_ACP'))         define('PERSONALBOX_SBCOLOR_ACP',           $pbSBColor);
        if(!defined('PERSONALBOX_SHOWUSERMARKING_ACP')) define('PERSONALBOX_SHOWUSERMARKING_ACP',   $pbShowUserMarking);
        if(!defined('PERSONALBOX_SHOWPROFILELINK_ACP')) define('PERSONALBOX_SHOWPROFILELINK_ACP',   $pbShowProfileLink);
        if(!defined('PERSONALBOX_SHOWDISPLAYLINK_ACP')) define('PERSONALBOX_SHOWDISPLAYLINK_ACP',   $pbShowDisplayLink);
        if(!defined('PERSONALBOX_FIRSTCOLWIDTH_ACP'))   define('PERSONALBOX_FIRSTCOLWIDTH_ACP',     $pbFirstColWidth);
        if(!defined('PERSONALBOX_TABLEWIDTH_ACP'))      define('PERSONALBOX_TABLEWIDTH_ACP',        $pbTableWidth);
        if(!defined('PERSONALBOX_CELLPADDING_ACP'))     define('PERSONALBOX_CELLPADDING_ACP',       $pbCellPadding);
        if(!defined('PERSONALBOX_MAXHEIGHT_ACP'))       define('PERSONALBOX_MAXHEIGHT_ACP',         $pbMaxHeight);
        if(!defined('PERSONALBOX_SHOW_AVATAR_ACP'))     define('PERSONALBOX_SHOW_AVATAR_ACP',       $pbShowAvatar);
        if(!defined('PERSONALBOX_AVATARMAXWIDTH_ACP'))  define('PERSONALBOX_AVATARMAXWIDTH_ACP',    $pbAvatarMaxWidth);
        if(!defined('PERSONALBOX_AVATARMAXHEIGHT_ACP')) define('PERSONALBOX_AVATARMAXHEIGHT_ACP',   $pbAvatarMaxHeight);
        if(!defined('PERSONALBOX_SHOW_PERSONAL_ACP'))   define('PERSONALBOX_SHOW_PERSONAL_ACP',     $pbShowPersonal);
        if(!defined('PERSONALBOX_SHOW_SEARCH_ACP'))     define('PERSONALBOX_SHOW_SEARCH_ACP',       $pbShowSearch);
        if(!defined('PERSONALBOX_SEARCH_DAYS_ACP'))     define('PERSONALBOX_SEARCH_DAYS_ACP',       $pbSearchDays);
        if(!defined('PERSONALBOX_SHOW_PM_ACP'))         define('PERSONALBOX_SHOW_PM_ACP',           $pbShowPM);
        if(!defined('PERSONALBOX_SHOW_USERCP_ACP'))     define('PERSONALBOX_SHOW_USERCP_ACP',       $pbShowUserCP);
        if(!defined('PERSONALBOX_SHOW_STYLES_ACP'))     define('PERSONALBOX_SHOW_STYLES_ACP',       $pbShowStyles);
        if(!defined('PERSONALBOX_SHOW_MISC_ACP'))       define('PERSONALBOX_SHOW_MISC_ACP',         $pbShowMisc);
        if(!defined('PERSONALBOX_WEATHER_SHOW_ACP'))    define('PERSONALBOX_WEATHER_SHOW_ACP',      $pbShowWeather);
        if(!defined('PERSONALBOX_WEATHERCOM_SHOW_ACP')) define('PERSONALBOX_WEATHERCOM_SHOW_ACP',   $pbShowWeatherCom);
        if(!defined('PERSONALBOX_SHOW_IM_ACP'))         define('PERSONALBOX_SHOW_IM_ACP',           false);
        if(!defined('PERSONALBOX_SHOW_PROFILEHITS_ACP')) define('PERSONALBOX_SHOW_PROFILEHITS_ACP', $pbShowProfileHits);

		if(WCF::getUser()->userID != 0) {
            // Include libraries...
            require_once(WBB_DIR.'lib/data/board/Board.class.php');
            require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

            // Boxen Hoehe
            if(WCF::getUser()->getPermission('user.profile.personalbox.canSetMaxheight')
                && (WCF::getUser()->personalbox_maxheight >= 100
                || WCF::getUser()->personalbox_maxheight == 0
                || WCF::getUser()->personalbox_maxheight == 1))
                    $pbMaxHeight = intval(WCF::getUser()->personalbox_maxheight);
            else if(PERSONALBOX_MAXHEIGHT_ACP >= 100) $pbMaxHeight = PERSONALBOX_MAXHEIGHT_ACP;
            // Avatar
            if(WCF::getUser()->getPermission('user.profile.personalbox.canSetAvatar') && WCF::getUser()->personalbox_show_avatar == 'enabled') $pbShowAvatar = true;
            else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetAvatar') && WCF::getUser()->personalbox_show_avatar == 'disabled') $pbShowAvatar = false;
            else $pbShowAvatar = PERSONALBOX_SHOW_AVATAR_ACP;
            // Persoenliches
            if(WCF::getUser()->getPermission('user.profile.personalbox.canSetPersonal') && WCF::getUser()->personalbox_show_personal == 'enabled') $pbShowPersonal = true;
            else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetPersonal') && WCF::getUser()->personalbox_show_personal == 'disabled') $pbShowPersonal = false;
            else $pbShowPersonal = PERSONALBOX_SHOW_PERSONAL_ACP;
            // Beitraege
            if(WCF::getUser()->getPermission('user.profile.personalbox.canSetCurPosts') && WCF::getUser()->personalbox_show_search == 'enabled') $pbShowSearch = true;
            else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetCurPosts') && WCF::getUser()->personalbox_show_search == 'disabled') $pbShowSearch = false;
            else $pbShowSearch = PERSONALBOX_SHOW_SEARCH_ACP;
            // Private Nachrichten
            if(WCF::getUser()->getPermission('user.profile.personalbox.canSetPM') && WCF::getUser()->personalbox_show_pm == 'enabled') $pbShowPM = true;
            else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetPM') && WCF::getUser()->personalbox_show_pm == 'disabled') $pbShowPM = false;
            else $pbShowPM = PERSONALBOX_SHOW_PM_ACP;
            // Verwaltung
            if(WCF::getUser()->getPermission('user.profile.personalbox.canSetUserCP') && WCF::getUser()->personalbox_show_usercp == 'enabled') $pbShowUserCP = true;
            else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetUserCP') && WCF::getUser()->personalbox_show_usercp == 'disabled') $pbShowUserCP = false;
            else $pbShowUserCP = PERSONALBOX_SHOW_USERCP_ACP;
            // Style
            if(WCF::getUser()->getPermission('user.profile.personalbox.canSetStyle') && WCF::getUser()->personalbox_show_styles == 'enabled') $pbShowStyles = true;
            else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetStyle') && WCF::getUser()->personalbox_show_styles == 'disabled') $pbShowStyles = false;
            else $pbShowStyles = PERSONALBOX_SHOW_STYLES_ACP;
            $pbStyles = array();
            if($pbShowStyles && defined('PERSONALBOX_CNTSTYLES_ACP') && PERSONALBOX_CNTSTYLES_ACP == true) {
                $i = $isDefaultIdx = $cntDisabled = 0;
                $sql = "SELECT s.styleID, s.styleName, s.isDefault, s.disabled, COUNT(u.userID) AS CNT"
                    ."\n  FROM wcf".WCF_N."_style s"
                    ."\n  LEFT JOIN wcf".WCF_N."_user u ON (u.styleID = s.styleID OR (u.styleID = 0 AND isDefault = 1))"
                    ."\n GROUP BY styleID, styleName, isDefault, disabled"
                    ."\n ORDER BY styleName";
                $result = WBBCore::getDB()->sendQuery($sql);
                while($row = WBBCore::getDB()->fetchArray($result)) {
                    $pbStyles[$i]['ID']         = $row['styleID'];
                    $pbStyles[$i]['NAME']       = $row['styleName'];
                    $pbStyles[$i]['DEFAULT']    = $row['isDefault'];
                    $pbStyles[$i]['DISABLED']   = $row['disabled'];
                    $pbStyles[$i]['CNT']        = $row['CNT'];
                    if(!empty($row['isDefault'])) $isDefaultIdx = $i;
                    if(!empty($row['disabled'])) $cntDisabled += $row['CNT'];
                    $i++;
                }
                if($cntDisabled > 0 && isset($pbStyles[$isDefaultIdx])) $pbStyles[$isDefaultIdx]['CNT'] += $cntDisabled;
            }


            // Sonstiges
            if(WCF::getUser()->getPermission('user.profile.personalbox.canSetMisc') && WCF::getUser()->personalbox_show_misc == 'enabled') $pbShowMisc = true;
            else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetMisc') && WCF::getUser()->personalbox_show_misc == 'disabled') $pbShowMisc = false;
            else $pbShowMisc = PERSONALBOX_SHOW_MISC_ACP;
            $pbLinks = array();
            if($pbShowMisc && defined('PERSONALBOX_LINKLIST_ACP') && PERSONALBOX_LINKLIST_ACP != '' && preg_match('/\|/', PERSONALBOX_LINKLIST_ACP)) {
                $linkList = preg_split("/\r?\n/", PERSONALBOX_LINKLIST_ACP);
                $i = 0;
                foreach($linkList as $line) {
                    $line = trim($line);
                    if(preg_match("/\{SPACER\}/", $line)) {
                        $pbLinks[$i]['TYPE'] = 'SPACER';
                        $pbLinks[$i]['SPACER'] = preg_replace("/\{SPACER\}(.*)\{\/SPACER\}/i", "$1", $line);
                        $i++;
                    } else if(preg_match("/\|/", $line)) {
                        list($img,$url,$title,$target,$perm) = preg_split("/\|/", $line,5);
                        $img = trim($img);
                        $url = trim($url);
                        $title = trim($title);
                        $target = trim($target);
                        $perm = trim($perm);
                        if(!empty($url) && !empty($title)) {
                            if(preg_match("/\{\@?RELATIVE_WBB_DIR\}/", $img) && defined('RELATIVE_WBB_DIR')) $img = preg_replace("/{\@?RELATIVE_WBB_DIR\}/", RELATIVE_WBB_DIR, $img);
                            if(preg_match("/\{\@?RELATIVE_WCF_DIR\}/", $img) && defined('RELATIVE_WCF_DIR')) $img = preg_replace("/{\@?RELATIVE_WCF_DIR\}/", RELATIVE_WCF_DIR, $img);
                            if(preg_match("/\{\@?RELATIVE_WBB_DIR\}/", $url) && defined('RELATIVE_WBB_DIR')) $url = preg_replace("/{\@?RELATIVE_WBB_DIR\}/", RELATIVE_WBB_DIR, $url);
                            if(preg_match("/\{\@?RELATIVE_WCF_DIR\}/", $url) && defined('RELATIVE_WCF_DIR')) $url = preg_replace("/{\@?RELATIVE_WCF_DIR\}/", RELATIVE_WCF_DIR, $url);
                            if(preg_match("/\{\@?SECURITY_TOKEN\}/", $url) && defined('SECURITY_TOKEN'))     $url = preg_replace("/{\@?SECURITY_TOKEN\}/", SECURITY_TOKEN, $url);
                            if(preg_match("/\{\@?PACKAGE_ID\}/", $url) && defined('PACKAGE_ID')) $url = preg_replace("/{\@?PACKAGE_ID\}/", PACKAGE_ID, $url);
                            if(preg_match("/\{\@?SID_ARG_2ND\}/", $url) && defined('SID_ARG_2ND')) $url = preg_replace("/{\@?SID_ARG_2ND\}/", SID_ARG_2ND, $url);
                            if(preg_match("/\{\@?USER_ID\}/", $url)) $url = preg_replace("/{\@?USER_ID\}/", WCF::getUser()->userID, $url);
                            $pbLinks[$i]['TYPE'] = 'LINK';
                            $pbLinks[$i]['IMG'] = $img;
                            $pbLinks[$i]['URL'] = $url;
                            $pbLinks[$i]['TITLE'] = $title;
                            $pbLinks[$i]['TARGET'] = $target;
                            $pbLinks[$i]['PERM'] = $perm;
                            $i++;
                        }
                    }
                }
            }

            // Wetter
            if(WCF::getUser()->getPermission('user.profile.personalbox.enableWeather')) {
                // Donnerwetter
                if(WCF::getUser()->getPermission('user.profile.personalbox.canSetWeather') && WCF::getUser()->personalbox_weather_enabled == 'enabled') $pbShowWeather = true;
                else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetWeather') && WCF::getUser()->personalbox_weather_enabled == 'disabled') $pbShowWeather = false;
                else $pbShowWeather = PERSONALBOX_WEATHER_SHOW_ACP;
                // PLZ fuer Donnerwetter
                if(WCF::getUser()->getPermission('user.profile.personalbox.canSetWeatherZip') && preg_match("/^[0-9]{4,5}$/",WCF::getUser()->personalbox_weather_zipcode)) $pbWeatherZipCode = WCF::getUser()->personalbox_weather_zipcode;
                else if(preg_match("/^[0-9]{4,5}$/",PERSONALBOX_WEATHER_ZIPCODE_ACP)) $pbWeatherZipCode = PERSONALBOX_WEATHER_ZIPCODE_ACP;
                // Style fuer Donnerwetter...
                if(WCF::getUser()->getPermission('user.profile.personalbox.canSetWeatherStyle') && preg_match("/^1|2$/",WCF::getUser()->personalbox_weather_style)) $pbWeatherStyle = WCF::getUser()->personalbox_weather_style;
                else if(preg_match("/^1|2$/",PERSONALBOX_WEATHER_STYLE_ACP)) $pbWeatherStyle = PERSONALBOX_WEATHER_STYLE_ACP;

                // wetter.com
                if(WCF::getUser()->getPermission('user.profile.personalbox.canSetWeather') && WCF::getUser()->personalbox_weathercom_enabled == 'enabled') $pbShowWeatherCom = true;
                else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetWeather') && WCF::getUser()->personalbox_weathercom_enabled == 'disabled') $pbShowWeatherCom = false;
                else $pbShowWeatherCom = PERSONALBOX_WEATHERCOM_SHOW_ACP;
                if(WCF::getUser()->getPermission('user.profile.personalbox.canSetWeatherZip') && preg_match("/^.*\,.*$/",WCF::getUser()->personalbox_weathercom_zipcode)) $pbWeatherComZipCode = WCF::getUser()->personalbox_weathercom_zipcode;
                else if(preg_match("/^.*\,.*$/",PERSONALBOX_WEATHERCOM_ZIPCODE_ACP)) $pbWeatherComZipCode = PERSONALBOX_WEATHERCOM_ZIPCODE_ACP;
                if(WCF::getUser()->getPermission('user.profile.personalbox.canSetWeatherStyle') && preg_match("/^[1-5]$/",WCF::getUser()->personalbox_weathercom_style)) $pbWeatherComStyle = WCF::getUser()->personalbox_weathercom_style;
                else if(preg_match("/^[1-5]$/",PERSONALBOX_WEATHERCOM_STYLE_ACP)) $pbWeatherComStyle = PERSONALBOX_WEATHERCOM_STYLE_ACP;
                if(WCF::getUser()->getPermission('user.profile.personalbox.canSetWeatherStyle') && preg_match("/^Z|C|F$/",WCF::getUser()->personalbox_weathercom_day)) $pbWeatherComDay = WCF::getUser()->personalbox_weathercom_day;
                else if(preg_match("/^Z|C|F$/",PERSONALBOX_WEATHERCOM_DAY_ACP)) $pbWeatherComDay = PERSONALBOX_WEATHERCOM_DAY_ACP;
            }

            // setze Timestamp genau auf 0.00 Uhr...
            if(WCF::getUser()->getPermission('user.profile.personalbox.canSetCurPosts') && WCF::getUser()->personalbox_search_days != 'default') $pbSearchDays = intval(WCF::getUser()->personalbox_search_days);
            else $pbSearchDays = PERSONALBOX_SEARCH_DAYS_ACP;
            if($pbSearchDays == 0) {
                $itstamp    = time();
            } else {
                $itstamp    = time() - $pbSearchDays * 86400;
            }
            $searchTime = mktime(0, 0, 0, (int) date("m",$itstamp), (int) date("d",$itstamp), (int) date("Y",$itstamp));

            // Hintergrundfarbe fuer Donnerwetter...
            if(preg_match("/^[a-f0-9]{6}$/i",PERSONALBOX_WEATHER_BGCOLOR_ACP)) {
                $bgColor    = strtoupper(PERSONALBOX_WEATHER_BGCOLOR_ACP);
            } else {
                $bgColor    = strtoupper(WBBCore::getStyle()->getVariable('container1.background.color'));
                $bgColor    = preg_replace("/\#/","",$bgColor);
                if(strlen($bgColor) < 6 && strlen($bgColor) > 0) $bgColor = str_pad($bgColor,6,substr($bgColor,-1,1));
            }
            // Rahmenfarbe fuer Donnerwetter...
            if(preg_match("/^[a-f0-9]{6}$/i",PERSONALBOX_WEATHER_BOCOLOR_ACP)) {
                $boColor    = strtoupper(PERSONALBOX_WEATHER_BOCOLOR_ACP);
            } else {
                $boColor    = $bgColor;
            }
            // Textfarbe fuer Donnerwetter...
            if(preg_match("/^[a-f0-9]{6}$/i",PERSONALBOX_WEATHER_TEXTCOLOR_ACP)) {
                $textColor  = strtoupper(PERSONALBOX_WEATHER_TEXTCOLOR_ACP);
            } else {
                $textColor  = strtoupper(WBBCore::getStyle()->getVariable('container1.font.color'));
                $textColor  = preg_replace("/\#/","",$textColor);
                if(strlen($textColor) < 6 && strlen($textColor) > 0) $textColor = str_pad($textColor,6,substr($textColor,-1,1));
            }
            // Standardfarben, falls bis hierhin keine zugeordnet...
            if(empty($bgColor))     $bgColor    = 'FFFFFF';
            if(empty($boColor))     $boColor    = 'FFFFFF';
            if(empty($textColor))   $textColor  = '000000';



            $boardIDs   = Board::getAccessibleBoards();
            $user       = new UserProfile(WCF::getUser()->userID);

            // RANK
            if($user->rankImage) {
                if(PERSONALBOX_REPEATRANKIMAGE_ACP && $user->repeatImage) {
                    $pbRankImage = '';
                    for($i=0;$i<$user->repeatImage;$i++) $pbRankImage .= '<img src="'.RELATIVE_WCF_DIR.$user->rankImage.'" alt="" title="'.WCF::getLanguage()->get($user->rankTitle).'" />';
                } else {
                    $pbRankImage = '<img src="'.RELATIVE_WCF_DIR.$user->rankImage.'" alt="" title="'.WCF::getLanguage()->get($user->rankTitle).'" />';
                }
            }

            $user->username         = StringUtil::encodeHTML(WCF::getUser()->username);
            $user->searchTime       = $searchTime;
            $user->bgColor          = $bgColor;
            $user->boColor          = $boColor;
            $user->textColor        = $textColor;
            $user->posts            = 0;
            $user->cntNewPosts      = 0;
            $user->cntLastPosts     = 0;
            $user->cntReported      = 0;
            $user->cntSub           = 0;

            if(WCF::getUser()->getPermission('user.profile.personalbox.cntOwnPosts')) {
                // Anzahl Postings...
                $sql = "SELECT wbu.posts"
                    ."\n  FROM wbb".WBB_N."_user wbu"
                    ."\n WHERE wbu.userid = ".WCF::getUser()->userID;
                $result = WBBCore::getDB()->getFirstRow($sql);
                $user->posts = StringUtil::formatInteger($result['posts']);
            }
            // Instant Messenger by Tatzelwurm
            if(INSTANTMESSENGER_AKTIV
            &&(WCF::getUser()->getPermission('user.board.instantmessenger.canUseInstantMessenger') || WCF::getUser()->getPermission('user.instantmessenger.canUseInstantMessenger'))) {
                if(@require_once(WCF_DIR.'lib/data/InstantMessage/IM.class.php')) {
                    if(WCF::getUser()->getPermission('user.profile.personalbox.canSetIM') && WCF::getUser()->personalbox_show_im == 'enabled') $pbShowIM = true;
                    else if(WCF::getUser()->getPermission('user.profile.personalbox.canSetIM') && WCF::getUser()->personalbox_show_im == 'disabled') $pbShowIM = false;
                    else $pbShowIM = PERSONALBOX_SHOW_IM_ACP;
                    $imcount = IM::countNewIM();
                }
            }

            // userOnlineMarking...
            if(PERSONALBOX_SHOWUSERMARKING_ACP) {
                $sql = "SELECT wcg.userOnlineMarking"
                    ."\n  FROM wcf".WCF_N."_group wcg"
                    ."\n  JOIN wcf".WCF_N."_user wcu ON (wcu.userOnlineGroupID = wcg.groupID)"
                    ."\n WHERE wcu.userID = ".WCF::getUser()->userID;
                $result = WBBCore::getDB()->getFirstRow($sql);
                $userOnlineMarking = $result['userOnlineMarking'];
                if($userOnlineMarking && $userOnlineMarking != '%s') $user->username = sprintf($userOnlineMarking, StringUtil::encodeHTML(WCF::getUser()->username));
            }

            // neue Beitraege seit letztem Besuch und n Tagen, Abonnements...
            if($pbShowSearch) {
                if(WCF::getUser()->getPermission('user.profile.personalbox.cntCurPosts')) {
                    $sql = "SELECT COUNT(*) cntNewPosts"
                        ."\n  FROM wbb".WBB_N."_thread wbt"
                        ."\n WHERE wbt.boardID IN (0".$boardIDs.")"
                        ."\n   AND wbt.lastPostTime >= ".WCF::getUser()->boardLastActivityTime;
                    $result = WBBCore::getDB()->getFirstRow($sql);
                    $user->cntNewPosts = $result['cntNewPosts'];
                }
                if(WCF::getUser()->getPermission('user.profile.personalbox.cntLastPosts')) {
                    $sql = "SELECT COUNT(*) cntLastPosts"
                        ."\n  FROM wbb".WBB_N."_thread wbt"
                        ."\n WHERE wbt.boardID IN (0".$boardIDs.")"
                        ."\n   AND wbt.lastPostTime >= ".$searchTime;
                    $result = WBBCore::getDB()->getFirstRow($sql);
                    $user->cntLastPosts = $result['cntLastPosts'];
                }
                if(WCF::getUser()->getPermission('user.profile.personalbox.cntSubscriptions')) {
                    $sql = "SELECT COUNT(*) AS newSubscriptions"
                        ."\n  FROM wbb".WBB_N."_thread_subscription subscription"
                        ."\n  JOIN wbb".WBB_N."_thread thread ON (thread.threadID = subscription.threadID)"
                        ."\n  JOIN	wbb".WBB_N."_thread_visit thread_visit ON (thread_visit.threadID = subscription.threadID AND subscription.userID = thread_visit.userID)"
                        ."\n  JOIN	wbb".WBB_N."_board_visit board_visit ON (board_visit.boardID = thread.boardID AND subscription.userID = board_visit.userID)"
                        ."\n WHERE subscription.userID = ".WCF::getUser()->userID
                        ."\n   AND thread_visit.lastVisitTime < thread.lastPostTime"
                        ."\n   AND (thread_visit.lastVisitTime > board_visit.lastVisitTime"
                        ."\n    OR board_visit.lastVisitTime < thread.lastPostTime"
                        ."\n    OR board_visit.lastVisitTime IS NULL)"
                        ."\n GROUP BY subscription.userID";
                    $result = WBBCore::getDB()->getFirstRow($sql);
                    $user->cntSub = intval($result['newSubscriptions']);
                }
            }

            // Moderation...
            if($pbShowUserCP && (WCF::getUser()->getPermission('admin.general.canUseAcp')
            || WCF::getUser()->getPermission('mod.board.canDeleteThreadCompletely')
            || WCF::getUser()->getPermission('mod.board.canDeletePostCompletely')
            || WCF::getUser()->getPermission('mod.board.canEnablePost')
            || WCF::getUser()->getPermission('mod.board.canEnableThread')
            )) {
                if(WCF::getUser()->getPermission('admin.general.canUseAcp')
                || WCF::getUser()->getPermission('mod.board.canEnablePost')
                || WCF::getUser()->getPermission('mod.board.canEnableThread')
                ) {
                    $sql = "SELECT COUNT(*) cntReported"
                        ."\n  FROM wbb".WBB_N."_post_report wbr";
                    $result = WBBCore::getDB()->getFirstRow($sql);
                    $user->cntReported = $result['cntReported'];
                }

                if(WCF::getUser()->getPermission('admin.general.canUseAcp')
                || WCF::getUser()->getPermission('mod.board.canDeleteThreadCompletely')
                || WCF::getUser()->getPermission('mod.board.canDeletePostCompletely')
                ) {
                    $sql = "SELECT COUNT(*) cntTrash"
                        ."\n  FROM wbb".WBB_N."_post wbp"
                        ."\n  JOIN wbb".WBB_N."_thread wbt ON (wbt.threadID = wbp.threadID)"
                        ."\n WHERE wbt.boardID IN (0".$boardIDs.")"
                        ."\n   AND (wbp.isDeleted > 0 OR wbt.isDeleted > 0)";
                    $result = WBBCore::getDB()->getFirstRow($sql);
                    $user->cntTrash = $result['cntTrash'];
                }
            }

            // Guestbook
            $user->cntGB = 0;
            if(WCF::getUser()->getPermission('user.guestbook.canUseOwn')) {
                $sql = "SELECT entries, userLastVisit, lastEntry"
                    ."\n  FROM wcf".WCF_N."_user_guestbook_header"
                    ."\n WHERE userID = ".WCF::getUser()->userID;
                $result = WBBCore::getDB()->getFirstRow($sql);
                $user->cntGB = (empty($result['entries'])?0:$result['entries']);
                if(!empty($result['lastEntry']) && $result['lastEntry'] > $result['userLastVisit']) $user->newGB = true;
                else $user->newGB = false;
            }
            $this->BoxData['user'] = $user;
        }

        // Template Variablen zuordnen...
        WCF::getTPL()->assign(array(
            'pbCatVertOffset' => intval(PERSONALBOX_CATSPACER_ACP),
            'pbFirstBoxColor' => intval(PERSONALBOX_FBCOLOR_ACP),
            'pbSecondBoxColor' => intval(PERSONALBOX_SBCOLOR_ACP),
            'pbFirstColWidth' => intval(PERSONALBOX_FIRSTCOLWIDTH_ACP),
            'pbTableWidth' => PERSONALBOX_TABLEWIDTH_ACP,
            'pbCellPadding' => intval(PERSONALBOX_CELLPADDING_ACP),
            'pbLargeImages' => PERSONALBOX_LARGERANKIMAGES_ACP,
            'pbShowIP' => (WCF::getUser()->getPermission('user.profile.personalbox.showIP') && WBBCore::getSession()->ipAddress ? WBBCore::getSession()->ipAddress : 0),
            'pbStyleWidth' => PERSONALBOX_STYLEBOXWIDTH_ACP,
            'pbLineFeedRank' => PERSONALBOX_LINEFEEDRANK_ACP,
            'pbShowProfileLink' => PERSONALBOX_SHOWPROFILELINK_ACP,
            'pbShowDisplayLink' => PERSONALBOX_SHOWDISPLAYLINK_ACP,
            'pbRankImage' => $pbRankImage,
            'pbMaxHeight' => $pbMaxHeight,
            'pbShowAvatar' => $pbShowAvatar,
            'pbAvatarMaxWidth' => PERSONALBOX_AVATARMAXWIDTH_ACP,
            'pbAvatarMaxHeight' => PERSONALBOX_AVATARMAXHEIGHT_ACP,
            'pbShowPersonal' => $pbShowPersonal,
            'pbShowSearch' => $pbShowSearch,
            'pbShowPM' => $pbShowPM,
            'pbShowUserCP' => $pbShowUserCP,
            'pbShowStyles' => $pbShowStyles,
            'pbShowMisc' => $pbShowMisc,
            'pbShowWeather' => $pbShowWeather,
            'pbWeatherZipCode' => $pbWeatherZipCode,
            'pbWeatherComZipCode' => $pbWeatherComZipCode,
            'pbWeatherStyle' => $pbWeatherStyle,
            'pbWeatherComStyle' => $pbWeatherComStyle,
            'pbWeatherWidth' => $pbWeatherWidth,
            'pbShowWeatherCom' => $pbShowWeatherCom,
            'pbWeatherComDay' => $pbWeatherComDay,
            'pbLinks' => (isset($pbLinks) ? $pbLinks : array()),
            'pbStyles' => (isset($pbStyles) ? $pbStyles : array()),
            // Instant Messenger by Tatzelwurm
            'imcount' => $imcount,
            'pbShowIM' => $pbShowIM,
            'pbShowProfileHits' => PERSONALBOX_SHOW_PROFILEHITS_ACP,
            'boxCurPage' => $boxCurPage
        ));
	}

	protected function getBoxStatus($data) {
		// get box status
		$this->BoxData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->BoxData['Status'] = intval(WBBCore::getUser()->personalbox);
		}
		else {
			if (WBBCore::getSession()->getVar('personalbox') != false) {
				$this->BoxData['Status'] = WBBCore::getSession()->getVar('personalbox');
			}
		}
	}

	public function getData() {
		return $this->BoxData;
	}
}

?>
