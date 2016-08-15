<?php

use App\Model\Config;
use App\Model\Announcement;

global $params;

wp_enqueue_script('match-height-js', get_template_directory_uri() . '/js/lib/jquery.matchHeight-min.js', array('jquery'), false, false);
wp_enqueue_script('poa-js', get_template_directory_uri() . '/js/poa.js', array('jquery', 'slick-js', 'match-height-js'), Config::getAppVersion(), true);

$context = Timber::get_context();
$announcements_page = get_page_by_path('residents/announcements');
$page = Timber::get_post($announcements_page->ID);

if (isset($params['qdate'])) {
	$date = $params['qdate'];
	$context['date_title'] = date("F Y",strtotime($date.'-01'));
	$announcements = Announcement::get(false,-1,$date);
} else {
	$announcements = Announcement::get(false,9);
} 

$context['archive_links'] = Announcement::getArchiveNav();
$context['sliders'] = $page->page_sliders;
$context['announcements'] = $announcements;

Timber::render('poa/page-announcements.twig', $context);