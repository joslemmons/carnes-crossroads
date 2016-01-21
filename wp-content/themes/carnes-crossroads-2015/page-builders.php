<?php

use App\Model\Builder;

$context = Timber::get_context();
$context['page'] = Timber::get_post(false, '\App\Model\Page');
$context['builders'] = Builder::all();

Timber::render('page-builders.twig', $context);