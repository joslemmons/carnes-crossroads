jQuery(function ($) {
    //$('.dropdown-toggle').dropdown();

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

    $('.expand-link').click(function () {
        $(this).hide();
        $(this).closest('div.child-content').find('span.more-content').show();
    });

});