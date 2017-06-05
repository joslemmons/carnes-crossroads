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

    const TAX_COMMUNITY_AMENITY = 'community_amenity';
    const TAX_NEIGHBORHOOD = 'neighborhood';
    const TAX_PARK_LAKE = 'park_lake';
    const TAX_TOWN = 'town';
    const TAX_REAL_ESTATE = 'real_estate';

    public static function getPostType()
    {
        return self::$_postType;
    }

    public static function getClass() {
        return get_class();
    }

    public static function bootstrap()
    {
        self::$_postType = 'place_of_interest';
        self::$_title = 'Place of Interest';
        self::$_taxonomy = 'place_of_interest_t';

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
        wp_insert_term('Community Amenities', self::$_taxonomy, array(
            'slug' => self::TAX_COMMUNITY_AMENITY
        ));
        wp_insert_term('Neighborhoods', self::$_taxonomy, array(
            'slug' => self::TAX_NEIGHBORHOOD
        ));
        wp_insert_term('Parks & Lakes', self::$_taxonomy, array(
            'slug' => self::TAX_PARK_LAKE
        ));
        wp_insert_term('Towns', self::$_taxonomy, array(
            'slug' => self::TAX_TOWN
        ));
        wp_insert_term('Real Estate', self::$_taxonomy, array(
            'slug' => self::TAX_REAL_ESTATE
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
        $categories = $this->get_terms('place_of_interest_t');
        $cat = end($categories);
        return $cat->slug;
    }
}