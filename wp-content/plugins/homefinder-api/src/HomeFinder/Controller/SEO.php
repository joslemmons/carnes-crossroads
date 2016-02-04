<?php namespace HomeFinder\Controller;

class SEO
{
    public static function getStylesheet()
    {
        return '<?xml-stylesheet type="text/xsl" href="' . preg_replace('/(^http[s]?:)/', '', esc_url(home_url('main-sitemap.xsl'))) . '"?>';
    }

    public static function routeGetPropertySitemap()
    {
        http_response_code(200);
        // Prevent the search engines from indexing the XML Sitemap.
        header('X-Robots-Tag: noindex, follow', true);
        header('Content-Type: text/xml');

        $properties = \HomeFinder\Model\SEO::getProperties();

        \Timber::render('property-sitemap.twig', array(
            'stylesheet' => apply_filters('wpseo_stylesheet_url', self::getStylesheet()) . "\n",
            'properties' => $properties
        ));
        exit();
    }
}