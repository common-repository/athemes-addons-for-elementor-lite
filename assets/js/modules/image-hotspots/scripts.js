(function ($) {

	var aThemesImageHotspots = function($scope, $) {
		var elements 	= $scope.find( ".hotspot-element" );

		$.each(elements, function (i, v) { 

			$( this ).on( "click", function(e) { 
				$( this ).find( '.hotspot-tooltip.on-click' ).toggleClass( 'hotspot-clicked' );
			});
		});
	}

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-image-hotspots.default', aThemesImageHotspots );
	});

})(jQuery);