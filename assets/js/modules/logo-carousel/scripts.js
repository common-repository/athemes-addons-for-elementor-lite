(function ($) {

	var aThemesLogoCarousel = function($scope, $) {

		var $carousel = $scope.find('.athemes-addons-logo-carousel').eq(0);
		var $id = $carousel.data('id');
		var $autoplay = $carousel.data('autoplay');
		var $autoplay_speed = $carousel.data('autoplay-speed');
		var $gap = $carousel.data('gap') !== undefined ? $carousel.data('gap') : 30;
		var $transition_speed = $carousel.data('transition-speed');
		var $infinite =  $carousel.data('infinite') !== undefined ? $carousel.data('infinite') : false;
		var $pause_on_hover = $carousel.data('pause-on-hover') !== undefined ? $carousel.data('pause-on-hover') : false;

		$items = $carousel.data('items') !== undefined ? $carousel.data('items') : 3;
		$items_tablet = $carousel.data('items-tablet') !== undefined ? $carousel.data('items-tablet') : 2;
		$items_mobile = $carousel.data('items-mobile') !== undefined ? $carousel.data('items-mobile') : 1;

		var swiperConfig = {
			effect: 'slide',
			direction: 'horizontal',
			loop: $infinite,
			autoHeight: true,
			speed: $transition_speed,    
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},			    
			pagination: {
			el: '.logo-carousel-pagination',
			clickable: true,
			},
			breakpoints: {
				1024: {
					slidesPerView: $items,
					spaceBetween: $gap,
				},
				768: {
					slidesPerView: $items_tablet,
					spaceBetween: $gap,
				},
				320: {
					slidesPerView: $items_mobile,
					spaceBetween: $gap,
				},
			}
		}

		if ( true == $autoplay ) {
			swiperConfig.autoplay = {
				delay: $autoplay_speed,
				disableOnInteraction: false
			}
		}

		const asyncSwiper = elementorFrontend.utils.swiper;
	
		new asyncSwiper( $carousel, swiperConfig ).then( ( newSwiperInstance ) => {		   
			swiperElement = newSwiperInstance;
		} );

		if ( $pause_on_hover ) {
			$carousel.on("mouseenter", function () {
				swiperElement.autoplay.stop();

			});
			$carousel.on("mouseleave", function () {
				swiperElement.autoplay.start();
			});
		}
			
	}

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-logo-carousel.default', aThemesLogoCarousel );
	});

})(jQuery);