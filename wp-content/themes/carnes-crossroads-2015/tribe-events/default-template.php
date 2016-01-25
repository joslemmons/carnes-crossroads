<?php

$context = Timber::get_context();
$post = Timber::get_post();

$context['post'] = $post;

Timber::render('events-calendar.twig', $context);
