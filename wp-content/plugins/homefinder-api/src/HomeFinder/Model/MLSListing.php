<?php namespace HomeFinder\Model;

class MLSListing
{
    public $_id;
    public $_score;
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
    public $amenities;
    public $dir;
    public $subsection;
    public $co_listing_member_short_id;
    public $un_branded_virtual_tour;
    public $lot_description;

    private function _safeGetByKey($key, array $array)
    {
        return (isset($array[$key])) ? $array[$key] : '';
    }

    public function __construct($mls_list_item)
    {
        $this->_id = self::_safeGetByKey('_id', $mls_list_item);
        $this->_score = self::_safeGetByKey('_score', $mls_list_item);

        $source = $mls_list_item['_source'];
        $this->sf_detached_style = self::_safeGetByKey('sf_detached_style', $source);
        $this->new_owned = self::_safeGetByKey('new_owned', $source);
        $this->water_sewer = self::_safeGetByKey('water_sewer', $source);
        $this->amenities = self::_safeGetByKey('amenities', $source);
        $this->internal_listing_id = self::_safeGetByKey('internal_listing_id', $source);
        $this->list_date = self::_safeGetByKey('list_date', $source);
        $this->picture_count = self::_safeGetByKey('picture_count', $source);
        $this->ground_level = self::_safeGetByKey('ground_level', $source);
        $this->status = self::_safeGetByKey('status', $source);
        $this->lease_purchase = self::_safeGetByKey('lease_purchase', $source);
        $this->auction = self::_safeGetByKey('auction', $source);
        $this->lease_purchase = self::_safeGetByKey('lease_purchase', $source);
        $this->auction = self::_safeGetByKey('auction', $source);
        $this->list_price = self::_safeGetByKey('list_price', $source);
        $this->old_price = self::_safeGetByKey('old_price', $source);
        $this->area = self::_safeGetByKey('area', $source);
        $this->list_number_main = self::_safeGetByKey('list_number_main', $source);
        $this->street_num = self::_safeGetByKey('street_num', $source);
        $this->dir = self::_safeGetByKey('dir', $source);
        $this->street_name = self::_safeGetByKey('street_name', $source);
        $this->street_suffix = self::_safeGetByKey('street_suffix', $source);
        $this->city = self::_safeGetByKey('city', $source);
        $this->county = self::_safeGetByKey('county', $source);
        $this->zip_code = self::_safeGetByKey('zip_code', $source);
        $this->apx_sqft = self::_safeGetByKey('apx_sqft', $source);
        $this->year_built = self::_safeGetByKey('year_build', $source);
        $this->acreage = self::_safeGetByKey('acreage', $source);
        $this->bedrooms = self::_safeGetByKey('bedrooms', $source);
        $this->baths_full = self::_safeGetByKey('baths_full', $source);
        $this->subdivision = self::_safeGetByKey('subdivision', $source);
        $this->public_remarks = self::_safeGetByKey('public_remarks', $source);
        $this->subsection = self::_safeGetByKey('subsection', $source);
        $this->timestamp = self::_safeGetByKey('timestamp', $source);
        $this->elementary_school = self::_safeGetByKey('elementary_school', $source);
        $this->middle_school = self::_safeGetByKey('middle_school', $source);
        $this->sub_type = self::_safeGetByKey('sub_type', $source);
        $this->high_school = self::_safeGetByKey('high_school', $source);
        $this->vow_automated_valuation_display = self::_safeGetByKey('vow_automated_valuation_display', $source);
        $this->vow_address_display = self::_safeGetByKey('vow_address_display', $source);
        $this->vow_consumer_comment = self::_safeGetByKey('vow_consumer_comment', $source);
        $this->co_listing_member_short_id = self::_safeGetByKey('co_listing_member_short_id', $source);
        $this->listing_member_short_id = self::_safeGetByKey('listing_member_short_id', $source);
        $this->listing_office_short_id = self::_safeGetByKey('listing_office_short_id', $source);
        $this->photo_filenames = self::_safeGetByKey('photo_filenames', $source);
        $this->z_textsearch_combined_address = self::_safeGetByKey('z_textsearch_combined_address', $source);
        $this->z_textsearch_neighborhood = self::_safeGetByKey('z_textsearch_neighborhood', $source);
        $this->z_textsearch_mls_num = self::_safeGetByKey('z_textsearch_mls_num', $source);
        $this->z_textsearch_combined_features = self::_safeGetByKey('z_textsearch_combined_features', $source);
        $this->is_active = self::_safeGetByKey('is_active', $source);
        $this->date_added = self::_safeGetByKey('date_added', $source);
        $this->date_updated = self::_safeGetByKey('date_updated', $source);
        $this->class = self::_safeGetByKey('class', $source);
        $this->source = self::_safeGetByKey('source', $source);
        $this->un_branded_virtual_tour = self::_safeGetByKey('un_branded_virtual_tour', $source);
        $this->lot_description = self::_safeGetByKey('log_description', $source);
    }
}