<?php namespace HomeFinder\Model;

use App\Model\LandingPage;
use Carbon\Carbon;

class SEO
{
    const OPTION_NAME_PROPERTY_BASE_PROPERTIES = 'pbase_properties_for_sitemap';

    private static $_properties = null;
    private static $_updated = false;

    public static function bootstrap()
    {
        add_filter('wpseo_enable_xml_sitemap_transient_caching', function () {
            if (true === defined('WP_DISABLE_TRANSIENTS')) {
                return !constant('WP_DISABLE_TRANSIENTS');
            }

            return true;
        });

        add_filter('wpseo_sitemap_index', array(get_class(), 'addPropertyBasePropertiesToXMLRootSitemap'));

        if (false === get_option(self::OPTION_NAME_PROPERTY_BASE_PROPERTIES)) {
            add_option(self::OPTION_NAME_PROPERTY_BASE_PROPERTIES, array(), '', 'no');
        }

        add_action('shutdown', array(get_class(), 'updatePropertyBasePropertiesForXMLSitemap'));

        // hide some meta boxes on the account page
        add_action('add_meta_boxes', function ($post_type, $post) {
            if (isset($post) && $post->ID === '19919') {
                remove_meta_box('wpseo_meta', 'page', 'normal');
                remove_meta_box('postdivrich', 'page', 'normal');
                remove_meta_box('piklist_meta_page', 'page', 'normal');
                remove_post_type_support('page', 'editor');
            }

            if ($post_type === LandingPage::getPostType()) {
//                remove_meta_box('wpseo_meta', $post_type, 'normal');
                remove_meta_box('postdivrich', $post_type, 'normal');
            }
        }, 10, 2);
    }

    public static function addPropertyBasePropertiesToXMLRootSitemap()
    {
        $linkToSiteMap = home_url() . '/property-sitemap.xml';

        // todo: get last modified from options
        $lastModifiedDate = Carbon::now();

        $sitemap = \Timber::compile('property-sitemap-append-to-root.twig', array(
            'link' => $linkToSiteMap,
            'lastModifiedDate' => $lastModifiedDate
        ));

        return $sitemap;
    }

    /**
     * @return array|bool
     */
    public static function getProperties()
    {
        if (null === self::$_properties) {
            self::$_properties = get_option(self::OPTION_NAME_PROPERTY_BASE_PROPERTIES, array());
        }

        return self::$_properties;
    }

    /**
     * We want pbase properties to show up in our sitemap. This functionality relies on Yoast SEO plugin
     *
     * @param Property $property
     */
    public static function addProperty(Property $property)
    {
        $properties = self::getProperties();

        if (false === isset($properties[$property->getId()])) {
            $properties[$property->getId()] = array(
                'link' => $property->link(),
                'lastModifiedDate' => $property->getLastModifiedDate(),
                'featuredImageSrc' => $property->getFeaturedImageSrc(),
                'address' => $property->getAddress(),
                'addedToSitemapDate' => Carbon::now()->format('Y-m-d')
            );
            self::_setProperties($properties);
        }
    }

    private static function _setProperties(array $properties)
    {
        self::$_updated = true;
        self::$_properties = $properties;
    }

    public static function updatePropertyBasePropertiesForXMLSitemap()
    {
        if (true === self::$_updated) {
            update_option(self::OPTION_NAME_PROPERTY_BASE_PROPERTIES, self::$_properties, 'no');
        }
    }
}