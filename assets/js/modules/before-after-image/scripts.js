(function ($) {

	var aThemesAddonsBeforeAfterImage = function($scope, $) {
		var $beforeAfterImage = $scope.find('.athemes-addons-before-after-image');

		$beforeAfterImage.twentytwenty({
			default_offset_pct: $scope.find('.athemes-addons-before-after-image').data('offset'),
			orientation: $scope.find('.athemes-addons-before-after-image').data('orientation'),
			move_on_hover: $scope.find('.athemes-addons-before-after-image').data('move-on-hover'),
			click_to_move: $scope.find('.athemes-addons-before-after-image').data('click-to-move'),
			no_overlay: $scope.find('.athemes-addons-before-after-image').data('overlay'),
			before_label: $scope.find('.athemes-addons-before-after-image').data('before-label'),
			after_label: $scope.find('.athemes-addons-before-after-image').data('after-label'),
		});
	}

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-before-after-image.default', aThemesAddonsBeforeAfterImage );
	});

})(jQuery);