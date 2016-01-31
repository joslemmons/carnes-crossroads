<?php

global $post;

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Result;

$context = Timber::get_context();

/* @var Result */
$new_offerings_result = HomeFinder::getNewOfferings();

$context['result'] = $new_offerings_result;
$context['item'] = $post;
$context['isNewOfferings'] = true;
$context['listingsTitle'] = 'New Offerings';

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);