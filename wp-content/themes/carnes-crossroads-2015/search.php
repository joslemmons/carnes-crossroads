<?php

global $s;

$context = Timber::get_context();

$context['search_term'] = $s;
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
$context['page'] = Timber::get_post();

Timber::render('search.twig', $context);
