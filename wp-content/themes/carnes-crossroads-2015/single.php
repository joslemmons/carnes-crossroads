<?php

use App\Model\Config;
use App\Model\Post;

$context = Timber::get_context();
/* @var Post $post */
$post = $context['post'] = Timber::get_post(false, '\App\Model\Post');

if ($post->isRecentActivityPost()) {
    require('tpl-recent-activity-post.php');
} else {
    if (in_category('new-offerings', $post->id)) {
        require('tpl-new-offering.php');
    } else {
        $context['categories'] = Timber::get_terms('category');
        Timber::render('news-single.twig', $context);
    }
}