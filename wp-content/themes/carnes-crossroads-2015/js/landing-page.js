jQuery(function ($) {
    
    //Featured Slider
    $('.featured-slider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        centerMode: false,
        arrows: true,
        respondTo: 'window'
    });

    $('.match-height').matchHeight();


});