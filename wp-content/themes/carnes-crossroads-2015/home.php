<?php

use App\Model\Post;

$context = Timber::get_context();
$context['page'] = Timber::get_post(get_option('page_for_posts'), '\App\Model\NewsAndEventsPage');
$context['categories'] = Timber::get_terms('category');
$context['featured_posts'] = Post::getFeaturedNews(1);
$context['recent_posts'] = Post::getRecentNews();

Timber::render('page-events.twig', $context);