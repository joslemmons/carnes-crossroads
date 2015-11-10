<?php

use App\Model\Config;

include('vendor/autoload.php');

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

\App\Model\Bootstrap::init();

$missing_plugins = Config::getMissingPlugins();
if (false === is_admin() && false === empty($missing_plugins)) {
    exit('missing plugins <a href="/wp-admin">admin</a>');
}
