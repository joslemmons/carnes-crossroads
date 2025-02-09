<?php

function redirectThisToThat($this_url, $that_url)
{
    Routes::map($this_url, function () use ($that_url) {
        wp_redirect(home_url() . $that_url);
        exit();
    });
}

Routes::map('/category/all', function () {
    Routes::load('category.php');
});

Routes::map('/home-finder/saved-listings/print-sampler/:id', array('\HomeFinder\Controller\HomeFinder', 'routePrintSavedPropertiesSampler'));
Routes::map('/category/all/page/:paged', function($params) {
    Routes::load('category.php', $params);
});


// SEO redirects from old site
redirectThisToThat('/search/', '/home-finder/');
redirectThisToThat('/search/page/:page/?display-imap', '/home-finder/');
redirectThisToThat('/builder/', '/homes/builders/');
redirectThisToThat('/town/commercial-opportunities/', '/community/');
redirectThisToThat('/whats-difference-master-planned-community-subdivision/', '/community/');
redirectThisToThat('/carnes-crossroads-real-estate/faqs/', '/faq/');
redirectThisToThat('/whats-happening-green-barn/', '/lifestyle/');
redirectThisToThat('/charleston-area/summerville-goose-creek/', '/location/');
redirectThisToThat('/charleston-area/', '/location/');
redirectThisToThat('/about-carnes-crossroads-charleston/history/', '/location/');
redirectThisToThat('/events/all-events/', '/news-events/');

Routes::map('/property/:address/pbase/:property_base_id/', function ($params) {
    $address = sanitize_text_field($params['address']);
    $propertyBaseId = sanitize_text_field($params['property_base_id']);

    wp_redirect(home_url() . '/home-finder/properties/' . $address . '/pb_' . $propertyBaseId . '/');
});

Timber::add_route('residents/events-activities/:qdate', function( $params ) {
        Timber::load_template('page-events-activities.php',null,200,$params);
});

Timber::add_route('residents/events-activities/category/:qslug', function( $params ) {
        Timber::load_template('page-events-activities.php',null,200,$params);
});

Timber::add_route('residents/events-activities/event/:eslug/:qdate', function( $params ) {
        Timber::load_template('page-events-activities.php',null,200,$params);
});

Timber::add_route('residents/events-activities/event/:eslug', function( $params ) {
        Timber::load_template('page-events-activities.php',null,200,$params);
});

Timber::add_route('residents/events-activities/category/:qslug/:qdate', function( $params ) {
        Timber::load_template('page-events-activities.php',null,200,$params);
});

Timber::add_route('residents/galleries/:qslug', function( $params ) {
        Timber::load_template('page-residents-gallery.php',null,200,$params);
});

Timber::add_route('galleries/:qslug', function( $params ) {
        Timber::load_template('page-community-gallery.php',null,200,$params);
});

Timber::add_route('residents/announcements/archive/:qdate', function( $params ) {
        Timber::load_template('page-announcements.php',null,200,$params);
});