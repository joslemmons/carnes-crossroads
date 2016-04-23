<?php namespace HomeFinder\Request;

use Carbon\Carbon;
use HomeFinder\Model\HomeFinderFilters;
use HomeFinder\Model\Property;
use HomeFinder\Model\PropertyBaseListing;
use HomeFinder\Model\Result;

class PropertyBase
{
    public $message;
    public $status;
    public $body;

    const STATUS_OK = 'success';
    const STATUS_ERROR = 'error';

    const API_ENDPOINT = 'http://danielisland.force.com/pb__WebserviceWeblisting2';

    private static $_default_request_url_params = array(
        'token' => 'a1hsGpjkpgdGwvEGzA8btIsG',
        'format' => 'xml',
        'record_type' => '6_Unit,3_Plot',
        'max_pb__PurchaseListPrice__c' => 'any',
        'min_pb__PurchaseListPrice__c' => '0',
        'pb__CommunityId__c' => 'a0A800000077YasEAE',
        'sort' => 'pb__PurchaseListPrice__c ASC',
        'in_pb__UnitBedrooms__c' => '',
        'is_for_sale' => 'Sale',
        'reference_number' => '',
        'emirate' => 'any',
        'type' => 'any',
        'community' => 'any',
        'PicklistFields' => 'pb__UnitView__c',
        'pb__IsListed__c' => 'true',
        'pb_isAvailable__c' => 'true',
        'page_limit' => '6'
    );

    private static function _extractDataFromXMLResponse($pbase_response_raw)
    {
        $listings = array();
        if ($pbase_response_raw) {
            $response = self::withData($pbase_response_raw);

            if (true === isset($response->body) && true === isset($response->body['itemList'])) {
                $item_list = $response->body['itemList'];

                $item_list = json_decode(json_encode($item_list), true);

                if (true === isset($item_list['item'])) {
                    $listings = $item_list['item'];
                }
            }
        }

        return $listings;
    }

    private static function _extractTotalFromXMLResponse($pbase_response_raw)
    {
        $total = 0;
        if ($pbase_response_raw) {
            $response = self::withData($pbase_response_raw);

            if (true === isset($response->body) && true === isset($response->body['pagination'])) {
                $pagination = $response->body['pagination'];

                $pagination = json_decode(json_encode($pagination), true);

                if (true === isset($pagination['numberOfItems'])) {
                    $total = $pagination['numberOfItems'];
                }
            }
        }

        return $total;
    }

    /**
     * These are the fields that you want returned with the response. ie. if you put Id, then the Id will be given back
     * in the request
     *
     * This is a list of available fields as of 12/8/15
     * https://na8.salesforce.com/01I80000000XaZq?setupid=CustomObjects
     *
     * @return string
     */
    private static function _getDefaultRequestFields()
    {
        return implode(',', array(
            'Id',
            'pb__UnitView__c',
            'pb__UnitType__c',
            'pb__ItemType__c',
            'pb__IsListed__c',
            'Listing_Date__c',
            'Builder_Name__C',
            'Builder_Name_Website__c',
            'Resale__c',
            'pb__Latitude__c',
            'pb__Longitude__c',
            'pb__TotalAreaSqft__c',
            'pb__UnitBedrooms__c',
            'Full_Bathrooms__c',
            'Half_Bathrooms__c',
            'pb__IsForSale__c',
            'pb__IsForLease__c',
            'Project_Name__c',
            'AddressWeb__c',
            'pb__PurchaseListPrice__c',
            'LastPurchasePrice__c',
            'pb__ProjectId__c',
            'Is_Featured__c',
            'pb__ItemStatus__c',
            'pb__CommunityId__c',
            'pb__MLSNumber__c',
            'HTML_Description__c',
            'LastModifiedDate',
            'X3D_Tours__c',
            'FLV_Tour_URL__c',
            'PDF_Floorplan_URL__c',
            'Listing_Agent__c',
            'pb__SalesAgentId__c',
            'MLS_Page__c',
            'prop_short_desc__c'
        ));
    }

    /**
     * @param HomeFinderFilters $filters
     * @param int $per_page
     * @param int $page
     * @param string $order_by
     * @param string $order
     * @return Result
     */
    public static function getWithFilters(HomeFinderFilters $filters, $per_page = 24, $page = 1, $order_by = 'price', $order = 'desc')
    {
        // use MLS to get by last date... there's no way to filter by last date modified with v0.9 of property base
        if ($filters->getMinLastUpdate() !== false || $filters->getHomeFeatures() !== false || $filters->getViews() !== false) {
            return Result::withTotalAndPerPageAndCurrentItems(0, $per_page, array());
        }

        switch ($order_by) {
            case ('price') :
            default:
                // use the pbase key for price
                $sort_by = 'pb__PurchaseListPrice__c';
        }

        if ($order === null) {
            $order = 'desc';
        }

        $default_url_params = array_merge(
            PropertyBase::$_default_request_url_params,
            array(
                'page_limit' => $per_page,
                'page' => $page
            ),
            $filters->getFiltersAsArrayForPropertyBaseRequest()
        );

        $pbase_response_raw = wp_remote_get(PropertyBase::API_ENDPOINT . '?' . http_build_query(
                array_merge($default_url_params,
                array(
                    'fields' => PropertyBase::_getDefaultRequestFields(),
                    'sort' => $sort_by . ' ' . strtoupper($order)
                ))
            )
        );

        $properties = array();
        $item_list = PropertyBase::_extractDataFromXMLResponse($pbase_response_raw);

        if (isset($item_list['Id'])) {
            // only returned 1 result
            $pbase_listing = new PropertyBaseListing($item_list);
            $property = Property::withPropertyBaseListing($pbase_listing);
            $properties[] = $property;
        } else {
            foreach ($item_list as $item) {
                $pbase_listing = new PropertyBaseListing($item);
                $property = Property::withPropertyBaseListing($pbase_listing);
                $properties[] = $property;
            }
        }

        $result = Result::withTotalAndPerPageAndCurrentItems(PropertyBase::_extractTotalFromXMLResponse($pbase_response_raw), $per_page, $properties);

        return $result;
    }

    /**
     * @param int $per_page
     * @param int $page
     * @param string $order_by
     * @param string $order
     * @return Result
     */
    public static function getFeatured($per_page = 24, $page = 1, $order_by = 'price', $order = 'desc')
    {
        switch ($order_by) {
            case ('price') :
            default:
                // use the pbase key for price
                $sort_by = 'pb__PurchaseListPrice__c';
        }

        if ($order === null) {
            $order = 'desc';
        }

        $default_url_params = array_merge(PropertyBase::$_default_request_url_params, array(
            'page_limit' => $per_page,
            'page' => $page
        ));

        $pbase_response_raw = wp_remote_get(PropertyBase::API_ENDPOINT . '?' . http_build_query(
                array_merge($default_url_params,
                array(
                    'fields' => PropertyBase::_getDefaultRequestFields(),
                    'Is_Featured__c' => 'true',
                    'sort' => $sort_by . ' ' . strtoupper($order)
                ))));

        $properties = array();
        $item_list = PropertyBase::_extractDataFromXMLResponse($pbase_response_raw);

        if (isset($item_list['Id'])) {
            // only returned 1 result
            $pbase_listing = new PropertyBaseListing($item_list);
            $property = Property::withPropertyBaseListing($pbase_listing);
            $properties[] = $property;
        } else {
            foreach ($item_list as $item) {
                $pbase_listing = new PropertyBaseListing($item);
                $property = Property::withPropertyBaseListing($pbase_listing);
                $properties[] = $property;
            }
        }

        $result = Result::withTotalAndPerPageAndCurrentItems(PropertyBase::_extractTotalFromXMLResponse($pbase_response_raw), $per_page, $properties);

        return $result;
    }

    public static function getRecentlyListed($per_page = 24, $page = 1)
    {
        $default_url_params = array_merge(PropertyBase::$_default_request_url_params, array(
            'page_limit' => $per_page,
            'page' => $page
        ));

        $pbase_response_raw = wp_remote_get(PropertyBase::API_ENDPOINT . '?' . http_build_query(
                array_merge($default_url_params,
                array(
                    'fields' => PropertyBase::_getDefaultRequestFields(),
                    'sort' => 'Listing_Date__c DESC'
                ))));

        $properties = array();
        $item_list = PropertyBase::_extractDataFromXMLResponse($pbase_response_raw);

        if (isset($item_list['Id'])) {
            // only returned 1 result
            $pbase_listing = new PropertyBaseListing($item_list);
            $property = Property::withPropertyBaseListing($pbase_listing);
            $properties[] = $property;
        } else {
            foreach ($item_list as $item) {
                $pbase_listing = new PropertyBaseListing($item);
                $property = Property::withPropertyBaseListing($pbase_listing);
                $properties[] = $property;
            }
        }

        $result = Result::withTotalAndPerPageAndCurrentItems(PropertyBase::_extractTotalFromXMLResponse($pbase_response_raw), $per_page, $properties);

        return $result;
    }

    public static function getRecentlySold($per_page = 24, $page = 1)
    {
        $default_url_params = PropertyBase::$_default_request_url_params;
        unset($default_url_params['is_for_sale']);
        unset($default_url_params['pb__IsListed__c']);
        unset($default_url_params['pb_isAvailable__c']);
        $default_url_params = array_merge($default_url_params, array(
            'page_limit' => $per_page,
            'page' => $page,
            'pb__ItemStatus__c' => 'Sold',
            'get_images' => 'false'
        ));

        $pbase_response_raw = wp_remote_get(PropertyBase::API_ENDPOINT . '?' . http_build_query(
                array_merge($default_url_params,
                array(
                    'fields' => PropertyBase::_getDefaultRequestFields(),
                    'sort' => 'LastModifiedDate DESC'
                ))));

        $properties = array();
        $item_list = PropertyBase::_extractDataFromXMLResponse($pbase_response_raw);

        if (isset($item_list['Id'])) {
            // only returned 1 result
            $pbase_listing = new PropertyBaseListing($item_list);
            $property = Property::withPropertyBaseListing($pbase_listing);
            $properties[] = $property;
        } else {
            foreach ($item_list as $item) {
                $pbase_listing = new PropertyBaseListing($item);
                $property = Property::withPropertyBaseListing($pbase_listing);
                $properties[] = $property;
            }
        }

        $result = Result::withTotalAndPerPageAndCurrentItems(PropertyBase::_extractTotalFromXMLResponse($pbase_response_raw), $per_page, $properties);

        return $result;
    }

    /**
     * @param $id
     * @return \HomeFinder\Model\Property|bool
     */
    public static function getByPropertyBaseId($id)
    {
        $default_url_params = array_merge(PropertyBase::$_default_request_url_params, array(
            'pb__IsListed__c' => '',
            'pb_isAvailable__c' => '',
            'is_for_sale' => ''
        ));
        $pbase_response_raw = wp_remote_get(PropertyBase::API_ENDPOINT . '?' . http_build_query(
                array_merge($default_url_params,
                array(
                    'Id' => $id,
                    'fields' => PropertyBase::_getDefaultRequestFields()
                )
                )));

        $item_list = PropertyBase::_extractDataFromXMLResponse($pbase_response_raw);

        if (true === empty($item_list)) {
            return false;
        }

        $pbase_listing = new PropertyBaseListing($item_list);
        $property = Property::withPropertyBaseListing($pbase_listing);

        return $property;
    }

    public static function getWithMLSNumber($mls_number)
    {
        $default_url_params = array_merge(PropertyBase::$_default_request_url_params, array(
            'pb__IsListed__c' => '',
            'pb_isAvailable__c' => '',
            'is_for_sale' => '',
            'page_limit' => '1'
        ));

        $pbase_response_raw = wp_remote_get(PropertyBase::API_ENDPOINT . '?' . http_build_query(
                array_merge($default_url_params,
                    array(
                        'pb__MLSNumber__c' => $mls_number,
                        'fields' => PropertyBase::_getDefaultRequestFields()
                    )
                )));

        $item_list = PropertyBase::_extractDataFromXMLResponse($pbase_response_raw);

        if (true === empty($item_list)) {
            return false;
        }

        $pbase_listing = new PropertyBaseListing($item_list);
        $property = Property::withPropertyBaseListing($pbase_listing);

        if ($property->getMLSNumber() !== $mls_number) {
            return false;
        }

        return $property;
    }

    public static function getWithAddressBedroomsAndFullBathrooms($address, $bedrooms, $full_bathrooms)
    {
        $default_url_params = array_merge(PropertyBase::$_default_request_url_params, array(
            'page_limit' => '1'
        ));

        $pbase_response_raw = wp_remote_get(PropertyBase::API_ENDPOINT . '?' . http_build_query(
                array_merge($default_url_params,
                    array(
                        'in_pb__UnitBedrooms__c' => $bedrooms,
                        'in_Full_Bathrooms__c' => $full_bathrooms,
                        'like_AddressWeb__c' => $address,
                        'fields' => PropertyBase::_getDefaultRequestFields()
                    )
                )));

        $item_list = PropertyBase::_extractDataFromXMLResponse($pbase_response_raw);

        if (true === empty($item_list)) {
            return false;
        }

        $pbase_listing = new PropertyBaseListing($item_list);
        $property = Property::withPropertyBaseListing($pbase_listing);

        return $property;
    }

    private static function withData($data)
    {
        $instance = new PropertyBase();

        if (wp_remote_retrieve_response_code($data) != 200) {

            $instance->message = wp_remote_retrieve_response_message($data);
            $instance->status = wp_remote_retrieve_response_code($data);

        } else if (!$body = wp_remote_retrieve_body($data)) {

            if (is_wp_error($data)) {
                $instance->message = 'WP_Error';
            } else {
                $instance->message = 'Unregistered Error';
            }

            $instance->status = self::STATUS_ERROR;
            $instance->body = $data;

        } else {
            $instance->message = 'Data was received.';
            $instance->status = self::STATUS_OK;
            $instance->body = $instance->_parse_xml($body);
        }

        return $instance;
    }

    /**
     * Parses an XML response body.
     *
     * @version 0.0.2
     * @updated 05.14.12
     */
    private function _parse_xml($response_body)
    {
        if (function_exists('simplexml_load_string')) {

            $errors = libxml_use_internal_errors('true');
            $data = simplexml_load_string($response_body);
            libxml_use_internal_errors($errors);

            if (is_object($data)) {
                $data = (array)$data;
            }
        } else {
            $data = false;
        }

        return $data;

    }
}