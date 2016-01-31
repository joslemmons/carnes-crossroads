<?php

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Result;

$context = Timber::get_context();

$per_page = HomeFinder::LISTINGS_PER_PAGE;

/* @var Result */
$new_offerings_result = HomeFinder::getNewOfferings();

$context['result'] = $new_offerings_result;
$context['isNewOfferings'] = true;
$context['listingsTitle'] = 'New Offerings';

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);