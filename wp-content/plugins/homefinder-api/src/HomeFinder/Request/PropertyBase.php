<?php namespace HomeFinder\Request;

use HomeFinder\Model\Property;
use HomeFinder\Model\PropertyBaseListing;

class PropertyBase
{
    public $message;
    public $status;
    public $body;

    const STATUS_OK = 'success';
    const STATUS_ERROR = 'error';

    public static function getFeatured()
    {
        $pbase_response_raw = wp_remote_get('http://danielisland.force.com/pb__WebserviceWeblisting2?' . http_build_query(array(
                'token' => 'a1hsGpjkpgdGwvEGzA8btIsG',
                'format' => 'json',
                'fields' => 'Id,pb__UnitView__c,pb__UnitType__c,pb__ItemType__c,pb__IsListed__c,Listing_Date__c,Builder_Name__C,Builder_Name_Website__c,Resale__c,pb__Latitude__c,pb__Longitude__c,pb__TotalAreaSqft__c,pb__UnitBedrooms__c,Full_Bathrooms__c,Half_Bathrooms__c,pb__IsForSale__c,pb__IsForLease__c,Project_Name__c,AddressWeb__c,pb__PurchaseListPrice__c,LastPurchasePrice__c,pb__ProjectId__c,Is_Featured__c,pb__ItemStatus__c,pb__CommunityId__c,pb__MLSNumber__c',
                'record_type' => '6_Unit,3_Plot',
                'max_pb__PurchaseListPrice__c' => 'any',
                'min_pb__PurchaseListPrice__c' => '0',
                'pb__CommunityId__c' => 'a0A800000077YaiEAE',
//                'sort' => 'pb__PurchaseListPrice__c+ASC',
                'sort' => 'pb__PurchaseListPrice__c',
                'in_pb__UnitBedrooms__c' => '',
                'is_for_sale' => 'Sale',
                'reference_number' => '',
                'emirate' => 'any',
                'type' => 'any',
                'community' => 'any',
                'PicklistFields' => 'pb__UnitView__c',
                'pb__IsListed__c' => 'true',
                'page_limit' => '25',
                'Is_Featured__c' => 'true'
            )));

        $properties = array();
        if ($pbase_response_raw) {
            $response = self::withData($pbase_response_raw);

            if (true === isset($response->body) && true === isset($response->body['itemList'])) {
                $item_list = $response->body['itemList'];

                $item_list = json_decode(json_encode($item_list), true);

                if (true === isset($item_list['item'])) {
                    $item_list = $item_list['item'];
                }

                foreach ($item_list as $item) {
                    $pbase_listing = new PropertyBaseListing($item);
                    $property = Property::withPropertyBaseListing($pbase_listing);
                    $properties[] = $property;
                }
            }
        }

        return $properties;
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