<?php

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('DISALLOW_FILE_EDIT', true);
define('WP_DISABLE_TRANSIENTS', true);

// Are we in SSL mode?
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

if (!defined('WP_SITEURL')) {
    define('WP_SITEURL', $protocol . $_SERVER['HTTP_HOST'] . '/');
}
if (!defined('WP_HOME')) {
    define('WP_HOME', $protocol . $_SERVER['HTTP_HOST'] . '');
}

// Local DB config
// if (!defined('DB_NAME')) {
// 	define('DB_NAME', 'cx_2015');
// }
// if (!defined('DB_USER')) {
// 	define('DB_USER', 'homestead');
// }
// if (!defined('DB_PASSWORD')) {
// 	define('DB_PASSWORD', 'secret');
// }
// if (!defined('DB_HOST')) {
// 	define('DB_HOST', 'localhost');
// }
