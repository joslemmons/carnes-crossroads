<?php

global $cat;

$context = Timber::get_context();

$context['posts'] = tribe_get_events(array(
    'posts_per_page' => 12,
    'start_date' => date('Y-m-01')
));

$context['categories'] = Timber::get_terms('category');
$context['categoryName'] = get_cat_name($cat);
$context['pagination'] = Timber::get_pagination();
$context['news_page'] = Timber::get_post(get_option( 'page_for_posts' ), '\App\Model\NewsAndEventsPage');

wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, false);
wp_enqueue_style('slick-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), false, false);

Timber::render('category-upcoming-events.twig', $context);

