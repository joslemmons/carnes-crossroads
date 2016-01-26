<?php namespace HomeFinder\Model;

use App\Model\Builder;
use App\Model\Config;
use App\Model\FloorPlan;
use App\Model\NewOfferings;
use HomeFinder\Request\MLS;
use HomeFinder\Request\PropertyBase;
use Voodoo\Paginator;

class HomeFinder
{
    const LISTINGS_PER_PAGE = 12;
    const DEFAULT_CACHE_TIME = 10800;

    public static function getProperties(HomeFinderFilters $filters, $per_page = null, $page = 1)
    {
        if (null === $per_page) {
            $per_page = HomeFinder::LISTINGS_PER_PAGE;
        }

        $result = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_properties_search_transient_' . $per_page . '_' . $page . '_' . $filters->getFiltersAsHashToUseAsId(), function () use ($filters, $per_page, $page) {
            // search pbase
            $result = PropertyBase::getWithFilters($filters, $per_page, $page);

            // if MLS included, search MLS
            if (true === $filters->shouldIncludeMLS()) {
                // ignore overlap between pbase and MLS
                // TODO: this needs to include past searches for this filter. So... pbase on page 1 pulled a property
                // TODO: that should continue to be ignored on page 10 of MLS
                $filters->setPropertiesToExclude($result->items);

                $mls_result = MLS::getWithFilters($filters, $per_page, $page);

                if (false !== $mls_result && is_array($mls_result->items) && false === empty($mls_result->items)) {
                    // combine pbase with mls
                    $total = (int)$result->total + (int)$mls_result->total;
                    $items = array_merge($result->items, $mls_result->items);

                    $result = Result::withTotalAndPerPageAndCurrentItems($total, count($items), $items);
                }
            }

            if (true === $filters->shouldIncludePlans()) {
                $floor_plans = FloorPlan::withFilter($filters);

                if (false !== $floor_plans && is_array($floor_plans) && false === empty($floor_plans)) {
                    $total = (int)$result->total + count($floor_plans);
                    $items = array_merge($result->items, $floor_plans);

                    $result = Result::withTotalAndPerPageAndCurrentItems($total, count($items), $items);
                }
            }

            $paginator = new Paginator('page/(:num)');
            $paginator->setUrl(home_url() . '/api/home-finder/properties/');
            $paginator->setItems($result->total, $per_page, $per_page);

            $result->paginator = $paginator;

            return $result;
        }, self::DEFAULT_CACHE_TIME);

        return $result;
    }

    /**
     * @param int $per_page
     * @param int $page
     * @return Result
     */
    public static function getFeaturedProperties($per_page = null, $page = 1)
    {
        if (null === $per_page) {
            $per_page = HomeFinder::LISTINGS_PER_PAGE;
        }

        // 3 hour cache
        $properties = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_featured_properties_transient_' . $per_page . '_' . $page, function () use ($per_page, $page) {
            $properties_result = PropertyBase::getFeatured($per_page, $page);
            $paginator = new Paginator('page/(:num)');
            $paginator->setUrl(home_url() . '/api/home-finder/featured-properties/');
            $paginator->setItems($properties_result->total, $per_page, $per_page);

            $properties_result->paginator = $paginator;

            return $properties_result;
        }, self::DEFAULT_CACHE_TIME);

        // mix up the results
        $items = $properties->items;
        shuffle($items);
        $properties->items = $items;

        return $properties;
    }

    public static function getNewOfferings($per_page = null, $page = 1)
    {
        if (null === $per_page) {
            $per_page = HomeFinder::LISTINGS_PER_PAGE;
        }

        $total = 0;

        $termInfo = get_term_by('slug', 'new-offerings', 'category');
        if (false !== $termInfo) {
            $total = $termInfo->count;
        }

        $offerings = NewOfferings::all($per_page, $page);

        $result = Result::withTotalAndPerPageAndCurrentItems($total, $per_page, $offerings);
        $paginator = new Paginator('page/(:num)');
        $paginator->setUrl(home_url() . '/api/home-finder/new-offerings/');
        $paginator->setItems($total, $per_page, $per_page);

        $result->paginator = $paginator;

        return $result;
    }

    public static function getRecentlyListed($per_page = null, $page = 1)
    {
        if (null === $per_page) {
            $per_page = HomeFinder::LISTINGS_PER_PAGE;
        }

        // 3 hour cache
        $properties = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_newly_listed_transient_' . $per_page . '_' . $page, function () use ($per_page, $page) {
            $properties_result = PropertyBase::getRecentlyListed($per_page, $page);
            $paginator = new Paginator('page/(:num)');
            $paginator->setUrl(home_url() . '/api/home-finder/recently-listed/');
            $paginator->setItems($properties_result->total, $per_page, $per_page);

            $properties_result->paginator = $paginator;

            return $properties_result;
        }, self::DEFAULT_CACHE_TIME);

        return $properties;
    }

    /**
     * @param User $user
     * @return Result
     */
    public static function getSavedListingsForUser(User $user)
    {
        $result = new Result();

        $properties = array();
        $saved_property_ids = $user->getSavedPropertyIds();
        foreach ($saved_property_ids as $property_id) {
            $property = Property::withId($property_id);
            $properties[] = $property;
        }
        $result->items = $properties;

        $result->per_page = count($saved_property_ids);
        $result->total = count($saved_property_ids);

        return $result;
    }
}