<?php

interface MarkableLostAndFoundItem {
	
	public function mark();
	
	public function unmark();
	
	public function isMarked();
	
	public static function unmarkAll($itemName);
	
}
?>