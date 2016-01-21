<?php

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
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
