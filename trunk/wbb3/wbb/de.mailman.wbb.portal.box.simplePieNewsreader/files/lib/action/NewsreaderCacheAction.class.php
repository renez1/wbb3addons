<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/simplepie.inc');

class NewsreaderCacheAction extends AbstractAction {
    public $action = '0';
    public function readParameters() {
        parent::readParameters();
        if (isset($_REQUEST['action'])) $this->action = $_REQUEST['action'];
    }
    public function execute() {
		parent::execute();
        if ($this->action == 'NewsreaderCache') {
            $urls = preg_split('/\r?\n/', SPNRBOX_FEEDS);
            $cache_location = WBB_DIR.'lib/data/boxes/SimplePieNewsReader/cache';
            
            // CHARSET
            if(!defined('CHARSET'))         define('CHARSET', 'UTF-8');
            if(!defined('SPNRBOX_CHARSET')) define('SPNRBOX_CHARSET', 'UTF-8');
            if(SPNRBOX_CHARSET == 'default') $charset = CHARSET;
            else $charset = SPNRBOX_CHARSET;
            
            // FILTER?
            if(SPNRBOX_FILTER && (strlen(SPNRBOX_FILTERWORDS) >= 3)){
                require_once(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/simplepie_filter.php');
                $feed = new SimplePie_Filter();
                if(!defined('SPNRBOX_FILTERCLASS')) define('SPNRBOX_FILTERCLASS', 'hightlight');
                define('SPNRBOX_FILTERON', 1);
            }
            else {
                $feed = new SimplePie();
                define('SPNRBOX_FILTERON', 0);
            }

            $feed->set_feed_url($urls);
            $feed->set_cache_location($cache_location);
            $feed->set_autodiscovery_cache_duration(0);
            $feed->set_cache_duration(0);
            $feed->set_favicon_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');
            $feed->set_image_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');
            $feed->set_output_encoding($charset);
            $feed->set_timeout(10);
            $feed->init();
            $feed->handle_content_type();
            if(SPNRBOX_FILTERON) $feed->set_filter(SPNRBOX_FILTERWORDS, SPNRBOX_FILTERMODE);
            header("Content-type: text/plain; charset=UTF-8");
            foreach($urls as $feeds){
                $feeds = trim($feeds);
                if(empty($feeds)) continue;
                $feed->set_feed_url($feeds);
                $feed->init();
                $items = $feed->get_items();
                if(SPNRBOX_FILTERON) $items = $feed->filter($items);
                echo $feed->get_title() . "\n";
                if(!count($items)){
                    echo "\tKeine Feeds gefunden.\n";
                }
                else{
                    $i = 0;
                    foreach($items as $item) {
                        if($i >= SPNRBOX_NUMOFFEEDS) break;
                        (SPNRBOX_FILTERON) ? $this->highlight(SPNRBOX_FILTERWORDS, $item->get_content(), SPNRBOX_FILTERCLASS) : $item->get_content();
                        echo "\t\"" . $item->get_title() . "\" -> wurde geladen.\n";
                        $i++;
                    }
                }
            }
        }
    }
    protected function highlight($wordsToHighlight, $text, $className){
        $w = addslashes($wordsToHighlight);
        $w = explode(' ',$w);
        foreach($w as $word){
            $text = preg_replace("/((<[^>]*)|$word)/ie", '"\2"=="\1"? "\1":"<span class=\"$className\">\1</span>"', $text);
        }
        return $text;
    }
}
?>
