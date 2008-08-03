<?php
class SimplePieNewsreaderBox {
	protected $spnrbData = array();

	public function __construct($data, $boxname = "") {
		$this->spnrbData['templatename'] = "simplePieNewsreaderBox";
		$this->getBoxStatus($data);
		$this->spnrbData['boxID'] = $data['boxID'];
		if(SPNRBOX_BOXOPENED == true) $this->spnrbData['Status'] = 1;

        if(WBBCore::getUser()->getPermission('user.board.canViewSimplePieNewsreaderBox')) {
            require_once(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/simplepie.inc');
            require_once(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/idna_convert.class.php');

            $feed = new SimplePie();
            $feed->set_autodiscovery_cache_duration(SPNRBOX_CACHEMAX);
            $feed->set_cache_duration(SPNRBOX_CACHEMIN);
            $feed->set_cache_location(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/cache');
            $feed->set_favicon_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');
            $feed->set_image_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');

            $feedUrls = preg_split('/\r?\n/', SPNRBOX_FEEDS);
            $cntFeedUrl = 0;
            foreach($feedUrls as $k => $feedurl) {
                $feedurl = trim($feedurl);
                if(empty($feedurl)) continue;
                $feed->set_feed_url($feedurl);
                $feed->init();
                $feed->handle_content_type();
                if(!$favicon = $feed->get_favicon()) $favicon = RELATIVE_WBB_DIR.'icon/alternate_favicon.png';
                $this->spnrbData['spnrFeeds'][$cntFeedUrl]['id']        = $cntFeedUrl;
                $this->spnrbData['spnrFeeds'][$cntFeedUrl]['link']      = $feed->get_permalink();
                $this->spnrbData['spnrFeeds'][$cntFeedUrl]['title']     = $feed->get_title();
                $this->spnrbData['spnrFeeds'][$cntFeedUrl]['favicon']   = $favicon;
                $i = 0;
                foreach($feed->get_items() as $item) {
                    if($i >= SPNRBOX_NUMOFFEEDS) break;
                    $iFeed = $item->get_feed();
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['id']       = $i;
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['link']     = $item->get_permalink();
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['title']    = html_entity_decode($item->get_title(), ENT_QUOTES, 'UTF-8');
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['content']  = $item->get_content();
                    $this->spnrbData['spnrFeeds'][$cntFeedUrl]['iFeed'][$i]['date']     = $item->get_date('d.m.Y - H:i:s');
                    if($enclosure = $item->get_enclosure()) {
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

	protected function getBoxStatus($data) {
		// get box status
		$this->spnrbData['Status'] = 1;
		if (WBBCore::getUser()->userID) {
			$this->spnrbData['Status'] = intval(WBBCore::getUser()->simplePieNewsreaderBox);
		}
		else {
			if (WBBCore::getSession()->getVar('simplePieNewsreaderBox') != false) {
				$this->spnrbData['Status'] = WBBCore::getSession()->getVar('simplePieNewsreaderBox');
			}
		}
	}

	public function getData() {
		return $this->spnrbData;
	}
}

?>
