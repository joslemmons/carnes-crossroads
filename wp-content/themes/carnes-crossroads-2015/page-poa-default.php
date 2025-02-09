<?php
/*
 * Template Name: POA Default
 * Description: Default page for POA
 */

use App\Model\Config;

wp_enqueue_script('waypoints-js', get_template_directory_uri() . '/js/lib/jquery.waypoints.min.js', array('jquery'), false, false);

wp_enqueue_script('match-height-js', get_template_directory_uri() . '/js/lib/jquery.matchHeight-min.js', array('jquery'), false, false);
wp_enqueue_script('poa-js', get_template_directory_uri() . '/js/poa.js', array('jquery', 'backbone', 'underscore', 'slick-js', 'match-height-js'), Config::getAppVersion(), true);

$context = Timber::get_context();
$page = Timber::get_post(false, '\App\Model\Page');

$active_child_page = null;
if ( ($page->parent() !== false) && ($page->parent->slug != 'residents') ) {
    // this is a child page... need to show it's parent since this page is shown on the parent
    $active_child_page = $page;
    $page->PostClass = '\App\Model\Page';
    $page = $page->parent();
}

$context['page'] = $page;

if ($page->slug == 'directories') { 
	
	$page_hl = get_page_by_path( 'residents/directories/helpful-links-contacts' );
	$hl_meta = get_post_meta($page_hl->ID);
	$context['helpful_links'] = unserialize($hl_meta['page_links'][0]);
	
	$page_staff = get_page_by_path( 'residents/directories/poa-staff' );
	$staff_meta = get_post_meta($page_staff->ID);
	$context['staff_members'] = unserialize($staff_meta['staff_members'][0]);

} elseif ( strpos($page->slug, 'faq') !== false ) { 
	
	$page_faq = get_page_by_path( $_SERVER['REQUEST_URI'] );
	$faq_meta = get_post_meta($page_faq->ID);
	$context['faqs'] = unserialize($faq_meta['page_faqs'][0]);
	
}

Timber::render('poa/page-default.twig', $context);