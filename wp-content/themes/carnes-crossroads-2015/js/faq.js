jQuery(document).ready(function($) {
    $('ul.faq-set .faq-question').on('click', function () {
        $(this).parent().find('span.faq-answer').slideToggle({
            easing: 'swing',
            start: function () {
                if ($(this).parent().find('img').attr('src').indexOf('minus') > -1) {
                    $(this).parent().find('img').attr('src', '/wp-content/themes/carnes-crossroads-2015/img/plus-circle_360.png');
                }
                else {
                    $(this).parent().find('img').attr('src', '/wp-content/themes/carnes-crossroads-2015/img/minus-circle_360.png');
                }
            }
        });
    });
});
