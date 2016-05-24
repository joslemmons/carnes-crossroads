<?php

function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/carnes_logo.png) !important;
            padding-bottom: 30px;
            width: 250px !important;
            height: 120px !important;
            background-size: contain !important;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Carnes Crossroads';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );


require(get_template_directory() . '/vendor/autoload.php');

// this function is apache only. make it for nginx too
// http://www.php.net/manual/en/function.getallheaders.php#84262
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/* Disable Timber plugin update notices -- prevents breakage */
function filter_plugin_updates( $value ) {
    unset( $value->response['timber-library/timber.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );


/* Disable Timber plugin "Deactivate" link -- prevents breakage */
add_filter( 'plugin_action_links', 'disable_plugin_deactivation', 10, 4 );
function disable_plugin_deactivation( $actions, $plugin_file, $plugin_data, $context ) {
    // Remove edit link for all
    if ( array_key_exists( 'edit', $actions ) )
        unset( $actions['edit'] );
    // Remove deactivate link for crucial plugins
    if ( array_key_exists( 'deactivate', $actions ) && in_array( $plugin_file, array(
        'timber-library/timber.php'
    )))
        unset( $actions['deactivate'] );
    return $actions;
}

\App\Model\Bootstrap::init();
