<?php 

$context = Timber::get_context();
$context['post'] = new TimberPost();

Timber::render('404.twig', $context);