jQuery(function ($) {

    var $saveSearchSection = $('#saveSearchSection'),
        order = 'default';

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
            $('div.listings-wrapper').find('div.listing').first().trigger('click');
        });
    }

    function showNewOfferings() {
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

            clearFilters();
            $('#filter-searchAddress').val('');

            // auto click the first result
            $('div.listings-wrapper').find('div.listing').first().trigger('click');
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
            $('div.listings-wrapper').find('div.listing').first().trigger('click');
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
            'home-finder/new-offerings/': 'showNewOfferings',
            'home-finder/recently-listed/': 'showRecentlyListed',
            'home-finder/saved-listings/': 'showSavedListings',
            'home-finder/all-listings/': 'showAllListings'
        },

        showSavedListings: function () {
            showSavedListings();
        },

        showRecentlyListed: function () {
            showRecentlyListed();

        },

        showNewOfferings: function () {
            showNewOfferings();
        },

        showFeaturedListings: function () {
            showFeaturedListings();
        },

        showProperty: function (address, id) {
            showProperty(address, id);
        },

        showAllListings: function () {
            showAllListings();
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

    $('#filter-price,#filter-bedrooms,#filter-bathrooms,#filter-lastUpdate,#filter-sqft,#filter-builders').on('change', function () {
        $('#filter-searchAddress').val('');
        order = 'default';
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
            clearFilters();

            performSearch();
        }
    }, 2000);

    $('#filter-searchAddress').on('keyup', throttleSearch);

    var pauseSearch = false;

    function performSearch(sort, isAllListings) {
        if (pauseSearch === true) {
            return;
        }

        if (typeof sort === 'undefined') {
            sort = 'default';
        }

        var filters = {
            //propertyTypes: getFilterPropertyTypes(),
            //neighborhoods: getFilterNeighborhoods(),
            prices: getFilterPrice(),
            bedrooms: getFilterBedrooms(),
            bathrooms: getFilterBathrooms(),
            //shouldSearchMLS: getShouldSearchMLS(),
            searchAddress: getSearchAddress(),
            //lastUpdate: getLastUpdate(),
            //squareFootage: getFilterSquareFootage(),
            //homeFeatures: getFilterHomeFeatures(),
            //views: getFilterViews(),
            includePlans: getShouldIncludePlans(),
            builders: getBuilders()
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
            getFilterPropertyTypes() === '' &&
            getFilterNeighborhoods() === '' &&
            getFilterPrice() === '' &&
            getFilterBedrooms() === '' &&
            getFilterBathrooms() === '' &&
            getLastUpdate() === '' &&
            getFilterSquareFootage() === '' &&
            getFilterHomeFeatures() === '' &&
            getFilterViews() === ''
        );
    }

    function getListings(filters, sort, isAllListings) {
        if (typeof sort === 'undefined') {
            sort = 'default';
        }

        if (typeof isAllListings === 'undefined') {
            isAllListings = false;
        }

        if (isAllListings === true) {
            $saveSearchSection.hide();
        }

        $.get(
            '/api/home-finder/search',
            {
                //propertyTypes: filters.propertyTypes,
                //neighborhoods: filters.neighborhoods,
                prices: filters.prices,
                bedrooms: filters.bedrooms,
                bathrooms: filters.bathrooms,
                //searchMLS: filters.shouldSearchMLS,
                searchAddress: filters.searchAddress,
                sort: sort,
                //lastUpdate: filters.lastUpdate,
                //squareFootage: filters.squareFootage,
                //homeFeatures: filters.homeFeatures,
                //views: filters.views,
                includePlans: filters.includePlans,
                builders: filters.builders
            },
            function (data) {
                var html = data.rsp,
                    total = data.total;

                locations = [];

                hideLoadingListingsIndicator();

                if (isAllListings === false) {
                    $('h2.listings-title').text('Search Listings');
                    $('div.listings-type').find('select').find('option').first().prop('selected', 'selected');
                }
                else {
                    $('h2.listings-title').text('All Listings');
                }

                $('div.results-count').text(pluralize('Result', total, true));

                $('div.listings-wrapper').html(html).fadeTo('slow', 1);

                // auto click the first result
                $('div.listings-wrapper').find('div.listing').first().trigger('click');
            });
    }

    function getBuilders() {
        return $('#filter-builders').find('option:selected').val();
    }

    function getShouldIncludePlans() {
        return $('#filter-includePlans').is(':checked');
    }

    function getLastUpdate() {
        return $('#filter-lastUpdate').val();
    }

    function getFilterSquareFootage() {
        return $('#filter-sqft').find('option:selected').val();
    }

    function getFilterHomeFeatures() {
        return $('#filter-homeFeatures').multipleSelect('getSelects');
    }

    function getFilterViews() {
        return $('#filter-view').multipleSelect('getSelects');
    }

    function getSearchAddress() {
        return $('#filter-searchAddress').val();
    }

    function getShouldSearchMLS() {
        return true;
    }

    function getFilterPropertyTypes() {
        return $('#filter-propertyType').multipleSelect('getSelects');
    }

    function getFilterNeighborhoods() {
        return $('#filter-neighborhood').multipleSelect('getSelects');
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

    $('div.listings-type select').on('change', function () {
        var choice = $(this).find('option:selected').val();
        //('home-finder/' === Backbone.history.getFragment())
        switch (choice) {
            case 'new-offerings':
                router.navigate('home-finder/new-offerings/', {trigger: false});
                $('div.results-sort').hide();
                showNewOfferings();
                break;
            case 'recently-listed':
                router.navigate('home-finder/recently-listed/', {trigger: false});
                $('div.results-sort').hide();
                showRecentlyListed();
                break;
            case 'saved-listings':
                router.navigate('home-finder/saved-listings/', {trigger: false});
                $('div.results-sort').show();
                showSavedListings();
                break;
            case 'all-listings':
                router.navigate('home-finder/all-listings/', {trigger: false});
                $('div.results-sort').show();
                showAllListings();
                break;
            case 'featured-listings':
            default:
                router.navigate('home-finder/featured-listings/', {trigger: false});
                $('div.results-sort').show();
                showFeaturedListings();
        }
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

    $(document).on('click', 'a.filterByPropertyType', function () {
        var propertyType = $(this).attr('data-property-type');

        order = 'default';

        clearFilters();
        $('#filter-propertyType').find('option[value="' + propertyType.toLowerCase() + '"]').prop('selected', 'selected');
        $('#filter-bathrooms').trigger('change');

        return false;
    });

    $(document).on('click', 'a.filterByNeighborhood', function () {
        var neighborhood = $(this).attr('data-neighborhood');

        order = 'default';

        clearFilters();
        $('#filter-neighborhood').find('option:contains(' + neighborhood + ')').prop('selected', 'selected');
        $('#filter-bathrooms').trigger('change');

        return false;
    });

    function clearFilters() {
        order = 'default';
        pauseSearch = true;
        $('div.home-finder-filters').find('select option:selected').removeProp('selected');
        $('#filter-searchAddress').val('');
        $('#filter-includePlans').prop('checked', false);
        $('#filter-propertyType').multipleSelect('uncheckAll');
        $('#filter-neighborhood').multipleSelect('uncheckAll');
        $('#filter-homeFeatures').multipleSelect('uncheckAll');
        $('#filter-view').multipleSelect('uncheckAll');
        filterPriceSlider.noUiSlider.set([0, 5000000]);
        filterSqftSlider.noUiSlider.set([0, 5000]);

        pauseSearch = false;
    }

    $('#filter-clearAll').on('click', function () {
        clearFilters();

        // trigger a change in order to pull listings again
        $('#filter-bathrooms').trigger('change');

        return false;
    });

    $(document).on('click', 'a.sortByPriceHighToLow', function () {
        var $listingsType = $('div.listings-type').find('select').find('option:selected');

        if ($listingsType.val() === '' || $listingsType.val() === 'Select Filters' || $listingsType.val() === 'all-listings') {
            var isAllListings = false;
            if ($listingsType.val() === 'all-listings') {
                isAllListings = true;
            }
            performSearch('price.desc', isAllListings);
        }
        else {
            order = 'price.desc';
            $('div.listings-type select').trigger('change');
        }

        return false;
    });

    $(document).on('click', 'a.sortByPriceLowToHigh', function () {
        var $listingsType = $('div.listings-type').find('select').find('option:selected');

        if ($listingsType.val() === '' || $listingsType.val() === 'Select Filters' || $listingsType.val() === 'all-listings') {
            var isAllListings = false;
            if ($listingsType.val() === 'all-listings') {
                isAllListings = true;
            }
            performSearch('price.asc', isAllListings);
        }
        else {
            order = 'price.asc';
            $('div.listings-type select').trigger('change');
        }

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
            //homeFeatures: getFilterHomeFeatures(),
            //views: getFilterViews(),
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

    //Multiple Select
    $('#filter-propertyType').multipleSelect({
        placeholder: "Property Type",
        onClick: function (view) {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        },
        onCheckAll: function () {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        },
        onUncheckAll: function () {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        }
    });

    $('#filter-neighborhood').multipleSelect({
        placeholder: "Neighborhood",
        onClick: function (view) {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        },
        onCheckAll: function () {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        },
        onUncheckAll: function () {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        }
    });

    $('#filter-homeFeatures').multipleSelect({
        placeholder: "Home Features",
        onClick: function (view) {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        },
        onCheckAll: function () {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        },
        onUncheckAll: function () {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        }
    });

    $('#filter-view').multipleSelect({
        placeholder: "View",
        onClick: function (view) {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        },
        onCheckAll: function () {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        },
        onUncheckAll: function () {
            $('#filter-searchAddress').val('');
            order = 'default';
            performSearch();
        }
    });

    // Harvest Chosen Select Boxes
    $("#filter-builders, #filter-bedrooms, #filter-bathrooms, #filter-listings-type").chosen({
        disable_search: 'true'
      });

    // price slider

    $('#showPriceFilter').on('click', function () {
        $('#priceFilterSection').toggle();
    });

    var filterPriceSlider = document.getElementById('filter-price');

    noUiSlider.create(filterPriceSlider, {
        start: [0, 500000],
        connect: true,
        step: 25000,
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