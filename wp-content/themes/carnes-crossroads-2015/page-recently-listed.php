<?php

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Result;

$context = Timber::get_context();

/* @var Result */
$featured_properties_result = HomeFinder::getRecentlyListed();

$context['result'] = $featured_properties_result;
$context['isRecentlyListed'] = true;
$context['listingsTitle'] = 'Recently Listed';

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);