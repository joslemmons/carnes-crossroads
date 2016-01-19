<?php

global $post;

use App\Model\Config;

$context = Timber::get_context();
$context['page'] = Timber::get_post($post->ID, '\App\Model\FAQPage');

wp_enqueue_script('faq-js', get_template_directory_uri() . '/js/faq.js', array('jquery'), Config::getAppVersion(), true);

Timber::render('page-faq.twig', $context);