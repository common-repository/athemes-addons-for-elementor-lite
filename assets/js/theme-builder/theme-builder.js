(function ($) {
    const $sticky_header = $('.athemes-addons-custom-header[data-header-type="sticky"]');

    // Return if sticky header is not found
    if ( !$sticky_header.length ) {
        return;
    }

    let lastScrollTop = 0;
    let isSticky = false;

    const handleScroll = () => {
        const scrollTop = $( window ).scrollTop();

        if ( scrollTop > 300 && !isSticky ) {
            $sticky_header.addClass( 'is-sticky' );
            isSticky = true;
        } else if ( scrollTop <= 300 && isSticky ) {
            $sticky_header.removeClass( 'is-sticky' );
            isSticky = false;
        }

        lastScrollTop = scrollTop;
    };

    $( window ).on( 'scroll', () => {
        requestAnimationFrame(handleScroll);
    });
})(jQuery);