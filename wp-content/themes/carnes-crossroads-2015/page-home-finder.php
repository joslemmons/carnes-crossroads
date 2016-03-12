<?php

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Result;

$context = Timber::get_context();

$filters = \HomeFinder\Model\HomeFinderFilters::withREQUESTParams();
$filters->setShouldIncludeHomes(true);

/* @var Result */
$featured_properties_result = HomeFinder::getProperties($filters);

$context['result'] = $featured_properties_result;
$context['isAvailableHomes'] = true;
$context['listingsTitle'] = 'Search Listings';

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);

