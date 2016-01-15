<?php

$context = Timber::get_context();
$context['page'] = Timber::get_post(false, '\App\Model\Page');

wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, false);
wp_enqueue_style('slick-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), false, false);

Timber::render('page-privacy.twig', $context);

