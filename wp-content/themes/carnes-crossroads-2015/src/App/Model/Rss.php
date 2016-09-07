<?php namespace App\Model;

class Rss
{
	
	private static $ns_name;
	private static $ns_url;
	
    public static function init()
    {
    	self::$ns_name = 'media';
    	self::$ns_url = 'http://search.yahoo.com/mrss';
    	   
        add_action('rss2_ns', function () {
		    echo 'xmlns:'.self::$ns_name.'="'.self::$ns_url.'"'."\n";
		});
		
		add_action('rss2_item', array(get_class(),'_cx_rss2_item' ));
        
    }

    public static function _cx_rss2_item() {
	    
	    $post_id = get_the_ID();
	    if(get_the_post_thumbnail($post_id)) {
		    echo "<media:content url=\"".wp_get_attachment_url(get_post_thumbnail_id($post_id))."\" medium=\"image\" />";
	    }
   
	}
	
}
