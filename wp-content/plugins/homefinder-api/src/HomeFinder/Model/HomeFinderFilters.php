<?php namespace HomeFinder\Model;

use App\Controller\Router;
use Carbon\Carbon;
use Cocur\Slugify\Slugify;

class HomeFinderFilters
{
    private $_propertyTypes = array();
    private $_itemTypes = array();
    private $_neighborhoods = array();
    private $_minPrice = '';
    private $_maxPrice = '';
    private $_bedrooms = array();
    private $_bathrooms = array();
    private $_minLastUpdate = '';
    private $_minSquareFootage = '';
    private $_maxSquareFootage = '';
    private $_homeFeatures = array();
    private $_views = array();
    private $_searchMLS = false;
    private $_searchAddress = '';
    private $_listingAgents = array();
    private $_includePlans = true;
    private $_includeHomes = true;
    private $_builders = '';

    private $_rawMinLastUpdate = '';
    private $_rawSquareFootage = array();
    private $_rawHomeFeatures = array();
    private $_rawViews = array();
    private $_rawPropertyTypes = array();
    private $_rawNeighborhoods = array();
    private $_rawPrices = array();
    private $_rawBedrooms = array();
    private $_rawBathrooms = array();
    private $_rawSearchMLS = '';
    private $_rawSearchAddress = '';
    private $_rawListingAgents = array();
    private $_rawIncludePlans = '';
    private $_rawBuilders = '';
    private $_rawIncludeHomes = '';

    private $_propertiesToExclude = array();

    private $_mlsPage = 1;

    public function setPropertiesToExclude($properties)
    {
        $this->_propertiesToExclude = $properties;
    }

    public function getPropertiesToExclude()
    {
        return $this->_propertiesToExclude;
    }

    private static function _getFilterFromArrayByKey($array, $key)
    {
        $filters = (isset($array[$key])) ? $array[$key] : array();

        if (false === is_array($filters)) {
            $filters = array($filters);
        }

        if (false === empty($filters)) {
            // sanitize
            $filters = array_map(function ($el) {
                return sanitize_text_field($el);
            }, $filters);

            // remove empty strings
            $filters = array_filter($filters, function ($el) {
                return (trim($el) !== '');
            });
        }

        return $filters;
    }

    public static function withRawFilters($data)
    {
        $filters = new HomeFinderFilters();

        $shouldSearchMLS = (isset($data['searchMLS'])) ? sanitize_text_field($data['searchMLS']) : false;

        if ($shouldSearchMLS === 'true') {
            $shouldSearchMLS = true;
        } else {
            $shouldSearchMLS = false;
        }

        $filters->setShouldSearchMLS($shouldSearchMLS);

        $mlsPage = (isset($data['mlsPage'])) ? filter_var($data['mlsPage'], FILTER_VALIDATE_INT) : false;
        $filters->setMLSPage($mlsPage);

        $shouldIncludePlans = (isset($data['includePlans'])) ? sanitize_text_field($data['includePlans']) : true;
        if ($shouldIncludePlans === 'true') {
            $shouldIncludePlans = true;
        }

        $shouldIncludeHomes = (isset($data['includeHomes'])) ? sanitize_text_field($data['includeHomes']) : true;
        if ($shouldIncludeHomes === 'true') {
            $shouldIncludeHomes = true;
        }

        $filters->setShouldIncludePlans($shouldIncludePlans);
        $filters->setShouldIncludeHomes($shouldIncludeHomes);

        $searchAddress = (isset($data['searchAddress'])) ? sanitize_text_field($data['searchAddress']) : '';
        $filters->setSearchAddress($searchAddress);

        $minLastUpdate = (isset($data['lastUpdate'])) ? filter_var($data['lastUpdate'], FILTER_VALIDATE_INT) : '';

        $filters->_rawPropertyTypes = self::_getFilterFromArrayByKey($data, 'propertyTypes');
        $filters->_rawNeighborhoods = self::_getFilterFromArrayByKey($data, 'neighborhoods');
        $filters->_rawPrices = self::_getFilterFromArrayByKey($data, 'prices');
        $filters->_rawBedrooms = self::_getFilterFromArrayByKey($data, 'bedrooms');
        $filters->_rawBathrooms = self::_getFilterFromArrayByKey($data, 'bathrooms');
        $filters->_rawSearchMLS = ($shouldSearchMLS) ? 'true' : 'false';
        $filters->_rawSearchAddress = $searchAddress;
        $filters->_rawListingAgents = self::_getFilterFromArrayByKey($data, 'listingAgents');
        $filters->_rawMinLastUpdate = $minLastUpdate;
        $filters->_rawSquareFootage = self::_getFilterFromArrayByKey($data, 'squareFootage');
        $filters->_rawHomeFeatures = self::_getFilterFromArrayByKey($data, 'homeFeatures');
        $filters->_rawViews = self::_getFilterFromArrayByKey($data, 'views');
        $filters->_rawIncludePlans = ($shouldIncludePlans) ? 'true' : 'false';
        $filters->_rawIncludeHomes = ($shouldIncludeHomes) ? 'true' : 'false';
        $filters->_rawBuilders = self::_getFilterFromArrayByKey($data, 'builders');
        
        // add the various filters passed as GET params to $filters
        $filters->setPropertyTypes(self::_getFilterFromArrayByKey($data, 'propertyTypes'));
        $filters->setNeighborhoods(self::_getFilterFromArrayByKey($data, 'neighborhoods'));
        $filters->setHomeFeatures(self::_getFilterFromArrayByKey($data, 'homeFeatures'));
        $filters->setViews(self::_getFilterFromArrayByKey($data, 'views'));
        $filters->setBuilders(self::_getFilterFromArrayByKey($data, 'builders'));

        $prices = self::_getFilterFromArrayByKey($data, 'prices');
        if (true === is_array($prices)) {
            $prices = array_pop($prices);
        }
        $prices_split_by_hyphen = explode('-', $prices);
        if (2 === count($prices_split_by_hyphen)) {
            $min_price = filter_var($prices_split_by_hyphen[0], FILTER_VALIDATE_INT, array(
                'options' => array(
                    'default' => 0
                )
            ));
            $max_price = filter_var($prices_split_by_hyphen[1], FILTER_VALIDATE_INT, array(
                'options' => array(
                    'default' => 5000000
                )
            ));
            $filters->setMinPrice($min_price);
            $filters->setMaxPrice($max_price);
        }

        $square_footages = self::_getFilterFromArrayByKey($data, 'squareFootage');
        if (true === is_array($square_footages)) {
            $square_footages = array_pop($square_footages);
        }
        $square_footages_split_by_hyphen = explode('-', $square_footages);
        if (2 === count($square_footages_split_by_hyphen)) {
            $min_square_footage = filter_var($square_footages_split_by_hyphen[0], FILTER_VALIDATE_INT, array(
                'options' => array(
                    'default' => 0
                )
            ));
            $max_square_footage = filter_var($square_footages_split_by_hyphen[1], FILTER_VALIDATE_INT, array(
                'options' => array(
                    'default' => 10000
                )
            ));
            $filters->setMinSquareFootage($min_square_footage);
            $filters->setMaxSquareFootage($max_square_footage);
        }

        $bedrooms = self::_getFilterFromArrayByKey($data, 'bedrooms');
        $bedrooms = array_map(function ($el) {
            return filter_var($el, FILTER_VALIDATE_INT);
        }, $bedrooms);
        // pbase expects a list. so >=3 won't work. need 3,4,5,6,7+. I chose 20 since
        // that's probably as many bedrooms as you'll find
        if (1 === count($bedrooms)) {
            $bedrooms = range($bedrooms[0], 20);
        }
        $filters->setBedrooms($bedrooms);

        $bathrooms = self::_getFilterFromArrayByKey($data, 'bathrooms');
        $bathrooms = array_map(function ($el) {
            return filter_var($el, FILTER_VALIDATE_INT);
        }, $bathrooms);
        // pbase expects a list. so >=3 won't work. need 3,4,5,6,7+. I chose 20 since
        // that's probably as many bathrooms as you'll find
        if (1 === count($bathrooms)) {
            $bathrooms = range($bathrooms[0], 20);
        }
        $filters->setbathrooms($bathrooms);

        $listingAgents = self::_getFilterFromArrayByKey($data, 'listingAgents');
        $filters->setListingAgents($listingAgents);

        return $filters;
    }

    public function setListingAgents($listingAgents)
    {
        $this->_listingAgents = $listingAgents;
    }

    public static function withREQUESTParams()
    {
        $filters = HomeFinderFilters::withRawFilters($_REQUEST);

        return $filters;
    }

    public function getRawFilters()
    {
        $filters = array(
//            'propertyTypes' => $this->_rawPropertyTypes,
//            'neighborhoods' => $this->_rawNeighborhoods,
            'prices' => $this->_rawPrices,
            'bedrooms' => $this->_rawBedrooms,
            'bathrooms' => $this->_rawBathrooms,
//            'searchMLS' => $this->_rawSearchMLS,
            'searchAddress' => $this->_rawSearchAddress,
//            'mlsPage' => $this->getMLSPage(),
//            'lastUpdate' => $this->_rawMinLastUpdate,
            'squareFootage' => $this->_rawSquareFootage,
            'homeFeatures' => $this->_rawHomeFeatures,
//            'views' => $this->_rawViews,
            'builders' => $this->_rawBuilders,
            'includePlans' => $this->_rawIncludePlans,
            'includeHomes' => $this->_rawIncludeHomes
        );

        return $filters;
    }

    /**
     * @return bool
     */
    public function shouldIncludeMLS()
    {
//        if ($this->getListingAgents() !== false) {
//            // don't show MLS when trying to view a listing agents listings. This is because MLS
//            // doesn't have a filter for listing agent that we can work with
//            return false;
//        }
//
//        return true;

        // always false until DI/CX wants to flip on
        return false;
    }

    public function setShouldSearchMLS($should)
    {
        $this->_searchMLS = $should;
    }

    public function getBuilders()
    {
        if ($this->_builders === '' || empty($this->_builders)) {
            return false;
        }

        return implode(',', $this->_builders);
    }

    public function setBuilders($builderId)
    {
        $this->_builders = $builderId;
    }

    public function shouldIncludePlans()
    {
        return $this->_includePlans;
    }

    public function setShouldIncludePlans($should)
    {
        $this->_includePlans = $should;
    }

    public function shouldIncludeHomes()
    {
        return $this->_includeHomes;
    }

    public function setShouldIncludeHomes($should)
    {
        $this->_includeHomes = $should;
    }

    public function setSearchAddress($address)
    {
        $this->_searchAddress = $address;
    }

    public function setMinDaysAgoLastUpdate($lastUpdate)
    {
        $this->_minLastUpdate = $lastUpdate;
    }

    public function setMinSquareFootage($footage)
    {
        $this->_minSquareFootage = $footage;
    }

    public function setMaxSquareFootage($footage)
    {
        $this->_maxSquareFootage = $footage;
    }

    public function setHomeFeatures($homeFeatures)
    {
        $this->_homeFeatures = $homeFeatures;
    }

    public function setViews($views)
    {
        $this->_views = $views;
    }

    public function setPropertyTypes(array $propertyTypes)
    {
        // to filter by "homesite" you need to use the itemType pbase key
        if (in_array('homesite', $propertyTypes)) {
            if (($key = array_search('homesite', $propertyTypes)) !== false) {
                unset($propertyTypes[$key]);
            }

            $this->setItemTypes(array('Plot'));
        }
        $this->_propertyTypes = $propertyTypes;
    }

    public function setItemTypes(array $itemTypes)
    {
        $this->_itemTypes = $itemTypes;
    }

    public function setNeighborhoods(array $neighborhoods)
    {
        $this->_neighborhoods = $neighborhoods;
    }

    public function setMinPrice($price)
    {
        $this->_minPrice = $price;
    }

    public function setMaxPrice($price)
    {
        $this->_maxPrice = $price;
    }

    public function setBedrooms(array $bedrooms)
    {
        $this->_bedrooms = $bedrooms;
    }

    public function setBathrooms(array $bathrooms)
    {
        $this->_bathrooms = $bathrooms;
    }

    public function setMinLastUpdate($minLastUpdate)
    {
        $this->_minLastUpdate = $minLastUpdate;
    }

    /**
     * @return bool|string
     */
    public function getPropertyTypes()
    {
        if (empty($this->_propertyTypes)) {
            return false;
        }

        if (count($this->_propertyTypes) === 3 && count($this->_itemTypes) === 1 && $this->_itemTypes[0] === 'Plot') {
            // search everything. in order to do this, remove the property types
            return false;
        }

        return implode(',', $this->_propertyTypes);
    }

    public function getItemTypes()
    {
        if (empty($this->_itemTypes)) {
            return false;
        }

        // if all property types selected, then include "unit" in the item types so that
        // everything is returned. This is because property types (home, condo, townhome)
        // are separate from homesites as a field in PB. So, if you wanted to see all...
        // you can't do a OR sort of statement. Can't be a home and a site.

        if (count($this->_propertyTypes) === 3 && count($this->_itemTypes) === 1 && $this->_itemTypes[0] === 'Plot') {
            // search everything. in order to do this, remove the property types
            return false;
        }

        return implode(',', $this->_itemTypes);
    }

    /**
     * @return bool|string
     */
    public function getNeighborhoods()
    {
        if (empty($this->_neighborhoods)) {
            return false;
        }

        $neighborhoods_by_id = self::getPropertyBaseNeighborhoods();
        if (count($this->_neighborhoods) === count($neighborhoods_by_id)) {
            return false;
        }

        return implode(',', $this->_neighborhoods);
    }

    public function getMinPrice()
    {
        if ($this->_minPrice === '') {
            return 0;
        }

        return $this->_minPrice;
    }

    public function getMaxPrice()
    {
        if ($this->_maxPrice === '' || $this->_maxPrice === 'any') {
            return 5000000;
        }

        return $this->_maxPrice;
    }

    /**
     * @return bool|string
     */
    public function getBedrooms()
    {
        if (empty($this->_bedrooms)) {
            return false;
        }

        return implode(',', $this->_bedrooms);
    }

    /**
     * @return bool|string
     */
    public function getBathrooms()
    {
        if (empty($this->_bathrooms)) {
            return false;
        }

        return implode(',', $this->_bathrooms);
    }

    /**
     * @return bool|string
     */
    public function getListingAgents()
    {
        if (empty($this->_listingAgents)) {
            return false;
        }

        return implode(',', $this->_listingAgents);
    }

    public function getMinLastUpdate()
    {
        if ('' === $this->_minLastUpdate || empty($this->_minLastUpdate)) {
            return false;
        }

        return $this->_minLastUpdate;
    }

    public function getMinSquareFootage()
    {
        if ($this->_minSquareFootage === '') {
            return 0;
        }

        return $this->_minSquareFootage;
    }

    public function getMaxSquareFootage()
    {
        if ($this->_maxSquareFootage === '' || $this->_maxSquareFootage === 'any') {
            return 10000;
        }

        return $this->_maxSquareFootage;
    }

    public function getHomeFeatures()
    {
        if (empty($this->_homeFeatures)) {
            return false;
        }

        return implode(',', $this->_homeFeatures);
    }

    public function getViews()
    {
        if (empty($this->_views)) {
            return false;
        }

        return implode(',', $this->_views);
    }

    public function getSearchAddress()
    {
        if ('' === $this->_searchAddress) {
            return false;
        }

        return $this->_searchAddress;
    }

    public function getFiltersAsHashToUseAsId()
    {
        $slug = new Slugify();
        return $slug->slugify($this->getFriendlyName() . ' ' . $this->_searchMLS . ' ' . $this->_searchAddress . ' ' . $this->_includePlans . ' ' . $this->_includeHomes . ' ' . $this->getBuilders() . ' ' . $this->getHomeFeatures());
    }

    public function getAreaFiltersForMLSRequest()
    {
        // TODO: set the MLS area for Carnes
        return ['match_phrase' => ['area' => '77 - Daniel Island']];
    }

    public function getPricesFiltersForMLSRequest()
    {
        $min_price = $this->getMinPrice();
        if (false === $min_price) {
            $min_price = 0;
        }

        $max_price = $this->getMaxPrice();
        if (false === $max_price) {
            $max_price = 100000000;
        }

        return ['range' => ['list_price' => ['gte' => $min_price, 'lte' => $max_price]]];
    }

    public function getSquareFootageFiltersForMLSRequest()
    {
        $min_square_footage = $this->getMinSquareFootage();
        if (false === $min_square_footage) {
            $min_square_footage = 0;
        }

        $max_square_footage = $this->getMaxSquareFootage();
        if (false === $max_square_footage) {
            $max_square_footage = 100000000;
        }

        return ['range' => ['apx_sqft' => ['gte' => $min_square_footage, 'lte' => $max_square_footage]]];
    }

    public function getLastUpdateFiltersForMLSRequest()
    {
        if (false !== $this->getMinLastUpdate()) {
            $today = Carbon::now();
            $today->subDays((int)$this->getMinLastUpdate());
            return ['range' => ['list_date' => ['gte' => $today->timestamp]]];
        }

        return [];
    }

    public function getNeighborhoodFiltersForMLSRequest()
    {
        $neighborhoods = $this->getNeighborhoods();
        $neighborhoodsSplitByComma = explode(',', $neighborhoods);
        $neighborhoodsById = self::getPropertyBaseNeighborhoods();

        if (
            count($neighborhoodsSplitByComma) === count($neighborhoodsById) ||
            $neighborhoods === false
        ) {
            // search everything. in order to do this, remove the property types
            return [];
        }

        $neighborhoods = array_map(function ($el) use ($neighborhoodsById) {
            if (isset($neighborhoodsById[$el])) {
                return $neighborhoodsById[$el];
            }

            return '';
        }, $neighborhoodsSplitByComma);

        if (empty($neighborhoods) === false) {
            return ['match_phrase' => ['z_textsearch_neighborhood' => implode(' ', $neighborhoods)]];
        }

        return [];
    }

    public function getPropertyTypeFiltersForMLSRequest()
    {
        $classes = array();
        $subTypes = array();

        $propertyTypes = $propertyTypesAsArray = $this->getPropertyTypes();
        if ($propertyTypes === false) {
            // check raw. the property type is altered for pbase to remove
            // homesite and put it in a different filter.
            $rawPropertyTypes = $this->_rawPropertyTypes;
            if (empty($rawPropertyTypes) === false) {
                $propertyTypes = $rawPropertyTypes;
            }
        }

        if (empty($rawPropertyTypes) === true) {
            return [];
        }

        // search all if all selected by not setting any search terms
        if (count($propertyTypes) === 4) {
            return [];
        }

        $has_vac = false;
        $has_res = false;
        $has_detached = false;
        $has_attached = false;
        if ($propertyTypes) {
            foreach ($propertyTypes as $propertyType) {
                if ($propertyType === 'homesite') {
                    $has_vac = true;
                } else {
                    $has_res = true;

                    if ($propertyType === 'home') {
                        $has_detached = true;
                    } elseif ($propertyType === 'condominium' || $propertyType === 'townhome') {
                        $has_attached = true;
                    } else {

                    }
                }
            }
        }

        if ($has_vac) {
            $classes[] = 'VAC';
        }

        if ($has_res) {
            $classes[] = 'RES';
        }

        if ($has_detached) {
            $subTypes[] = 'detached';
        }

        if ($has_attached) {
            $subTypes[] = 'attached';
        }

        $filters = array();
        if ($classes !== '') {
            $filters[] = ['match' => ['class' => ['query' => implode(' ', $classes), 'operator' => 'or']]];
        }

        if ($subTypes !== '') {
            $filters[] = ['match' => ['sub_type' => ['query' => implode(' ', $subTypes), 'operator' => 'or']]];
        }

        return $filters;
    }

    public function getBedroomsFiltersForMLSRequest()
    {
        $bedrooms = $this->getBedrooms();

        if (false === $bedrooms) {
            return [];
        }

        $bedrooms = explode(',', $bedrooms);
        $bedroom = array_shift($bedrooms);

        return ['range' => ['bedrooms' => ['gte' => $bedroom]]];
    }

    public function getBathroomsFiltersForMLSRequest()
    {
        $bathrooms = $this->getBathrooms();

        if (false === $bathrooms) {
            return [];
        }

        $bathrooms = explode(',', $bathrooms);
        $bathrooms = array_shift($bathrooms);

        return ['range' => ['baths_full' => ['gte' => $bathrooms]]];
    }

    public function getHomeFeaturesForMLSRequest()
    {
        $homeFeatures = $this->getHomeFeatures();
        if ($homeFeatures === false) {
            return [];
        }

        $homeFeaturesAsArray = explode(',', $homeFeatures);

        $return = array();
        foreach ($homeFeaturesAsArray as $feature) {
            $return[] = ['multi_match' => ['fields' => [
                'amenities',
                'public_remarks',
                'misc_exterior',
                'misc_interior',
                'rooms',
                'master_bedroom'
            ], 'query' => $feature]];
        }

        return $return;
    }

    public function getViewsForMLSRequest()
    {
        $views = $this->getViews();
        if ($views === false) {
            return [];
        }

        $viewsAsArray = explode(',', $views);

        $return = array();
        foreach ($viewsAsArray as $view) {
            $return[] = ['match' => ['lot_description' => ['query' => $view, 'type' => 'phrase']]];
        }

        return $return;
    }

    public function getPropertiesToExcludeForMLSRequest()
    {
        $propertiesToExclude = $this->getPropertiesToExclude();
        $mlsNumbersToExclude = array();
        foreach ($propertiesToExclude as $property) {
            /* @var Property $property */
            $mlsNumbersToExclude[] = ['match' => ['z_textsearch_mls_num' => $property->getMLSNumber()]];
        }

        return $mlsNumbersToExclude;
    }

    public function getFiltersAsArrayForPropertyBaseRequest()
    {
        // filter options
        //pb__IsForSale__c=true
        //min_pb__PurchaseListPrice__c=100 (>=)
        //max_pb__PurchaseListPrice__c=2000 (<=)
        //like_name=6383
        //in_pb__UnitBedrooms__c=Studio,1,2,3

        $filters = array();

        // if an address search is performed, then we don't care about other filters
        if (false !== $this->getSearchAddress()) {
            $filters['like_AddressWeb__c'] = $this->getSearchAddress();
        } else {
            if (false !== $this->getPropertyTypes()) {
                $filters['in_pb__UnitType__c'] = $this->getPropertyTypes();
            }

            if (false !== $this->getItemTypes()) {
                $filters['in_pb__ItemType__c'] = $this->getItemTypes();
            }

            if (false !== $this->getNeighborhoods()) {
                $filters['in_pb__ProjectId__c'] = $this->getNeighborhoods();
            }

            if (false !== $this->getMinPrice()) {
                $filters['min_pb__PurchaseListPrice__c'] = $this->getMinPrice();
            }

            if (false !== $this->getMaxPrice()) {
                $filters['max_pb__PurchaseListPrice__c'] = $this->getMaxPrice();
            }

            if (false !== $this->getBedrooms()) {
                $filters['in_pb__UnitBedrooms__c'] = $this->getBedrooms();
            }

            if (false !== $this->getBathrooms()) {
                $filters['in_Full_Bathrooms__c'] = $this->getBathrooms();
            }

            if (false !== $this->getBuilders()) {
                $filters['like_Builder_Name_Website__c'] = str_replace('-', ' ', $this->getBuilders());
            }

            if (false !== $this->getListingAgents()) {
                $filters['in_Listing_Agent__c'] = $this->getListingAgents();
            }

            if (false !== $this->getMinLastUpdate()) {
                $today = Carbon::now();
                $today->subDays((int)$this->getMinLastUpdate());
//                $filters['min_Listing_Date__c'] = $today->toDateString() . ' 00:00:00';
//                $filters['LastModifiedDate'] = '[' . $today->toDateString() . ' 00:00:00' . ';]';
            }

            if (false !== $this->getMinSquareFootage()) {
                $filters['min_pb__TotalAreaSqft__c'] = $this->getMinSquareFootage();
            }

            if (false !== $this->getMaxSquareFootage()) {
                $filters['max_pb__TotalAreaSqft__c'] = $this->getMaxSquareFootage();
            }

            if (false !== $this->getHomeFeatures()) {
                $homeFeatures = $this->getHomeFeatures();

                if (stristr($homeFeatures, 'master') !== false) {
                    $filters['Master_Down__c'] = 'true';
                }

                if (stristr($homeFeatures, 'single') !== false) {
                    $filters['max_pb__BuildingFloors__c'] = '1';
                }
            }

            if (false !== $this->getViews()) {
                // not set in pbase... use MLS
            }

        }

        return $filters;
    }

    public static function getPropertyBaseNeighborhoods()
    {
        // TODO: get pbase neighborhoods for carnes
        $neighborhoods = array(
            "a0A800000077YbM" => 'Barfield Park',
            "a0A800000077YbN" => 'Center Park',
            "a0A800000077YbO" => 'Cochran Park',
            "a0A800000077YbQ" => 'Codner\'s Ferry Park',
            "a0A800000077YbP" => 'Daniel Island Park',
            "a0A800000077YbX" => 'Downtown',
            "a0A800000077YbR" => 'Etiwan Park',
            "a0A800000077YbS" => 'Pierce Park',
            "a0A800000077YbU" => 'Smythe Park'
        );

        return $neighborhoods;
    }

    public function getFriendlyName()
    {
        $friendly_name = array();

        $raw_filters = $this->getRawFilters();

        $property_type = (isset($raw_filters['propertyTypes'])) ? $raw_filters['propertyTypes'] : false;
        if (false !== $property_type && is_array($property_type) && !empty($property_type)) {
            $property_type = array_map(function ($el) {
                return ucfirst($el);
            }, $property_type);
            $friendly_name[] = implode(', ', $property_type);
        }

        $neighborhood = (isset($raw_filters['neighborhoods'])) ? $raw_filters['neighborhoods'] : false;
        if (false !== $neighborhood && is_array($neighborhood) && !empty($neighborhood)) {
            $property_base_neighborhoods = self::getPropertyBaseNeighborhoods();
            $neighborhoods = array_map(function ($el) use ($property_base_neighborhoods) {
                if (isset($property_base_neighborhoods[$el])) {
                    return $property_base_neighborhoods[$el];
                }

                return '';
            }, $neighborhood);

            $friendly_name[] = implode(', ', $neighborhoods);
        }

        $prices = (isset($raw_filters['prices'])) ? $raw_filters['prices'] : false;
        if (false !== $prices && is_array($prices) && !empty($prices)) {
            $prices = $prices[0];
            $min_and_max_prices = explode('-', $prices);
            if (count($min_and_max_prices) === 2) {
                $min_price = $min_and_max_prices[0];
                $max_price = $min_and_max_prices[1];

                if (is_numeric($min_price)) {
                    $min_price = number_format($min_price);
                }

                if (is_numeric($max_price)) {
                    $max_price = number_format($max_price);
                }

                $friendly_name[] = sprintf('$%s-$%s', $min_price, $max_price);
            }
        }

        $bedrooms = (isset($raw_filters['bedrooms'])) ? $raw_filters['bedrooms'] : false;
        if (false !== $bedrooms && is_array($bedrooms) && !empty($bedrooms)) {
            $bedrooms_count = (int)$bedrooms[0];
            $friendly_bedroom_name = $bedrooms_count . '+ Bedrooms';
            $friendly_name[] = $friendly_bedroom_name;
        }

        $bathrooms = (isset($raw_filters['bathrooms'])) ? $raw_filters['bathrooms'] : false;
        if (false !== $bathrooms && is_array($bathrooms) && !empty($bathrooms)) {
            $bathrooms_count = (int)$bathrooms[0];
            $friendly_bathroom_name = $bathrooms_count . '+ Bathrooms';
            $friendly_name[] = $friendly_bathroom_name;
        }

        $lastUpdate = (isset($raw_filters['lastUpdate'])) ? $raw_filters['lastUpdate'] : false;
        if (false !== $lastUpdate && $lastUpdate !== '') {
            $s = ($lastUpdate > 1) ? 's' : '';
            $friendly_last_update_name = 'Last Update ' . $lastUpdate . ' day' . $s . ' ago';
            $friendly_name[] = $friendly_last_update_name;
        }

        $square_footages = (isset($raw_filters['squareFootage'])) ? $raw_filters['squareFootage'] : false;
        if (false !== $square_footages && is_array($square_footages) && !empty($square_footages)) {
            $square_footages = $square_footages[0];
            $min_and_max_square_footages = explode('-', $square_footages);
            if (count($min_and_max_square_footages) === 2) {
                $min_square_footage = $min_and_max_square_footages[0];
                $max_square_footage = $min_and_max_square_footages[1];

                if (is_numeric($min_square_footage)) {
                    $min_square_footage = number_format($min_square_footage);
                }

                if (is_numeric($max_square_footage)) {
                    $max_square_footage = number_format($max_square_footage);
                }

                $friendly_name[] = sprintf('%s-%s sqft', $min_square_footage, $max_square_footage);
            }
        }

        $home_features = (isset($raw_filters['homeFeatures'])) ? $raw_filters['homeFeatures'] : false;
        if (false !== $home_features && is_array($home_features) && !empty($home_features)) {
            $home_features = array_map(function ($el) {
                return ucfirst($el);
            }, $home_features);
            $friendly_name[] = implode(', ', $home_features);
        }

        $views = (isset($raw_filters['views'])) ? $raw_filters['views'] : false;
        if (false !== $views && is_array($views) && !empty($views)) {
            $views = array_map(function ($el) {
                return ucfirst($el);
            }, $views);
            $friendly_name[] = implode(', ', $views);
        }

        $builders = (isset($raw_filters['builders'])) ? $raw_filters['builders'] : false;
        if (false !== $builders && is_array($builders) && !empty($builders)) {
            $builders = $builders[0];
            $friendly_name[] = ucfirst($builders);
        }

        $includePlans = (isset($raw_filters['includePlans'])) ? $raw_filters['includePlans'] : true;
        if (false !== $includePlans && $includePlans !== '') {
            if ($includePlans === 'true') {
                $friendly_name[] = 'Include Floor Plans';
            }
        }

        $includeHomes = (isset($raw_filters['includeHomes'])) ? $raw_filters['includeHomes'] : true;
        if (false !== $includeHomes && $includeHomes !== '') {
            if ($includeHomes === 'true') {
                $friendly_name[] = 'Include Available Homes';
            }
        }

        $friendly_name = implode(', ', $friendly_name);

        return $friendly_name;
    }

    public function link()
    {
        return home_url() . '/home-finder/search/?' . http_build_query($this->getRawFilters());
    }

    public function removeLink()
    {
        return home_url() . '/api/home-finder/un-save-search?' . http_build_query($this->getRawFilters());
    }

    public function isEmptySearch()
    {
        return (
            false === $this->getPropertyTypes() &&
            false === $this->getNeighborhoods() &&
            false === $this->getMinPrice() &&
            false === $this->getMaxPrice() &&
            false === $this->getBedrooms() &&
            false === $this->getBathrooms() &&
            false === $this->getMinSquareFootage() &&
            false === $this->getMaxSquareFootage() &&
            false === $this->getListingAgents() &&
            false === $this->getViews() &&
            false === $this->getHomeFeatures() &&
            false === $this->getMinLastUpdate() &&
            false === $this->getBuilders()
        );
    }

    public function getMLSPage()
    {
        if ($this->_mlsPage !== false && $this->_mlsPage !== '') {
            return $this->_mlsPage;
        }

        return 1;
    }

    public function setMLSPage($page)
    {
        $this->_mlsPage = $page;
    }

}