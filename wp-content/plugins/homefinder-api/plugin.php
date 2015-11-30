<?php
/**
 * Plugin Name: HomeFinder API
 * Description: Provides functionality for interacting with Property Base and MLS
 * Version: 1.0.0
 * Author: Chernoff Newman
 * Author URI: http://chernoffnewman.com
 */

require 'vendor/autoload.php';

add_action('after_setup_theme', function () {
    $theme = wp_get_theme();

    if ($theme->get('Name') === 'Carnes Crossroads 2015') {
        \HomeFinder\Model\Bootstrap::init();
    } else {
        add_action('admin_notices', function () {
            echo "<div class='update-nag'><p>Home Finder API Plugin requires Carnes Crossroads 2015 theme by Chernoff Newman</p></div>";
        });
    }
});

