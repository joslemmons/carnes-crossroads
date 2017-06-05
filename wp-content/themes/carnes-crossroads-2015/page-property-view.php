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

$back_url = $params['http_referer'];
$need_search = 'real-estate/home-finder/search-listings';
$need_featured = 'real-estate/home-finder/featured-listings';
if (is_null($back_url) || ((strpos($back_url,$need_search) === FALSE &&
        strpos($back_url,$need_featured) === FALSE)))
{
    $back_url = '/home-finder';
}
$context['back_url'] = $back_url;

HomeFinderPage::enqueueAssets();

Timber::render('page-property-view.twig', $context);