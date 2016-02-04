<?php namespace HomeFinder\Model;

class Metric
{

    private static $_km = null;

    const LANDING_PAGE_INTEREST = 'LP Interest';
    const AD_CAMPAIGN_HIT = 'Ad Campaign Hit';
    const PROPERTY_ACTION_PRINT = 'Property Action Print';
    const PROPERTY_ACTION_SHARE = 'Property Action Share';
    const PROPERTY_ACTION_FAVORITE = 'Property Action Favorite';
    const FORM_SUBMISSION = 'Form Submitted';
    const PAGE_VIEW = 'Page View';
    const PROPERTY_LISTING_VIEW = 'Property Listing View';
    const SEARCH = 'Search';

    public static function bootstrap()
    {
        add_action('gform_after_submission', array(get_class(), 'trackFormSubmission'), 10, 2);
        add_action('template_redirect', array(get_class(), 'trackPageView'), 10, 2);
    }

    /**
     * @return \KISSmetrics\Client|null
     */
    private static function _getKISSmetrics()
    {
        if (self::$_km === null) {
            self::$_km = new \KISSmetrics\Client(
                '7679a70c909fc65210725a4dc1d0e754ee0085b4',
                \KISSmetrics\Transport\Sockets::initDefault()
            );
        }

        return self::$_km;
    }

    public static function trackFormSubmission($entry, $form)
    {
        $km = self::_getKISSmetrics();
        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            return;
        }

        $km->identify($user->getEmail())
            ->set(array(
                'Form Title' => $form['title'],
                'Entry ID' => $entry['id'],
                'source_url' => $entry['source_url']
            ))
            ->record(self::FORM_SUBMISSION);

        $km->submit();
    }

    public static function trackPageView()
    {
        global $post;

        $km = self::_getKISSmetrics();
        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            return;
        }

        if (isset($post->post_type) === false || $post->post_type !== 'page') {
            return;
        }

        $km->identify($user->getEmail())
            ->set(array(
                'Page Title' => $post->post_title,
                'Page ID' => $post->id
            ))
            ->record(self::PAGE_VIEW);

        $km->submit();
    }

    public static function trackPropertyListingView(Property $property)
    {
        $km = self::_getKISSmetrics();

        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            return;
        }

        $data = array();
        if ($property->isFloorPlan) {
            $data['Builder'] = $property->getBuilder()->title();
            $data['Floor Plan'] = $property->title;
        } else {
            $data = array(
                'Property Base ID' => $property->property_base_id,
                'Property MLS Number' => $property->getMLSNumber(),
                'Property Address' => $property->getAddress()
            );
        }

        $km->identify($user->getEmail())
            ->set($data)
            ->record(self::PROPERTY_LISTING_VIEW);

        $km->submit();
    }

    public static function trackSearch(HomeFinderFilters $filters)
    {
        $km = self::_getKISSmetrics();

        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            return;
        }

        $km->identify($user->getEmail())
            ->set(array(
                'Friendly Search Name' => $filters->getFriendlyName()
            ))
            ->record(self::SEARCH);

        $km->submit();
    }

    public static function trackShareProperty(Property $property, $social_network)
    {
        $km = self::_getKISSmetrics();

        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            return;
        }

        $km->identify($user->getEmail())
            ->set(array(
                'Property Base ID' => $property->property_base_id,
                'Property MLS Number' => $property->getMLSNumber(),
                'Property Address' => $property->getAddress()
            ))
            ->record(self::PROPERTY_ACTION_SHARE);

        $km->submit();
    }

    public static function trackPrintProperty(Property $property)
    {
        $km = self::_getKISSmetrics();

        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            return;
        }

        $km->identify($user->getEmail())
            ->set(array(
                'Property Base ID' => $property->property_base_id,
                'Property MLS Number' => $property->getMLSNumber(),
                'Property Address' => $property->getAddress()
            ))
            ->record(self::PROPERTY_ACTION_PRINT);

        $km->submit();
    }

    public static function trackFavoriteProperty(Property $property)
    {
        $km = self::_getKISSmetrics();

        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            return;
        }

        $km->identify($user->getEmail())
            ->set(array(
                'Property Base ID' => $property->property_base_id,
                'Property MLS Number' => $property->getMLSNumber(),
                'Property Address' => $property->getAddress()
            ))
            ->record(self::PROPERTY_ACTION_FAVORITE);

        $km->submit();
    }

    // TODO
    public static function trackAdCampaignHit($campaign_title)
    {
        $km = self::_getKISSmetrics();

        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            return;
        }

        $km->identify($user->getEmail())
            ->set(array(
                'Landing Page Title' => $campaign_title
            ))
            ->record(self::AD_CAMPAIGN_HIT);

        $km->submit();
    }

    public static function trackLandingPageFormSubmission($landing_page_title)
    {
        $km = self::_getKISSmetrics();

        $user = User::getCurrentlyLoggedUser();

        if ($user === false) {
            return;
        }

        $km->identify($user->getEmail())
            ->set(array(
                'Landing Page Title' => $landing_page_title
            ))
            ->record(self::LANDING_PAGE_INTEREST);

        $km->submit();
    }
}