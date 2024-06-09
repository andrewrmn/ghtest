jQuery( function ($){
    const $footer_items = $('.footerNav').children('li');
    $footer_items.each(function(i,el){
        const $this = $(el);
        const $handle = $this.children('a');

        $handle.on('click', function(e){
            e.preventDefault();
            $this.toggleClass('is-open');
        })
    });
});