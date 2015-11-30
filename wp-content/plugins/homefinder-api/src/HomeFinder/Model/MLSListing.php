<?php namespace HomeFinder\Model;

class MLSListing
{
    public $sf_detached_style;
    public $roof;
    public $exterior;
    public $foundation;
    public $garage_parking;
    public $stories;
    public $new_owned;
    public $water_sewer;
    public $internal_listing_id;
    public $list_date;
    public $picture_count;
    public $picture_timestamp;
    public $ground_level;
    public $status;
    public $lease_purchase;
    public $auction;
    public $list_price;
    public $area;
    public $list_number_main;
    public $street_num;
    public $street_name;
    public $street_suffix;
    public $city;
    public $county;
    public $zip_code;
    public $apx_sqft;
    public $year_built;
    public $acreage;
    public $bedrooms;
    public $baths_full;
    public $baths_half;
    public $subdivision;
    public $public_remarks;
    public $timestamp;
    public $elementary_school;
    public $middle_school;
    public $sub_type;
    public $high_school;
    public $vow_automated_valuation_display;
    public $vow_address_display;
    public $vow_consumer_comment;
    public $listing_member_short_id;
    public $listing_office_short_id;
    public $photo_filenames;
    public $z_textsearch_combined_address;
    public $z_textsearch_neighborhood;
    public $z_textsearch_mls_num;
    public $z_textsearch_combined_features;
    public $is_active;
    public $date_added;
    public $date_updated;
    public $class;
    public $source;
    public $old_price;

    public function __construct($mls_list_item)
    {

    }
}