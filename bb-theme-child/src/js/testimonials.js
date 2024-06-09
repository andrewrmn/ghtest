jQuery( function ($){
    $('.ghTestimonials__carousel').owlCarousel({
        items: 1,
        loop: true,
        nav: true,
        navText : [
            '<i class="ri-arrow-left-s-line"></i>',
            '<i class="ri-arrow-right-s-line"></i>',
        ],
        dots : true
    });
});