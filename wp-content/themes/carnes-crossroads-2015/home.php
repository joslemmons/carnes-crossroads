<?php

use App\Model\Config;
use App\Model\Post;
use App\Model\Social;

$context = Timber::get_context();
$context['page'] = Timber::get_post(get_option('page_for_posts'), '\App\Model\NewsAndEventsPage');
$context['categories'] = Timber::get_terms('category');
$featured_posts = Post::getFeaturedNews(1);
$context['recent_posts'] = $recent_posts = Post::getRecentNews();

if (empty($featured_posts) === true) {
    $featured_posts[] = array_shift($recent_posts);
}

$context['featured_posts'] = $featured_posts;

$context['tribe_events'] = tribe_get_events(array(
    'posts_per_page' => 12,
    'start_date' => date('Y-m-01')
));

// TODO: something is up with instagram
$context['social_feed'] = TimberHelper::transient(Config::getKeyPrefix() . 'recent_social_posts', function () {
    return Social::getSocialFeed();
}, 60 * 60);

Timber::render('page-events.twig', $context);