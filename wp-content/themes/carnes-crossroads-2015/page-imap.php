<?php
/**
 * Created by PhpStorm.
 * User: jcolfer
 * Date: 2/17/17
 * Time: 11:25 AM
 */

$context = Timber::get_context();
$page = Timber::get_post(false, '\App\Model\Page');

$listings = \App\Model\PlaceOfInterest::all();

foreach ($listings as $listing) {
    $listing->tooltip = Timber::compile('partials/home-finder/imap-tool-tip.twig', array(
        'post' => $listing
    ));
}

$request_data = array(
    'prices' => "0-5000000",
    'bedrooms' => "",
    'bathrooms' => "",
    'searchAddress' => "",
    'sort' => "default",
    'includePlans' => "false",
    'builders' => ""
);

$filters = \HomeFinder\Model\HomeFinderFilters::withRawFilters($request_data);
$available_homes = \HomeFinder\Model\HomeFinder::getProperties($filters)->items;

$context['page'] = $page;
$context['listings'] = $listings;
$context['available_homes'] = $available_homes;

\App\Model\iMap::enqueueAssets();

Timber::render('page-imap.twig', $context, \App\Model\Helper::getPageCacheTime());
