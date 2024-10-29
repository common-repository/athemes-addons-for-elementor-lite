(function ($) {

	var aThemesTestimonials = function($scope, $) {

		var $carousel = $scope.find('.athemes-addons-testimonials');
		var $skin_id = $carousel.data('id');
		var $autoplay = $carousel.data('autoplay');
		var $dots = $carousel.data('dots');
		var $arrows = $carousel.data('arrows');
		var $autoplay_speed = $carousel.data('autoplay-speed');
		var $transition_speed = $carousel.data('transition-speed');
		var $infinite =  $carousel.data('infinite') !== undefined ? $carousel.data('infinite') : false;
		var $pause_on_hover = $carousel.data('pause-on-hover') !== undefined ? $carousel.data('pause-on-hover') : false;

		if ( 'athemes-addons-testimonials-side-by-side' === $skin_id ) {
			$items = $items_tablet = $items_mobile = 1;
		} else {
			$items = $carousel.data('items') !== undefined ? $carousel.data('items') : 2;
			$items_tablet = $carousel.data('items-tablet') !== undefined ? $carousel.data('items-tablet') : 2;
			$items_mobile = $carousel.data('items-mobile') !== undefined ? $carousel.data('items-mobile') : 1;
		}

		if ( 'athemes-addons-testimonials-centered' === $skin_id ) {
			$items = $carousel.data('items') !== undefined ? $carousel.data('items') : 3;
		}

		var swiperConfig = {
			effect: 'slide',
			direction: 'horizontal',
			loop: $infinite,
			autoHeight: true,
			speed: $transition_speed,    		    
			breakpoints: {
				1024: {
					slidesPerView: $items,
					spaceBetween: 30,
				},
				768: {
					slidesPerView: $items_tablet,
					spaceBetween: 20,
				},
				320: {
					slidesPerView: $items_mobile,
					spaceBetween: 20,
				},
			}
		}

		if ( 'athemes-addons-testimonials-centered' === $skin_id ) {
			swiperConfig.centeredSlides = true;
			swiperConfig.autoHeight = false;
			swiperConfig.breakpoints = {
				1024: {
					slidesPerView: $items,
					spaceBetween: 0,
				},
				768: {
					slidesPerView: $items_tablet,
					spaceBetween: 0,
				},
				320: {
					slidesPerView: $items_mobile,
					spaceBetween: 0,
				},
			}
		}

		//autoplay
		if ( $autoplay ) {
			swiperConfig.autoplay = {
				delay: $autoplay_speed,
				disableOnInteraction: false,
			}
		}

		//dots
		if ( 'yes' === $dots ) {
			swiperConfig.pagination = {
				el: $scope.find('.testimonials-pagination')[0],
				clickable: true,
			}
		}

		//arrows
		if ( 'yes' === $arrows ) {
			swiperConfig.navigation = {
				nextEl: $scope.find('.swiper-button-next')[0],
				prevEl: $scope.find('.swiper-button-prev')[0]
			}
		}

		//thumbs
		var thumbsContainer = $scope.find('.athemes-addons-testimonials-thumbs');

		if ( thumbsContainer.length ) {
			swiperConfig.thumbs = {
				swiper: {
					el: $scope.find('.athemes-addons-testimonials-thumbs')[0],
					slidesPerView: 3,
					spaceBetween: 0,
					loop: true,
					breakpoints: {
						1200: {
							slidesPerView: 3,
						},
						992: {
							slidesPerView: 3,
						},
						768: {
							slidesPerView: 3,
						},
					},
				},
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
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-testimonials.default', aThemesTestimonials );
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-testimonials.athemes-addons-testimonials-side-by-side', aThemesTestimonials );
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-testimonials.athemes-addons-testimonials-modern', aThemesTestimonials );
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-testimonials.athemes-addons-testimonials-centered', aThemesTestimonials );
	});

})(jQuery);