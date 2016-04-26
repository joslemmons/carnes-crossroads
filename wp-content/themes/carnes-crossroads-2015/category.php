<?php

global $cat;

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

global $params;
if (!isset($params['paged']) || !$params['paged']) {
    $paged = $params['paged'];
}

$context = Timber::get_context();

if (true === isset($cat) && $cat !== '') {
    $posts = Timber::get_posts();
} else {
    // all
    $queryArgs = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'paged' => $paged
    );
    query_posts($queryArgs);
    $posts = Timber::get_posts($queryArgs);
}

$context['page'] = $posts;
$context['categories'] = Timber::get_terms('category');
$context['categoryName'] = get_cat_name($cat);
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
$context['news_page'] = Timber::get_post(get_option( 'page_for_posts' ), '\App\Model\NewsAndEventsPage');

wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, false);
wp_enqueue_style('slick-css', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.css', array(), false, false);

Timber::render('category.twig', $context);

