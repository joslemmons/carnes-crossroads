<?php namespace App\Model;

use HomeFinder\Model\User;

class HomeFinderPage
{
    const PAGE_ID = 17;

    public static function enqueueAssets()
    {
        $current_user = User::getCurrentlyLoggedUser();

//        wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, true);
//        wp_enqueue_style('slick-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), false);
//        wp_enqueue_style('slick-theme-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick-theme.css', array('slick-css'), false, false);
        wp_enqueue_script('google-map-js', 'https://maps.google.com/maps/api/js', array(), false, true);
        wp_enqueue_script('multiple-select-js', get_template_directory_uri() . '/js/lib/multiple-select.js', array('jquery'), false, true);
        wp_enqueue_script('wnumb-js', get_template_directory_uri() . '/js/lib/wnumb/wNumb.js', array(), false, true);
        wp_enqueue_script('nouislider-js', get_template_directory_uri() . '/js/lib/nouislider/nouislider.min.js', array('wnumb-js'), false, true);
        wp_enqueue_style('nouislider-css', get_template_directory_uri() . '/js/lib/nouislider/nouislider.min.css');
        wp_enqueue_script('jquery-outside-events-js', get_template_directory_uri() . '/js/lib/jquery.ba-outside-events.min.js', array('jquery'), false, true);
        wp_enqueue_script('home-finder-js', get_template_directory_uri() . '/js/home-finder.js', array('jquery', 'backbone', 'nouislider-js', 'jquery-outside-events-js', 'multiple-select-js'), Config::getAppVersion(), true);
        wp_localize_script('home-finder-js', 'DI', array(
            'templateUri' => get_template_directory_uri(),
            'isLoggedIn' => ($current_user !== false) ? 'true' : 'false'
        ));
        
        //enqueue mapbox resources
        wp_enqueue_script('mapbox-js', 'https://api.mapbox.com/mapbox.js/v3.0.0/mapbox.js', array(), false, true);
        wp_enqueue_style('mapbox-css', 'https://api.mapbox.com/mapbox.js/v3.0.0/mapbox.css');

        wp_enqueue_script('imap-js', get_template_directory_uri() . '/js/imap.js', array('jquery', 'google-map-js'), Config::getAppVersion(), true);
        wp_localize_script('imap-js', 'DI', array(
            'templateUri' => get_template_directory_uri(),
            'isLoggedIn' => ($current_user !== false) ? 'true' : 'false'
        ));

        add_action('wp_footer', function() {
            echo '<script>var DIG = {}; DIG.isHomeFinderPage = true</script>';
        });

    }
}
