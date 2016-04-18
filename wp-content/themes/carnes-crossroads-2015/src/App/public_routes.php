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
