<?php namespace HomeFinder\Model;

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
    private $_communityAmenities = array();
    private $_minSquareFootage = '';
    private $_maxSquareFootage = '';
    private $_minAcreage = '';
    private $_maxAcreage = '';
    private $_homeFeatures = array();
    private $_view = array();
    private $_searchMLS = false;
    private $_searchAddress = '';
    private $_includePlans = false;
    private $_builderId = '';

    private $_rawPropertyTypes = array();
    private $_rawNeighborhoods = array();
    private $_rawPrices = array();
    private $_rawBedrooms = array();
    private $_rawBathrooms = array();
    private $_rawSearchMLS = '';
    private $_rawSearchAddress = '';
    private $_rawIncludePlans = '';
    private $_rawBuilderId = '';

    private $_propertiesToExclude = array();

    public function setPropertiesToExclude($properties)
    {
        $this->_propertiesToExclude = $properties;
    }

    public function getPropertiesToExclude()
    {
        return $this->_propertiesToExclude;
    }

    private static function _getFilterFromRequestByKey($key)
    {
        $filters = (isset($_REQUEST[$key])) ? $_REQUEST[$key] : array();

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

        $shouldIncludePlans = (isset($data['includeMLS'])) ? sanitize_text_field($data['includeMLS']) : false;
        if ($shouldIncludePlans === 'true') {
            $shouldIncludePlans = true;
        }
        else {
            $shouldIncludePlans = false;
        }

        $filters->setShouldIncludePlans($shouldIncludePlans);

        $builderId = (isset($data['builderId'])) ? filter_var($data['builderId'], FILTER_SANITIZE_NUMBER_INT) : false;
        if ($builderId !== false && $builderId !== '') {
            $filters->setBuilderId($builderId);
        }
        else {
            $filters->setBuilderId(false);
        }

        $searchAddress = (isset($data['searchAddress'])) ? sanitize_text_field($data['searchAddress']) : '';
        $filters->setSearchAddress($searchAddress);

        $filters->_rawPropertyTypes = $data['propertyTypes'];
        $filters->_rawNeighborhoods = $data['neighborhoods'];
        $filters->_rawPrices = $data['prices'];
        $filters->_rawBedrooms = $data['bedrooms'];
        $filters->_rawBathrooms = $data['bathrooms'];
        $filters->_rawSearchMLS = ($shouldSearchMLS) ? 'true' : 'false';
        $filters->_rawSearchAddress = $searchAddress;
        $filters->_rawIncludePlans = $shouldIncludePlans;

        // add the various filters passed as GET params to $filters
        $filters->setPropertyTypes($data['propertyTypes']);
        $filters->setNeighborhoods($data['neighborhoods']);

        $prices = $data['prices'];
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
            $max_price = filter_var($prices_split_by_hyphen[1], FILTER_VALIDATE_INT);
            $filters->setMinPrice($min_price);
            $filters->setMaxPrice($max_price);
        }

        $bedrooms = $data['bedrooms'];
        $bedrooms = array_map(function ($el) {
            return filter_var($el, FILTER_VALIDATE_INT);
        }, $bedrooms);
        // pbase expects a list. so >=3 won't work. need 3,4,5,6,7+. I chose 20 since
        // that's probably as many bedrooms as you'll find
        if (1 === count($bedrooms)) {
            $bedrooms = range($bedrooms[0], 20);
        }
        $filters->setBedrooms($bedrooms);

        $bathrooms = $data['bathrooms'];
        $bathrooms = array_map(function ($el) {
            return filter_var($el, FILTER_VALIDATE_INT);
        }, $bathrooms);
        // pbase expects a list. so >=3 won't work. need 3,4,5,6,7+. I chose 20 since
        // that's probably as many bathrooms as you'll find
        if (1 === count($bathrooms)) {
            $bathrooms = range($bathrooms[0], 20);
        }
        $filters->setbathrooms($bathrooms);

        return $filters;
    }

    public static function withGETParams()
    {
        $filters = new HomeFinderFilters();

        $shouldSearchMLS = (isset($_REQUEST['searchMLS'])) ? sanitize_text_field($_REQUEST['searchMLS']) : false;

        if ($shouldSearchMLS === 'true') {
            $shouldSearchMLS = true;
        } else {
            $shouldSearchMLS = false;
        }

        $filters->setShouldSearchMLS($shouldSearchMLS);

        $shouldIncludePlans = (isset($_REQUEST['includeMLS'])) ? sanitize_text_field($_REQUEST['includeMLS']) : false;
        if ($shouldIncludePlans === 'true') {
            $shouldIncludePlans = true;
        }
        else {
            $shouldIncludePlans = false;
        }

        $filters->setShouldIncludePlans($shouldIncludePlans);
        
        $builderId = (isset($_REQUEST['builderId'])) ? filter_var($_REQUEST['builderId'], FILTER_SANITIZE_NUMBER_INT) : false;
        if ($builderId !== false && $builderId !== '') {
            $filters->setBuilderId($builderId);
        }
        else {
            $filters->setBuilderId(false);
        }

        $searchAddress = (isset($_REQUEST['searchAddress'])) ? sanitize_text_field($_REQUEST['searchAddress']) : '';
        $filters->setSearchAddress($searchAddress);

        $filters->_rawPropertyTypes = self::_getFilterFromRequestByKey('propertyTypes');
        $filters->_rawNeighborhoods = self::_getFilterFromRequestByKey('neighborhoods');
        $filters->_rawPrices = self::_getFilterFromRequestByKey('prices');
        $filters->_rawBedrooms = self::_getFilterFromRequestByKey('bedrooms');
        $filters->_rawBathrooms = self::_getFilterFromRequestByKey('bathrooms');
        $filters->_rawSearchMLS = ($shouldSearchMLS) ? 'true' : 'false';
        $filters->_rawSearchAddress = $searchAddress;
        $filters->_rawIncludePlans = $shouldIncludePlans;

        // add the various filters passed as GET params to $filters
        $filters->setPropertyTypes(self::_getFilterFromRequestByKey('propertyTypes'));
        $filters->setNeighborhoods(self::_getFilterFromRequestByKey('neighborhoods'));

        $prices = self::_getFilterFromRequestByKey('prices');
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
            $max_price = filter_var($prices_split_by_hyphen[1], FILTER_VALIDATE_INT);
            $filters->setMinPrice($min_price);
            $filters->setMaxPrice($max_price);
        }

        $bedrooms = self::_getFilterFromRequestByKey('bedrooms');
        $bedrooms = array_map(function ($el) {
            return filter_var($el, FILTER_VALIDATE_INT);
        }, $bedrooms);
        // pbase expects a list. so >=3 won't work. need 3,4,5,6,7+. I chose 20 since
        // that's probably as many bedrooms as you'll find
        if (1 === count($bedrooms)) {
            $bedrooms = range($bedrooms[0], 20);
        }
        $filters->setBedrooms($bedrooms);

        $bathrooms = self::_getFilterFromRequestByKey('bathrooms');
        $bathrooms = array_map(function ($el) {
            return filter_var($el, FILTER_VALIDATE_INT);
        }, $bathrooms);
        // pbase expects a list. so >=3 won't work. need 3,4,5,6,7+. I chose 20 since
        // that's probably as many bathrooms as you'll find
        if (1 === count($bathrooms)) {
            $bathrooms = range($bathrooms[0], 20);
        }
        $filters->setbathrooms($bathrooms);

        return $filters;
    }

    public function getRawFilters()
    {
        $filters = array(
            'propertyTypes' => $this->_rawPropertyTypes,
            'neighborhoods' => $this->_rawNeighborhoods,
            'prices' => $this->_rawPrices,
            'bedrooms' => $this->_rawBedrooms,
            'bathrooms' => $this->_rawBathrooms,
            'searchMLS' => $this->_rawSearchMLS,
            'searchAddress' => $this->_rawSearchAddress
        );

        return $filters;
    }

    /**
     * @return bool
     */
    public function shouldIncludeMLS()
    {
        return $this->_searchMLS;
    }

    public function setShouldSearchMLS($should)
    {
        $this->_searchMLS = $should;
    }

    public function getBuilderId()
    {
        if ($this->_builderId === '') {
            return false;
        }

        return $this->_builderId;
    }

    public function setBuilderId($builderId)
    {
        $this->_builderId = $builderId;
    }

    public function shouldIncludePlans()
    {
        return $this->_includePlans;
    }

    public function setShouldIncludePlans($should)
    {
        $this->_includePlans = $should;
    }

    public function setSearchAddress($address)
    {
        $this->_searchAddress = $address;
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

        return implode(',', $this->_propertyTypes);
    }

    public function getItemTypes()
    {
        if (empty($this->_itemTypes)) {
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

        return implode(',', $this->_neighborhoods);
    }

    public function getMinPrice()
    {
        if ($this->_minPrice === '') {
            return false;
        }

        return $this->_minPrice;
    }

    public function getMaxPrice()
    {
        if ($this->_maxPrice === '' || $this->_maxPrice === 'any') {
            return false;
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

    public function getMinLastUpdate()
    {
        if ('' === $this->_minLastUpdate) {
            return false;
        }

        return $this->_minLastUpdate;
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
        return $slug->slugify($this->getFriendlyName() . ' ' . $this->_searchMLS . ' ' . $this->_searchAddress . ' ' . $this->_includePlans);
    }

    public function getAreaFiltersForMLSRequest()
    {
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

    public function getNeighborhoodFiltersForMLSRequest()
    {
        $neighborhoods = $this->getNeighborhoods();
        $neighborhoodsSplitByComma = explode(',', $neighborhoods);
        $neighborhoodsById = self::getPropertyBaseNeighborhoods();

        $neighborhood = '';
        foreach ($neighborhoodsSplitByComma as $neighborhood) {
            if (isset($neighborhoodsById[$neighborhood])) {
                $neighborhood = $neighborhoodsById[$neighborhood];
                break;
            }
        }

        if ($neighborhood !== '') {
            return ['match_phrase' => ['z_textsearch_neighborhood' => $neighborhood]];
        }

        return [];
    }

    public function getPropertyTypeFiltersForMLSRequest()
    {
        $classes = '';
        $subTypes = '';

        $propertyTypes = $this->getPropertyTypes();
        if ($propertyTypes === false) {
            // check raw. the property type is altered for pbase to remove
            // homesite and put it in a different filter.
            $rawPropertyTypes = $this->_rawPropertyTypes;
            if ('' !== $rawPropertyTypes) {
                $propertyTypes = implode(',', $rawPropertyTypes);
            }
        }

        if ($propertyTypes) {
            $propertyTypesAsArray = explode(',', $propertyTypes);

            // take first. I don't think we're allowing multiple
            // filters/item now or soon
            $propertyType = array_pop($propertyTypesAsArray);

            if ($propertyType === 'homesite') {
                $classes = 'VAC';
            } else {
                $classes = 'RES';

                if ($propertyType === 'home') {
                    $subTypes = 'detached';
                } elseif ($propertyType === 'condominium' || $propertyType === 'townhome') {
                    $subTypes = 'attached';
                } else {

                }
            }
        }

        $filters = array();
        if ($classes !== '') {
            $filters[] = ['match' => ['class' => $classes]];
        }

        if ($subTypes !== '') {
            $filters[] = ['match' => ['sub_type' => $subTypes]];
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

    public function getPropertiesToExcludeForMLSRequest()
    {
        $propertiesToExclude = $this->getPropertiesToExclude();
        $mlsNumbersToExclude = array();
        foreach ($propertiesToExclude as $property) {
            /* @var Property $property */
            $mlsNumbersToExclude[] = array(
                "fquery" => array(
                    "query" => array(
                        "query_string" => array(
                            "query" => "z_textsearch_mls_num:(\"" . $property->getMLSNumber() . "\")"
                        )
                    )
                )
            );
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
        }

        return $filters;
    }

    public static function getPropertyBaseNeighborhoods()
    {
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
            $property_type = $property_type[0];
            $friendly_name[] = ucfirst($property_type);
        }

        $neighborhood = (isset($raw_filters['neighborhoods'])) ? $raw_filters['neighborhoods'] : false;
        if (false !== $neighborhood && is_array($neighborhood) && !empty($neighborhood)) {
            $neighborhood = $neighborhood[0];
            $property_base_neighborhoods = self::getPropertyBaseNeighborhoods();

            if (isset($property_base_neighborhoods[$neighborhood])) {
                $friendly_name[] = $property_base_neighborhoods[$neighborhood];
            }
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

}