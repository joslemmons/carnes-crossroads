<?php namespace App\Model;

use HomeFinder\Model\User;

class HomeFinderPage
{
    const PAGE_ID = 17;

    public static function enqueueAssets()
    {
        $current_user = User::getCurrentlyLoggedUser();

        wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, true);
        wp_enqueue_style('slick-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), false);
        wp_enqueue_style('slick-theme-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick-theme.css', array('slick-css'), false, false);
        wp_enqueue_script('google-map-js', 'https://maps.google.com/maps/api/js', array(), false, true);
        wp_enqueue_script('home-finder-js', get_template_directory_uri() . '/js/home-finder.js', array('jquery', 'slick-js', 'backbone', 'google-map-js'), Config::getAppVersion(), true);
        wp_localize_script('home-finder-js', 'CX', array(
            'templateUri' => get_template_directory_uri(),
            'isLoggedIn' => ($current_user !== false) ? 'true' : 'false'

        ));

        add_action('wp_footer', function () {
            echo '<script>var CXG = {}; CXG.isHomeFinderPage = true</script>';
        });

//        wp_enqueue_script('semantic-ui-js', get_template_directory_uri() . '/bower_components/semantic-ui/dist/semantic.min.js', array(), false, true);
//        wp_enqueue_script('semantic-ui-css', get_template_directory_uri() . '/bower_components/semantic-ui/dist/semantic.min.css', array(), false, true);
    }
}