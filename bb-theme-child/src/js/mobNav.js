jQuery( function ($){

    const $mobNav = $('.mobMenu');
    const $withChildren = $mobNav.find('.menu-item-has-children');
    const $html = $('html');
    const $hamburger = $('.siteHeader__hamburger');

    let mob_menu_open = false;


    $hamburger.on('click', function(){
        if( mob_menu_open ) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    $withChildren.each(function(i,el){
        const $li = $(el);
        const $a = $li.children('a');

        $a.on('click', function(e){
            if( !$li.hasClass('is-open')) {
                $li.addClass('is-open');
                e.preventDefault();
            }
        })
    });


    function openMenu() {
        mob_menu_open = true;
        $mobNav.addClass('is-open');
        $hamburger.addClass('is-active');
        $html.addClass('mob-menu-open');
    }
    function closeMenu() {
        mob_menu_open = false;
        $withChildren.removeClass('is-open');
        $mobNav.removeClass('is-open');
        $hamburger.removeClass('is-active');
        $html.removeClass('mob-menu-open');
    }

});