<?php namespace HomeFinder\Model;

/**
 * This class represents exactly what comes back from pbase. It is then fed into the Property class that is
 * used throughout the website.
 *
 * Class PropertyBaseListing
 * @package App\Model
 */
class PropertyBaseListing
{
    public $Id;
    public $pb__UnitView__c;
    public $pb__UnitType__c;
    public $pb__ItemType__c;
    public $pb__IsListed__c;
    public $Listing_Date__c;
    public $Builder_Name__C;
    public $Builder_Name_Website__c;
    public $Resale__c;
    public $pb__Latitude__c;
    public $pb__Longitude__c;
    public $pb__TotalAreaSqft__c;
    public $pb__UnitBedrooms__c;
    public $Full_Bathrooms__c;
    public $Half_Bathrooms__c;
    public $pb__IsForSale__c;
    public $pb__IsForLease__c;
    public $Project_Name__c;
    public $AddressWeb__c;
    public $pb__PurchaseListPrice__c;
    public $LastPurchasePrice__c;
    public $pb__ProjectId__c;
    public $Is_Featured__c;
    public $pb__ItemStatus__c;
    public $pb__CommunityId__c;
    public $pb__MLSNumber__c;
    public $HTML_Description__c;
    public $asset;
    public $LastModifiedDate;
    public $X3D_Tours__c;
    public $FLV_Tour_URL__c;
    public $PDF_Floorplan_URL__c;
    public $Listing_Agent__c;
    public $pb__SalesAgentId__c;

    private function _safeGetByKey($key, array $array)
    {
        return (isset($array[$key])) ? $array[$key] : '';
    }

    public function __construct($pbase_list_item)
    {
        $this->Id = self::_safeGetByKey('Id', $pbase_list_item);
        $this->pb__UnitView__c = self::_safeGetByKey('pb__UnitView__c', $pbase_list_item);
        $this->pb__UnitType__c = self::_safeGetByKey('pb__UnitType__c', $pbase_list_item);
        $this->pb__ItemType__c = self::_safeGetByKey('pb__ItemType__c', $pbase_list_item);
        $this->pb__IsListed__c = self::_safeGetByKey('pb__IsListed__c', $pbase_list_item);
        $this->Listing_Date__c = self::_safeGetByKey('Listing_Date__c', $pbase_list_item);
        $this->Builder_Name__C = self::_safeGetByKey('Builder_Name__C', $pbase_list_item);
        $this->Builder_Name_Website__c = self::_safeGetByKey('Builder_Name_Website__c', $pbase_list_item);
        $this->Resale__c = self::_safeGetByKey('Resale__c', $pbase_list_item);
        $this->pb__Latitude__c = self::_safeGetByKey('pb__Latitude__c', $pbase_list_item);
        $this->pb__Longitude__c = self::_safeGetByKey('pb__Longitude__c', $pbase_list_item);
        $this->pb__TotalAreaSqft__c = self::_safeGetByKey('pb__TotalAreaSqft__c', $pbase_list_item);
        $this->pb__UnitBedrooms__c = self::_safeGetByKey('pb__UnitBedrooms__c', $pbase_list_item);
        $this->Full_Bathrooms__c = self::_safeGetByKey('Full_Bathrooms__c', $pbase_list_item);
        $this->Half_Bathrooms__c = self::_safeGetByKey('Half_Bathrooms__c', $pbase_list_item);
        $this->pb__IsForSale__c = self::_safeGetByKey('pb__IsForSale__c', $pbase_list_item);
        $this->pb__IsForLease__c = self::_safeGetByKey('pb__IsForLease__c', $pbase_list_item);
        $this->Project_Name__c = self::_safeGetByKey('Project_Name__c', $pbase_list_item);
        $this->AddressWeb__c = self::_safeGetByKey('AddressWeb__c', $pbase_list_item);
        $this->pb__PurchaseListPrice__c = self::_safeGetByKey('pb__PurchaseListPrice__c', $pbase_list_item);
        $this->LastPurchasePrice__c = self::_safeGetByKey('LastPurchasePrice__c', $pbase_list_item);
        $this->pb__ProjectId__c = self::_safeGetByKey('pb__ProjectId__c', $pbase_list_item);
        $this->Is_Featured__c = self::_safeGetByKey('Is_Featured__c', $pbase_list_item);
        $this->pb__ItemStatus__c = self::_safeGetByKey('pb__ItemStatus__c', $pbase_list_item);
        $this->pb__CommunityId__c = self::_safeGetByKey('pb__CommunityId__c', $pbase_list_item);
        $this->pb__MLSNumber__c = self::_safeGetByKey('pb__MLSNumber__c', $pbase_list_item);
        $this->asset = self::_safeGetByKey('asset', $pbase_list_item);
        $this->HTML_Description__c = self::_safeGetByKey('HTML_Description__c', $pbase_list_item);
        $this->LastModifiedDate = self::_safeGetByKey('LastModifiedDate', $pbase_list_item);
        $this->X3D_Tours__c = self::_safeGetByKey('X3D_Tours__c', $pbase_list_item);
        $this->FLV_Tour_URL__c = self::_safeGetByKey('FLV_Tour_URL__c', $pbase_list_item);
        $this->PDF_Floorplan_URL__c = self::_safeGetByKey('PDF_Floorplan_URL__c', $pbase_list_item);
        $this->Listing_Agent__c = self::_safeGetByKey('Listing_Agent__c', $pbase_list_item);
        $this->pb__SalesAgentId__c = self::_safeGetByKey('pb__SalesAgentId__c', $pbase_list_item);
    }

}
