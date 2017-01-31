Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

jQuery(function ($) {
    var map, filters, checkboxes, layer, listings;
    L.mapbox.accessToken = 'pk.eyJ1IjoiZGlkZXZjbyIsImEiOiJjaXM3cWY3NDEwNDc0Mnpwa2w5YnllMXZkIn0.4pWeAL6-vhtobhpFd2HDuA';

    var $saveSearchSection = $('#saveSearchSection'),
        order = 'default';

    function initMap(placesOfInterest) {
        filters = document.getElementById('legend-items');
        checkboxes = document.getElementsByClassName('squared-checkbox');
        listings = document.getElementsByClassName('map-results-box');

        map = L.mapbox.map('map', 'mapbox.streets', {
            'maxZoom': 18,
            'minZoom': 15,
            'scrollWheelZoom' : 'center'
        })
            .setView([33.055457, -80.103917], 17);

        layer = L.mapbox.featureLayer().addTo(map);

        var geoJson = {
            type: 'FeatureCollection',
            features: []
        };

        for(var j = 0; j < placesOfInterest.length; j++) {
            geoJson.features.push({
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [-80.103914, 33.055454]
                },
                "properties": {
                    "listing-type": "place-of-interest"
                }
            });
        }

        for(var i = 0; i < locations.length; i++) {
            geoJson.features.push({
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [parseFloat(locations[i][2]), parseFloat(locations[i][1])]
                },
                "properties": {
                    "address": locations[i][0],
                    "marker-color": (locations[i][6] === 'Single Family Home') ? '#b06a6a' : (locations[i][6] === 'Condominium' || locations[i][6] === 'Townhome') ? '#0a8c7c' : '#c9c23d',
                    "pop-up": locations[i][5],
                    "listing-type": (locations[i][6] === 'Single Family Home') ? 'available-homes' : (locations[i][6] === 'Condominium' || locations[i][6] === 'Townhome') ? 'available-townhomes' : 'available-homesites'
                }
            });
        }

        layer.setGeoJSON(geoJson);

        var stamenLayer = L.tileLayer(DI.templateUri + "/img/imap/tiles/{z}/{x}/{y}.png").addTo(map);

        //re-filter the markers when the form is changed
        filters.onchange = change;
        //initially trigger the filter
        change();

        map.eachLayer(function(marker) {
            if(marker.feature && marker.feature.properties['pop-up']) {
                marker.bindPopup(marker.feature.properties['pop-up'], L.popup({ 'autoPan' : true }));
            }
        });

        for(var k = 0; k < listings.length; k++) listings[k].onmouseover = hoverMarkerPopUp;
    }

    function hoverMarkerPopUp() {
        var address = $(this).find($('div.map-address')).text().trim();

        map.eachLayer(function(marker) {
            if (marker.feature && marker.feature.properties.address) {
                if (marker.feature.properties.address === address) {
                    marker.openPopup();
                }
            }
        });
    }

    function change() {
        var on = ['place-of-interest'];
        // Find all checkboxes that are checked and build a list of their values
        for(var i = 0; i < checkboxes.length; i++) {
            if(checkboxes[i].childNodes[1].checked) on.push(checkboxes[i].childNodes[1].name);
        }
        // The filter function takes a GeoJSON feature object
        // and returns true to show it or false to hide it.
        layer.setFilter(function (f) {
            // check each marker's symbol to see if its value is in the list
            // of symbols that should be on, stored in the 'on' array
            return on.indexOf(f.properties["listing-type"]) !== -1;
        });

        return false;
    }

    $('#grid-view-toggle').on('click', function () {
        if(!$(this).hasClass('active')) {
            $('#map-view-toggle').removeClass('active');
            $(this).addClass('active');
            $('.pagination').attr('data-page',1);
            performSearch();
        }
    });

    $('#map-view-toggle').on('click', function () {
        if(!$(this).hasClass('active')) {
            $('#grid-view-toggle').removeClass('active');
            $(this).addClass('active');
            $('.pagination').attr('data-page',1);
            performSearch();
        }
    });

    function slugifyListingType() {
        return $('h2.listings-title')
            .text()
            .toLowerCase()
            .replace(/ /g,'-')
            .replace(/[^\w-]+/g,'');
    }

    function updateListingDetailAreaToBeFirstResult() {
        if ($('div.listings-wrapper').find('div.listing').length > 0) {
            var $property = $('div.listings-wrapper').find('div.listing').first();

            if ($property.hasClass('offering') || $property.hasClass('floor-plan')) {
                $property.trigger('click');
            }
            else {
                var propertyId = $property.attr('data-property-id');
                showProperty('_', propertyId);
            }

        }
        else {
            showProperty('_', '_');
        }
    }

    function showSavedListings() {
        $('div.listings-wrapper').fadeTo('slow', 0.3);
        $saveSearchSection.hide();
        showLoadingListingsIndicator();
        $.get('/api/home-finder/saved-listings/page/1', {sort: order}, function (data) {
            var html = data.rsp,
                total = data.total;

            hideLoadingListingsIndicator();

            $('h2.listings-title').text('Saved Listings');

            $('div.results-count').text(pluralize('Result', total, true));

            $('div.listings-wrapper').html(html).fadeTo('slow', 1);

            clearFilters();
            $('#filter-searchAddress').val('');

            // auto click the first result
            $('div.listings-wrapper').find('div.listing').first().trigger('click');
        });
    }

    function showRecentlyListed() {
        $('div.listings-wrapper').fadeTo('slow', 0.3);
        $saveSearchSection.hide();
        showLoadingListingsIndicator();
        $.get('/api/home-finder/recently-listed/page/1', {}, function (data) {
            var html = data.rsp,
                total = data.total;

            hideLoadingListingsIndicator();

            $('h2.listings-title').text('Recently Listed');

            $('div.results-count').text(pluralize('Result', total, true));

            $('div.listings-wrapper').html(html).fadeTo('slow', 1);

            clearFilters();
            $('#filter-searchAddress').val('');

            // auto click the first result
            updateListingDetailAreaToBeFirstResult()
        });
    }

    function showFeaturedListings() {
        $('div.listings-wrapper').fadeTo('slow', 0.3);
        $saveSearchSection.hide();
        showLoadingListingsIndicator();
        $.get('/api/home-finder/featured-properties/page/1', {sort: order}, function (data) {
            var html = data.rsp,
                total = data.total;

            hideLoadingListingsIndicator();

            $('h2.listings-title').text('Featured Listings');

            $('div.results-count').text(pluralize('Result', total, true));

            $('div.listings-wrapper').html(html).fadeTo('slow', 1);

            clearFilters();
            $('#filter-searchAddress').val('');

            // auto click the first result
            updateListingDetailAreaToBeFirstResult()
        });
    }

    function showAllListings() {
        $('div.listings-wrapper').fadeTo('slow', 0.3);
        clearFilters();
        performSearch('default', true);
    }

    function showProperty(address, id) {
        $('div.single-listing-col').fadeTo('slow', 0.3);
        $.get('/api/home-finder/properties/' + id, {}, function (data) {
            var propertyHTML = data.rsp;

            $('div.single-listing-col').html(propertyHTML).fadeTo('slow', 1);


            $('div.single-listing-col .listing-images').slick({
                lazyLoad: 'ondemand',
                dots: false,
                infinite: false,
                speed: 300,
                slidesToShow: 2,
                slidesToScroll: 1,
                centerMode: false,
                arrows: true,
                variableWidth: true,
                respondTo: 'window',
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            infinite: false,
                            variableWidth: true
                        }
                    }
                ]
            });

            //addthis.button($('.addthis_sharing_toolbox').get(0), {
            //    //services_compact: 'facebook,twitter'
            //    ui_use_css: false
            //}, {url: window.location.href});

            initColorboxElements();
        });
    }

    var Router = Backbone.Router.extend({
        routes: {
            'home-finder/properties/:address/:id/': 'showProperty',
            'home-finder/featured-listings/': 'showFeaturedListings',
            'home-finder/recently-listed/': 'showRecentlyListed',
            'home-finder/saved-listings/': 'showSavedListings',
            'home-finder/all-listings/': 'showAllListings',
        },

        showSavedListings: function () {
            showSavedListings();
        },

        showRecentlyListed: function () {
            showRecentlyListed();

        },

        showFeaturedListings: function () {
            showFeaturedListings();
        },

        showProperty: function (address, id) {
            showProperty(address, id);
        },

        showAllListings: function () {
            showAllListings();
        },

    });

    var router = new Router();

    if (!Backbone.history.started) {
        Backbone.history.start({
            pushState: "pushState" in window.history,
            silent: true
        });
        Backbone.history.started = true;

        if ('home-finder/' === Backbone.history.getFragment()) {
            router.navigate("home-finder/featured-listings/", {trigger: false, replace: true});
        }
    }

    function showLoadingListingsIndicator() {
        $('div.listings-wrapper').append('<div class="loading"></div>');
    }

    function hideLoadingListingsIndicator() {
        $('div.listings-wrapper div.loading').remove();
    }

    $('#filter-bedrooms,#filter-bathrooms,#filter-builders,#filter-sqft').on('change', function () {
        $('#filter-searchAddress').val('');
        order = 'default';
        performSearch();
    });

    $('div.listings-type select').on('change', function () {
        var selection = $(this).find('option:selected').val();

        if (selection === 'home-plans') {
            $('div.view-on-map').css('visibility', 'hidden');
        }
        else {
            $('div.view-on-map').css('visibility', 'visible');
        }

        $('#filter-searchAddress').val('');
        performSearch();
    });

    var typingTimer;
    var doneTypingInterval = 2000;
    var $input = $('#filter-searchAddress');

    $input.on('keyup', function (e) {
        clearTimeout(typingTimer);
        if (event.keyCode == '13') {
            doneTyping(true);
        }
        else {
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        }

    });

    $input.on('keydown', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    var pauseSearch = false;

    function doneTyping(ignoreLengthTest) {
        var shouldTestLength = true;
        if (typeof ignoreLengthTest !== 'undefined') {
            shouldTestLength = false;
        }

        if (shouldTestLength === false || $input.val().length > 2) {
            $input.prop('readonly', true);
            order = 'default';
            pauseSearch = true;
            $('div.home-finder-filters').find('select option:selected').removeProp('selected');
            $('#filter-builders').trigger("chosen:updated");
            $('#filter-bedrooms').trigger("chosen:updated");
            $('#filter-bathrooms').trigger("chosen:updated");
            $('#filter-homeFeatures').multipleSelect('uncheckAll');
            pauseSearch = false;

            performSearch();
        }
    }

    function performSearch(sort, isAllListings) {
        if (pauseSearch === true) {
            return;
        }

        if (typeof sort === 'undefined') {
            sort = 'default';
        }

        var view = $('#map-view-toggle').hasClass('active') ? 'map' : 'grid';

        var filters = {
            prices: getFilterPrice(),
            bedrooms: getFilterBedrooms(),
            bathrooms: getFilterBathrooms(),
            searchAddress: getSearchAddress(),
            includePlans: getShouldIncludePlans(),
            includeHomes: getShouldIncludeHomes(),
            builders: getBuilders(),
            homeFeatures: getFilterHomeFeatures(),
            squareFootage: getFilterSquareFootage(),
            name: $('.home-finder').attr('data-nameType')
        };

        // On Change Filter, reset the pagination.
        router.navigate('real-estate/home-finder/'+$('.home-finder').attr('data-nameType')+'/?' + $.param(filters), {trigger: false});
        page = $('.pagination').attr('data-page',1);

        $('div.results-sort').show();
        showLoadingListingsIndicator();

        $('div.listings-wrapper').fadeTo('slow', 0.3);
        if (isSearchEmpty() === false) {
            $saveSearchSection.show();
        } else {
            $saveSearchSection.hide();
        }

        getListings(filters, sort, isAllListings, view);
    }

    function isSearchEmpty() {
        return (
            getBuilders() === '' &&
            getFilterPrice() === '0-500000' &&
            getFilterBedrooms() === '' &&
            getFilterBathrooms() === '',
            getFilterHomeFeatures() === '' &&
            getFilterSquareFootage() === ''
        );
    }

    function getListings(filters, sort, isAllListings, view) {
        if (typeof sort === 'undefined') {
            sort = 'default';
        }

        if (typeof isAllListings === 'undefined') {
            isAllListings = false;
        }

        var filtersForQuery = {
            prices: filters.prices,
            bedrooms: filters.bedrooms,
            bathrooms: filters.bathrooms,
            searchAddress: filters.searchAddress,
            sort: sort,
            includePlans: filters.includePlans,
            includeHomes: filters.includeHomes,
            builders: filters.builders,
            homeFeatures: filters.homeFeatures,
            squareFootage: filters.squareFootage,
            view: view,
            name: $('.home-finder').attr('data-nameType')
        };

        if (isAllListings === true) {
            $saveSearchSection.hide();
        }

        router.navigate('home-finder/search-listings/?' + $.param(filtersForQuery), {trigger: false});

        page = $('.pagination').data('page');

        $.ajax({
            url: '/api/home-finder/search',
            data: filtersForQuery,
            success: function (data) {
                var html = data.rsp,
                    total = data.total,
                    placesOfInterest = data.placesOfInterest;

                locations = data.locations;

                hideLoadingListingsIndicator();

                $('h2.listings-title').text('Search Listings');

                $('div.results-count').text(pluralize('Result', total, true));

                $('div.home-finder-container').html(html).fadeTo('slow', 1);

                if(view == 'map'){
                    initMap(placesOfInterest);
                }

                $input.prop('readonly', false);
                // auto click the first result
                updateListingDetailAreaToBeFirstResult();
            },
            error: function (data) {
                $input.prop('readonly', false);
                hideLoadingListingsIndicator();
                $('div.listings-wrapper').html('<div class="end-results">End of Results</div>').fadeTo('slow', 1);
                showProperty('_', '_');
            }
        });

    }

    function getBuilders() {
        return $('#filter-builders').find('option:selected').val();
    }

    function getShouldIncludePlans() {
        var selection = $('#filter-listings-type-copy').find('option:selected').val();

        return (selection === 'home-plans' || selection === 'available-homes-and-plans');
    }

    function getShouldIncludeHomes() {
        var selection = $('#filter-listings-type-copy').find('option:selected').val();

        return (selection === 'available-homes' || selection === 'available-homes-and-plans');
    }

    function getSearchAddress() {
        return $('#filter-searchAddress').val();
    }

    function getFilterPrice() {
        return $('#minPriceFilter').val().replace(/\D/g, '') + '-' + $('#maxPriceFilter').val().replace(/\D/g, '');
    }

    function getFilterBedrooms() {
        var bedrooms,
            $select = $('#filter-bedrooms');

        return $select.find('option:selected').val();
    }

    function getFilterBathrooms() {
        var bathrooms,
            $select = $('#filter-bathrooms');

        return $select.find('option:selected').val();
    }

    function getFilterSquareFootage() {
        return $('#filter-sqft').val();
    }

    function getFilterHomeFeatures() {
        return $('#filter-homeFeatures').multipleSelect('getSelects');
    }

    $('div.all-listings-col').on('click', 'div.listings-wrapper div.listing:not(.offering,.floor-plan)', function () {
        var propertyId = $(this).attr('data-property-id'),
            propertyAddress = $(this).attr('data-property-address');

        $(this).parent().find('div.listing.active').removeClass('active');
        $(this).addClass('active');

        router.navigate('home-finder/properties/' + propertyAddress + '/' + propertyId + '/', {trigger: true});
    });

    $('div.all-listings-col').on('click', 'div.listings-wrapper div.floor-plan', function () {
        var link = $(this).attr('data-floor-plan-link'),
            builderSlug = $(this).attr('data-builder-title'),
            floorPlanSlug = $(this).attr('data-floor-plan-title');

        $(this).parent().find('div.listing.active').removeClass('active');
        $(this).addClass('active');

        router.navigate(link, {trigger: false});

        $.get('/api/home-finder/floor-plans/' + builderSlug + '/' + floorPlanSlug, {}, function (data) {
            var propertyHTML = data.rsp;

            $('div.single-listing-col').html(propertyHTML);

            $('div.single-listing-col .listing-images').slick({
                dots: false,
                infinite: false,
                speed: 300,
                slidesToShow: 2,
                centerMode: false,
                arrows: true,
                variableWidth: true,
                respondTo: 'window'
            });
        });
    });

    $('.listing-images').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 2,
        centerMode: false,
        arrows: true,
        variableWidth: true,
        respondTo: 'window'
    });

    var throttleScroll = _.throttle(function () {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 200) {
            var $infiniteCheck = $('div.infinite-check');

            if ($infiniteCheck.length > 0) {
                $infiniteCheck.removeClass('infinite-check');
                var nextUrl = $infiniteCheck.attr('data-infinite-scroll-next-url');

                showLoadingListingsIndicator();

                $.get(nextUrl, {}, function (data) {
                    hideLoadingListingsIndicator();
                    var html = data.rsp;
                    $('div.listings-wrapper').append(html);
                });
            }
        }
    }, 100);
    $('div.all-listings-col').on('scroll', throttleScroll);

    $('.more-button').click(function () {
        $('.more-options').toggle('slow');
        $('.home-finder-filters').toggleClass('more');
    });

    //Mobile Only
    $('.toggle-more').click(function () {
        $('.filter-options').toggle('slow');
    });

    $('div.home-finder-main').on('click', 'div.save.action-link a', function () {
        if (typeof DI === 'undefined' || typeof DI.isLoggedIn === 'undefined' || DI.isLoggedIn !== 'true') {
            $('a.showAccountPage').trigger('click');
            return false;
        }

        var propertyId = $(this).attr('data-property-id'),
            toSaveOrUnSave = 'save',
            $that = $(this);

        if ($(this).parent().hasClass('saved')) {
            toSaveOrUnSave = 'un-save';
        }

        if (toSaveOrUnSave === 'save') {
            $that.closest('div.action-link').find('div.not-saved').hide();
            $that.closest('div.action-link').find('div.saved').show();
        }
        else {
            $that.closest('div.action-link').find('div.not-saved').show();
            $that.closest('div.action-link').find('div.saved').hide();
        }

        saveOrUnSaveProperty(propertyId, toSaveOrUnSave);

        return false;
    });

    function saveOrUnSaveProperty(propertyId, action) {
        $.post('/api/home-finder/properties/' + propertyId + '/' + action);
    }

    function initColorboxElements() {
        $('a.color-box-group').colorbox({
            rel: 'color-box-group',
            maxWidth: '75%'
        });

        $('a.color-box-floor-plan').colorbox();
    }

    initColorboxElements();

    function clearFilters() {
        order = 'default';
        pauseSearch = true;
        $('#filter-searchAddress').val('');
        filterPriceSlider.noUiSlider.set([0, 500000]);
        $("#filter-builders, #filter-bedrooms, #filter-bathrooms, #filter-sqft").find('option').removeProp('selected');
        $("#filter-builders, #filter-bedrooms, #filter-bathrooms, #filter-sqft").trigger('chosen:updated');
        $('#filter-homeFeatures').multipleSelect('uncheckAll');
        pauseSearch = false;
    }

    $('#filter-clearAll').on('click', function () {
        clearFilters();

        // trigger a change in order to pull listings again
        $('#filter-bathrooms').trigger('change');

        return false;
    });

    $(document).on('click', 'a.sortByPriceHighToLow', function () {
        performSearch('price.desc');
        return false;
    });

    $(document).on('click', 'a.sortByPriceLowToHigh', function () {
        performSearch('price.asc');
        return false;
    });

    /** Request Showing **/

    $(document).on('click', '#requestShowing', function () {
        var $modal = $('#modal-request-showing');
        $modal.modal('show');

        $modal.find('button').prop('disabled', false);
        $modal.find('input[name="name"]').val('');
        $modal.find('input[name="email"]').val('');
        $modal.find('textarea[name="message"]').val('');
        $modal.find('span.success-message').hide();
        $modal.find('span.error-message').hide();
        $modal.find('input[name="createAccount"]').prop('checked', false);
        $modal.find('form').show();

        return false;
    });

    $(document).on('click', '#modal-request-showing button', function () {
        var propertyId = $(this).parent().find('input[name="propertyId"]').val(),
            builderTitle = $(this).parent().find('input[name="builderTitle"]').val(),
            floorPlanTitle = $(this).parent().find('input[name="floorPlanTitle"]').val(),
            name = $(this).parent().find('input[name="name"]').val(),
            email = $(this).parent().find('input[name="email"]').val(),
            link = $(this).parent().find('input[name="link"]').val(),
            message = $(this).parent().find('textarea[name="message"]').val(),
            shouldCreateAccount = $(this).parent().find('input[name="createAccount"]').is(':checked'),
            $successMessage = $(this).parent().parent().find('span.success-message'),
            $errorMessage = $(this).parent().find('span.error-message'),
            $button = $(this);

        $button.prop('disabled', true);
        $errorMessage.hide();
        $successMessage.hide();

        $button.text('Sending...');

        $.ajax({
            type: "POST",
            url: '/api/home-finder/request-showing',
            data: {
                propertyId: propertyId,
                builderTitle: builderTitle,
                floorPlanTitle: floorPlanTitle,
                name: name,
                email: email,
                message: message,
                shouldCreateAccount: shouldCreateAccount
            },
            error: function (data) {
                $button.prop('disabled', false);

                if (typeof data.responseJSON !== 'undefined') {
                    switch (data.responseJSON.status) {
                        case (404) :
                            $button.parent().find('input[name="email"]').addClass('error');
                            $errorMessage.text(data.responseJSON.rsp);
                            $errorMessage.show();
                            break;
                        case (500) :
                        default:
                            $errorMessage.text('Failed to save message. Please try again. If it still fails, go to the contact page and let us know. Thanks!');
                            $errorMessage.show();
                    }

                    $button.text('Send');
                    $button.prop('disabled', false);
                }
            },
            success: function (data) {
                $button.prop('disabled', false);
                $button.parent().hide();
                $successMessage.show();
                $button.text('Send');

                // reload page if they wanted to create an account
                if (shouldCreateAccount === true) {
                    window.location.href = link;
                }
            }
        });

        return false;
    });


    /* Account */
    $(document).on('click', '#accountSaveSearch', function () {
        if (typeof DI === 'undefined' || typeof DI.isLoggedIn === 'undefined' || DI.isLoggedIn !== 'true') {
            $('a.showAccountPage').trigger('click');
            return false;
        }

        var filters = {
                //propertyTypes: getFilterPropertyTypes(),
                //neighborhoods: getFilterNeighborhoods(),
                prices: getFilterPrice(),
                bedrooms: getFilterBedrooms(),
                bathrooms: getFilterBathrooms(),
                //shouldSearchMLS: getShouldSearchMLS(),
                //lastUpdate: getLastUpdate(),
                squareFootage: getFilterSquareFootage(),
                homeFeatures: getFilterHomeFeatures(),
                //views: getFilterViews(),
                includePlans: getShouldIncludePlans(),
                includeHomes: getShouldIncludeHomes(),
                builders: getBuilders()
            },
            $that = $(this);

        $.post('/api/home-finder/save-search', filters, function (rsp) {
            if (typeof rsp.savedSearchCount !== "undefined") {
                var savedSearchesCount = rsp.savedSearchCount;

                $that.parent().find('a.showAccountPage').text('(' + savedSearchesCount + ')');
                $that.parent().find('a.showAccountPage').show();

                $('a.savedSearchesCount').text('Saved Searches ' + savedSearchesCount);
            }
        });

        return false;
    });

    //Scroll Single Listing to Top
    $(document).on('click', 'div.listing', function () {
        $('.single-listing-col').animate({scrollTop: "0px"});
    });

    /*** Match Height ***/
    //-Grid View
    // $('.row-grid-view').each(function(i, elem) {
    //     $(elem)
    //         .find('.grid-results-box')   // Only children of this row
    //         .matchHeight({byRow: false}); // Row detection gets confused so disable it
    // });

    //-Map View
    $('.col-map-listings').each(function(i, elem) {
        $(elem)
            .find('.map-results-box')   // Only children of this row
            .matchHeight({byRow: false}); // Row detection gets confused so disable it
    });


    //Mobile Only
    $(document).on('click', 'div.listing', function () {
        $('.single-listing-col').addClass('move-up');
        $('.home-finder-filters').addClass('hide-filters');
    });

    $(document).on('click', 'div.back-to-listings', function () {
        $('.single-listing-col').removeClass('move-up');
        $('.home-finder-filters').removeClass('hide-filters');
    });

    $('.all-listings-col').scroll(function () {
        var scroll = $('.all-listings-col').scrollTop();

        if (scroll >= 250) {
            $('.home-finder-filters').addClass('hide-filters');
        } else {
            $('.home-finder-filters').removeClass('hide-filters');
        }
    });

    //Share Widget
    $(document).on('click', '.open-share-widget', function () {
        $('.share-widget').toggleClass('open');
    });

    $(document).on('click', '.share-icon', function () {
        $('.share-widget').removeClass('open');
    });

    function saveShareMetric(network, property_id) {
        $.post('/api/home-finder/properties/' + property_id + '/share', {network: network}, function () {
        });
    }

    $(document).on('click', 'div.share-icon.fb-icon', function () {
        var property_id = $(this).parent().parent().attr('data-property-id');
        saveShareMetric('facebook', property_id);
    });

    $(document).on('click', 'div.share-icon.twitter-icon', function () {
        var property_id = $(this).parent().parent().attr('data-property-id');
        saveShareMetric('twitter', property_id);
    });

    $(document).on('click', 'div.share-icon.email-icon', function () {
        var property_id = $(this).parent().parent().attr('data-property-id');
        saveShareMetric('email', property_id);
    });

    // Harvest Chosen Select Boxes
    $("#filter-builders, #filter-bedrooms, #filter-listings-type, #filter-listings-type-copy").chosen({
        disable_search: 'true'
    });

    $("#filter-sq-ft, #filter-bathrooms").chosen({
        disable_search: 'true',
        width: '165px'
    });

    function simpleClearForMultipleSelect(view) {
        //$('#filter-searchAddress').val('');
        order = 'default';
        performSearch();
    }

    $('#filter-homeFeatures').multipleSelect({
        placeholder: "Home Features",
        onClick: simpleClearForMultipleSelect,
        onCheckAll: simpleClearForMultipleSelect,
        onUncheckAll: simpleClearForMultipleSelect
    });

    // price slider

    $('#showPriceFilter').on('click', function (e) {
    $('#priceFilterSection').show();

    e.stopPropagation();
  });

  $('#priceFilterSection').on('clickoutside', function () {
    $(this).hide();
  });

  var filterPriceSlider = document.getElementById('filter-price');

  noUiSlider.create(filterPriceSlider, {
    start: [0, 500000],
    connect: true,
    step: 50000,
    range: {
      'min': 0,
      'max': 500000
    },
    format: wNumb({
      decimals: 0,
      thousand: ',',
      prefix: '$'
    })
  });

  filterPriceSlider.noUiSlider.on('set', function () {
    $('#filter-searchAddress').val('');
    order = 'default';
    performSearch();
  });

  filterPriceSlider.noUiSlider.on('update', function (values, handle) {
    var prices = [$('#minPriceFilter'), $('#maxPriceFilter')];

    prices[handle].val(values[handle]);
  });

  $('#minPriceFilter').on('change', function (event) {
    filterPriceSlider.noUiSlider.set([$(this).val(), $('#maxPriceFilter').val()]);
  });

  $('#maxPriceFilter').on('change', function (event) {
    filterPriceSlider.noUiSlider.set([$('#minPriceFilter').val(), $(this).val()]);
  });

  // make the filter listings in the sidebar and top filters copy each other
  $('#filter-listings-type').on('change', function() {
      $('#filter-listings-type-copy').find('option[value="' + $(this).find('option:selected').val() + '"]').prop('selected', true);
      $('#filter-listings-type-copy').trigger("chosen:updated");
  });
  $('#filter-listings-type-copy').on('change', function() {
      $('#filter-listings-type').find('option[value="' + $(this).find('option:selected').val() + '"]').prop('selected', true);
      $('#filter-listings-type').trigger("chosen:updated");
      var selection = $(this).find('option:selected').val();

        if (selection === 'home-plans') {
            $('div.view-on-map').css('visibility', 'hidden');
        }
        else {
            $('div.view-on-map').css('visibility', 'visible');
        }

        $('#filter-searchAddress').val('');
        performSearch();
  });

    //Fullscreen Map
    $(document).on('click', '#fullscreen-map', function () {
        $('.col-map').toggleClass('open');
    });

     //Map Legend
    $(document).on('click', '#legend-title', function () {
        $('#legend-title').toggleClass('close');
        $('#legend-items').toggleClass('close');
    });

});