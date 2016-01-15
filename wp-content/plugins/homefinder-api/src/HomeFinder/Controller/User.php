<?php namespace HomeFinder\Controller;

use App\Controller\Router;
use App\Model\Config;
use App\Model\Helper;
use HomeFinder\Model\HomeFinderFilters;
use HomeFinder\Model\Property;

class User extends Router
{
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

        $filters = HomeFinderFilters::withGETParams();
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

        $filters = HomeFinderFilters::withGETParams();
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


}