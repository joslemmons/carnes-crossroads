jQuery(function ($) {
    //$('.dropdown-toggle').dropdown();

    $.each($('div.slider-lg'), function (i, el) {
        $(el).bxSlider({
            slideWidth: 300,
            minSlides: 2,
            maxSlides: 5,
            moveSlides: 1,
            slideMargin: 30,
            pager: false,
            nextSelector: $(el).parent().find('span.next-lg'),
            prevSelector: $(el).parent().find('span.prev-lg'),
            nextText: '',
            prevText: ''
        });
    });

    $.each($('div.slider-sm'), function (i, el) {
        $(el).bxSlider({
            mode: 'fade',
            slideWidth: 800,
            minSlides: 1,
            maxSlides: 1,
            moveSlides: 1,
            slideMargin: 0,
            pager: false,
            nextSelector: $(el).parent().find('span.next-sm'),
            prevSelector: $(el).parent().find('span.prev-sm'),
            nextText: '',
            prevText: ''
        });
    });


});
