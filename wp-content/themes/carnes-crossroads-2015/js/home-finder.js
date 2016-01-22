jQuery(function ($) {

    var $saveSearchSection = $('#saveSearchSection');

    var Router = Backbone.Router.extend({
        routes: {
            'home-finder/properties/:address/:id/': 'showProperty',
            //'home-finder/'
            'home-finder/featured-listings/': 'showFeaturedListings',
            'home-finder/new-offerings/': 'showNewOfferings',
            'home-finder/recently-listed/': 'showRecentlyListed',
            'home-finder/saved-listings/': 'showSavedListings'
        },

        showSavedListings: function () {
            $('div.listings-wrapper').fadeTo('slow', 0.3);
            $saveSearchSection.hide();
            showLoadingListingsIndicator();
            $.get('/api/home-finder/saved-listings/page/1', {}, function (data) {
                var html = data.rsp,
                    total = data.total;

                hideLoadingListingsIndicator();

                $('h2.listings-title').text('Saved Listings');

                $('div.results-count').text(pluralize('Result', total, true));

                $('div.listings-wrapper').html(html).fadeTo('slow', 1);

                $('div.home-finder-filters').find('select option:selected').removeProp('selected');
                $('#filter-searchAddress').val('');
            });
        },

        showRecentlyListed: function () {
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

                $('div.home-finder-filters').find('select option:selected').removeProp('selected');
                $('#filter-searchAddress').val('');
            });

        },

        showNewOfferings: function () {
            $('div.listings-wrapper').fadeTo('slow', 0.3);
            $saveSearchSection.hide();
            showLoadingListingsIndicator();
            $.get('/api/home-finder/new-offerings/page/1', {}, function (data) {
                var html = data.rsp,
                    total = data.total;

                hideLoadingListingsIndicator();

                $('h2.listings-title').text('New Offerings');

                $('div.results-count').text(pluralize('Result', total, true));

                $('div.listings-wrapper').html(html).fadeTo('slow', 1);

                $('div.home-finder-filters').find('select option:selected').removeProp('selected');
                $('#filter-searchAddress').val('');
            });
        },

        showFeaturedListings: function () {
            $('div.listings-wrapper').fadeTo('slow', 0.3);
            $saveSearchSection.hide();
            showLoadingListingsIndicator();
            $.get('/api/home-finder/featured-properties/page/1', {}, function (data) {
                var html = data.rsp,
                    total = data.total;

                hideLoadingListingsIndicator();

                $('h2.listings-title').text('Featured Listings');

                $('div.results-count').text(pluralize('Result', total, true));

                $('div.listings-wrapper').html(html).fadeTo('slow', 1);

                $('div.home-finder-filters').find('select option:selected').removeProp('selected');
                $('#filter-searchAddress').val('');
            });
        },

        showProperty: function (address, id) {
            $('div.single-listing-col').fadeTo('slow', 0.3);
            $.get('/api/home-finder/properties/' + id, {}, function (data) {
                var propertyHTML = data.rsp;

                $('div.single-listing-col').html(propertyHTML).fadeTo('slow', 1);

                //$('div.single-listing-col').scrollTop = 0;

                $('div.single-listing-col .listing-images').slick({
                    dots: false,
                    infinite: true,
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
                                infinite: true,
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

    $('div.home-finder-filters').find('select').on('change', function() {
        $('#filter-searchAddress').val('');
        performSearch();
    });
    $('#filter-mlsListing').on('click', function () {
        $('#filter-searchAddress').val('');
        performSearch();
    });
    $('#filter-includePlans').on('click', function () {
        $('#filter-searchAddress').val('');
        performSearch();
    });

    var throttleSearch = _.throttle(function () {
        if ($(this).val().length > 2) {
            // clear out filters
            $('div.home-finder-filters').find('select option:selected').removeProp('selected');

            performSearch();
        }
    }, 2000);

    $('#filter-searchAddress').on('keyup', throttleSearch);

    function performSearch() {
        var filters = {
            propertyTypes: getFilterPropertyTypes(),
            neighborhoods: getFilterNeighborhoods(),
            prices: getFilterPrice(),
            bedrooms: getFilterBedrooms(),
            bathrooms: getFilterBathrooms(),
            shouldSearchMLS: getShouldSearchMLS(),
            searchAddress: getSearchAddress(),
            includePlans: getShouldIncludePlans(),
            builders: getBuilders()
        };

        showLoadingListingsIndicator();

        $('div.listings-wrapper').fadeTo('slow', 0.3);
        if (isSearchEmpty() === false) {
            $saveSearchSection.show();
        } else {
            $saveSearchSection.hide();
        }

        getListings(filters);
    }

    function isSearchEmpty() {
        return (
            getFilterPropertyTypes() === '' &&
            getFilterNeighborhoods() === '' &&
            getFilterPrice() === '' &&
            getFilterBedrooms() === '' &&
            getFilterBathrooms() === ''
        );
    }

    function getListings(filters) {
        $.get(
            '/api/home-finder/search',
            {
                propertyTypes: filters.propertyTypes,
                neighborhoods: filters.neighborhoods,
                prices: filters.prices,
                bedrooms: filters.bedrooms,
                bathrooms: filters.bathrooms,
                searchMLS: filters.shouldSearchMLS,
                searchAddress: filters.searchAddress,
                includePlans: filters.includePlans,
                builders: filters.builders
            },
            function (data) {
                var html = data.rsp,
                    total = data.total;

                locations = [];

                hideLoadingListingsIndicator();

                $('h2.listings-title').text('Search Listings');

                $('div.results-count').text(pluralize('Result', total, true));

                $('div.listings-wrapper').html(html).fadeTo('slow', 1);

                $('div.listings-type').find('select').find('option').first().prop('selected', 'selected');
            });
    }

    function getBuilders() {
        return $('#filter-builders').find('option:selected').val();
    }

    function getShouldIncludePlans()
    {
        return $('#filter-includePlans').is(':checked');
    }

    function getSearchAddress() {
        return $('#filter-searchAddress').val();
    }

    function getShouldSearchMLS() {
        return true;
    }

    function getFilterPropertyTypes() {
        var propertyTypes,
            $select = $('#filter-propertyType');

        return $select.find('option:selected').val();
    }

    function getFilterNeighborhoods() {
        var neighborhoods,
            $select = $('#filter-neighborhood');

        return $select.find('option:selected').val();
    }

    function getFilterPrice() {
        var prices,
            $select = $('#filter-price');

        return $select.find('option:selected').val();
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
                infinite: true,
                speed: 300,
                slidesToShow: 2,
                centerMode: false,
                arrows: true,
                variableWidth: true,
                respondTo: 'window'
            });
        });
    });

    $('div.all-listings-col').on('click', 'div.listings-wrapper div.offering', function () {
        var link = $(this).attr('data-offering-link'),
            id = $(this).attr('data-offering-id');

        $(this).parent().find('div.listing.active').removeClass('active');
        $(this).addClass('active');

        router.navigate(link, {trigger: false});

        $.get('/api/home-finder/new-offerings/' + id, {}, function (data) {
            var propertyHTML = data.rsp;

            $('div.single-listing-col').html(propertyHTML);

            $('div.single-listing-col .listing-images').slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 2,
                centerMode: false,
                arrows: true,
                variableWidth: true,
                respondTo: 'window'
            });
        });
    });

    $('div.listings-type select').on('change', function () {
        var choice = $(this).find('option:selected').val();

        switch (choice) {
            case 'new-offerings':
                router.navigate('home-finder/new-offerings/', {trigger: true});
                break;
            case 'recently-listed':
                router.navigate('home-finder/recently-listed/', {trigger: true});
                break;
            case 'saved-listings':
                router.navigate('home-finder/saved-listings/', {trigger: true});
                break;
            case 'featured-listings':
            default:
                router.navigate('home-finder/featured-listings/', {trigger: true});
        }
    });

    $('.listing-images').slick({
        dots: false,
        infinite: true,
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
    });

    //Mobile Only
    $('.toggle-more').click(function () {
        $('.filter-options').toggle('slow');
    });

    $('div.home-finder-main').on('click', 'div.save.action-link a', function () {
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

    $(document).on('click', 'a.filterByPropertyType', function () {
        var propertyType = $(this).attr('data-property-type');

        $('div.home-finder-filters').find('select option:selected').removeProp('selected');
        $('#filter-propertyType').find('option[value="' + propertyType.toLowerCase() + '"]').prop('selected', 'selected');
        $('#filter-propertyType').trigger('change');

        return false;
    });

    $(document).on('click', 'a.filterByNeighborhood', function () {
        var neighborhood = $(this).attr('data-neighborhood');

        $('div.home-finder-filters').find('select option:selected').removeProp('selected');
        $('#filter-neighborhood').find('option:contains(' + neighborhood + ')').prop('selected', 'selected');
        $('#filter-neighborhood').trigger('change');

        return false;
    });

    $('#filter-clearAll').on('click', function () {
        $('div.home-finder-filters').find('select option:selected').removeProp('selected');
        $('#filter-searchAddress').val('');
        $('#filter-includePlans').prop('checked', false);
        // trigger a change in order to pull listings again
        $('#filter-builders').trigger('change');

        return false;
    });

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
                        $errorMessage.text('Failed to send request. Please try again. If it still fails, go to the contact page and let us know. Thanks!');
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

    /**
     *
     * MAP STUFF
     *
     **/

    $('#modal-view-on-map').on('hidden.bs.modal', function (e) {
        $('#imap').empty();
    });

    $(document).on('click', '#viewListingsOnMap', function () {
        var $modal = $('#modal-view-on-map');
        $modal.modal('show');

        initImap(locations);

        return false;
    });

    $(document).on('click', '#viewOnMap', function () {
        var $modal = $('#modal-view-on-map'),
            latitude = $(this).attr('data-latitude'),
            longitude = $(this).attr('data-longitude'),
            address = $(this).attr('data-address'),
            link = $(this).attr('data-link'),
            propertyType = $(this).attr('data-property-type'),
            toolTipHTML = $(this).attr('data-tool-tip');

        if (propertyType === 'Homesite') {
            propertyType = 0;
        }

        $modal.modal('show');
        initImap([
            [
                address,
                latitude,
                longitude,
                '4',
                link,
                toolTipHTML,
                propertyType
            ]
        ]);
    });

    $(document).on('click', 'a.showProperty', function () {
        var propertyId = $(this).attr('data-property-id'),
            propertyAddress = $(this).attr('data-property-address');

        $('div.listing.active').removeClass('active');
        $('div.listing[data-property-id="' + propertyId + '"]').addClass('active');

        router.navigate('home-finder/properties/' + propertyAddress + '/' + propertyId + '/', {trigger: true});

        $('#modal-view-on-map').modal('hide');

        return false;
    });

    // misc vars
    var mapMinZoom = 17;
    var mapMaxZoom = 19;

//set custom tiles (maptype)
    var diOptions = {
        getTileUrl: function (coord, zoom) {
            var ymax = 1 << zoom;
            var y = ymax - coord.y - 1;
            url = DI.templateUri + "/img/imap/tiles/" + zoom + "/" + coord.x + "/" + y + ".png";
            return url;
        },
        tileSize: new google.maps.Size(256, 256),
        isPng: true
    };

    var diMapType = new google.maps.ImageMapType(diOptions);
    var bounds = new google.maps.LatLngBounds();
    var geocoder = new google.maps.Geocoder();

//<?php if ( $z_query->query->is_archive ) { ?>
    // remove below to zoom into properties
    //bounds.extend(new google.maps.LatLng(32.83064187300698, -79.93316068560326));	//set min x/y
    //bounds.extend(new google.maps.LatLng(32.89325262945007, -79.88402077618287));	//set max x/y
//<?php } ?>

    /**
     * Data for the markers consisting of a name, a LatLng and a zIndex for
     * the order in which these markers should display on top of each
     * other.
     */


// googles info pane
    var infowindow = new google.maps.InfoWindow({
        size: new google.maps.Size(150, 50)
    });


    function setMarkers(map, locations) {
        // Add markers to the map
        var house_image = new google.maps.MarkerImage(DI.templateUri + '/img/imap/icons/house-icon.png',
            // This marker is 20 pixels wide by 32 pixels tall.
            new google.maps.Size(20, 32),
            // The origin for this image is 0,0.
            new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at 0,32.
            new google.maps.Point(5, 20));

        var lot_image = new google.maps.MarkerImage(DI.templateUri + '/img/imap/icons/lot-icon.png',
            // This marker is 20 pixels wide by 32 pixels tall.
            new google.maps.Size(20, 32),
            // The origin for this image is 0,0.
            new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at 0,32.
            new google.maps.Point(5, 20));

        var shadow;	//not used
        /*
         var shadow = new google.maps.MarkerImage('<?php //echo get_stylesheet_directory_uri();?>/images/house-icon-shadow.png', //should be set to a shadow image
         // The shadow image is larger in the horizontal dimension
         // while the position and offset are the same as for the main image.
         new google.maps.Size(45, 40),
         new google.maps.Point(0,0),
         new google.maps.Point(0, 40));*/

        // Shapes define the clickable region of the icon. The type defines an HTML &lt;area&gt; element 'poly' whichtraces out a polygon as a series of X,Y points. The final coordinate closes the poly by connecting to the firstcoordinate. NOT USED
        var shape = {
            coord: [1, 1, 1, 32, 20, 32, 20, 1],
            type: 'poly'
        };

        for (var i = 0; i < locations.length; i++) {
            var location = locations[i];
            var title = location[0];
            var zIndex = location[3];
            var theLink = location[4];
            var html = location[5];
            var prop_type = location[6];

            if (location[1] && location[2]) {
                var myLatLng = new google.maps.LatLng(location[1], location[2]);

                if (prop_type != 0)
                    var icon_image = house_image;
                else
                    var icon_image = lot_image;

                var marker = createMarker(map, myLatLng, icon_image, shadow, title, zIndex, theLink, html);

                //<?php if ( $z_query->query->is_archive ) { ?>
                //	//use this if you want to only show all the points in the map bounds
                //	bounds.extend(myLatLng);
                //	map.panTo(myLatLng);
                //<?php } ?>
            } else {
                geocoder.geocode({'address': location[0]}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        // var myLatLng = results[0].geometry.location;

                        if (prop_type)
                            var icon_image = house_image;
                        else
                            var icon_image = lot_image;

                        var marker = createMarker(map, results[0].geometry.location, icon_image, shadow, title, zIndex, theLink, html);

                        //<?php if ( $z_query->query->is_archive ) { ?>
                        //	//use this if you want to only show all the points in the map bounds
                        //	bounds.extend(myLatLng);
                        //	map.panTo(myLatLng);
                        //<?php } ?>
                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            }

            //if (i === locations.length - 1) {
            //    map.panTo(new google.maps.LatLng(location[1], location[2]));
            //}

        } // end for
    } // end function setMarkers

    function createMarker(map, myLatLng, image, shadow, title, zIndex, theLink, html) {

        var contentString = html;

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            //shadow: shadow,
            icon: image,
            //shape: shape,
            //title: title,
            zIndex: 99999,
            clickable: true
        });

        //<?php if ( $z_query->query->is_single ) { ?>
        //	bounds.extend(myLatLng); // extend bounds of map to show all results
        //	map.panTo(myLatLng); // centers map to last property
        //<?php } ?>

        //mouseovers
        google.maps.event.addListener(marker, "click", function () {
            infowindow.setContent(contentString);
            infowindow.open(map, marker);

        });

        return marker;
    } // end function createMarker


    function initImap(properties) {
        var myOptions = {
            zoom: mapMinZoom,
            center: new google.maps.LatLng(33.055457, -80.103917),
            navigationControl: true,
            mapTypeControl: false,
            scaleControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("imap"), myOptions);
        map.overlayMapTypes.insertAt(0, diMapType);

        //needed for gettin pixes from latlng for mouseover div position
        overlay = new google.maps.OverlayView();
        overlay.draw = function () {
        };
        overlay.setMap(map);

        // don't allow panning pass bounds. Listen for the dragend event
        //google.maps.event.addListener(map, 'dragend', function () {
        //
        //     if ( bounds.contains( map.getCenter() ) ) return;
        //
        //    // Out of bounds - Move the map back within the bounds
        //    var c = map.getCenter(),
        //        x = c.lng(),
        //        y = c.lat(),
        //        maxX = bounds.getNorthEast().lng(),
        //        maxY = bounds.getNorthEast().lat(),
        //        minX = bounds.getSouthWest().lng(),
        //        minY = bounds.getSouthWest().lat();
        //
        //    if (x < minX) x = minX;
        //    if (x > maxX) x = maxX;
        //    if (y < minY) y = minY;
        //    if (y > maxY) y = maxY;
        //
        //    map.setCenter(new google.maps.LatLng(y, x));
        //
        //});

        // restrict max and min zoom in v3 api
        google.maps.event.addListener(map, "zoom_changed", function () {
            if (map.getZoom() < mapMinZoom) map.setZoom(mapMinZoom);
            if (map.getZoom() > mapMaxZoom) map.setZoom(mapMaxZoom);
        });

        setMarkers(map, properties);

        $("#modal-view-on-map").on("shown.bs.modal", function () {
            google.maps.event.trigger(map, "resize");

            if (properties.length === 1) {
                map.setCenter(new google.maps.LatLng(locations[0][1], locations[0][2]));
            }
            else {
                map.setCenter(new google.maps.LatLng(33.055457, -80.103917));
            }
        });

        //<?php if ( $z_query->query->is_archive ) { ?>
        //map.fitBounds(bounds);
        //<?php } ?>

    } // end function initImap

    /* Account */
    $(document).on('click', '#accountSaveSearch', function () {
        var filters = {
            propertyTypes: getFilterPropertyTypes(),
            neighborhoods: getFilterNeighborhoods(),
            prices: getFilterPrice(),
            bedrooms: getFilterBedrooms(),
            bathrooms: getFilterBathrooms(),
            shouldSearchMLS: getShouldSearchMLS(),
            includePlans: getShouldIncludePlans(),
            builders: getBuilders()
        };

        var savedSearchesCount = parseInt($(this).parent().find('a.showAccountPage').text().replace('(', '').replace(')', ''));
        savedSearchesCount++;

        $(this).parent().find('a.showAccountPage').text('(' + savedSearchesCount + ')');
        $(this).parent().find('a.showAccountPage').show();

        $.post('/api/home-finder/save-search', filters, function (rsp) {
        });

        return false;
    });

    //Mobile Only
    $(document).on('click','div.listing',function () {
        $('.single-listing-col').addClass('move-up');
        $('.home-finder-filters').addClass('hide-filters');
    });

    $(document).on('click','div.back-to-listings',function () {
        $('.single-listing-col').removeClass('move-up');
        $('.home-finder-filters').removeClass('hide-filters');
    });

    //Share Widget
    $(document).on('click','.open-share-widget',function () {
        $('.share-widget').toggleClass('open');
    });

    $(document).on('click','.share-icon',function () {
        $('.share-widget').removeClass('open');
    });

});