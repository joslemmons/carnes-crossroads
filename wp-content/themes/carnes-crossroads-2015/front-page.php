<?php

$context = Timber::get_context();
$page = Timber::get_post(false, '\App\Model\FrontPage');

$context['page'] = $page;

Timber::render('front-page.twig', $context);