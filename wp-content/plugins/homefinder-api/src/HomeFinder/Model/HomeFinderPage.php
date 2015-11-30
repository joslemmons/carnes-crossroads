<?php namespace HomeFinder\Model;

use App\Model\Config;
use App\Model\Helper;

class HomeFinderPage
{
    public static function enqueueAssets()
    {
        if (true === Helper::isProduction()) {
            // we just need the final build
            wp_enqueue_script('app-js', get_template_directory_uri() . '/assets/js/require_main.built.js', array(), Config::getAppVersion(), true);

        } else {
            wp_enqueue_script('require-js', get_template_directory_uri() . '/assets/js/vendor/requirejs/require.js', array(), false, true);
            wp_enqueue_script('require-js-main', get_template_directory_uri() . '/assets/js/require_main.js', array('require-js'), Config::getAppVersion(), true);
            wp_localize_script('require-js-main', 'app_config', array(
                'baseUrl' => get_template_directory_uri() . '/assets/js'
            ));
        }

    }
}