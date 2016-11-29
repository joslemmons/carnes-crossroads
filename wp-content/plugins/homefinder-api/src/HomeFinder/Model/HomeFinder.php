<?php namespace HomeFinder\Model;

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

    /**
     * @param $properties
     * @param $order_by
     * @param $order
     * @return mixed
     */
    private static function _sortProperties($properties, $order_by, $order)
    {
        switch ($order_by) {
            case 'price':
            default:
                usort($properties, function ($prop_a, $prop_b) use ($order) {
                    /* @var Property $prop_a */
                    /* @var Property $prop_b */
                    $prop_a_price = (float)preg_replace('/\D/', '', $prop_a->getPurchaseListPrice());
                    $prop_b_price = (float)preg_replace('/\D/', '', $prop_b->getPurchaseListPrice());

                    if ($prop_a_price === $prop_b_price) {
                        return 0;
                    } else if ($prop_a_price > $prop_b_price) {
                        return ($order === 'desc') ? -1 : 1;
                    } else {
                        return ($order === 'desc') ? 1 : -1;
                    }
                });
        }

        return $properties;
    }

    /**
     * @param Property[] $properties
     * @return array
     */
    private static function _convertMLSToPropertyBaseProperty(array $properties)
    {
        $return = array();
        foreach ($properties as $property) {
            if ($property->isFloorPlan === true) {
                $return[] = $property;
                continue;
            }

            if ($property->isFromPropertyBase()) {
                $return[] = $property;
                continue;
            }

//            if (trim($property->mls_number) === '') {
//                $return[] = $property;
//                continue;
//            }

//            $pbase_property = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_mls_number_' . $property->mls_number, function () use ($property) {
//                $property = PropertyBase::getWithMLSNumber($property->mls_number);
//
//                return $property;
//            }, 60 * 60 * 3);

            $pbase_property = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_' . $property->getAddress() . '_' . $property->getBedroomCount() . '_' . $property->getFullBathroomCount(), function () use ($property) {
                $property = PropertyBase::getWithAddressBedroomsAndFullBathrooms($property->getAddress(), $property->getBedroomCount(), $property->getFullBathroomCount());

                return $property;
            }, 60 * 60 * 3);

            // if we get a pbase property back, then use it. we've successfully converted. Else, use the old property
            if ($pbase_property !== false) {
                $return[] = $pbase_property;
            } else {
                $return[] = $property;
            }
        }

        return $return;
    }

    /**
     * @param HomeFinderFilters $filters
     * @param null $per_page
     * @param int $page
     * @param null $order_by
     * @param null $order
     * @return Result
     */
    public static function getProperties(HomeFinderFilters $filters, $per_page = null, $page = 1, $order_by = null, $order = null)
    {
        if (null === $per_page) {
            $per_page = HomeFinder::LISTINGS_PER_PAGE;
        }

        $result = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_properties_search_transient_' . $per_page . '_' . $page . '_' . $order_by . '_' . $order . '_' . $filters->getFiltersAsHashToUseAsId(), function () use ($filters, $per_page, $page, $order_by, $order) {

            $result = new Result();
            $result->items = array();
            $result->total = 0;

            if ($filters->shouldIncludeHomes() === true) {
                // search pbase
                $result = PropertyBase::getWithFilters($filters, $per_page, $page, $order_by, $order);
            }

            $startSearchingMLS = false;
            if ($order !== null) {
                $startSearchingMLS = true;
            } else {
                $property_base_properties_count = count($result->items);
                if ($property_base_properties_count < $per_page) {
                    $startSearchingMLS = true;
                }
            }

            // if MLS included, search MLS
            if ($filters->shouldIncludeMLS() === true) {
                $items = $result->items;
                $total = (int)$result->total;
                $mlsPage = false;
                if ($startSearchingMLS === true) {
                    $mlsPage = $filters->getMLSPage();
                    $mls_result = MLS::getWithFilters($filters, $per_page, $mlsPage, $order_by, $order);

                    if (false !== $mls_result && is_array($mls_result->items) && false === empty($mls_result->items)) {
                        // combine pbase with mls
                        $items = array_merge($result->items, $mls_result->items);
                        $total += (int)$mls_result->total;
                    }
                } else {
                    $total += (int)MLS::getCountWithFilters($filters);
                }

                $result = Result::withTotalAndPerPageAndCurrentItems($total, count($items), $items);
                if ($mlsPage !== false) {
                    $result->mlsPage = $mlsPage + 1;
                }
            }

            if (true === $filters->shouldIncludePlans() && $page === 1) {
                // all floor plans are pulled on the first request.
                $floor_plans = FloorPlan::withFilter($filters);

                if (false !== $floor_plans && is_array($floor_plans) && false === empty($floor_plans)) {
                    $total = (int)$result->total + count($floor_plans);
                    $items = array_merge($result->items, $floor_plans);

                    $result = Result::withTotalAndPerPageAndCurrentItems($total, count($items), $items);
                }
            }

            $paginator = new Paginator('page/(:num)');
            $paginator->setUrl(home_url() . '/api/home-finder/search/');
            $paginator->setItems($result->total, $per_page, $per_page);

            $result->paginator = $paginator;

//            $result->items = self::_convertMLSToPropertyBaseProperty($result->items);

            if ($order !== null) {
                $result->items = self::_sortProperties($result->items, $order_by, $order);
            }

            return $result;
        }, self::DEFAULT_CACHE_TIME);

        if(empty($result->items)) {
            delete_transient(apply_filters('timber/transient/slug', Config::getKeyPrefix() . 'home_finder_newly_listed_transient_' . $per_page . '_' . $page));
        }

        return $result;
    }

    /**
     * @param null $per_page
     * @param int $page
     * @param null $order_by
     * @param null $order
     * @return mixed
     */
    public static function getFeaturedProperties($per_page = null, $page = 1, $order_by = null, $order = null)
    {
        if (null === $per_page) {
            $per_page = HomeFinder::LISTINGS_PER_PAGE;
        }

        // 3 hour cache
        $properties = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_featured_properties_transient_' . $per_page . '_' . $page . '_' . $order_by . '_' . $order, function () use ($per_page, $page, $order_by, $order) {
            $properties_result = PropertyBase::getFeatured($per_page, $page, $order_by, $order);
            $paginator = new Paginator('page/(:num)');
            $paginator->setUrl(home_url() . '/api/home-finder/featured-properties/');
            $paginator->setItems($properties_result->total, $per_page, $per_page);

            $properties_result->paginator = $paginator;

            if ($order !== null) {
                $properties_result->items = self::_sortProperties($properties_result->items, $order_by, $order);
            }

            return $properties_result;
        }, self::DEFAULT_CACHE_TIME);

        if ($order === null) {
            // mix up the results
            $items = $properties->items;
            shuffle($items);
            $properties->items = $items;
        }

        if(empty($properties->items)) {
            delete_transient(apply_filters('timber/transient/slug', Config::getKeyPrefix() . 'home_finder_newly_listed_transient_' . $per_page . '_' . $page));
        }

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

        if(empty($properties->items)) {
            delete_transient(apply_filters('timber/transient/slug', Config::getKeyPrefix() . 'home_finder_newly_listed_transient_' . $per_page . '_' . $page));
        }

        return $properties;
    }

    /**
     * @param null $per_page
     * @param int $page
     * @return mixed|Result
     */
    public static function getRecentlySold($per_page = null, $page = 1)
    {
        if (null === $per_page) {
            $per_page = HomeFinder::LISTINGS_PER_PAGE;
        }

        // 3 hour cache
        $properties = \TimberHelper::transient(Config::getKeyPrefix() . 'home_finder_recently_sold_transient_' . $per_page . '_' . $page, function () use ($per_page, $page) {
            $properties_result = PropertyBase::getRecentlySold($per_page, $page);
            $paginator = new Paginator('page/(:num)');
            $paginator->setUrl(home_url() . '/api/home-finder/recently-sold/');
            $paginator->setItems($properties_result->total, $per_page, $per_page);

            $properties_result->paginator = $paginator;

            return $properties_result;
        }, self::DEFAULT_CACHE_TIME);

        if(empty($properties->items)) {
            delete_transient(apply_filters('timber/transient/slug', Config::getKeyPrefix() . 'home_finder_newly_listed_transient_' . $per_page . '_' . $page));
        }

        return $properties;
    }

    /**
     * @param User $user
     * @param null $order_by
     * @param null $order
     * @return Result
     */
    public static function getSavedListingsForUser(User $user, $order_by = null, $order = null)
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

        if ($order !== null) {
            $result->items = self::_sortProperties($result->items, $order_by, $order);
        }

        return $result;
    }
}