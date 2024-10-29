(function ($) {

	var aThemesAnimatedHeading = function($scope, $) {
		var $strings 	= $scope.find( ".athemes-addons-animated-heading" ).data( 'strings' ).split('|');
		var $id 		= $scope.find( ".athemes-addons-animated-heading" ).data( 'id' );
		var $typeSpeed 	= $scope.find( ".athemes-addons-animated-heading" ).data( 'type-speed' );
		var $backSpeed 	= $scope.find( ".athemes-addons-animated-heading" ).data( 'back-speed' );
		var $backDelay 	= $scope.find( ".athemes-addons-animated-heading" ).data( 'back-delay' );

		new Typed( "#animated-heading-" + $id, {
			strings: $strings,
			loop: true,
			typeSpeed: $typeSpeed,
			backSpeed: $backSpeed,
			backDelay: $backDelay,
		});
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-animated-heading.default', aThemesAnimatedHeading );
	});

})(jQuery);