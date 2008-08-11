<?php

class SimplePieNewsreaderBox{
	protected $spnrbData = array();

	public function __construct($data, $boxname = ""){
		$this->spnrbData['templatename'] = "simplePieNewsreaderBox";
		$this->getBoxStatus($data);
		$this->spnrbData['boxID'] = $data['boxID'];
		if(SPNRBOX_BOXOPENED == true) $this->spnrbData['Status'] = 1;

        if(WBBCore::getUser()->getPermission('user.board.canViewSimplePieNewsreaderBox')){
            require_once(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/simplepie.inc');
            require_once(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/idna_convert.class.php');

            $bookmarks = array();
            $feed = new SimplePie();
            if(SPNRBOX_CACHEMAX != 0 && SPNRBOX_CACHEMIN != 0){
                $feed->set_autodiscovery_cache_duration(SPNRBOX_CACHEMAX);
                $feed->set_cache_duration(SPNRBOX_CACHEMIN);
            }
            else{
                $feed->set_autodiscovery_cache_duration(9999999999);
                $feed->set_cache_duration(9999999999);
            }
            $feed->set_cache_location(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/cache');
            $feed->set_favicon_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');
            $feed->set_image_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');

            if(SPNRBOX_SHOWSOCIALBOOKMARKS){
                $socialBookmarks = preg_split("/\r?\n/", SPNRBOX_SOCIALBOOKMARKS);
                $cntBookmark = 0;
                foreach($socialBookmarks as $row){
                    $row = trim($row);
                    if(preg_match("/\|/", $row)) {
                        list($bookmarkTitle,$bookmarkUrl,$bookmarkImg,$bookmarkEncodeTitle,$bookmarkEncodeUrl) = preg_split("/\|/", $row, 5);
                        $bookmarkTitle = trim($bookmarkTitle);
                        $bookmarkUrl = trim($bookmarkUrl);
                        $bookmarkImg = trim($bookmarkImg);
                        $bookmarkEncodeTitle = trim($bookmarkEncodeTitle);
                        $bookmarkEncodeUrl = trim($bookmarkEncodeUrl);
                        if(!empty($bookmarkTitle) && !empty($bookmarkUrl) && !empty($bookmarkImg) && isset($bookmarkEncodeTitle) && isset($bookmarkEncodeUrl)){
                            $bookmarks[$cntBookmark]['bookmarkTitle']       = $bookmarkTitle;
                            $bookmarks[$cntBookmark]['bookmarkUrl']         = $bookmarkUrl;
                            $bookmarks[$cntBookmark]['bookmarkImg']         = $bookmarkImg;
                            $bookmarks[$cntBookmark]['bookmarkEncodeTitle'] = ($bookmarkEncodeTitle == 1) ? 1 : 0;
                            $bookmarks[$cntBookmark]['bookmarkEncodeUrl']   = ($bookmarkEncodeUrl == 1)   ? 1 : 0;
                            $cntBookmark++;
                        }
                    }
                }
            }

            if(WCF::getUser()->getPermission('user.board.canViewThreadToFeed') && SPNRBOX_FEEDTOTHREAD){
                require_once(WBB_DIR.'lib/data/board/Board.class.php');
                $accessibleBoards   = explode(',', Board::getAccessibleBoards());
                $selectiveBoards    = explode(',', SPNRBOX_FEEDTOTHREADBOARDID);
                $boardStructur      = WCF::getCache()->get('board', 'boardStructure');
                if(count($selectiveBoards) != 0) {
                    $this->spnrbData['boardsForm'] = (count($selectiveBoards) == 1) ? 'button' : 'list';
                    $cntBoards = 0;
                    $prefix = '';
                    foreach($selectiveBoards as $k => $v){
                        $tmp = Board::getBoard($v);
                        if($tmp->boardType < 2 && in_array($v, $accessibleBoards)){
                            $this->spnrbData['boards'][$cntBoards]['id']    = $tmp->boardID;
                            $this->spnrbData['boards'][$cntBoards]['type']  = $tmp->boardType;
                            $prefix = '';
                            foreach($boardStructur as $boardDepth => $boardKey){
                                if(in_array($this->spnrbData['boards'][$cntBoards]['id'], $boardKey)){
                                    $prefix = str_repeat('--', $boardDepth);
                                    break;
                                }
                            }
                            $this->spnrbData['boards'][$cntBoards]['title'] = (($prefix != '') ? $prefix : ''). ' ' . $tmp->title;
                            $cntBoards++;
                        }
                    }
                }
                else{
                    $this->spnrbData['boardsForm'] = '';
                }
            }

            $feedUrls = preg_split('/\r?\n/', SPNRBOX_FEEDS);
            $cntFeedUrl = 0;
            foreach($feedUrls as $k => $feedurl){
                $feedurl = trim($feedurl);
                if(empty($feedurl)) continue;
                $feed->set_feed_url($feedurl);
                $feed->set_output_encoding(@CHARSET);
                $feed->init();
                $feed->handle_content_type();
                if(!$favicon = $feed->get_favicon()) $favicon = RELATIVE_WBB_DIR.'icon/alternate_favicon.png';
                $this->spnrbData['spnrFeeds'][$cntFeedUrl]['id']        = $cntFeedUrl;
                $this->spnrbData['spnrFeeds'][$cntFeedUrl]['link']      = $feed->get_permalink();
                $this->spnrbData['spnrFeeds'][$cntFeedUrl]['title']     = $feed->get_title();
                $this->spnrbData['spnrFeeds'][$cntFeedUrl]['favicon']   = $favicon;
                $this->spnrbData['spnrFeeds'][$cntFeedUrl]['xml']       = $feedurl;
                $i = 0;
                foreach($feed->get_items() as $item){
                    if($i >= SPNRBOX_NUMOFFEEDS) break;
                    $iFeed = $item->get_feed();
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['id']           = $i;
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['link']         = $item->get_permalink();
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['title']        = html_entity_decode($item->get_title(), ENT_QUOTES, @CHARSET);
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['content']      = $item->get_content();
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['date']         = $item->get_date('d.m.Y - H:i:s');
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['bookmarks']    = array();
                    if(count($bookmarks)){
                        $x = 0;
                        foreach($bookmarks as $bookmark){
                            $search[0] = "/\{TITLE\}/";
                            $search[1] = "/\{URL\}/";
                            $replace[0] = ($bookmark['bookmarkEncodeTitle'] == 1)   ? rawurlencode(html_entity_decode($item->get_title(), ENT_QUOTES, @CHARSET))       : html_entity_decode($item->get_title());
                            $replace[1] = ($bookmark['bookmarkEncodeUrl'] == 1)     ? rawurlencode(html_entity_decode($item->get_permalink(), ENT_QUOTES, @CHARSET))   : html_entity_decode($item->get_permalink());
                            $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['bookmarks'][$x]['bookmarkTitle']   = htmlspecialchars($bookmark['bookmarkTitle']);
                            $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['bookmarks'][$x]['bookmarkUrl']     = preg_replace($search, $replace, html_entity_decode($bookmark['bookmarkUrl']));
                            $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['bookmarks'][$x]['bookmarkImg']     = RELATIVE_WBB_DIR."icon/".$bookmark['bookmarkImg'];
                            $x++;
                        }
                    }
                    if($enclosure = $item->get_enclosure()){
                        $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['enclosure'] = '<p>'
                            .$enclosure->native_embed(array(
                                'audio' => RELATIVE_WBB_DIR.'icon/place_audio.png',
                                'video' => RELATIVE_WBB_DIR.'icon/place_video.png',
                                'mediaplayer' => RELATIVE_WBB_DIR.'icon/mediaplayer.swf',
                                'alt' => '<img src="'.RELATIVE_WBB_DIR.'icon/mini_podcast.png" class="download" border="0" title="Download Podcast (' . $enclosure->get_extension() . '; ' . $enclosure->get_size() . ' MB)" />',
                                'altclass' => 'download'
                        ))
                            .'</p>';
                    }
                    $i++;
                }
                $cntFeedUrl++;
            }
        }
	}



	protected function getBoxStatus($data){
		// get box status
		$this->spnrbData['Status'] = 1;
		if (WBBCore::getUser()->userID){
			$this->spnrbData['Status'] = intval(WBBCore::getUser()->simplePieNewsreaderBox);
		}
		else {
			if (WBBCore::getSession()->getVar('simplePieNewsreaderBox') != false){
				$this->spnrbData['Status'] = WBBCore::getSession()->getVar('simplePieNewsreaderBox');
			}
		}
	}

	public function getData(){
		return $this->spnrbData;
	}
}

?>
