<?php namespace App\Model;

class RealEstateAgent extends \TimberPost
{
    public static $postType;
    public static $labelTitle;

    public static function bootstrap()
    {
        self::$postType = 'agent';
        self::$labelTitle = 'Agent';

        add_filter('piklist_post_types', array(get_class(), 'registerCPT'));
    }

    public static function registerCPT($post_types)
    {
        $post_types[self::$postType] = array(
            'labels' => piklist('post_type_labels', self::$labelTitle),
            'title' => __('Name of Agent'),
            'public' => true,
            'menu_icon' => 'dashicons-businessman',
            'rewrite' => array(
                'slug' => '/homes/sales-team/agent',
                'with_front' => true
            ),
            'hierarchical' => true,
            'supports' => array(
                'title',
                'editor',
                'thumbnail'
            ),
            'hide_meta_box' => array(
                'author',
                'comments',
                'commentstatus',
            )
        );

        return $post_types;
    }

    public static function all()
    {
        return \Timber::get_posts(array(
            'posts_per_page' => -1,
            'post_type' => self::$postType,
            'orderby' => 'menu_order'
        ), get_class());
    }

    public function getGalleryImages()
    {
        $data = array();

        $data[] = $this->thumbnail();

        return $data;
    }
}