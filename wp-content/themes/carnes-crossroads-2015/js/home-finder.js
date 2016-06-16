jQuery(function ($) {

    var $saveSearchSection = $('#saveSearchSection'),
        order = 'default';

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
            'home-finder/saved-listings/': 'showSavedListings'
        },

        showSavedListings: function () {
            showSavedListings();
        },

        showProperty: function (address, id) {
            showProperty(address, id);
        }

    });

    var router = new Router();

    if (!Backbone.history.started) {
        Backbone.history.start({
            pushState: "pushState" in window.history,
            silent: true
        });
        Backbone.history.started = true;
    }

    function showLoadingListingsIndicator() {
        $('div.listings-wrapper').append('<div class="loading"></div>');
    }

    function hideLoadingListingsIndicator() {
        $('div.listings-wrapper div.loading').remove();
    }

    $('#filter-bedrooms,#filter-bathrooms,#filter-builders').on('change', function () {
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

        var filters = {
            prices: getFilterPrice(),
            bedrooms: getFilterBedrooms(),
            bathrooms: getFilterBathrooms(),
            searchAddress: getSearchAddress(),
            includePlans: getShouldIncludePlans(),
            includeHomes: getShouldIncludeHomes(),
            builders: getBuilders(),
            homeFeatures: getFilterHomeFeatures()
        };

        $('div.results-sort').show();
        showLoadingListingsIndicator();

        $('div.listings-wrapper').fadeTo('slow', 0.3);
        if (isSearchEmpty() === false) {
            $saveSearchSection.show();
        } else {
            $saveSearchSection.hide();
        }

        getListings(filters, sort, isAllListings);
    }

    function isSearchEmpty() {
        return (
            getBuilders() === '' &&
            getFilterPrice() === '0-500000' &&
            getFilterBedrooms() === '' &&
            getFilterBathrooms() === '',
            getFilterHomeFeatures() === ''
        );
    }

    function getListings(filters, sort, isAllListings) {
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
            homeFeatures: filters.homeFeatures
        };

        if (isAllListings === true) {
            $saveSearchSection.hide();
        }

        router.navigate('home-finder/search-listings/?' + $.param(filtersForQuery), {trigger: false});

        $.ajax({
            url: '/api/home-finder/search',
            data: filtersForQuery,
            success: function (data) {
                var html = data.rsp,
                    total = data.total;

                locations = [];

                hideLoadingListingsIndicator();

                $('h2.listings-title').text('Search Listings');

                $('div.results-count').text(pluralize('Result', total, true));

                $('div.listings-wrapper').html(html).fadeTo('slow', 1);

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
        var selection = $('#filter-listings-type').find('option:selected').val();

        return (selection === 'home-plans' || selection === 'available-homes-and-plans');
    }

    function getShouldIncludeHomes() {
        var selection = $('#filter-listings-type').find('option:selected').val();

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
        $("#filter-builders, #filter-bedrooms, #filter-bathrooms").find('option').removeProp('selected');
        $("#filter-builders, #filter-bedrooms, #filter-bathrooms").trigger('chosen:updated');
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
                //squareFootage: getFilterSquareFootage(),
                // homeFeatures: getFilterHomeFeatures(),
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
    $("#filter-builders, #filter-bedrooms, #filter-bathrooms, #filter-listings-type").chosen({
        disable_search: 'true'
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

    $('#showPriceFilter').on('click', function () {
        $('#priceFilterSection').toggle();
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

    $('#minPriceFilter,#maxPriceFilter').on('change', function (event) {
        filterPriceSlider.noUiSlider.set($(this).val());
    });

});