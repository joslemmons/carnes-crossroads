<?php

$context = Timber::get_context();
$context['page'] = Timber::get_post(get_option('page_for_posts'), '\App\Model\Page');

Timber::render('page-events.twig', $context);