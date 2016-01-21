<?php namespace HomeFinder\Controller;

use App\Controller\Router;
use App\Model\Config;
use App\Model\Helper;
use App\Model\NewOfferings;
use HomeFinder\Model\HomeFinderFilters;
use HomeFinder\Model\Property;
use HomeFinder\Model\Result;
use HomeFinder\Model\User;

class HomeFinder extends Router
{

    public static function routeSavedListings($params = array())
    {
        $page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;

        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            self::renderJSON(array(
                'status' => 403,
                'total' => 0,
                'rsp' => ''
            ));
        }

        /* @var Result */
        $result = \HomeFinder\Model\HomeFinder::getSavedListingsForUser($user);

        $html = \Timber::compile('partials/home-finder/property-list.twig', array(
            'properties' => $result->items,
            'nextPageUrl' => '/api/home-finder/saved-listings/page/' . ($page + 1),
            'current_user' => User::getCurrentlyLoggedUser()
        ));

        self::renderJSON(array(
            'status' => 200,
            'total' => $result->total,
            'rsp' => $html
        ));
    }

    public static function routeSearch($params = array())
    {
        $per_page = \HomeFinder\Model\HomeFinder::LISTINGS_PER_PAGE;
        $page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;

        // do not search through MLS 1/20
        unset($_REQUEST['searchMLS']);

        $filters = HomeFinderFilters::withGETParams();

        /* @var $result Result */
        $result = \HomeFinder\Model\HomeFinder::getProperties($filters, $per_page, $page);

        $html = \Timber::compile('partials/home-finder/property-list.twig', array(
            'properties' => $result->items,
            'nextPageUrl' => '/api/home-finder/search/page/' . ($page + 1) . '?' . http_build_query($filters->getRawFilters()),
            'current_user' => User::getCurrentlyLoggedUser()
        ));

        self::renderJSON(array(
            'status' => 200,
            'total' => $result->total,
            'rsp' => $html
        ));
    }

    public static function routeFeaturedProperties($params = array())
    {
        $per_page = \HomeFinder\Model\HomeFinder::LISTINGS_PER_PAGE;
        $page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;

        /* @var Result */
        $featured_properties_result = \HomeFinder\Model\HomeFinder::getFeaturedProperties($per_page, $page);

        $html = \Timber::compile('partials/home-finder/property-list.twig', array(
            'properties' => $featured_properties_result->items,
            'nextPageUrl' => '/api/home-finder/featured-properties/page/' . ($page + 1),
            'current_user' => User::getCurrentlyLoggedUser()
        ));

        self::renderJSON(array(
            'status' => 200,
            'total' => $featured_properties_result->total,
            'rsp' => $html
        ));
    }

    public static function routeProperty($params = array())
    {
        $property_id = (isset($params['id'])) ? $params['id'] : false;

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

        // mark the property as viewed by logged user (if logged in)
        if (is_user_logged_in()) {
            $user = User::getCurrentlyLoggedUser();
            $user->markPropertyAsViewed($property);
        }

        $html = \Timber::compile('partials/home-finder/property.twig', array(
            'template_uri' => get_template_directory_uri(),
            'property' => $property,
            'current_user' => User::getCurrentlyLoggedUser()
        ));

        self::renderJSON(array(
            'status' => 200,
            'rsp' => $html
        ));
    }

    public static function routeNewOfferings($params = array())
    {
        $per_page = \HomeFinder\Model\HomeFinder::LISTINGS_PER_PAGE;
        $page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;

        $offerings = NewOfferings::all($per_page, $page);
        $termInfo = get_term_by('slug', 'new-offerings', 'category');

        $total = 0;
        if (false !== $termInfo) {
            $total = $termInfo->count;
        }

        $html = \Timber::compile('partials/home-finder/new-offerings-list.twig', array(
            'offerings' => $offerings,
            'nextPageUrl' => '/api/home-finder/new-offerings/page/' . ($page + 1),
            'current_user' => User::getCurrentlyLoggedUser()
        ));

        self::renderJSON(array(
            'status' => 200,
            'total' => $total,
            'rsp' => $html
        ));
    }

    public static function routeRecentlyListed($params = array())
    {
        $per_page = \HomeFinder\Model\HomeFinder::LISTINGS_PER_PAGE;
        $page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;

        /* @var Result */
        $result = \HomeFinder\Model\HomeFinder::getRecentlyListed($per_page, $page);

        $html = \Timber::compile('partials/home-finder/property-list.twig', array(
            'properties' => $result->items,
            'nextPageUrl' => '/api/home-finder/recently-listed/page/' . ($page + 1),
            'current_user' => User::getCurrentlyLoggedUser()
        ));

        self::renderJSON(array(
            'status' => 200,
            'total' => $result->total,
            'rsp' => $html
        ));
    }

    public static function routeNewOffering($params = array())
    {
        $id = (isset($params['id']) && false !== filter_var($params['id'], FILTER_VALIDATE_INT)) ? (int)$params['id'] : null;

        if (null === $id) {
            self::renderJSON(array(
                'status' => 404
            ));
        }

        $offering = NewOfferings::find($id);

        if ($offering) {
            $html = \Timber::compile('partials/home-finder/new-offering.twig', array(
                'offering' => $offering,
                'template_uri' => get_template_directory_uri(),
                'current_user' => User::getCurrentlyLoggedUser()
            ));

            self::renderJSON(array(
                'status' => 200,
                'rsp' => $html
            ));
        } else {
            self::renderJSON(array(
                'status' => 404
            ));
        }
    }

    public static function routeRequestShowing($params = array())
    {
        if (false === Helper::isPost()) {
            self::renderJSON(array(
                'status' => 405,
                'rsp' => 'Method Not Allowed'
            ), 405);
        }

        $propertyId = (isset($_POST['propertyId'])) ? sanitize_text_field($_POST['propertyId']) : null;
        $name = (isset($_POST['name'])) ? sanitize_text_field($_POST['name']) : null;
        $email = (isset($_POST['email'])) ? sanitize_email($_POST['email']) : null;
        $message = (isset($_POST['message'])) ? sanitize_text_field($_POST['message']) : null;
        $shouldCreateAccount = (isset($_POST['shouldCreateAccount'])) ? sanitize_text_field($_POST['shouldCreateAccount']) : false;
        $error_messages = array();

        if (null === $propertyId) {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'Invalid Property Id'
            ), 404);
        }

        if ('' === $email) {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'Invalid Email Address'
            ), 404);
        }

        if ($shouldCreateAccount === 'true') {
            $shouldCreateAccount = true;
        }

        $property = Property::withId($propertyId);
        $address = $property->getAddress();

        if (false === $property) {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'Invalid Property Id'
            ), 404);
        }

        $to_email = 'developer@chernoffnewman.com';
        if (Helper::isProduction()) {
            $to_email = 'carneshomefinder@carnesrealestate.com';
        }

        try {
            $mandrill = new \Mandrill('hpjxvPoVhO64Xh-ZGN3gGw');
            $mandrill->messages->send(array(
                'html' => \Timber::compile('email/request-showing.twig',
                    array(
                        'property' => $property,
                        'name' => $name,
                        'email' => $email,
                        'message' => $message
                    )),
                'subject' => "[Request Showing] $name - $address",
                'from_email' => "$email",
                'from_name' => "$name",
                'to' => array(
                    array(
                        'email' => $to_email
                    )
                )
            ));
        } catch (\Mandrill_Error $ex) {
            // Log
            $error_messages[] = 'Internal Error occurred. Failed to send email. Please try again.';
        }


        if (true === $shouldCreateAccount) {
            $user = User::createOrLoginAndAuthenticateUser($email);
            if ($user === false) {
                $error_messages[] = 'Failed to create new user account';
            }
        }

        if (false === empty($error_messages)) {
            self::renderJSON(array(
                'status' => 500,
                'rsp' => implode(',', $error_messages)
            ), 500);
        }

        self::renderJSON(array(
            'status' => 200,
            'rsp' => 'OK'
        ), 200);

    }

    public static function routeGetAccountPageContent($params = array())
    {
        $page = \Timber::get_post(19919, '\App\Model\AccountPage');
        $current_user = User::getCurrentlyLoggedUser();

        $html = \Timber::compile('partials/account/account-content.twig', array(
            'page' => $page,
            'current_user' => $current_user
        ));

        self::renderJSON(array(
            'status' => 200,
            'rsp' => $html
        ));
    }
}