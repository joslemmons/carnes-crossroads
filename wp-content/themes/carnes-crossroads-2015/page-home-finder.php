<?php

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Result;

$context = Timber::get_context();

$filters = \HomeFinder\Model\HomeFinderFilters::withREQUESTParams();
$filters->setShouldIncludeHomes(true);
$filters->setShouldIncludePlans(false);

$view = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'grid';

/* @var Result */
$featured_properties_result = HomeFinder::getProperties($filters);

$context['view'] = $view;
$context['result'] = $featured_properties_result;
$context['isAvailableHomes'] = true;
$context['listingsTitle'] = 'Featured Listings';
$context['isFeaturedListings'] = true;

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);
