<?php

use App\Model\Config;
use App\Model\EventCalendar;

global $params;

//Event Calendar Styles
wp_enqueue_style('tribe-events-pro-full-mobile-css', '/wp-content/plugins/events-calendar-pro/src/resources/css/tribe-events-pro-full-mobile.min.css');
wp_enqueue_style('tribe-events-pro-full-css', '/wp-content/plugins/events-calendar-pro/src/resources/css/tribe-events-pro-full.min.css');
wp_enqueue_style('widget-this-week-full-css', '/wp-content/plugins/events-calendar-pro/src/resources/css/widget-this-week-full.min.css');

//Event Calendar Script
wp_enqueue_script('widget-this-week-js', '/wp-content/plugins/events-calendar-pro/src/resources/js/widget-this-week.min.js', array('jquery'), false, true);
wp_enqueue_script('tribe-events-bar-js', '/wp-content/plugins/the-events-calendar/src/resources/js/tribe-events-bar.min.js', array('jquery'), false, true);


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
$context['active_category'] = $category;

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