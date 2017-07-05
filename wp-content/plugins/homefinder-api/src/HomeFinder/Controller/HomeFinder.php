<?php namespace HomeFinder\Controller;

use App\Controller\Router;
use App\Model\Config;
use App\Model\Helper;
use App\Model\NewOfferings;
use HomeFinder\Model\HomeFinderFilters;
use HomeFinder\Model\Metric;
use HomeFinder\Model\Property;
use HomeFinder\Model\Result;
use HomeFinder\Model\User;


class HomeFinder extends Router
{

    public static function routeSavedListings($params = array())
    {
        $page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;
        $sort = (isset($_REQUEST['sort'])) ? sanitize_text_field($_REQUEST['sort']) : 'default';

        $order = null;
        $order_by = null;
        if ($sort !== 'default') {
            $sort_options = explode('.', $sort);
            if (count($sort_options) === 2) {
                // only allow sorting by price asc or desc
                if (
                    ($sort_options[1] === 'asc' || $sort_options[1] === 'desc') &&
                    $sort_options[0] === 'price'
                ) {
                    $order_by = $sort_options[0];
                    $order = $sort_options[1];
                }
            }
        }

        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            self::renderJSON(array(
                'status' => 403,
                'total' => 0,
                'rsp' => ''
            ));
        }

        if ($page === 1) {
            /* @var Result */
            $result = \HomeFinder\Model\HomeFinder::getSavedListingsForUser($user, $order_by, $order);
        } else {
            // only 1 page for user saved listings. all are pulled to start. no pagination
            $result = new Result();
            $result->items = array();
            $result->total = 0;
        }


        $html = \Timber::compile('partials/home-finder/property-list.twig', array(
            'properties' => $result->items,
            'nextPageUrl' => '/api/home-finder/saved-listings/page/' . ($page + 1) . '?' . http_build_query(
                    array(
                        'sort' => $sort
                    )
                ),
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
        $sort = (isset($_REQUEST['sort'])) ? sanitize_text_field($_REQUEST['sort']) : 'default';

        $view = (isset($_REQUEST['view'])) ? sanitize_text_field($_REQUEST['view']) : 'grid';
        $render_view = ($view == 'map') ? 'partials/home-finder/results-map-view.twig' : 'partials/home-finder/results-grid-view.twig';
        $name = (isset($_REQUEST['name'])) ? sanitize_text_field($_REQUEST['name']) : '';

        $order = null;
        $order_by = null;
        if ($sort !== 'default') {
            $sort_options = explode('.', $sort);
            if (count($sort_options) === 2) {
                // only allow sorting by price asc or desc
                if (
                    ($sort_options[1] === 'asc' || $sort_options[1] === 'desc') &&
                    $sort_options[0] === 'price'
                ) {
                    $order_by = $sort_options[0];
                    $order = $sort_options[1];
                }
            }
        }

        $filters = HomeFinderFilters::withREQUESTParams();
        Metric::trackSearch($filters);

        /* @var $result Result */
        $result = \HomeFinder\Model\HomeFinder::getProperties($filters, $per_page, $page, $order_by, $order);
        $resultAll = \HomeFinder\Model\HomeFinder::getProperties($filters, $result->total, 1, $order_by, $order);

        $filters->setMLSPage($result->mlsPage);

        $locations = array();

        foreach ($resultAll->items as $property) {
            $latitude = ($property->latitude) ? $property->latitude : 33.055447;
            $longitude = ($property->longitude) ? $property->longitude : -80.103878;

            if ($property->getPropertyType() == 'Homesite') {
                $op = 0;
            } else {
                $op = $property->getPropertyType();
            }

            $htmlAux = \Timber::compile('partials/home-finder/map-tool-tip.twig', array(
                'property' => $property
            ));

            $auxLocation = array(
                $property->address_web,
                $latitude,
                $longitude,
                '4',
                $property->link,
                $htmlAux,
                $op
            );

            $locations[] = $auxLocation;
            
        }

        $html = \Timber::compile($render_view, array(
            'name' => $name,
            'result' => $resultAll,
            'nextPageUrl' => '/api/home-finder/search/page/' . ($page + 1) . '?' . http_build_query(
                    array_merge(
                        $filters->getRawFilters(),
                        array(
                            'sort' => $sort
                        )
                    )
                ),
            'current_user' => User::getCurrentlyLoggedUser(),
            'page' => $result->paginator->getPage(),
            'pages' => $result->paginator->count()
        ));

        self::renderJSON(array(
            'status' => 200,
            'total' => $result->total,
            'locations' => $locations,
            'rsp' => $html
        ));
    }

    public static function routeFeaturedProperties($params = array())
    {
        $per_page = \HomeFinder\Model\HomeFinder::LISTINGS_PER_PAGE;
        $page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;
        $sort = (isset($_REQUEST['sort'])) ? sanitize_text_field($_REQUEST['sort']) : 'default';

        $order = null;
        $order_by = null;
        if ($sort !== 'default') {
            $sort_options = explode('.', $sort);
            if (count($sort_options) === 2) {
                // only allow sorting by price asc or desc
                if (
                    ($sort_options[1] === 'asc' || $sort_options[1] === 'desc') &&
                    $sort_options[0] === 'price'
                ) {
                    $order_by = $sort_options[0];
                    $order = $sort_options[1];
                }
            }
        }

        /* @var Result */
        $featured_properties_result = \HomeFinder\Model\HomeFinder::getFeaturedProperties($per_page, $page, $order_by, $order);

        $html = \Timber::compile('partials/home-finder/property-list.twig', array(
            'properties' => $featured_properties_result->items,
            'nextPageUrl' => '/api/home-finder/featured-properties/page/' . ($page + 1) . '?' . http_build_query(
                    array(
                        'sort' => $sort
                    )
                ),
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

//        if (false === $property) {
//            self::renderJSON(array(
//                'status' => 404,
//                'rsp' => 'Invalid property id'
//            ), 404);
//        }

        // mark the property as viewed by logged user (if logged in)
        if ($property !== false && is_user_logged_in()) {
            $user = User::getCurrentlyLoggedUser();
            $user->markPropertyAsViewed($property);
            Metric::trackPropertyListingView($property);
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
        $builder_title = (isset($_POST['builderTitle'])) ? str_replace('-', ' ', sanitize_text_field($_POST['builderTitle'])) : null;
        $floor_plan_title = (isset($_POST['floorPlanTitle'])) ? str_replace('-', ' ', sanitize_text_field($_POST['floorPlanTitle'])) : null;
        $name = (isset($_POST['name'])) ? sanitize_text_field($_POST['name']) : null;
        $email = (isset($_POST['email'])) ? sanitize_email($_POST['email']) : null;
        $message = (isset($_POST['message'])) ? sanitize_text_field($_POST['message']) : null;
        $shouldCreateAccount = (isset($_POST['shouldCreateAccount'])) ? sanitize_text_field($_POST['shouldCreateAccount']) : false;
        $error_messages = array();

        if ($propertyId === null && $builder_title === null && $floor_plan_title === null) {
            self::renderJSON(array(
                'status' => 500,
                'rsp' => 'Failed to find Listing. Please try again.'
            ), 500);
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

        $item = null;

        if ($propertyId !== null && $propertyId !== '') {
            $property = Property::withId($propertyId);
            $address = $property->getAddress();

            if (false === $property) {
                self::renderJSON(array(
                    'status' => 404,
                    'rsp' => 'Invalid Property Id'
                ), 404);
            }
            $item = $property;
            $twig_file = 'email/request-showing.twig';
        } elseif ($builder_title !== null && $builder_title !== '' && $floor_plan_title !== null && $floor_plan_title !== '') {
            $builder = \App\Model\Builder::withName($builder_title);

            if (!$builder) {
                self::renderJSON(array(
                    'rsp' => 'Unable to find Builder'
                ), 404);
            }

            $floor_plan = $builder->getFloorPlanByName($floor_plan_title);
            $address = $floor_plan_title;

            if (!$floor_plan) {
                self::renderJSON(array(
                    'rsp' => 'Unable to find Floor Plan'
                ), 404);
            }

            $item = $floor_plan;
            $twig_file = 'email/request-information-on-floor-plan.twig';
        } else {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'Invalid Listing'
            ), 404);
        }

        $to_email = 'developer@chernoffnewman.com';
        if (Helper::isProduction()) {
            $to_email = 'carneshomefinder@carnesrealestate.com';
        }

        wp_mail(
            $to_email,
            "[Request Showing] $name - $address",
            \Timber::compile($twig_file,
                    array(
                        'item' => $item,
                        'name' => $name,
                        'email' => $email,
                        'message' => $message
                    )),
            array(
                'From: ' . $name . ' <' . $email . '>'
            )
        );

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
        $page = \Timber::get_post(257, '\App\Model\AccountPage');
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

    public static function routePrintProperty($params = array())
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

        Metric::trackPrintProperty($property);

        \Timber::render('partials/home-finder/print-view-of-property.twig', array(
            'properties' => array($property)
        ));
        exit();
    }

    public static function routePrintSavedProperties($params = array())
    {
        $user = User::getCurrentlyLoggedUser();
        if ($user) {
            $properties = $user->getSavedProperties();

            foreach ($properties as $property) {
                Metric::trackPrintProperty($property);
            }

            \Timber::render('partials/home-finder/print-view-of-property.twig', array(
                'properties' => $properties
            ));
            exit();
        }

        self::renderJSON(array(
            'status' => 404,
            'rsp' => 'login to print saved properties'
        ));
    }

    public static function routePrintSavedPropertiesSampler($params = array())
    {
        $user_id = isset($params['id']) ? filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT) : null;

        if ($user_id !== null) {
            $user = new User($user_id);
        } else {
            $user = User::getCurrentlyLoggedUser();
        }

        if ($user) {
            $properties = $user->getSavedProperties();

            \Timber::render('partials/home-finder/print-view-of-property-sampler.twig', array(
                'properties' => $properties
            ));
            exit();
        } else {
            self::renderJSON(array(
                'status' => 404,
                'rsp' => 'login to print saved properties'
            ));
        }
    }
}
