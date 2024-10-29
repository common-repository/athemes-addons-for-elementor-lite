(function ($) {

	var aThemesAddonsImageAccordion = function ($scope, $) {
		var $accordion = $('.athemes-addons-image-accordion', $scope);

		var openMode = $accordion.data('open-mode');

		if ( 'hover' === openMode ) {
			$accordion.on('mouseenter', '.image-accordion-item', function () {
				$(this).addClass( 'accordion-item-active' ).siblings().removeClass( 'accordion-item-active' );
			});

			$accordion.on('mouseleave', function () {
				$('.image-accordion-item', $accordion).removeClass( 'accordion-item-active' );
			} );
		} else {
			$accordion.on('click', '.image-accordion-item', function () {
				$(this).addClass( 'accordion-item-active' ).siblings().removeClass( 'accordion-item-active' );
			});
		}
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-image-accordion.default', aThemesAddonsImageAccordion );
	});

})(jQuery);