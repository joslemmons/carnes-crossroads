jQuery(function ($) {
    //$('.dropdown-toggle').dropdown();

    // $(document).ready(function(){
    //   $(window.location.hash).append('<a name="' + window.location.hash.replace('#','')+ '"></a>');
    //   window.location.href=window.location.href;
    // });


    //vimeo player
        $(document).on('click', '.video-play', function (e) {
            e.preventDefault();
            createPopup($(this), " ");
            return false;
        });

    //Carousels - bxslider
    $.each($('div.slider-lg'), function (i, el) {
        $(el).bxSlider({
            slideWidth: 325,
            minSlides: 2,
            maxSlides: 5,
            moveSlides: 1,
            slideMargin: 15,
            pager: false,
            nextSelector: $(el).parent().find('span.next-lg'),
            prevSelector: $(el).parent().find('span.prev-lg'),
            nextText: '',
            prevText: ''
        });
    });

    //Slideshows - slick.js
    $('.slider-sm').slick({
        dots: false,
        infinite: true,
        speed: 800,
        fade: true,
        arrows: true,
        prevArrow: '<span class="prev-sm"></span>',
        nextArrow: '<span class="next-sm"></span>',
        cssEase: 'linear'
    });

     /*** Match Height - Landing Page ***/
    if ($('.match-height').length > 0) {
        $('.match-height').matchHeight();
    }

    //Read More link
    $('.expand-link').click(function () {
        $(this).hide();
        $(this).closest('div.child-content').find('span.more-content').show();
    });

    //Smooth Scroll
    $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });

    //Navigation
    var slideRight = new Menu({
        wrapper: '#o-wrapper',
        type: 'slide-right',
        menuOpenerClass: '.c-button',
        maskId: '#c-mask'
    });

    var slideRightBtn = $('#c-button--slide-right,div.open-menu');

    slideRightBtn.on('click', function (e) {
        e.preventDefault();
        slideRight.open();
        return false;
    });

    //Back to Top Btn and Hamburger Menu for Mobile
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 250) {
            $('.menu-icon').addClass('scroll');
        } else {
            $('.menu-icon').removeClass('scroll');
        }
    });



    // account functionality

    function saveOrUnSaveProperty(propertyId, action) {
        $.post('/api/home-finder/properties/' + propertyId + '/' + action);
    }

    $('#modal-account').on('show.bs.modal', function (e) {
        var $modals = $('div.modal');
        $.each($modals, function (i, el) {
            if (($(el).data('bs.modal') || {}).isShown === true) {
                $(el).modal('hide');
            }
        });
    });

    $(document).on('click', 'a.showAccountPage', function () {
        var $modal = $('#modal-account');

        if (typeof ($modal.data('bs.modal') || {}).isShown === 'undefined' || ($modal.data('bs.modal') || {}).isShown === false) {
            $modal.modal('show');
        }
        else {
            return false;
        }

        loadAccountContentInModal();

        return false;
    });

    function loadAccountContentInModal() {
        var $modal = $('#modal-account'),
            $loadingScreen = $modal.find('div.account-loading-screen');

        $loadingScreen.show();
        $modal.find('div.modal-content div.child-page-content').remove();

        $.ajax({
            type: "GET",
            url: '/api/home-finder/account-page',
            data: {},
            error: function () {
                $modal.find('div.modal-content div.primary-content').after('<p>Failed to load Account Information. Please try again later. If it fails again, please let us know. <a href="/contact/">Contact Page</a></p>');

            },
            success: function (data) {
                var html = data.rsp;

                // refresh content
                $loadingScreen.hide();
                $modal.find('div.modal-content div.primary-content').after(html);

                //My Account Slider
                $('.saved-listings-slider').slick({
                    infinite: false,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: false,
                    autoplaySpeed: 8000,
                    variableWidth: true
                });
            }
        });
    }

    $(document).on('click', '#account-saved-listings a.account-unsave-listing', function () {
        var propertyId = $(this).attr('data-property-id'),
            slickSlideNum = $(this).parent().attr('data-slick-index');

        if (confirm('Are you sure?')) {
            $('.saved-listings-slider').slick('slickRemove', slickSlideNum);
        }

        saveOrUnSaveProperty(propertyId, 'un-save');

        return false;
    });

    $(document).on('click', 'a.accountUnSaveSearch', function () {
        var unSaveLink = $(this).attr('data-un-save-link');

        var savedSearchesCount = parseInt($('#saveSearchSection').find('a.showAccountPage').text().replace('(', '').replace(')', ''));
        savedSearchesCount--;

        $('#saveSearchSection').find('a.showAccountPage').text('(' + savedSearchesCount + ')');

        $(this).parent().remove();

        $.post(unSaveLink, {}, function (rsp) {
        });

        return false;
    });

    $(document).on('click', '#notification-options form button', function () {
        var choice = $(this).parent().parent().find('input[type="radio"]:checked').val(),
            $error = $(this).parent().find('span.error-message'),
            $success = $(this).parent().find('span.success-message'),
            $button = $(this),
            buttonOriginalText = $(this).text();

        $button.text('Updating...');
        $button.prop('disabled', true);

        $.ajax({
            type: "POST",
            url: '/api/home-finder/save-notification-option',
            data: {
                choice: choice
            },
            error: function (data) {
                $success.hide();
                $error.text(data.rsp);
                $error.show();
                $button.text(buttonOriginalText);
                $button.prop('disabled', false);
            },
            success: function (data) {
                $error.hide();
                $success.show();
                $button.text(buttonOriginalText);
                $button.prop('disabled', false);
            }
        });

        return false;
    });

    $(document).on('click', '#account-register form button', function () {
        var email = $(this).parent().find('input[name="email"]').val(),
            $error = $(this).parent().find('span.error-message'),
            $success = $(this).parent().find('span.success-message'),
            $button = $(this),
            buttonOriginalText = $(this).text();

        $button.text('Signing in...');
        $button.prop('disabled', true);

        $.ajax({
            type: "POST",
            url: '/api/home-finder/sign-in',
            data: {
                email: email
            },
            error: function (data) {
                if (typeof data.responseJSON !== 'undefined') {
                    switch (data.responseJSON.status) {
                        case (500) :
                        default:
                            $error.text('Failed to login or create account. Please try again. If it still fails, go to the contact page and let us know. Thanks!');
                    }
                }
                $error.show();
                $button.text(buttonOriginalText);
                $button.prop('disabled', false);
            },
            success: function (data) {
                $error.hide();

                loadAccountContentInModal();

                // if the page is HomeFinder, then reload so all the account functionality works
                if (typeof DIG !== 'undefined' && DIG.isHomeFinderPage !== 'undefined' && DIG.isHomeFinderPage === true) {
                    $('#modal-account').on('hide.bs.modal', function () {
                        location.reload();
                    });
                }
            }
        });

        return false;
    });

    $(document).on('keypress', '#footer-search', function (e) {
        if (e.which === 13) {
            var searchValue = $(this).val();
            window.location = "/?s=" + searchValue;
        }
    });

    function initColorboxElements() {
        $('a.color-box-single').colorbox({
            maxWidth: '95%'
        });
    }

    initColorboxElements();

});