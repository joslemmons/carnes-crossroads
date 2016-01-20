<?php

$context = Timber::get_context();
$context['post'] = Timber::get_post(false, '\App\Model\Post');
$context['categories'] = Timber::get_terms('category');

Timber::render('news-single.twig', $context);