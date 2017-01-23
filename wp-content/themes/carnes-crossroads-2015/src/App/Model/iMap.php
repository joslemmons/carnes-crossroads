<?php namespace App\Model;

use HomeFinder\Model\User;

class iMap
{
    public static function enqueueAssets()
    {
        $current_user = User::getCurrentlyLoggedUser();

        wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, false);
        wp_enqueue_style('slick-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), false, false);
        wp_enqueue_style('slick-theme-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick-theme.css', array('slick-css'), false, false);
        wp_enqueue_script('waypoints-js', get_template_directory_uri() . '/js/lib/jquery.waypoints.min.js', array('jquery'), false, false);
        wp_enqueue_script('sticky-js', get_template_directory_uri() . '/js/lib/sticky.min.js', array('jquery'), false, false);
        wp_enqueue_script('primary-page-js', get_template_directory_uri() . '/js/primary-page.js', array('jquery', 'slick-js', 'waypoints-js', 'sticky-js', 'backbone'), Config::getAppVersion(), true);
        wp_localize_script('primary-page-js', 'DIRE', array(
            'isRealEstatePage' => 'true'
        ));

        //mapbox assets
        wp_enqueue_script('mapbox-js', 'https://api.mapbox.com/mapbox.js/v3.0.0/mapbox.js', array(), false, true);
        wp_enqueue_script('imap-main-js', get_template_directory_uri() . '/js/imap-main.js', array('jquery', 'mapbox-js'), Config::getAppVersion(), true);
        wp_localize_script('imap-main-js', 'DI', array(
            'templateUri' => get_template_directory_uri(),
            'isLoggedIn' => ($current_user !== false) ? 'true' : 'false'
        ));

        wp_enqueue_style('mapbox-css', 'https://api.mapbox.com/mapbox.js/v3.0.0/mapbox.css');
    }
}