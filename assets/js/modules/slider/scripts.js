(function ($) {

	var aThemesAddonsSlider = function ($scope, $) {
        $(document).ready(function () {

			var slider = $scope.find('.athemes-addons-slider');

			var data = {
				loop: slider.data('loop') == 'yes' ? true : false,
				autoplayDelay: slider.data('autoplay-delay'),
				autoplayHoverPause: slider.data('pause-on-hover') == 'yes' ? true : false,
				autoplayDisableOnInteraction: slider.data('pause-on-interaction') == 'yes' ? true : false,
				effect: slider.data('effect'),
			}

			var swiperConfig = {
				effect: data.effect,
				direction: 'horizontal',
				loop: data.loop,
				autoplay: {
					delay: data.autoplayDelay,
					pauseOnMouseEnter: data.autoplayHoverPause,
					disableOnInteraction: data.autoplayDisableOnInteraction,
				},
				speed: 1000,    
				navigation: {
				  nextEl: '.swiper-button-next',
				  prevEl: '.swiper-button-prev',
				},			    
				pagination: {
				  el: '.swiper-pagination',
				  clickable: true,
				},
			}

			var thumbs = $scope.find('.athemes-addons-slider-thumbs');

			if ( thumbs.length ) {
				swiperConfig.thumbs = {
					swiper: {
						el: '.athemes-addons-slider-thumbs',
						slidesPerView: 3,
						spaceBetween: 10,
						breakpoints: {
							1200: {
								slidesPerView: 3,
							},
							992: {
								slidesPerView: 2,
							},
							768: {
								slidesPerView: 1,
							},
						},
					},
				}
			}

			const asyncSwiper = elementorFrontend.utils.swiper;
			
			new asyncSwiper( slider, swiperConfig ).then( ( newSwiperInstance ) => {		   
				swiperElement = newSwiperInstance;

				swiperElement.on('slideChangeTransitionEnd', function () {
					$(this.slides).removeClass('run-animation');
					$(this.slides[this.activeIndex]).addClass('run-animation');
				} );

			} );

        }); 
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-slider.default', aThemesAddonsSlider );
	});

})(jQuery);