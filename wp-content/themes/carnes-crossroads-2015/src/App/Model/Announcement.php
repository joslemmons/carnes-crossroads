<?php namespace App\Model;

class Announcement extends \TimberPost
{
    private static $_postType;
    private static $_title;
    
    public static $field_expire_date;

    public static function getPostType()
    {
        return self::$_postType;
    }

    public static function bootstrap()
    {
        self::$_postType = 'announcement';
        self::$_title = 'Announcements';      
		
		self::$field_expire_date = Config::getKeyPrefix() . 'expire_date';
		
        add_filter('piklist_post_types', array(__CLASS__, '_registerCPT'));
        
    }

    public static function _registerCPT($post_types)
    {

        $post_types[self::$_postType] = array(
            'labels' => piklist('post_type_labels', self::$_title),
            'title' => 'Title',
            'public' => true,
            'rewrite' => array(
                'slug' => 'residents/announcements'
            ),
            'supports' => array(
                'title',
                'revisions',
                'editor',
                'thumbnail',                
            ),
            'hide_meta_box' => array(
                //'slug',
                'author',
                'revisions',
                'comments',
                'commentstatus'
            ),
            'taxonomies' => array('post_tag')
        );

        return $post_types;
    }

    public function getExpireDate()
    {
        $field = self::$field_expire_date;
        return $this->$field;
    }

	
	public static function get($featured = false, $posts_per_page = -1,$date = '') {
	
		$args = array(
            'post_type' => self::$_postType,
            'posts_per_page' => $posts_per_page,
            'post_status' => array('publish'),  
            'meta_query' => array(
	            'relation' => 'OR',
		        array(
		            'relation' => 'AND',
		            array(
			            'key' => self::$field_expire_date,
			            'value' => '',
			            'compare' => '!='
		            ),
		            array(
		            	'key'     => self::$field_expire_date,
		            	'value'   => date("Y-m-d"),
		            	'compare' => '>='
		            )
		        ),
		        array(
		            'key' => self::$field_expire_date,
		            'value' => '',
		            'compare' => '=',
		            
		        )	           	            
	        ),        
        );
        
        if ($featured) {
	        $args['tag'] = 'featured';
        }
        
        if (!empty($date)) {
	        list($year,$month) = explode('-', $date);	        
	        $args['year'] = $year;
	        $args['monthnum'] = intval($month);
        }
        	        
	    $posts = \Timber::get_posts($args, get_class());

        return $posts;

    }
    
    public static function getArchiveNav () {
	    
	    $announcements = self::get(); // get all unexpired
	    
	    $links = array();
	    
	    foreach ($announcements as $ann) {
		    $dtime = strtotime($ann->post_date);
		    $stub = date("Y-m",$dtime);
		    $key = date("F Y",$dtime);
			$links[$key] = $stub;    
	    }
	    
	    return $links;
	    
    }
    
}
