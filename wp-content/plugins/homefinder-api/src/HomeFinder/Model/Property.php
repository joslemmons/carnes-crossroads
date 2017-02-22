<?php namespace HomeFinder\Model;

use App\Model\Builder;
use App\Model\RealEstateAgent;
use App\Model\Twig;
use HomeFinder\Request\MLS;
use HomeFinder\Request\PropertyBase;

class Property
{
    /* @var PropertyBaseListing */
    private $_pb_base_listing;

    public $isFloorPlan = false;

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
    public $description;
    public $short_description;
    public $last_modified_date;
    public $X3D_tour_link;
    public $address_street;
    public $address_city;
    public $address_zip;
    public $listing_office_name;
    public $listing_office_id;
    public $listing_agent_id;
    public $sales_agent_id;
    public $phase_name;
    public $phase_id;

    public static function withPropertyBaseListing(PropertyBaseListing $pb_base_listing)
    {
        $instance = new Property();

        $instance->property_base_id = $pb_base_listing->Id;
        $instance->unit_view = $pb_base_listing->pb__UnitView__c;
        $instance->unit_type = (empty($pb_base_listing->pb__UnitType__c)) ? 'Homesite' : $pb_base_listing->pb__UnitType__c;
        $instance->item_type = $pb_base_listing->pb__ItemType__c;
        $instance->is_listed = $pb_base_listing->pb__IsListed__c;
        $instance->listing_date = (empty($pb_base_listing->Listing_Date__c)) ? '' : $pb_base_listing->Listing_Date__c;
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
        $instance->mls_number = (empty($pb_base_listing->pb__MLSNumber__c)) ? '' : $pb_base_listing->pb__MLSNumber__c;
        $instance->description = $pb_base_listing->HTML_Description__c;
        $instance->short_description = (empty($pb_base_listing->prop_short_desc__c)) ? '' : $pb_base_listing->prop_short_desc__c;
        $instance->assets = $pb_base_listing->asset;
        $instance->last_modified_date = $pb_base_listing->LastModifiedDate;
        $instance->X3D_tour_link = $pb_base_listing->X3D_Tours__c;
        $instance->listing_agent_id = (empty($pb_base_listing->Listing_Agent__c)) ? '' : $pb_base_listing->Listing_Agent__c;
        $instance->sales_agent_id = (empty($pb_base_listing->pb__SalesAgentId__c)) ? '' : $pb_base_listing->pb__SalesAgentId__c;
        $instance->phase_id = $pb_base_listing->pb__ZoneId__c;

        SEO::addProperty($instance);

        return $instance;
    }

    public function getPhaseId()
    {
        if ($this->phase_id == 'a0AC000000Iq6krMAB') {
            return 'St. Thomas Park';
        }

        if ($this->phase_id == 'a0AC000001X9zCsMAB') {
            return 'St. Thomas Park';
        }

        if ($this->phase_id == 'a0AC000001X9zCsMAJ') {
            return 'St. James Park';
        }

        return $this->phase_id;
    }

    public function getListingAgentId()
    {
        return $this->listing_agent_id;
    }

    /**
     * @return RealEstateAgent|null
     */
    public function getRealEstateAgent()
    {
        $id = $this->listing_agent_id;
        $agent = RealEstateAgent::withPropertyBaseListingAgentId($id);

        return $agent;
    }

    public function getSalesAgentId()
    {
        return $this->sales_agent_id;
    }

    public function getPropertyType()
    {
        if (is_array($this->unit_type) && empty($this->unit_type)) {
            return 'Homesite';
        }

        if ($this->unit_type === 'Home') {
            return 'Single Family Home';
        }

        return $this->unit_type;
    }

    public static function withMLSListing(MLSListing $mls_listing)
    {
        $instance = new Property();

        $instance->unit_view = $mls_listing->lot_description;
        $instance->unit_type = '';
        $instance->item_type = '';
        $instance->is_listed = true;
        $instance->listing_date = $mls_listing->list_date;
        $instance->builder_name = '';
        $instance->builder_website = '';
        $instance->resale = ($mls_listing->new_owned === "Pre-Owned") ? true : false;
        $instance->latitude = '';
        $instance->longitude = '';
        $instance->total_area_sqft = $mls_listing->apx_sqft;
        $instance->unit_bedrooms = $mls_listing->bedrooms;
        $instance->full_bathrooms = $mls_listing->baths_full;
        $instance->half_bathrooms = $mls_listing->baths_half;
        $instance->is_for_sale = true;
        $instance->is_for_lease = false;
        $instance->project_name = $mls_listing->z_textsearch_neighborhood;
        $instance->address_web = sprintf('%s %s %s', $mls_listing->street_num, $mls_listing->street_name, $mls_listing->street_suffix);
        $instance->purchase_list_price = $mls_listing->list_price;
        $instance->last_purchase_price = $mls_listing->old_price;
        $instance->project_id = '';
        $instance->is_featured = false;
        $instance->item_status = $mls_listing->status;
        $instance->community_id = '';
        $instance->mls_number = $mls_listing->z_textsearch_mls_num;
        $instance->description = $mls_listing->public_remarks;
        $instance->short_description = $mls_listing->public_remarks;
        $instance->assets = explode(',', $mls_listing->photo_filenames);
        $instance->last_modified_date = $mls_listing->date_updated;
        $instance->X3D_tour_link = $mls_listing->un_branded_virtual_tour;
        $instance->listing_office_id = $mls_listing->listing_office_short_id;
        $instance->listing_office_name = $mls_listing->listing_office_name;

        // do some data finagling to match how pbase works

        // MLS uses VAC/RES instead of Home, Townhome, etc like pbase.
        if ($mls_listing->class === 'VAC') {
            $instance->unit_type = 'Homesite';
        } elseif ($mls_listing->class === 'RES') {
            $instance->unit_type = $mls_listing->sub_type;
            $instance->unit_type = str_replace('Single Family Attached', 'Attached', $instance->unit_type);
            $instance->unit_type = str_replace('Single Family Detached', 'Detached', $instance->unit_type);
        } else {

        }

        // MLS sends an acreage amount. Set that to the total area sqft that's used in the property view.
        // note: if we want to show Acreage in the future along with total area sqft... we'll need a new field
        // in pbase since pbase sends acreage in total area sqft.
        if ($mls_listing->class === 'VAC') {
            $instance->total_area_sqft = $mls_listing->acreage;
        }

        SEO::addProperty($instance);

        return $instance;
    }

    /**
     * If the $id contains 'pb_' then go to Property Base for data else go to MLS
     *
     * @param $id
     * @return Property|bool
     */
    public static function withId($id)
    {
        if (false !== stristr($id, 'pb_')) {
            $id = str_replace('pb_', '', $id);
            $property = PropertyBase::getByPropertyBaseId($id);
        } else {
            $property = MLS::getByMLSNumber($id);
        }

        return $property;
    }

    /**
     * @return string
     */
    public function link()
    {
        $id = $this->getId();
        $address = Twig::slugify($this->getAddress());
        return home_url() . "/home-finder/properties/$address/$id/";
    }

    public function getLastModifiedDate()
    {
        return $this->last_modified_date;
    }

    /**
     * @return bool
     */
    public function isListed()
    {
        return $this->is_listed === 'true';
    }

    public function getFeaturedImageSrc()
    {
        if ($this->isFromPropertyBase()) {
            $featured_image = $this->assets;
            if (is_array($featured_image) && !empty($featured_image) && isset($featured_image['id']) === false) {
                $featured_image = array_shift($featured_image);
            }

            if (isset($featured_image['midresUrl'])) {
                return $featured_image['midresUrl'];
            }
        } else {
            $assets = $this->assets;

            if (is_array($assets) && !empty($assets)) {
                return array_shift($assets);
            }
        }

        return '';
    }

    /**
     * @return array
     */
    public function getImages()
    {
        $assets = $this->assets;

        $images = array();
        if ($this->isFromPropertyBase()) {
            //        $floor_plan_url = '';

        foreach ($assets as $asset) {
            if (
                isset($asset['url']) &&
                isset($asset['mimeType']) &&
                false !== stristr($asset['mimeType'], 'image') &&
                false === stristr($asset['url'], 'fplan')
            ) {
                $images[] = $asset['url'];
            }

//            if (false !== stristr($asset['url'], 'fplan')) {
//                $floor_plan_url = $asset['url'];
//            }
        }

            // stick the floor plan at the end of the images
            // DI would rather not show the floor plans in the gallery
//        if ('' !== $floor_plan_url) {
//            $images[] = $floor_plan_url;
//        }
        } else {
            $images = $assets;
        }

        return $images;
    }

    public function getFloorPlanDocumentLink()
    {
        $assets = $this->assets;
        $floor_plan_document_link = '';
        if ($this->isFromPropertyBase()) {
            $floor_plan_document_link = '';
            foreach ($assets as $asset) {
                if (false !== stristr($asset['url'], 'fplan')) {
                    $floor_plan_document_link = $asset['url'];
                }
            }
        }

        return $floor_plan_document_link;
    }

    public function getBuilderName()
    {
        // for some reason the builder name is in builder website not builder name...
        return $this->builder_website;
    }

    public function getBuilder()
    {
        return Builder::withName($this->getBuilderName());
    }

    public function getAddress()
    {
        return $this->address_web;
    }

    public function getNeighborhood()
    {
        return $this->project_name;
    }

    public function getFullBathroomCount()
    {
        if (is_array($this->full_bathrooms) && empty($this->full_bathrooms)) {
            return '0';
        }

        return $this->full_bathrooms;
    }

    public function getHalfBathroomCount()
    {
        return $this->half_bathrooms;
    }

    public function getBedroomCount()
    {
        if (is_array($this->unit_bedrooms) && empty($this->unit_bedrooms)) {
            return '0';
        }

        return $this->unit_bedrooms;
    }

    public function getTotalAreaSquareFootage()
    {
        if ($this->isFromPropertyBase()) {
            if ('Homesite' === $this->getPropertyType()) {
                // return as acres
                return $this->total_area_sqft * .0000229;
            } else {
                return $this->total_area_sqft;
            }
        } else {
            return $this->total_area_sqft;
        }
    }

    public function getTotalAreaSquareFootageUnitOfMeasurement()
    {
        if ('Homesite' === $this->getPropertyType()) {
            return 'ACRES';
        } else {
            return 'SQ FT';
        }
    }

    public function getPurchaseListPrice()
    {
        return $this->purchase_list_price;
    }

    public function getMLSNumber()
    {
        return $this->mls_number;
    }

    public function getUnitView()
    {
        return $this->unit_view;
    }

    /**
     * @return mixed
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    public function getDescription()
    {
        $description = $this->description;
        $description = str_replace('<STYLE>', '<!-- ', $description);
        $description = str_replace('</STYLE>', ' -->', $description);

        // a better way to get the data out of the html fragment... but it screws up encoding
//        return $this->description;
//        $dom = html5qp($this->description);
//        return $dom->html();
//        $domd = new \DOMDocument('1.0', 'UTF-8');
//        $domd->loadHTML($this->description);
//        return $domd->saveHTML();
//
//        // remove <style>
//        $nodeList = $domd->getElementsByTagName('style');
//        for ($nodeIdx = $nodeList->length; --$nodeIdx >= 0;) {
//            $node = $nodeList->item($nodeIdx);
//            $node->parentNode->removeChild($node);
//        }
//
//        // remove the <ul> that is stuck in there if there are no <li> tags
//        $nodeList = $domd->getElementsByTagName('li');
//        if ($nodeList->length === 0) {
//            $node = $domd->getElementsByTagName('ul')->item(0);
////            $description = $this->_getInnerHTMLOfDOMNode($node);
//            $description = $domd->saveHTML($node);
//        } else {
//            $description = $domd->saveHTML();
//        }

        return $description;
    }

    public function getId()
    {
        if ($this->property_base_id) {
            return 'pb_' . $this->property_base_id;
        }

        return $this->mls_number;
    }

    /**
     * http://stackoverflow.com/questions/2087103/how-to-get-innerhtml-of-domnode
     *
     * @param \DOMNode $element
     * @return string
     */
    private function _getInnerHTMLOfDOMNode(\DOMNode $element)
    {
        $innerHTML = "";
        $children = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    /**
     * @return bool
     */
    public function isFromPropertyBase()
    {
        return ($this->property_base_id) ? true : false;
    }

    public function isFromMLS()
    {
        return !$this->isFromPropertyBase();
    }

    public function getX3DTourLink()
    {
        $link = '';
        if (isset($this->X3D_tour_link) && $this->X3D_tour_link !== '') {
            $link = $this->X3D_tour_link;
        }

        return $link;
    }

    /**
     * uses YouTube
     *
     * @return string
     */
    public function getVideoLink()
    {
        $assets = $this->assets;

        $video_url = '';
        foreach ($assets as $asset) {
//            if (
//                isset($asset['url']) &&
//                isset($asset['mimeType']) &&
//                false !== stristr($asset['mimeType'], 'video')
//            ) {
//                $video_url = $asset['url'];
//            }

            if (false === isset($asset['mimeType'])) {
                // maybe a youtube video
                if (
                    isset($asset['category']) && 'Videos' === $asset['category'] &&
                    isset($asset['isExternalLink']) && "true" === $asset['isExternalLink']
                ) {
                    $video_url = $this->_getYouTubeUrlFromPbaseUrl($asset['url']);
                }

            }
        }

        return $video_url;
    }

    private function _getYouTubeUrlFromPbaseUrl($url)
    {
        preg_match("/(?<=\?v=)([a-zA-Z0-9_-]+)/", $url, $matches);
        if (isset($matches[0])) {
            return "http://www.youtube.com/embed/" . $matches[0] . "?rel=0";
        }

        return '';
    }

    public function getFriendlyName()
    {
        return sprintf('%s, %s Bedrooms, %s Full Bathrooms, $%s',
            $this->getAddress(),
            $this->getBedroomCount(),
            $this->getFullBathroomCount(),
            number_format($this->getPurchaseListPrice())
        );
    }

}
