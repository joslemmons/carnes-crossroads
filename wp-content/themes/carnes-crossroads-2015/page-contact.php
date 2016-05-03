<?php

use App\Model\Config;

$context = Timber::get_context();
$context['page'] = Timber::get_post(false, '\App\Model\Page');

Timber::render('page-contact.twig', $context);

