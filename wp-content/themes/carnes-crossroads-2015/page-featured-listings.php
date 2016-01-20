<?php

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Result;

$context = Timber::get_context();

/* @var Result */
$featured_properties_result = HomeFinder::getFeaturedProperties();

$context['result'] = $featured_properties_result;
$context['isFeaturedListings'] = true;
$context['listingsTitle'] = 'Featured Listings';

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);