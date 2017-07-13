<?php

global $params;

use App\Model\PropertyView;
use HomeFinder\Model\Metric;
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
    Metric::trackPropertyListingView($property);
}

// If navigating directly to a property, then we'll show featured properties in the list be default
$featured_properties_result = HomeFinder::getFeaturedProperties();

$back_url = $params['http_referer'];
$need_search = 'home-finder/search-listings';
$need_featured = 'home-finder/featured-listings';
if (is_null($back_url) || ((strpos($back_url,$need_search) === FALSE &&
    strpos($back_url,$need_featured) === FALSE)))
{
    $back_url = '/home-finder';
}
$context['back_url'] = $back_url;

$context['listingsTitle'] = 'Featured Listings';

$context['isSingle'] = true;
$context['result'] = $featured_properties_result;
$context['property'] = $property;
$context['seo_title'] = $property->getAddress() . ' - Carnes Crossroads';
$context['property_images'] = $property->getImages();
$description = trim(preg_replace('/\s\s+/', ' ', strip_tags($property->getDescription())));
if (160 < strlen($description)) {
    $description = substr($description, 0, 157) . '...';
}

$back_url = $params['http_referer'];
$need_search = 'home-finder/search-listings';
$need_featured = 'home-finder/featured-listings';

if (is_null($back_url) || ((strpos($back_url,$need_search) === FALSE &&
        strpos($back_url,$need_featured) === FALSE)))
{
    $back_url = '/home-finder';
}

$context['back_url'] = $back_url;

$context['seo_description'] = $description;

PropertyView::enqueueAssets();

Timber::render('page-property-view.twig', $context);

