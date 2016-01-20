<?php

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Result;
use HomeFinder\Model\User;

$context = Timber::get_context();
$user = User::getCurrentlyLoggedUser();

if ($user === false) {
    wp_redirect(home_url() . '/real-estate/home-finder/featured-listings');
    exit();
}
/* @var Result */
$featured_properties_result = HomeFinder::getSavedListingsForUser($user);

$context['result'] = $featured_properties_result;
$context['isSavedListings'] = true;
$context['listingsTitle'] = 'Saved Listings';

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);