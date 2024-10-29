(function ($) {

	var aThemesAddonsLottie = function ($scope, $) {
		var $lottie = $('.athemes-addons-lottie-container', $scope);

		var animation = lottie.loadAnimation({
			container: $lottie[0],
			renderer: $lottie.data('render-type'),
			loop: $lottie.data('loop'),
			autoplay: $lottie.data('autoplay'),
			path: $lottie.data('json-url'),
		});

		animation.setSpeed( $lottie.data('speed') );

		if ( $lottie.data('reverse') ) {
			animation.setDirection(-1);
		}

		animation.addEventListener( 'DOMLoaded', function () {
			if ( 'hover' !== $lottie.data('trigger-type') && 'none' !== $lottie.data('trigger-type') && 'click' !== $lottie.data('trigger-type') ) {
				initLottie('load');

				$(window).on('scroll', initLottie);
			}

			if ( 'hover' === $lottie.data('trigger-type') ) {
				animation.pause();
				$lottie.hover(function () {
					animation.play();
				}, function () {
					animation.pause();
				});
			}

			if ( 'click' === $lottie.data('trigger-type') ) {
				animation.pause();
				$lottie.on('click', function () {
					animation.play();
				});
			}

			function initLottie(event) {
				animation.pause();

				if ( typeof $lottie[0].getBoundingClientRect === "function" ) {
										
					var height = document.documentElement.clientHeight;
					var scrollTop = ($lottie[0].getBoundingClientRect().top)/height * 100;
					var scrollBottom = ($lottie[0].getBoundingClientRect().bottom)/height * 100;
					var scrollEnd = scrollTop < $lottie.data('scroll-end');
					var scrollStart = scrollBottom > $lottie.data('scroll-start');

					if ( 'viewport' === $lottie.data('trigger-type') ) {
						scrollStart && scrollEnd ? animation.play() : animation.pause();
					}
					
					if ( 'scroll' === $lottie.data('trigger-type') ) {
						if( scrollStart && scrollEnd) {
							animation.pause();
							
							var scrollPercent = 100 * $(window).scrollTop() / ($(document).height() - $(window).height());
								
							var scrollPercentRounded = Math.round(scrollPercent);
						
							animation.goToAndStop( scrollPercentRounded, true );
						}
					};
				}
			}				

		});

	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-lottie.default', aThemesAddonsLottie );
	});

})(jQuery);