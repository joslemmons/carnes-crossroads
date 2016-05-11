<?php

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

\App\Model\Bootstrap::init();
