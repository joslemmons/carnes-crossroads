<?php

// get properties with various filter options (pbase, mls, all prop filters)
Routes::map('/api/home-finder/properties', array('\HomeFinder\Controller\HomeFinder', 'routeProperties'));
Routes::map('/api/home-finder/featured-properties', array('\HomeFinder\Controller\HomeFinder', 'routeFeaturedProperties'));
Routes::map('/api/home-finder/new-offerings', array('\HomeFinder\Controller\HomeFinder', 'routeNewOfferings'));
Routes::map('/api/home-finder/recent-listings', array('\HomeFinder\Controller\HomeFinder', 'routeRecentListings'));
Routes::map('/api/home-finder/recently-viewed', array('\HomeFinder\Controller\HomeFinder', 'routeRecentlyViewed'));

Routes::map('/api/home-finder/:pbase_id/viewed', array('\HomeFinder\Controller\User', 'routeViewedProperty'));
Routes::map('/api/home-finder/:pbase_id/favorite', array('\HomeFinder\Controller\User', 'routeFavoriteProperty'));
Routes::map('/api/home-finder/save-search', array('\HomeFinder\Controller\User', 'routeSaveSearch'));
Routes::map('/api/home-finder/favorites', array('\HomeFinder\Controller\User', 'routeGetFavorites'));
Routes::map('/api/home-finder/saved-searches', array('\HomeFinder\Controller\User', 'routeGetFavorites'));
