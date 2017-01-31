<?php

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\HomeFinderFilters;
use HomeFinder\Model\Result;

$context = Timber::get_context();
$page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;
$view = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'grid';
$filterString = '';
if (!empty($_REQUEST)) {
    $filterString = '/?';
    foreach ($_REQUEST as $fName => $fValue) {
        $filterString .= $fName . '=' . $fValue . '&';
    }    
    $filterString = rtrim($filterString, "&");
}

/* @var Result */
$result = HomeFinder::getFeaturedProperties(null, $page, null, null);

$filters = HomeFinderFilters::withREQUESTParams();
$filters->setShouldIncludePlans(false);

$context['pages'] = $result->paginator->count();;
$context['page'] = $page;
$context['name'] = $name;
$context['view'] = $view;
$context['filterString'] = $filterString;
$context['filters'] = $filters;

$context['result'] = $result;
$context['isFeaturedListings'] = true;
$context['listingsTitle'] = 'Featured Listings';

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context, 60 * 60 * 3);