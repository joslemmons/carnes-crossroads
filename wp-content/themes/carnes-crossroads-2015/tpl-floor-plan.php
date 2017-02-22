<?php

global $params;

use App\Model\HomeFinderPage;
use HomeFinder\Model\HomeFinder;

$context = Timber::get_context();

$builder_name = (isset($params['builder_name'])) ? str_replace('-', ' ', sanitize_text_field($params['builder_name'])) : false;
$floor_plan_title = (isset($params['floor_plan_title'])) ? str_replace('-', ' ', sanitize_text_field($params['floor_plan_title'])) : false;

$builder = \App\Model\Builder::withName($builder_name);

if (!$builder) {
    wp_redirect(home_url() . '/home-finder');
    exit();
}

$floor_plan = $builder->getFloorPlanByName($floor_plan_title);

if (!$floor_plan) {
    wp_redirect(home_url() . '/home-finder');
    exit();
}


// If navigating directly to a property, then we'll show featured properties in the list be default
$filters = \HomeFinder\Model\HomeFinderFilters::withREQUESTParams();
$filters->setShouldIncludePlans(true);
$featured_properties_result = HomeFinder::getProperties($filters);

$back_url = $params['http_referer'];
$need_search = 'home-finder/search-listings';
$need_featured = 'home-finder/featured-listings';
if (is_null($back_url) || ((strpos($back_url,$need_search) === FALSE &&
    strpos($back_url,$need_featured) === FALSE)))
{
    $back_url = '/home-finder';
}
$context['back_url'] = $back_url;

$context['listingsTitle'] = 'Search Listings';

$context['isSingle'] = true;
$context['isHomePlans'] = true;
$context['result'] = $featured_properties_result;
$context['floor_plan'] = $floor_plan;
$context['seo_title'] = $floor_plan->builder->title . ' - ' .  $floor_plan->title . ' - Daniel Island';
$description = trim(preg_replace('/\s\s+/', ' ', strip_tags($floor_plan->description)));
if (160 < strlen($description)) {
    $description = substr($description, 0, 157) . '...';
}
$context['seo_description'] = $description;

HomeFinderPage::enqueueAssets();

Timber::render('page-floor-plan-view.twig', $context);