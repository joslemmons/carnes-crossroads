<?php

$context = Timber::get_context();
$context['page'] = Timber::get_post(false, '\App\Model\Page');

Timber::render('page-builders.twig', $context);