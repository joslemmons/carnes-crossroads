<?php namespace HomeFinder\Controller;

use App\Controller\Router;
use App\Model\Config;
use App\Model\Helper;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\HomeFinderFilters;
use HomeFinder\Model\Metric;
use HomeFinder\Model\Property;

class User extends Router
{

    const CRON_ALERT_USERS_OF_NEW_PROPERTIES_FOR_SEARCHES_DAILY = 'app_update_users_on_searches_daily';
    const CRON_ALERT_USERS_OF_NEW_PROPERTIES_FOR_SEARCHES_WEEKLY = 'app_update_users_on_searches_weekly';

    public static function initCronJobs()
    {
        if (false === wp_get_schedule(self::CRON_ALERT_USERS_OF_NEW_PROPERTIES_FOR_SEARCHES_DAILY)) {
            wp_schedule_event(current_time('timestamp'), 'daily', self::CRON_ALERT_USERS_OF_NEW_PROPERTIES_FOR_SEARCHES_DAILY);
        }
        add_action(self::CRON_ALERT_USERS_OF_NEW_PROPERTIES_FOR_SEARCHES_DAILY, array(get_class(), 'cronAlertUsersDailyOfSearches'));

        if (false === wp_get_schedule(self::CRON_ALERT_USERS_OF_NEW_PROPERTIES_FOR_SEARCHES_WEEKLY)) {
            wp_schedule_event(current_time('timestamp'), 'weekly', self::CRON_ALERT_USERS_OF_NEW_PROPERTIES_FOR_SEARCHES_WEEKLY);
        }
        add_action(self::CRON_ALERT_USERS_OF_NEW_PROPERTIES_FOR_SEARCHES_WEEKLY, array(get_class(), 'cronAlertUsersWeeklyOfSearches'));
    }

    /**
     * @param \HomeFinder\Model\User $user
     * @param \HomeFinder\Model\HomeFinderFilters $filter
     * @param Property[] $properties
     * @param int $total
     */
    private static function _sendEmailToUserWithProperties($user, $filter, $properties, $total)
    {
        if (empty($properties)) {
            return;
        }

        $html = \Timber::compile('email/saved-search-update.twig', array(
            'template_uri' => get_template_directory_uri(),
            'filter' => $filter,
            'properties' => $properties,
            'total' => $total
        ));

        $to = $user->user_email;

        $to = sanitize_email($to);

        if ($to === '') {
            return;
        }

        wp_mail(
            $to,
            '[Carnes Crossroads Real Estate] Saved Search Update',
            $html
        );
    }

    /**
     * @param \HomeFinder\Model\User[] $users
     */
    public static function updateUsersOnSavedSearches($users)
    {
        add_filter('wp_mail_content_type', function () {
            return 'text/html';
        });

        foreach ($users as $user) {
            $filters = $user->getSavedSearchFilters();

            foreach ($filters as $filter) {
                if ($filter->isEmptySearch()) {
                    continue;
                }

                $result = HomeFinder::getProperties($filter, 48);
                $properties = $result->items;
                shuffle($properties);
                $properties = array_slice($properties, 0, 12);
                $total = $result->total;

                if (empty($properties) === false) {
                    self::_sendEmailToUserWithProperties($user, $filter, $properties, $total);
                }
            }
        }
    }

    public static function cronAlertUsersDailyOfSearches()
    {
        if (Helper::isProduction() || Helper::isStaging()) {
            $users = \HomeFinder\Model\User::getUsersWhoWantDailyUpdatesOnSearches();
            self::updateUsersOnSavedSearches($users);
        }
    }

    public static function cronAlertUsersWeeklyOfSearches()
    {
        if (Helper::isProduction() || Helper::isStaging()) {
            $users = \HomeFinder\Model\User::getUsersWhoWantWeeklyUpdatesOnSearches();
            self::updateUsersOnSavedSearches($users);
        }
    }

    public static function routeSaveProperty($params = array())
    {
        $property_id = (isset($params['id'])) ? $params['id'] : false;

        /* @var $property Property */
        $property = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_' . $property_id, function () use ($property_id) {
            $property = Property::withId($property_id);

            return $property;
        }, 60 * 60 * 3);

        if (false === $property) {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'Cannot save property with passed id'
            ), 404);
        }

        $user = \HomeFinder\Model\User::getCurrentlyLoggedUser();
        if ($user) {
            $user->saveProperty($property);
            Metric::trackFavoriteProperty($property);

            self::renderJSON(array(
                'status' => 200
            ));
        }

        self::renderJSON(array(
            'status' => 404,
            'rsp' => 'login to save properties'
        ));
    }

    public static function routeUnSaveProperty($params = array())
    {
        $property_id = (isset($params['id'])) ? $params['id'] : false;

        /* @var $property Property */
        $property = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_' . $property_id, function () use ($property_id) {
            $property = Property::withId($property_id);

            return $property;
        }, 60 * 60 * 3);

        if (false === $property) {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'Cannot un-save property with passed id'
            ), 404);
        }

        $user = \HomeFinder\Model\User::getCurrentlyLoggedUser();
        if ($user) {
            $user->unSaveProperty($property);

            self::renderJSON(array(
                'status' => 200
            ));
        }

        self::renderJSON(array(
            'status' => 404,
            'rsp' => 'login to un-save properties'
        ));
    }

    public static function routeSaveSearch($params = array())
    {
        if (false === Helper::isPost()) {
            self::renderJSON(array(
                'status' => 405,
                'rsp' => 'Method Not Allowed'
            ), 405);
        }

        $user = \HomeFinder\Model\User::getCurrentlyLoggedUser();

        if (false === $user) {
            self::renderJSON(array(), 401);
        }

        $filters = HomeFinderFilters::withREQUESTParams();
        $user->saveSearch($filters);

        self::renderJSON(array(
            'status' => 200,
            'rsp' => 'OK'
        ), 200);
    }

    public static function routeUnSaveSearch($params = array())
    {
        if (false === Helper::isPost()) {
            self::renderJSON(array(
                'status' => 405,
                'rsp' => 'Method Not Allowed'
            ), 405);
        }

        $user = \HomeFinder\Model\User::getCurrentlyLoggedUser();

        if (false === $user) {
            self::renderJSON(array(), 401);
        }

        $filters = HomeFinderFilters::withREQUESTParams();
        $user->unSaveSearch($filters);

        self::renderJSON(array(
            'status' => 200,
            'rsp' => 'OK'
        ), 200);
    }

    public static function routeSaveNotificationOption($params = array())
    {
        if (false === Helper::isPost()) {
            self::renderJSON(array(
                'status' => 405,
                'rsp' => 'Method Not Allowed'
            ), 405);
        }

        $choice = (isset($_POST['choice'])) ? sanitize_text_field($_POST['choice']) : \HomeFinder\Model\User::NO_NOTIFICATIONS;

        if (false === \HomeFinder\Model\User::isNotificationOptionValid($choice)) {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'Invalid notification option'
            ), 404);
        }

        $user = \HomeFinder\Model\User::getCurrentlyLoggedUser();

        if (false === $user) {
            self::renderJSON(array(), 401);
        }

        $user->setNotificationOption($choice);

        self::renderJSON(array(
            'status' => 200,
            'rsp' => 'OK'
        ), 200);
    }

    public static function routeSignIn()
    {
        if (false === Helper::isPost()) {
            self::renderJSON(array(
                'status' => 405,
                'rsp' => 'Method Not Allowed'
            ), 405);
        }

        $email = (isset($_POST['email'])) ? sanitize_email($_POST['email']) : false;

        if (false === $email || '' === $email) {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'Invalid email address'
            ), 404);
        }

        $user = \HomeFinder\Model\User::createOrLoginAndAuthenticateUser($email);

        if (false === $user) {
            self::renderJSON(array(
                'status' => 500
            ), 500);
        }

        self::renderJSON(array(
            'status' => 200,
            'rsp' => 'OK'
        ), 200);
    }

    public static function routeShareProperty($params = array())
    {
        if (Helper::isPost() === false) {
            self::renderJSON(array(
                'rsp' => 'Method Not Allowed'
            ), 405);
        }

        $property_id = (isset($params['id'])) ? $params['id'] : false;
        $social_network = (isset($_POST['network'])) ? sanitize_text_field($_POST['network']) : null;

        $allowed_networks = array('facebook', 'twitter', 'email');
        if ($social_network === null || in_array($social_network, $allowed_networks) === false) {
            self::renderJSON(array(
                'rsp' => 'Social Network not allowed'
            ), 404);
        }

        $property = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_' . $property_id, function () use ($property_id) {
            $property = Property::withId($property_id);

            return $property;
        }, 60 * 60 * 3);

        if (false === $property) {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'Invalid property id'
            ), 404);
        }

        Metric::trackShareProperty($property, $social_network);

        self::renderJSON(array(
            'rsp' => 'ok'
        ), 200);
    }



}