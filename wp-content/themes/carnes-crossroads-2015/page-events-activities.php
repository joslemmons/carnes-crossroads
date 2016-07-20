<?php

use App\Model\Config;
use App\Model\EventCalendar;

global $params;

wp_enqueue_script('match-height-js', get_template_directory_uri() . '/js/lib/jquery.matchHeight-min.js', array('jquery'), false, false);
wp_enqueue_script('poa-js', get_template_directory_uri() . '/js/poa.js', array('jquery', 'slick-js', 'match-height-js'), Config::getAppVersion(), true);

$context = Timber::get_context();

$events_page = get_page_by_path('community/events-activities');

$page = Timber::get_post($events_page->ID);

$context['sliders'] = $page->page_sliders;

$date = '';
if (isset($params['qdate'])) {
	$date = $params['qdate'];
};

$evcal = new EventCalendar($date);
$evcal->enqueue_assets();

$context['event_calendar'] = $evcal->getCalendar();

Timber::render('poa/page-events-activities.twig', $context);