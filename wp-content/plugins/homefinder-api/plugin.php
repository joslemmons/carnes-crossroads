<?php
/**
 * Plugin Name: HomeFinder API
 * Description: Provides functionality for interacting with Property Base and MLS
 * Version: 0.8.0
 * Author: Chernoff Newman
 * Author URI: http://chernoffnewman.com
 */

use App\Model\AdminArea;

require 'vendor/autoload.php';

add_action('after_setup_theme', function () {
    $theme = wp_get_theme();

    $required_plugins = array(
        'piklist/piklist.php' => array(
            'title' => 'Piklist',
            'url' => 'https://downloads.wordpress.org/plugin/piklist.0.9.4.27.zip'
        ),
        'timber-library/timber.php' => array(
            'title' => 'Timber',
            'url' => 'https://downloads.wordpress.org/plugin/timber-library.0.21.8.zip'
        )
    );

    $current_plugins = get_option('active_plugins');
    $missing_plugins = array();

    foreach ($required_plugins as $key => $required_plugin) {
        if (in_array($key, $current_plugins) === false) {
            $missing_plugins[] = $required_plugin;
        }
    }

    if (
        ($theme->get('Name') !== 'Daniel Island 2015' || $theme->get('Name') !== 'Carnes Crossroads 2015') &&
        empty($missing_plugins) === true
    ) {
        \HomeFinder\Model\Bootstrap::init();
    } else {
        if ($theme->get('Name') !== 'Daniel Island 2015' || $theme->get('Name') !== 'Carnes Crossroads 2015') {
            add_action('admin_notices', function () {
                echo "<div class='update-nag'><p>Home Finder API Plugin requires Daniel Island 2015 or Carnes Crossroads 2015 theme by Chernoff Newman</p></div>";
            });
        } else {
            foreach ($missing_plugins as $plugin) {
                AdminArea::addUpdateNagNotice(sprintf('Required Plugin Missing: <a href="%s">%s</a>', $plugin['url'], $plugin['title']));
            }
        }
    }
});


