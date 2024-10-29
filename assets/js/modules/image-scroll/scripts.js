(function ($) {
    var aThemesAddonsImageScroll = function ($scope, $) {
        var $imageScroll = $scope.find('.athemes-addons-image-scroll').eq(0);
        var $imageInner = $imageScroll.find('.image-scroll-inner img');
        var direction = $imageScroll.data('direction');
		
		if ( 'hover' === $imageScroll.data('trigger-type') ) {

			if ( 'btt' === direction ) {
				$imageInner.css('transform', 'translateY(-' + ($imageInner.height() - $imageScroll.height()) + 'px)');
			} else if ( 'rtl' === direction ) {
				$imageInner.css('transform', 'translateX(-' + ($imageInner.width() - $imageScroll.width()) + 'px)');
			}

			$imageScroll.on('mouseenter', function() {
				if ( 'ltr' === direction ) {
					$imageInner.css('transform', 'translateX(-' + ($imageInner.width() - $imageScroll.width()) + 'px)');
				} else if ( 'rtl' === direction ) {
					$imageInner.css('transform', 'translateX(0px)');
				} else if ( 'ttb' === direction ) {					
					$imageInner.css('transform', 'translateY(-' + ($imageInner.height() - $imageScroll.height()) + 'px)');
				} else if ( 'btt' === direction ) {
					$imageInner.css('transform', 'translateY(0px)');
				} else {
					$imageInner.css('transform', 'translateX(0px)');
				}
			} ).on('mouseleave', function() {
				if ( 'ltr' === direction ) {
					$imageInner.css('transform', 'translateX(0px)');
				} else if ( 'rtl' === direction ) {
					$imageInner.css('transform', 'translateX(-' + ($imageInner.width() - $imageScroll.width()) + 'px)');
				} else if ( 'ttb' === direction ) {
					$imageInner.css('transform', 'translateY(0px)');
				} else if ( 'btt' === direction ) {
					$imageInner.css('transform', 'translateY(-' + ($imageInner.height() - $imageScroll.height()) + 'px)');
				} else {
					$imageInner.css('transform', 'translateX(-' + ($imageInner.width() - $imageScroll.width()) + 'px)');
				}
			} );
		}
		



  
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-image-scroll.default', aThemesAddonsImageScroll);
    });
})(jQuery);
