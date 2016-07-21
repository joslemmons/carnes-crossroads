<?php

use App\Model\Config;
use App\Model\Announcement;

wp_enqueue_script('match-height-js', get_template_directory_uri() . '/js/lib/jquery.matchHeight-min.js', array('jquery'), false, false);
wp_enqueue_script('poa-js', get_template_directory_uri() . '/js/poa.js', array('jquery', 'slick-js', 'match-height-js'), Config::getAppVersion(), true);

$context = Timber::get_context();
$page = Timber::get_post();

$context['sliders'] = $page->page_sliders;
$context['announcements'] = Announcement::get();

Timber::render('poa/page-announcements.twig', $context);