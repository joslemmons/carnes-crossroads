<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// Include local configuration
if (file_exists(dirname(__FILE__) . '/local-config.php')) {
	include(dirname(__FILE__) . '/local-config.php');
}

// Global DB config
if (!defined('DB_NAME')) {
	define('DB_NAME', 'carnes_crossroads');
}
if (!defined('DB_USER')) {
	define('DB_USER', 'root');
}
if (!defined('DB_PASSWORD')) {
	// define('DB_PASSWORD', '2015!!cn');
    define('DB_PASSWORD', 'root');
}
if (!defined('DB_HOST')) {
	// define('DB_HOST', '192.168.12.5');
    define('DB_HOST', 'localhost');
}

/** Database Charset to use in creating database tables. */
if (!defined('DB_CHARSET')) {
	define('DB_CHARSET', 'utf8');
}

/** The Database Collate type. Don't change this if in doubt. */
if (!defined('DB_COLLATE')) {
	define('DB_COLLATE', '');
}

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Q/h6z|]VB0whxVW~p(IkLN5Yp@E?l>InBK:vBqhzgx6Tq4|&IJzY8OMDfba:+zPp');
define('SECURE_AUTH_KEY',  'cPr$u}n+z:B?):s-xi5|d!~|j]i!0f~|RZHi[%Roj#naBv=3<=NSPCs>Nr(tB8v%');
define('LOGGED_IN_KEY',    '9wu1rBQZ:@v|f[XF=DG:#m#w1{;h`<r$$_VRG3-PjpHvx|&3iA@FOJj&B%^<bKo-');
define('NONCE_KEY',        'SNR[=u5|).h]u&o:[/4C rtb-m7~;_W_2oU-aG>_Gq*+JKo?](:M=%C3y+gvAhh(');
define('AUTH_SALT',        'Lai5huMFy@?Mqw?Gl(ZOF|dwbt=SC+zw?;w|>I=drniG5GH{YcQUEv |}RE-L[7>');
define('SECURE_AUTH_SALT', 'm>w+F,9rH|N|}iW7LV-O)20Jke-rp9ptcJuz^Ugv)=q,#:5BsH%+`JJ+5:=(FaqD');
define('LOGGED_IN_SALT',   '`fsO@b1nn?31v+s!j-_~mR-fy<p9q&#i/Dsgq?cWg[*/GG@Alh Jv]&h0X*itaV.');
define('NONCE_SALT',       '|<yu)VHtCW/jXK+AlS102/2oLF)XVk9/xkiBwD{DLc3-a2CC6K8L;YJ/<S|uer?H');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_Ke8t8a_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
if (!defined('WP_DEBUG')) {
	define('WP_DEBUG', false);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
