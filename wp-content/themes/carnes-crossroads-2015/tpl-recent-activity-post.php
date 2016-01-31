<?php

use App\Model\Config;

$context = Timber::get_context();
$post = Timber::get_post(false, '\App\Model\Post');

$context['post'] = $post;

wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, false);
wp_enqueue_style('slick-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), false, false);
wp_enqueue_style('slick-theme-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick-theme.css', array('slick-css'), false, false);
wp_enqueue_script('match-height-js', get_template_directory_uri() . '/js/lib/jquery.matchHeight-min.js', array('jquery'), false, false);
wp_enqueue_script('landing-page-js', get_template_directory_uri() . '/js/landing-page.js', array('jquery', 'slick-js', 'match-height-js'), Config::getAppVersion(), false);

Timber::render('single-recent_activity_post.twig', $context, 10800);