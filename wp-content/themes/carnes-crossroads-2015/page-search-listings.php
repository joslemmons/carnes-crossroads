<?php

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinderFilters;
use HomeFinder\Model\Metric;
use HomeFinder\Model\Result;

function _getFilterFromRequestByKey($key)
{
    $filters = (isset($_GET[$key])) ? $_GET[$key] : array();

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

$context = Timber::get_context();

/* @var Result */
$per_page = \HomeFinder\Model\HomeFinder::LISTINGS_PER_PAGE;
$page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;

$filters = HomeFinderFilters::withREQUESTParams();
Metric::trackSearch($filters);

/* @var $result Result */
$result = \HomeFinder\Model\HomeFinder::getProperties($filters, $per_page, $page);

$context['view'] = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'grid';
$context['filters'] = $filters;
$context['result'] = $result;
$context['isSearchListings'] = true;
$context['listingsTitle'] = 'Search Listings';

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);
