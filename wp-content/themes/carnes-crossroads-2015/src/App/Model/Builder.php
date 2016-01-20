<?php namespace App\Model;

class Builder extends Page
{
    public static $postType;
    public static $labelTitle;

    public static function bootstrap()
    {
        self::$postType = 'builder';
        self::$labelTitle = 'Builder';

        add_filter('piklist_post_types', array(get_class(), 'registerCPT'));
    }

    public static function registerCPT($post_types)
    {
        $post_types[self::$postType] = array(
            'labels' => piklist('post_type_labels', self::$labelTitle),
            'title' => __('Title of Builder'),
            'public' => true,
            'menu_icon' => 'dashicons-store',
            'rewrite' => array(
                'slug' => 'builder'
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

    public function getGallery()
    {
        $data = array();

        $data[] = array(
            'image' => $this->thumbnail()
        );

        return $data;
    }
}