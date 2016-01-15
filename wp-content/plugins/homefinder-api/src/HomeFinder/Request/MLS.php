<?php namespace HomeFinder\Request;

use Elasticsearch\Common\Exceptions\ClientErrorResponseException;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use HomeFinder\Model\HomeFinderFilters;
use HomeFinder\Model\MLSListing;
use HomeFinder\Model\Property;
use HomeFinder\Model\Result;

class MLS
{
    public static function getByMLSNumber($mls_number)
    {
        // get MLS properties
        $client = new \Elasticsearch\Client(array(
            'hosts' => array('20bc6e1944d0a586000.qbox.io:80')
        ));

        try {
        $listings = $client->search(array(
                'index' => '',
                'type' => '_all',
                'body' => array(
                    'query' => array(
                        'match' => array(
                            'is_active' => 1
                        )
                    ),
                    "filter" => array(
                        "bool" => array(
                            "must" => array(
                                array(
                                    "fquery" => array(
                                        "query" => array(
                                            "query_string" => array(
                                                "query" => "z_textsearch_mls_num:(\"" . $mls_number . "\")"
                                            )
                                        ),
                                        "_cache" => true
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
        } catch (NoNodesAvailableException $ex) {
            $listings = array();
        }

        $property = false;
        if (isset($listings['hits']['hits']) && is_array($listings['hits']['hits']) && count($listings['hits']['hits']) > 0) {
            $item_list = $listings['hits']['hits'];
            $item = array_pop($item_list);

            $mls_listing = new MLSListing($item);
            $property = Property::withMLSListing($mls_listing);
        }

        return $property;
    }

    /**
     * @param HomeFinderFilters $filters
     * @param int $per_page
     * @param int $page
     * @return Result
     */
    public static function getWithFilters(HomeFinderFilters $filters, $per_page = 24, $page = 1)
    {
        $page--;
        $from = $per_page * $page;

        $areaFilters = $filters->getAreaFiltersForMLSRequest();
        $propertyTypeFilters = $filters->getPropertyTypeFiltersForMLSRequest();
        $neighborhoodFilters = $filters->getNeighborhoodFiltersForMLSRequest();
        $priceFilters = $filters->getPricesFiltersForMLSRequest();
        $bedroomFilters = $filters->getBedroomsFiltersForMLSRequest();
        $bathroomFilters = $filters->getBathroomsFiltersForMLSRequest();

        $mlsNumbersToExclude = $filters->getPropertiesToExcludeForMLSRequest();

        // get MLS properties
        $client = new \Elasticsearch\Client(array(
            'hosts' => array('20bc6e1944d0a586000.qbox.io:80')
        ));

        $must_not = array();
        foreach ($mlsNumbersToExclude as $fquery) {
            $must_not[] = $fquery;
        }

        try {
            $params = [
                'index' => '',
                'type' => '_all',
                'size' => $per_page,
                'from' => $from,
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => [
                                $areaFilters,
                                $priceFilters,
                                ['match' => ['is_active' => 1]]
                            ],
                            'minimum_should_match' => 1
                        ]
                    ]
                ]
            ];

            if ($neighborhoodFilters) {
                $params['body']['query']['bool']['must'][] = $neighborhoodFilters;
            }

            if ($propertyTypeFilters) {
                $params['body']['query']['bool']['must'][] = $propertyTypeFilters;
            }

            if ($bedroomFilters) {
                $params['body']['query']['bool']['must'][] = $bedroomFilters;
            }

            if ($bathroomFilters) {
                $params['body']['query']['bool']['must'][] = $bathroomFilters;
            }

            $listings = $client->search($params);
        } catch (NoNodesAvailableException $ex) {
            $listings = array();
        } catch (ClientErrorResponseException $ex) {
            $listings = array();
        }

        $properties = array();
        if (isset($listings['hits']['hits']) && is_array($listings['hits']['hits']) && count($listings['hits']['hits']) > 0) {
            $item_list = $listings['hits']['hits'];

            foreach ($item_list as $item) {
                $mls_listing = new MLSListing($item);
                $property = Property::withMLSListing($mls_listing);
                $properties[] = $property;
            }
        }

        $total = (isset($listings['hits']['total'])) ? $listings['hits']['total'] : 0;

        $result = Result::withTotalAndPerPageAndCurrentItems($total, $per_page, $properties);

        return $result;
    }
}