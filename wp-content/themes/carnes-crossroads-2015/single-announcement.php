<?php

use App\Model\Config;
use App\Model\Announcement;

$context = Timber::get_context();
/* @var Post $post */
$post = $context['post'] = Timber::get_post(false, '\App\Model\Post');

wp_enqueue_script('match-height-js', get_template_directory_uri() . '/js/lib/jquery.matchHeight-min.js', array('jquery'), false, false);
wp_enqueue_script('poa-js', get_template_directory_uri() . '/js/poa.js', array('jquery', 'slick-js', 'match-height-js'), Config::getAppVersion(), true);

Timber::render('poa/single-announcements.twig', $context);
    