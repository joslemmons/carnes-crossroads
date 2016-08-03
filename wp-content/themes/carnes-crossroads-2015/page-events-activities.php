<?php

use App\Model\Config;
use App\Model\EventCalendar;

global $params;

wp_enqueue_script('match-height-js', get_template_directory_uri() . '/js/lib/jquery.matchHeight-min.js', array('jquery'), false, false);
wp_enqueue_script('poa-js', get_template_directory_uri() . '/js/poa.js', array('jquery', 'slick-js', 'match-height-js'), Config::getAppVersion(), true);

$context = Timber::get_context();

$events_page = get_page_by_path('residents/events-activities');

$page = Timber::get_post($events_page->ID);

$context['sliders'] = $page->page_sliders;

$date = '';
if (isset($params['qdate'])) {
        $date = $params['qdate'];
};

$category = '';
if (isset($params['qslug'])) {
        $category = $params['qslug'];
};

$eslug = '';
if (isset($params['eslug'])) {
        $eslug = $params['eslug'];
};

$evcal = new EventCalendar($date,$category,$eslug);
$evcal->enqueue_assets();

$context['event_calendar'] = $evcal->getCalendar();

if (empty($eslug)) {
        $context['event_cats'] = $evcal->getEventCategories();

        $args = array(
                'post_type' => 'gallery',
                'posts_per_page' => 12,
                'post_status' => array('publish'),
        );

        $context['galleries'] =  Timber::get_posts($args);

        Timber::render('poa/page-events-activities.twig', $context);
} else {
        Timber::render('poa/single-events-activities.twig', $context);
}