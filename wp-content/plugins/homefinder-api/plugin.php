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
    \HomeFinder\Model\Bootstrap::init();
});

