<?php

use HomeFinder\Controller\HomeFinder;

Routes::map('/api/home-finder/properties/:id', array('\HomeFinder\Controller\HomeFinder', 'routeProperty'));
Routes::map('/home-finder/properties/:address/:id/print/', array('\HomeFinder\Controller\HomeFinder', 'routePrintProperty'));
Routes::map('/api/home-finder/search', array('\HomeFinder\Controller\HomeFinder', 'routeSearch'));
Routes::map('/api/home-finder/search/page/:num', array('\HomeFinder\Controller\HomeFinder', 'routeSearch'));
Routes::map('/api/home-finder/featured-properties/page/:num', array('\HomeFinder\Controller\HomeFinder', 'routeFeaturedProperties'));
Routes::map('/api/home-finder/recently-listed/page/:num', array('\HomeFinder\Controller\HomeFinder', 'routeRecentlyListed'));
Routes::map('/api/home-finder/saved-listings/page/:num', array('\HomeFinder\Controller\HomeFinder', 'routeSavedListings'));
Routes::map('/home-finder/saved-listings/print/', array('\HomeFinder\Controller\HomeFinder', 'routePrintSavedProperties'));
Routes::map('/api/home-finder/new-offerings/:id', array('\HomeFinder\Controller\HomeFinder', 'routeNewOffering'));
Routes::map('/api/home-finder/new-offerings/page/:num', array('\HomeFinder\Controller\HomeFinder', 'routeNewOfferings'));
Routes::map('/api/home-finder/new-offerings', array('\HomeFinder\Controller\HomeFinder', 'routeNewOfferings'));
Routes::map('/api/home-finder/recent-listings', array('\HomeFinder\Controller\HomeFinder', 'routeRecentListings'));
Routes::map('/api/home-finder/recently-viewed', array('\HomeFinder\Controller\HomeFinder', 'routeRecentlyViewed'));
Routes::map('/api/home-finder/properties/:id/save', array('\HomeFinder\Controller\User', 'routeSaveProperty'));
Routes::map('/api/home-finder/properties/:id/un-save', array('\HomeFinder\Controller\User', 'routeUnSaveProperty'));
Routes::map('/api/home-finder/properties/:id/share', array('\HomeFinder\Controller\User', 'routeShareProperty'));
Routes::map('/api/home-finder/save-search', array('\HomeFinder\Controller\User', 'routeSaveSearch'));
Routes::map('/api/home-finder/un-save-search', array('\HomeFinder\Controller\User', 'routeUnSaveSearch'));
Routes::map('/api/home-finder/request-showing', array('\HomeFinder\Controller\HomeFinder', 'routeRequestShowing'));
Routes::map('/api/home-finder/account-page', array('\HomeFinder\Controller\HomeFinder', 'routeGetAccountPageContent'));
Routes::map('/api/home-finder/save-notification-option', array('\HomeFinder\Controller\User', 'routeSaveNotificationOption'));
Routes::map('/api/home-finder/sign-in', array('\HomeFinder\Controller\User', 'routeSignIn'));

Routes::map('/property-sitemap.xml', array('\HomeFinder\Controller\SEO', 'routeGetPropertySitemap'));
Routes::map('/home-finder/properties/:address/:id/', function ($params) {
    Routes::load('tpl-property.php', $params);
});

Routes::map('/api/home-finder/floor-plans/:builder_name/:floor_plan_title', array('\HomeFinder\Controller\Builder', 'routeGetFloorPlan'));
Routes::map('/home-finder/floor-plans/:builder_name/:floor_plan_title/', function ($params) {
    Routes::load('tpl-floor-plan.php', $params);
});


