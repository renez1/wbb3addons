<?php
/********************************************************************
include('lib/data/boxes/SimplePieNewsReader.php');
********************************************************************/

// Include the SimplePie library, and the one that handles internationalized domain names.
require_once(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/simplepie.inc');
require_once(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/idna_convert.class.php');

$feed_url = array(
	'http://wbb3addons.ump2002.net/wbb/index.php?page=Feed&type=Atom',
	'http://www.woltlab.com/forum/index.php?page=Feed&type=Atom',
	'http://www.wbb3plugin.de/index.php?page=Feed&type=Atom',
	'http://youtube.com/rss/global/top_rated.rss'
);

?>
<link rel="stylesheet" type="text/css" media="screen" href="style/SimplePieNewsReader.css" />

<?php

$x = 0;
echo "		<div class=\"container-1\">\n";
echo "			<ul id=\"feedReader\">\n";

$feed = new SimplePie();
/**
 * @var int Auto-discovery cache duration (in seconds)
 * @see SimplePie::set_autodiscovery_cache_duration()
 * @access private
 */
$feed->set_autodiscovery_cache_duration(604800);
/**
 * @var int Cache duration (in seconds)
 * @see SimplePie::set_cache_duration()
 * @access private
 */
$feed->set_cache_duration(3600);

/**
 * @var string Cache location (relative to executing script)
 * @see SimplePie::set_cache_location()
 * @access private
 */
$feed->set_cache_location(WBB_DIR.'lib/data/boxes/SimplePieNewsReader/cache');

// When we set these, we need to make sure that the handler_image.php file is also trying to read from the same cache directory that we are.
$feed->set_favicon_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');
$feed->set_image_handler(RELATIVE_WBB_DIR.'lib/data/boxes/SimplePieNewsReader/handler_image.php');

foreach($feed_url as $feedurl){
	$x++;
	// Initialize some feeds for use.
	$feed->set_feed_url($feedurl);
	// Initialize the feed.
	$feed->init();

	// Make sure the page is being served with the UTF-8 headers.
	$feed->handle_content_type();
	// Let's add a favicon for each item. If one doesn't exist, we'll use an alternate one.
	if(!$favicon = $feed->get_favicon()) $favicon = RELATIVE_WBB_DIR.'icon/alternate_favicon.png';

	echo '<li>';
		echo ' <h4 class="feedReaderTitle"><a href="javascript: void(0)" onclick="openList(\'feedReaderSubTitle'.$x.'\', true)" class="feedReaderListSubTitle"><img src="'.RELATIVE_WCF_DIR.'icon/minusS.png" style="margin: 0 0 2px;" id="feedReaderSubTitle'.$x.'Image" alt="" /></a>';
		echo ' <a href="'.$feed->get_permalink().'" style="background-image:url('.$favicon.');" class="feedReaderTitleURL">'.$feed->get_title().'</a></h4>';
		echo ' <ul id="feedReaderSubTitle'.$x.'">';


    // Let's loop through each item in the feed.
    $y = 0;
    foreach($feed->get_items() as $item){
    if(5 <= $y){
    	break;
    }
    $y++;
    if(!$favicon = $feed->get_favicon()) $favicon = RELATIVE_WBB_DIR.'icon/alternate_favicon.png';
    // Let's give ourselves a reference to the parent $feed object for this particular item.
    $feed = $item->get_feed();
    
        echo '<li>';
    		echo '<h5 class="feedReaderSubTitle"><a href="javascript: void(0)" onclick="openList(\'feedReaderContent'.$x.'_'.$y.'\', true)" class="feedReaderListContent"><img src="'.RELATIVE_WCF_DIR.'icon/minusS.png" style="margin: 0 0 2px;" id="feedReaderContent'.$x.'_'.$y.'Image" alt="" /></a>';
    		echo ' <a href="'.$item->get_permalink().'" class="feedReaderSubTitleURL">'.html_entity_decode($item->get_title(), ENT_QUOTES, 'UTF-8').'</a></h5>';
    		echo ' <div id="feedReaderContent'.$x.'_'.$y.'">';
        echo $item->get_content();
    
    if ($enclosure = $item->get_enclosure()) {
    echo '<p>';
    echo $enclosure->native_embed(array(
    	// New 'mediaplayer' attribute shows off Flash-based MP3 and FLV playback.
    	'audio' => RELATIVE_WBB_DIR.'icon/place_audio.png',
    	'video' => RELATIVE_WBB_DIR.'icon/place_video.png',
    	'mediaplayer' => RELATIVE_WBB_DIR.'icon/mediaplayer.swf',
    	'alt' => '<img src="'.RELATIVE_WBB_DIR.'icon/mini_podcast.png" class="download" border="0" title="Download Podcast (' . $enclosure->get_extension() . '; ' . $enclosure->get_size() . ' MB)" />',
    	'altclass' => 'download'
    ));
    ?>
    								</p>
    <?php } ?>
    								<p class="feedReaderFooter">Feed vom <?php echo $item->get_date('d.m.Y - H:i:s'); ?> Uhr</p>
    							</div>
    							<script type="text/javascript">
    							//<![CDATA[
    							initList('feedReaderContent<?php echo $x . '_' . $y; ?>', 0);
    							//]]>
    							</script>
    						</li>
    	<?php
    	}
    	?>
    					</ul>
    					<script type="text/javascript">
    					//<![CDATA[
    					initList('feedReaderSubTitle<?php echo $x; ?>', 0);
    					//]]>
    					</script> 
    				</li>
    <?php
}
?>
			</ul>
		</div>
