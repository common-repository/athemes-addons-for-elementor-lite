(function ($) {

    var aThemesAddonsProgressBar = function($scope, $) {
        var $progressBar        = $scope.find('.athemes-addons-progress-bar').eq(0);

        var layout              = $progressBar.find('.aafe-progress-wrapper').data('layout');
        var value               = $progressBar.find('.aafe-progress-wrapper').data('max');
        var progressPercentage  = $progressBar.find('.progress-percentage');
        var iconType            = $progressBar.find('.aafe-progress-wrapper').data('icon');
        var duration            = 2000;

        elementorFrontend.waypoint($progressBar, function() {
            if ( layout === 'circle' ) {
				$progressBar.find( '.aafe-progress-circle' ).animate({
					'--progress': value
				}, {
					duration: duration,
					easing: 'linear',
					step: function(now) {
						$progressBar.find('.aafe-progress-circle').css('--progress', now);
                        progressPercentage.text(Math.round(now) + '%'); 
					}
				});
            } else if (layout === 'line') {
                $progressBar.find('.aafe-progress-bar').animate({
                    width: value + '%'
                }, {
                    duration: duration,
                    easing: 'linear',
                    step: function(now, fx) {
                        var percent = (fx.pos * value);
                        progressPercentage.text(Math.round(percent) + '%'); 
                    }
                });
            }

        });

        if ( 'lottie' === iconType ) {
            var $lottie = $progressBar.find('.aafe-lottie');
            
            var animation = lottie.loadAnimation({
                container: $lottie[0],
                renderer: 'svg',
                loop: true,
                path: $lottie.data('json-url'),
            });

            animation.play();
        }
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-progress-bar.default', aThemesAddonsProgressBar);
    });

})(jQuery);