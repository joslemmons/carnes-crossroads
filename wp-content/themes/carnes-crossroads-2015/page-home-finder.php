<?php

use App\Model\HomeFinderPage;
use App\Model\PlaceOfInterest;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Result;

$context = Timber::get_context();

$filters = \HomeFinder\Model\HomeFinderFilters::withREQUESTParams();
$filters->setShouldIncludeHomes(true);
$filters->setShouldIncludePlans(false);

$view = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'grid';

/* @var Result */
$featured_properties_result = HomeFinder::getProperties($filters);

$placesOfInterest = PlaceOfInterest::all();

foreach ($placesOfInterest as $listing) {
    $listing->tooltip = Timber::compile('partials/home-finder/imap-tool-tip.twig', array(
        'post' => $listing
    ));
}

$context['view'] = $view;
$context['result'] = $featured_properties_result;
$context['isAvailableHomes'] = true;
$context['listingsTitle'] = 'Search Listings';
$context['placesOfInterest'] = $placesOfInterest;

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);
