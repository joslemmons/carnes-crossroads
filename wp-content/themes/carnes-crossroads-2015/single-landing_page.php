<?php

$context = Timber::get_context();
$post = Timber::get_post(false, '\App\Model\LandingPage');


$context['post'] = $post;

Timber::render('single-landing_page.twig', $context);