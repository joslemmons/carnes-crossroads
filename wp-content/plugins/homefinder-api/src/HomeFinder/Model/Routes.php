<?php namespace HomeFinder\Model;

use App\Model\Helper;

class Routes
{
    public static function init()
    {
        if (class_exists('Timber')) {
            if (Helper::isProduction() == false &&
                Helper::isStaging() == false
            ) {
                self::_initPrivateRoutes();
            }
            self::_initPublicRoutes();
        }

    }

    private static function _initPrivateRoutes()
    {
        require(dirname(__DIR__) . '/private_routes.php');
    }

    private static function _initPublicRoutes()
    {
        require(dirname(__DIR__) . '/public_routes.php');
    }

}