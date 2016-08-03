<?php

$context = Timber::get_context();

global $params;

$id = 0;

if (isset($params['qslug'])) {
	if ( $pst = get_page_by_path( $params['qslug'], OBJECT, 'gallery' ) )
	    $id = $pst->ID;		
}

if ($id) {
	$context['page'] = Timber::get_post($id);
	$context['gallery'] = do_shortcode("[gallery id=\"$id\"]");
}

Timber::render('poa/page-residents-gallery.twig', $context);
