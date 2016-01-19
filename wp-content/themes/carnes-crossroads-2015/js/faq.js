jQuery(function ($) {
    $('ul').on('click', 'span.faq-question', function() {
        $(this).parent().find('span.faq-answer').slideToggle();
    });
});
