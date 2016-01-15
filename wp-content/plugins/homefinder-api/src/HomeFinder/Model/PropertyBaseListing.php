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

    public function __construct($pbase_list_item)
    {
        $this->Id = $pbase_list_item['Id'];
        $this->pb__UnitView__c = $pbase_list_item['pb__UnitView__c'];
        $this->pb__UnitType__c = $pbase_list_item['pb__UnitType__c'];
        $this->pb__ItemType__c = $pbase_list_item['pb__ItemType__c'];
        $this->pb__IsListed__c = $pbase_list_item['pb__IsListed__c'];
        $this->Listing_Date__c = $pbase_list_item['Listing_Date__c'];
        $this->Builder_Name__C = $pbase_list_item['Builder_Name__C'];
        $this->Builder_Name_Website__c = $pbase_list_item['Builder_Name_Website__c'];
        $this->Resale__c = $pbase_list_item['Resale__c'];
        $this->pb__Latitude__c = $pbase_list_item['pb__Latitude__c'];
        $this->pb__Longitude__c = $pbase_list_item['pb__Longitude__c'];
        $this->pb__TotalAreaSqft__c = $pbase_list_item['pb__TotalAreaSqft__c'];
        $this->pb__UnitBedrooms__c = $pbase_list_item['pb__UnitBedrooms__c'];
        $this->Full_Bathrooms__c = $pbase_list_item['Full_Bathrooms__c'];
        $this->Half_Bathrooms__c = $pbase_list_item['Half_Bathrooms__c'];
        $this->pb__IsForSale__c = $pbase_list_item['pb__IsForSale__c'];
        $this->pb__IsForLease__c = $pbase_list_item['pb__IsForLease__c'];
        $this->Project_Name__c = $pbase_list_item['Project_Name__c'];
        $this->AddressWeb__c = $pbase_list_item['AddressWeb__c'];
        $this->pb__PurchaseListPrice__c = $pbase_list_item['pb__PurchaseListPrice__c'];
        $this->LastPurchasePrice__c = $pbase_list_item['LastPurchasePrice__c'];
        $this->pb__ProjectId__c = $pbase_list_item['pb__ProjectId__c'];
        $this->Is_Featured__c = $pbase_list_item['Is_Featured__c'];
        $this->pb__ItemStatus__c = $pbase_list_item['pb__ItemStatus__c'];
        $this->pb__CommunityId__c = $pbase_list_item['pb__CommunityId__c'];
        $this->pb__MLSNumber__c = $pbase_list_item['pb__MLSNumber__c'];
        $this->asset = $pbase_list_item['asset'];
        $this->HTML_Description__c = $pbase_list_item['HTML_Description__c'];
        $this->LastModifiedDate = $pbase_list_item['LastModifiedDate'];
        $this->X3D_Tours__c = $pbase_list_item['X3D_Tours__c'];
        $this->FLV_Tour_URL__c = $pbase_list_item['FLV_Tour_URL__c'];
        $this->PDF_Floorplan_URL__c = $pbase_list_item['PDF_Floorplan_URL__c'];
    }

}
