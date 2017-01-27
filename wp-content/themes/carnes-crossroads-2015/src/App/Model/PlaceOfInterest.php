<?php
/**
 * Created by PhpStorm.
 * User: jcolfer
 * Date: 1/27/17
 * Time: 9:51 AM
 */

namespace App\Model;

class PlaceOfInterest extends \TimberPost {
    public static $_postType;
    public static $_title;
    public static $_taxonomy;

    public static $field_address;
    public static $field_longitude;
    public static $field_latitude;
    public static $field_website_url;

    const TAX_SPORTS_FITNESS = 'sports-fitness';
    const TAX_COMMERCIAL = 'commercial';
    const TAX_SCHOOLS = 'schools';
    const TAX_CHURCHES = 'churches';
    const TAX_LIBRARIES = 'libraries';
    const TAX_PARKS_POOLS = 'parks-pools';
    const TAX_WATERWAYS = 'waterways';
    const TAX_GOLF = 'golf';

    public static function getPostType()
    {
        return self::$_postType;
    }

    public static function getClass() {
        return get_class();
    }

    public static function bootstrap()
    {
        self::$_postType = Config::getKeyPrefix() . 'place_of_interest';
        self::$_title = 'Place of Interest';
        self::$_taxonomy = 'place_of_interest_type';

        self::$field_address = 'address';
        self::$field_longitude = 'longitude';
        self::$field_latitude = 'latitude';
        self::$field_website_url = 'website_url';

        add_filter('piklist_post_types', array(__CLASS__, '_registerCPT'));
        add_filter('piklist_taxonomies', array(get_class(), 'registerCategories'));
        add_action('wp_loaded', array(get_class(), 'addTermsForTaxonomy'));
    }

    public static function registerCategories() {
        $taxonomies[] = array(
            'post_type' => self::$_postType,
            'name' => self::$_taxonomy,
            'show_admin_column' => true,
            //'hide_meta_box' => true,
            'configuration' => array(
                'hierarchical' => true,
                'labels' => piklist('taxonomy_labels', 'Location Types'),
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => array(
                    'slug' => self::$_taxonomy
                )
            ),
            'supports' => array(
                'title',
                'revisions',
                // 'editor',
                'thumbnail',
                //'tags'
            ),
            'hide_meta_box' => array(
                //'slug',
                'author',
                'revisions',
                'comments',
                'commentstatus'
            ),
        );

        return $taxonomies;
    }

    public static function _registerCPT($post_types)
    {
        $post_types[self::$_postType] = array(
            'labels' => piklist('post_type_labels', self::$_title),
            'title' => __('Title of Listing'),
            'public' => true,
            'menu_icon' => 'dashicons-store',
            'rewrite' => array(
                'slug' => 'place-of-interest'
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

    public static function addTermsForTaxonomy()
    {
        wp_insert_term('Sports & Fitness', self::$_taxonomy, array(
            'slug' => self::TAX_SPORTS_FITNESS
        ));
        wp_insert_term('Commercial', self::$_taxonomy, array(
            'slug' => self::TAX_COMMERCIAL
        ));
        wp_insert_term('Schools', self::$_taxonomy, array(
            'slug' => self::TAX_SCHOOLS
        ));
        wp_insert_term('Churches', self::$_taxonomy, array(
            'slug' => self::TAX_CHURCHES
        ));
        wp_insert_term('Libraries', self::$_taxonomy, array(
            'slug' => self::TAX_LIBRARIES
        ));
        wp_insert_term('Parks & Pools', self::$_taxonomy, array(
            'slug' => self::TAX_PARKS_POOLS
        ));
        wp_insert_term('Waterways', self::$_taxonomy, array(
            'slug' => self::TAX_WATERWAYS
        ));
        wp_insert_term('Golf', self::$_taxonomy, array(
            'slug' => self::TAX_GOLF
        ));
    }

    public static function all($posts_per_page = -1, $paged = 1)
    {
        $posts = \Timber::get_posts(array(
            'post_type' => self::$_postType,
            'paged' => $paged,
            'posts_per_page' => $posts_per_page,
            'post_status' => array('publish'),
        ), get_class());

        return $posts;
    }

    public function getCategory() {
        $categories = $this->get_terms('place_of_interest_type');
        $cat = end($categories);
        return $cat->slug;
    }
}