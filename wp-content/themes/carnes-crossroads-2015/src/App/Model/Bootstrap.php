<?php namespace App\Model;

class Bootstrap
{

    public static function init()
    {
        Config::init();
        Routes::init();
        Cron::init();
        Assets::enqueue();
        Twig::init();
        AdminArea::init();
        Social::init();
        Analytics::init();
        VisualEditor::init();
        ContactFooter::init();

        date_default_timezone_set('America/New_York');

        add_theme_support('post-thumbnails');
        add_theme_support('menus');

        add_shortcode('nggallery', '__return_false');

        self::_notifyAdminOfMissingPlugins();

        if (class_exists('Timber')) {
            self::_registerCustomPostTypes();

            TimberContext::init();

            \Timber::$dirname = '/src/App/View';
        }

        if (Config::isAuthRequired() === true) {
            $request_uri = $_SERVER['REQUEST_URI'];
            $path = explode('?', $request_uri);

            if (isset($path[0]) === true && $path[0] !== '/login') {
                Auth::checkAuthorization();
            }
        }
    }

    private static function _notifyAdminOfMissingPlugins()
    {
        $missing_plugins = Config::getMissingPlugins();

        foreach ($missing_plugins as $plugin) {
            AdminArea::addUpdateNagNotice(sprintf('Required Plugin Missing: <a href="%s">%s</a>', $plugin['url'], $plugin['title']));
        }
    }

    private static function _registerCustomPostTypes()
    {
        Instagram::bootstrap();
        Page::bootstrap();
        FrontPage::bootstrap();
        NewsAndEventsPage::bootstrap();
        LandingPage::bootstrap();
        AccountPage::bootstrap();
        FAQPage::bootstrap();
        Post::bootstrap();
        Builder::bootstrap();
    }

}
