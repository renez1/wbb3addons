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
            $feed = new SimplePie();
            $feed->set_feed_url($urls);
            $feed->set_cache_location($cache_location);
            $feed->set_autodiscovery_cache_duration(0);
            $feed->set_cache_duration(0);
            $feed->set_favicon_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');
            $feed->set_image_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');
            $feed->set_timeout(10);
            $feed->init();
            $feed->handle_content_type();
            header("Content-type: text/plain; charset=UTF-8");
            foreach($urls as $feeds){
                $feeds = trim($feeds);
                if(empty($feeds)) continue;
                $feed->set_feed_url($feeds);
                $feed->init();
                echo $feed->get_title() . "\n";
                $i = 0;
                foreach($feed->get_items() as $item) {
                    if($i >= SPNRBOX_NUMOFFEEDS) break;
                    $item->get_content();
                    echo "\t\"" . $item->get_title() . "\" -> wurde geladen.\n";
                    $i++;
                }
            }
        }
    }
}
?>
