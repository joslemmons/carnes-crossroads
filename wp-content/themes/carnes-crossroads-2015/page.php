<?php

$context = Timber::get_context();
$context['page'] = Timber::get_post(false, '\App\Model\Page');

wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, false);
wp_enqueue_style('slick-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), false, false);

wp_enqueue_script('outdated-browser-js', get_template_directory_uri() . '/js/lib/outdatedbrowser/outdatedbrowser.min.js', array('jquery'), false, false);
wp_enqueue_style('outdated-browser-css', get_template_directory_uri() . '/js/lib/outdatedbrowser/outdatedbrowser.min.css', array(), false, false);

Timber::render('page-default.twig', $context);