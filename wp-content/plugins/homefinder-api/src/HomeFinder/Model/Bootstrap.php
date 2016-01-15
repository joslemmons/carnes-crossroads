<?php namespace HomeFinder\Model;

use Carbon\Carbon;

class Bootstrap
{

    public static function init()
    {
        Routes::init();
        SEO::bootstrap();
        User::bootstrap();

        add_filter('timber_locations', function ($loc) {
            $loc[] = dirname(__DIR__) . '/View';
            return $loc;
        });
    }

}
