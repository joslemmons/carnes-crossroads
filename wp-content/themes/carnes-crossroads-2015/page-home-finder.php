<?php

use App\Model\HomeFinderPage;
use App\Model\PlaceOfInterest;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Result;

$context = Timber::get_context();

$filters = \HomeFinder\Model\HomeFinderFilters::withREQUESTParams();
$filters->setShouldIncludeHomes(true);
$filters->setShouldIncludePlans(true);

$view = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'grid';

/* @var Result */
$featured_properties_result = HomeFinder::getProperties($filters);

$context['view'] = $view;
$context['result'] = $featured_properties_result;
$context['isAvailableHomes'] = true;
$context['listingsTitle'] = 'Search Listings';
$context['places_of_interest'] = PlaceOfInterest::all();

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);
