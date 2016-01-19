<?php

$context = Timber::get_context();
$context['post'] = new TimberPost();
$context['categories'] = Timber::get_terms('category');

Timber::render('news-single.twig', $context);