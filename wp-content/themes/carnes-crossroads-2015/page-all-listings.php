<?php

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinderFilters;
use HomeFinder\Model\Result;

$context = Timber::get_context();

/* @var Result */
$per_page = \HomeFinder\Model\HomeFinder::LISTINGS_PER_PAGE;
$page = (isset($params['num']) && false !== filter_var($params['num'], FILTER_VALIDATE_INT)) ? (int)$params['num'] : 1;

$filters = HomeFinderFilters::withREQUESTParams();

/* @var $result Result */
$result = \HomeFinder\Model\HomeFinder::getProperties($filters, $per_page, $page);

$context['filters'] = $filters;
$context['result'] = $result;
$context['isAllListings'] = true;
$context['listingsTitle'] = 'All Listings';

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);

