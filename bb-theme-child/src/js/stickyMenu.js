jQuery( function ($){

    const $header = $('.siteHeader');
    let scrolling = false;
    const min_scroll_sticky = 300;
    const sticky_threshold = 0;

    let previous_scroll = window.scrollY;

    $(window).on('scroll', function(){
        if( !scrolling ) {
            scrolling = true;
            requestAnimationFrame( scroll );
        }
    });

    function scroll() {
        const current_scroll = window.scrollY;
        const scrolling_up = current_scroll - previous_scroll < 0;

        if( scrolling_up && current_scroll >= min_scroll_sticky ) {
            $header.addClass(['sticky', 'in']);
        } else if( !scrolling_up ) {
            $header.removeClass('in');
        }

        if( current_scroll <= sticky_threshold ) {
            $header.removeClass(['sticky', 'in']);
        }

        previous_scroll = current_scroll;
        scrolling = false;
    }

});