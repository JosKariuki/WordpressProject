jQuery(document).ready(function() {

    /**
     * Top Slider
     **/
    jQuery('.owl-carousel.islemag-top-carousel').owlCarousel('destroy').owlCarousel({
        loop: true,
        margin: 0,
        responsiveClass: true,
        nav: true,
        navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
        dots: false,
        autoplay: true,
        autoplayTimeout: 10000,
        lazyLoad: true,
        animateIn: true,
        responsive: {
            0: {items: 1},
            600: {items: 2},
            992: {items: 3}
        }
    });

});