<?php

global $params;

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;
use HomeFinder\Model\Property;
use HomeFinder\Model\User;

$context = Timber::get_context();

$id = $params['id'];
$property = Property::withId($id);

if (false === $property) {
    wp_redirect(home_url() . '/real-estate/home-finder');
    exit();
}

// mark the property as viewed by logged user (if logged in)
if (is_user_logged_in()) {
    $user = User::getCurrentlyLoggedUser();
    $user->markPropertyAsViewed($property);
}

// If navigating directly to a property, then we'll show featured properties in the list be default
$featured_properties_result = HomeFinder::getFeaturedProperties();
$context['listingsTitle'] = 'Featured Listings';

$context['isSingle'] = true;
$context['result'] = $featured_properties_result;
$context['active_item'] = $property;
$context['seo_title'] = $property->getAddress() . ' - Daniel Island';
$description = trim(preg_replace('/\s\s+/', ' ', strip_tags($property->getDescription())));
if (160 < strlen($description)) {
    $description = substr($description, 0, 157) . '...';
}
$context['seo_description'] = $description;

HomeFinderPage::enqueueAssets();

Timber::render('page-home-finder.twig', $context);

