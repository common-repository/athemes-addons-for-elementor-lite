(function ($) {

	var aThemesAddonsGallery = function ($scope, $) {
        $(document).ready(function () {
			$( '.athemes-addons-gallery' ).each(function() {
				var $gallery = $( '.gallery-items', $scope);
				var $filter  = $( '.gallery-filter', $scope);
	
				$gallery.isotope({
					itemSelector: '.aafe-gallery-item',	
					percentPosition: true,
				});
	
				$gallery.imagesLoaded().progress(function() {
					$gallery.isotope('layout');
				});	
				
				$filter.on( 'click', 'a', function(e) {
					e.preventDefault();
					$( this ).addClass( 'active' );
					$( this ).siblings().removeClass( 'active' );
					var filterValue = $( this ).attr('data-filter');
					$gallery.isotope({ filter: filterValue });
				});	
			});	
        }); 
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-gallery.default', aThemesAddonsGallery );
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-gallery.athemes-addons-gallery-card', aThemesAddonsGallery );
	});

})(jQuery);