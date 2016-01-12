jQuery(function ($) {
    //$('.dropdown-toggle').dropdown();

    //Carousels - bxslider
    $.each($('div.slider-lg'), function (i, el) {
        $(el).bxSlider({
            slideWidth: 335,
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

});