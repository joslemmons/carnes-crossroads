var router;

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
    var map;
    L.mapbox.accessToken = 'pk.eyJ1IjoiZGlkZXZjbyIsImEiOiJjaXM3cWY3NDEwNDc0Mnpwa2w5YnllMXZkIn0.4pWeAL6-vhtobhpFd2HDuA';

    if( $('#single-map').length ) {
        var map = L.mapbox.map('single-map', 'mapbox.streets', { scrollWheelZoom : false });
        map.setZoom(17);

        var southWest = L.latLng(32.83064187300698, -79.93316068560326);
        var northEast = L.latLng(32.89325262945007, -79.88402077618287);
        var bounds = L.latLngBounds(southWest, northEast);
        map.fitBounds(bounds);

        var hometype = $('#single-map').attr('data-property-type');

        var stamenLayer = L.tileLayer(DI.templateUri + '/img/imap/imap-cx-update/{z}/{x}/{y}.png',{}).addTo(map);

        var latitude = parseFloat($('#single-map').attr('data-latitude'));
        var longitude = parseFloat($('#single-map').attr('data-longitude'));

        if(latitude && longitude) {
            map.setView([latitude, longitude], 15);
            L.marker([latitude, longitude], {
                icon: L.mapbox.marker.icon({
                    'marker-color': '#f86767'
                })
            }).addTo(map);
        }
    }
    
    
    //duplicated for mobile map
    if( $('#single-map-2').length ) {
        var map = L.mapbox.map('single-map', 'mapbox.streets', { scrollWheelZoom : false });
        map.setZoom(17);

        var southWest = L.latLng(32.83064187300698, -79.93316068560326);
        var northEast = L.latLng(32.89325262945007, -79.88402077618287);
        var bounds = L.latLngBounds(southWest, northEast);
        map.fitBounds(bounds);

        var hometype = $('#single-map').attr('data-property-type');

        var stamenLayer = L.tileLayer(DI.templateUri + '/img/imap/imap-cx-update/{z}/{x}/{y}.png',{}).addTo(map);

        var latitude = parseFloat($('#single-map').attr('data-latitude'));
        var longitude = parseFloat($('#single-map').attr('data-longitude'));

        if(latitude && longitude) {
            map.setView([latitude, longitude], 15);
            L.marker([latitude, longitude], {
                icon: L.mapbox.marker.icon({
                    'marker-color': '#f86767'
                })
            }).addTo(map);
        }
    }

    var $saveSearchSection = $('#saveSearchSection'),
        order = 'default';

    function slugifyListingType() {
        return $('h2.listings-title')
            .text()
            .toLowerCase()
            .replace(/ /g,'-')
            .replace(/[^\w-]+/g,'');
    }

 
    function showMarkerPopup() {
        console.log('hovered over the thing!');

        map.featureLayer.eachLayer(function(marker) {
            console.log(marker);
            marker.openPopup();
        });
    }

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

    $(document).on('click', 'div.agent-form button', function () {
        var propertyId = $(this).parent().find('input[name="propertyId"]').val(),
            name = $(this).parent().find('input[name="name"]').val(),
            email = $(this).parent().find('input[name="email"]').val(),
            link = $(this).parent().find('input[name="link"]').val(),
            message = $(this).parent().find('textarea[name="message"]').val(),
            shouldCreateAccount = $(this).parent().find('input[name="createAccount"]').is(':checked'),
            agentId = $(this).parent().find('input[name="agentId"]').val(),
            $successMessage = $(this).parent().find('span.success-message-right'),
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
                name: name,
                email: email,
                message: message,
                shouldCreateAccount: shouldCreateAccount,
                agentId: agentId
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
                propertyTypes: getFilterPropertyTypes(),
                neighborhoods: getFilterNeighborhoods(),
                prices: getFilterPrice(),
                bedrooms: getFilterBedrooms(),
                bathrooms: getFilterBathrooms(),
                shouldSearchMLS: getShouldSearchMLS(),
                lastUpdate: getLastUpdate(),
                squareFootage: getFilterSquareFootage(),
                homeFeatures: getFilterHomeFeatures(),
                views: getFilterViews()
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


    //Share Widget

    $('.open-share-widget').click( function(event){
        event.stopPropagation();
        $('.share-widget').addClass('open');
    });

    $(document).click( function(){
        $('.share-widget').removeClass('open');
    });

    $('div.property-view-content').on('click', 'div.save.action-link a', function () {
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


});