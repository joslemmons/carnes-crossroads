<?php

$context = Timber::get_context();
$context['page'] = Timber::get_post(false, '\App\Model\FAQPage');

Timber::render('page-faq.twig', $context);