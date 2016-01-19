<?php

global $cat;

$context = Timber::get_context();
$context['page'] = Timber::get_post(false, '\App\Model\Page');
$context['categories'] = Timber::get_terms('category');
$context['categoryName'] = get_cat_name($cat);
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();

wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, false);
wp_enqueue_style('slick-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), false, false);

Timber::render('category.twig', $context);

