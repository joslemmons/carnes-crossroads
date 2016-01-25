<?php

$context = Timber::get_context();
$context['page'] = Timber::get_post(false, '\App\Model\HomesPage');

Timber::render('page-homes.twig', $context);