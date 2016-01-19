<?php

$context = Timber::get_context();
$context['page'] = Timber::get_post(get_option('page_for_posts'), '\App\Model\NewsAndEventsPage');
$context['categories'] = Timber::get_terms('category');

Timber::render('page-events.twig', $context);