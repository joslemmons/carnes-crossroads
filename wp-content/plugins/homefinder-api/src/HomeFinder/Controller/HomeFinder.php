<?php namespace HomeFinder\Controller;

use App\Controller\Router;
use App\Model\Config;
use HomeFinder\Request\PropertyBase;

class HomeFinder extends Router
{

    public static function routeProperties()
    {
        // get MLS properties
        $client = new \Elasticsearch\Client(array(
            'hosts' => array('20bc6e1944d0a586000.qbox.io:80')
        ));

        $result = $client->search(array(
                'index' => '',
                'type' => '_all',
                'body' => array(
                    'query' => array(
                        'match' => array(
                            'is_active' => 1
                        )
                    )
                )
            )
        );

        echo '<pre>';
        print_r($result);
        echo '</pre>';
        exit();
    }

    public static function routeFeaturedProperties()
    {
        // 3 hour cache
        $properties = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_featured_properties_transient', function () {
            // direct request to pbase
            $properties = PropertyBase::getFeatured();

            return $properties;
        }, 60 * 60 * 3);

        // we only want a random 6
        shuffle($properties);
        $properties = array_slice($properties, 0, 6);

        self::renderJSON(array(
            'status' => 200,
            'rsp' => array(
                'total' => count($properties),
                'total_per_page' => 6,
                'properties' => $properties
            )
        ));
    }
}