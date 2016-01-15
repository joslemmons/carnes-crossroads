<?php namespace HomeFinder\Model;

use App\Model\Helper;

class User extends \TimberUser
{
    const END_USER_ROLE_NAME = 'end_user';
    const SAVED_PROPERTIES_KEY = '_saved_property_ids';
    const VIEWED_PROPERTIES_KEY = '_viewed_property_ids';
    const SAVED_SEARCHES_KEY = '_saved_searches';
    const NOTIFICATION_OPTIONS_KEY = '_notification_option';

    const NO_NOTIFICATIONS = 'none';
    const DAILY_NOTIFICATIONS = 'daily';
    const WEEKLY_NOTIFICATIONS = 'weekly';

    // I know this is crazy. People log in with only their email so passwords don't matter. However,
    // a password needs to be set because WP is still used for authentication. So, use something unlikely
    // to be found out.
    const DEFAULT_PASSWORD = 'x@gtmTqDt4Qdr@mkfkTV6WWN';

    private static $_currentUser = null;

    public function __construct($uid)
    {
        parent::__construct($uid);
    }

    public static function bootstrap()
    {
        self::_registerEndUserRole();

        add_filter('pre_option_default_role', function ($default_role) {
            return self::END_USER_ROLE_NAME;
        });

        add_action('gform_user_registered', array(get_class(), 'loginAfterRegistration'), 10, 4);

        add_filter('login_redirect', array(get_class(), 'afterLoginRedirectToAccountPage'), 10, 3);
    }

    public static function afterLoginRedirectToAccountPage($redirect_to, $request, $user)
    {
        global $user;
        if (isset($user->roles) && is_array($user->roles)) {
            if (in_array(self::END_USER_ROLE_NAME, $user->roles)) {
                if (false !== stristr($redirect_to, 'wp-admin')) {
                    return home_url() . '/account/';
                }

                return $redirect_to;
            }
        }

        return $redirect_to;
    }

    public static function loginAfterRegistration($user_id, $user_config, $entry, $user_pass)
    {
        $user = get_user_by('id', $user_id);
        if ($user) {
            wp_set_current_user($user_id, $user->user_login);
            wp_set_auth_cookie($user_id);
            do_action('wp_login', $user->user_login);
        }
    }

    public static function _registerEndUserRole()
    {
        // allow this one capability if not on production
        $read = (true === Helper::isProduction() || true === Helper::isStaging()) ? false : true;

        remove_role(self::END_USER_ROLE_NAME);
        add_role(
            self::END_USER_ROLE_NAME,
            __('End User'),
            array(
                'read' => $read
            )
        );
    }

    /**
     * @param Property $property
     */
    public function markPropertyAsViewed(Property $property)
    {
        if (false == $this->hasViewedProperty($property)) {
            add_user_meta($this->id, self::VIEWED_PROPERTIES_KEY, $property->getId());
        }
    }

    /**
     * @param Property $property
     * @return bool
     */
    public function hasViewedProperty(Property $property)
    {
        $viewed_property_ids = $this->getViewedProperties();
        return (in_array($property->getId(), $viewed_property_ids));
    }

    /**
     * @return array
     */
    public function getViewedProperties()
    {
        $viewed_property_ids = get_user_meta($this->id, self::VIEWED_PROPERTIES_KEY);
        return $viewed_property_ids;
    }

    /**
     * @param Property $property
     * @return bool
     */
    public function hasSavedProperty(Property $property)
    {
        $saved_property_ids = $this->getSavedPropertyIds();
        return ($saved_property_ids && in_array($property->getId(), $saved_property_ids));
    }

    /**
     * @param Property $property
     */
    public function saveProperty(Property $property)
    {
        if (false == $this->hasSavedProperty($property)) {
            add_user_meta($this->id, self::SAVED_PROPERTIES_KEY, $property->getId());
        }
    }

    /**
     * @param Property $property
     */
    public function unSaveProperty(Property $property)
    {
        if (true == $this->hasSavedProperty($property)) {
            delete_user_meta($this->id, self::SAVED_PROPERTIES_KEY, $property->getId());
        }
    }

    /**
     * @return array
     */
    public function getSavedPropertyIds()
    {
        $saved_property_ids = get_user_meta($this->id, self::SAVED_PROPERTIES_KEY);
        return $saved_property_ids;
    }

    public function getSavedProperties($count = -1)
    {
        $properties = array();
        $saved_property_ids = $this->getSavedPropertyIds();

        $i = 0;
        foreach ($saved_property_ids as $property_id) {
            if ($count === -1 || $i < $count) {
                $property = Property::withId($property_id);
                $properties[] = $property;
                $i++;
            } else {
                break;
            }
        }

        return $properties;
    }

    /**
     * @return bool|User
     */
    public static function getCurrentlyLoggedUser()
    {
        $currently_logged_user = false;
        if (null === self::$_currentUser) {
            $current_user = wp_get_current_user();

            if (isset($current_user->ID) && $current_user->ID !== 0) {
                $currently_logged_user = new User($current_user->ID);
            }
        }

        return $currently_logged_user;
    }

    /**
     * @param $email
     * @return false|\WP_User
     */
    public static function createOrLoginAndAuthenticateUser($email)
    {
        $user = get_user_by('email', $email);

        if (false === $user) {
            // doesn't exist. make it!
            $user_id = wp_create_user($email, self::DEFAULT_PASSWORD, $email);
            if (is_wp_error($user)) {
                $codes = $user->get_error_codes();
            } else {
                $user = get_user_by('id', $user_id);
            }
        }

        if (false !== $user) {
            wp_set_current_user($user->id);
            wp_set_auth_cookie($user->id);
//            do_action('wp_login', $user->user_login);
        }

        return $user;
    }

    public function getRawSavedSearches()
    {
        $saved_searches = get_user_meta($this->id, self::SAVED_SEARCHES_KEY);
        return $saved_searches;
    }

    public function getSavedSearchFilters()
    {
        $raw_saved_searches = $this->getRawSavedSearches();

        $saved_searches = array();
        foreach ($raw_saved_searches as $raw_filters) {
            $filters = HomeFinderFilters::withRawFilters($raw_filters);
            $saved_searches[] = $filters;
        }

        return $saved_searches;
    }

    public function saveSearch(HomeFinderFilters $filters)
    {
        if (false == $this->hasSavedSearch($filters)) {
            $raw_filters = $filters->getRawFilters();
            add_user_meta($this->id, self::SAVED_SEARCHES_KEY, $raw_filters);
        }
    }

    public function unSaveSearch(HomeFinderFilters $filters)
    {
        delete_user_meta($this->id, self::SAVED_SEARCHES_KEY, $filters->getRawFilters());
    }

    public function hasSavedSearch(HomeFinderFilters $filters)
    {
        $saved_searches = $this->getSavedSearchFilters();
        foreach ($saved_searches as $saved_filters) {
            /* @var HomeFinderFilters $saved_filters */
            $a = serialize($saved_filters->getRawFilters());
            $b = serialize($filters->getRawFilters());

            if ($a === $b) {
                return true;
            }
        }

        return false;
    }

    public static function isNotificationOptionValid($option)
    {
        $valid_options = self::getNotificationOptions();
        return (in_array($option, $valid_options));
    }

    public function getNotificationOption()
    {
        $option = get_user_meta($this->id, self::NOTIFICATION_OPTIONS_KEY, true);

        if ('' === $option) {
            $option = self::NO_NOTIFICATIONS;
        }

        return $option;
    }

    public function setNotificationOption($option)
    {
        update_user_meta($this->id, self::NOTIFICATION_OPTIONS_KEY, $option);
    }

    public static function getNotificationOptions()
    {
        return array(
            self::NO_NOTIFICATIONS,
            self::DAILY_NOTIFICATIONS,
            self::WEEKLY_NOTIFICATIONS
        );
    }

}