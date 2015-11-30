<?php namespace HomeFinder\Model;

class Property
{
    /* @var PropertyBaseListing */
    private $_pb_base_listing;

    public $property_base_id;
    public $unit_view;
    public $unit_type;
    public $item_type;
    public $is_listed;
    public $listing_date;
    public $builder_name;
    public $builder_website;
    public $resale;
    public $latitude;
    public $longitude;
    public $total_area_sqft;
    public $unit_bedrooms;
    public $full_bathrooms;
    public $half_bathrooms;
    public $is_for_sale;
    public $is_for_lease;
    public $project_name;
    public $address_web;
    public $purchase_list_price;
    public $last_purchase_price;
    public $project_id;
    public $is_featured;
    public $item_status;
    public $community_id;
    public $mls_number;
    public $assets;

    public static function withPropertyBaseListing(PropertyBaseListing $pb_base_listing)
    {
        $instance = new Property();
        $instance->_pb_base_listing = $pb_base_listing;

        $instance->property_base_id = $pb_base_listing->Id;
        $instance->unit_view = $pb_base_listing->pb__UnitView__c;
        $instance->unit_type = $pb_base_listing->pb__UnitType__c;
        $instance->item_type = $pb_base_listing->pb__ItemType__c;
        $instance->is_listed = $pb_base_listing->pb__IsListed__c;
        $instance->listing_date = $pb_base_listing->Listing_Date__c;
        $instance->builder_name = $pb_base_listing->Builder_Name__C;
        $instance->builder_website = $pb_base_listing->Builder_Name_Website__c;
        $instance->resale = $pb_base_listing->Resale__c;
        $instance->latitude = $pb_base_listing->pb__Latitude__c;
        $instance->longitude = $pb_base_listing->pb__Longitude__c;
        $instance->total_area_sqft = $pb_base_listing->pb__TotalAreaSqft__c;
        $instance->unit_bedrooms = $pb_base_listing->pb__UnitBedrooms__c;
        $instance->full_bathrooms = $pb_base_listing->Full_Bathrooms__c;
        $instance->half_bathrooms = $pb_base_listing->Half_Bathrooms__c;
        $instance->is_for_sale = $pb_base_listing->pb__IsForSale__c;
        $instance->is_for_lease = $pb_base_listing->pb__IsForLease__c;
        $instance->project_name = $pb_base_listing->Project_Name__c;
        $instance->address_web = $pb_base_listing->AddressWeb__c;
        $instance->purchase_list_price = $pb_base_listing->pb__PurchaseListPrice__c;
        $instance->last_purchase_price = $pb_base_listing->LastPurchasePrice__c;
        $instance->project_id = $pb_base_listing->pb__ProjectId__c;
        $instance->is_featured = $pb_base_listing->Is_Featured__c;
        $instance->item_status = $pb_base_listing->pb__ItemStatus__c;
        $instance->community_id = $pb_base_listing->pb__CommunityId__c;
        $instance->mls_number = $pb_base_listing->pb__MLSNumber__c;
        $instance->assets = $pb_base_listing->asset;

        return $instance;
    }

    public static function withMLSListing(MLSListing $mls_listing)
    {
        $instance = new Property();

        return $instance;
    }

    /**
     * @return bool
     */
    public function isListed()
    {
        return $this->is_listed === 'true';
    }



}