jQuery(function ($) {
    
    //Mobile Menu
    $(".poa-menu-icon").click(function(){
        $(".poa-nav").toggleClass("visible");
    });
    
    //Mobile Sub Nav
    
    $('.down-menu').click(function(){
        $('.sidebar-mobile').slideToggle('slow');
        $('.icon-container').toggleClass('rotate');
    });
    
    //Back to Top Btn and Hamburger Menu for Mobile
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 250) {
            $('.back-top').addClass('scroll');
        } else {
            $('.back-top').removeClass('scroll');
        }
    });

    $('.back-top').on('click', function () {
        $('html,body').animate({
            scrollTop: 0
        }, 1000);
        return false;
    });
    
        //Sliders - Home Main
    $('.featured-slider').slick({
        dots: true,
        arrows: false
    });
        //Sliders - Home Events
     $('.featured-events-slider').slick({
        dots: false
    });
        //Sliders - Home Announcements
    $('.announcement-slider').slick({
        dots: false,
        slidesToShow: 3,
        responsive: [
            {
              breakpoint: 991,
              settings: {
                slidesToShow: 1
              }
            }
          ]
    });
    
    //Match Height - Home Page
    //--Events
    $('.home-events').each(function(i, elem) {
        $(elem)
            .find('.event-container-height')   // Only children of this row
            .matchHeight({byRow: false}); // Row detection gets confused so disable it
    });
    $('.featured-events-slider').each(function(i, elem) {
        $(elem)
            .find('.event-box')   // Only children of this row
            .matchHeight({byRow: false}); // Row detection gets confused so disable it
    });
    $('.featured-events-slider').each(function(i, elem) {
        $(elem)
            .find('.title-height')   // Only children of this row
            .matchHeight({byRow: false}); // Row detection gets confused so disable it
    });
    //--Announcements
    $('.home-annoucements').each(function(i, elem) {
        $(elem)
            .find('.annoucement-box')   // Only children of this row
            .matchHeight({byRow: false}); // Row detection gets confused so disable it
    });
    
    
    //Match Height - Directories
    //--Staff
    $('.staff-content').each(function(i, elem) {
        $(elem)
            .find('.staff-member-details')   // Only children of this row
            .matchHeight({byRow: false}); // Row detection gets confused so disable it
    });
    
    var Router = Backbone.Router.extend();
    var router = new Router();

    if (!Backbone.history.started) {
        Backbone.history.start({
            pushState: "pushState" in window.history,
            silent: true
        });
        Backbone.history.started = true;

        var pathArray = window.location.pathname.split('/');
        pathArray = _(pathArray).filter(function (el) {
            return (el.length > 0);
        });

        if (pathArray.length >= 3) {
            var target = $('#' + pathArray[2]);
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - 200
                }, 1000);
            }
        }
    }

    var navigateOptions = {
        trigger: false
    };

    if (typeof DIRE !== 'undefined' && typeof DIRE.isRealEstatePage !== 'undefined' && DIRE.isRealEstatePage === 'true') {
        navigateOptions.replace = true;
    }

    $('a.child-page-link').on('click', function (e) {
        e.stopPropagation();
        var slug = $(this).attr('data-page-slug'),
            urlPath = $(this).attr('data-page-url-path');

        //$('li a.child-page-link').parent().removeClass('active');
        //$(this).parent().addClass('active');

        //router.navigate(urlPath, {trigger: false});

        var target = $('#' + slug);
        if (target.length) {
            var offset = 200;
            if ($(window).scrollTop() > target.offset().top - offset) {
                // going up
                offset += 20;
            }
            else {
                // going down
            }

            $('html,body').animate({
                scrollTop: target.offset().top - offset
            }, 1000);
        }

        return false;
    });

    $('div.child-page-content').each(function (i, el) {
        var waypoint = new Waypoint({
            element: document.getElementById($(el).attr('id')),
            handler: function (dir) {
                var $menu = $('li a.child-page-link[data-page-slug="' + this.element.id + '"]'),
                    slug = $menu.attr('data-page-slug'),
                    urlPath = $menu.attr('data-page-url-path');

                $('li a.child-page-link').parent().removeClass('active');
                $menu.parent().addClass('active');

                router.navigate(urlPath, navigateOptions);
            },
            offset: 215
        });
    });

    
    //--Accordion Info
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function(){
            this.classList.toggle("active");
            this.nextElementSibling.classList.toggle("show");
        }
    }
});
