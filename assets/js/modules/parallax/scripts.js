(function ($) {
    var aThemesAddonsParallax = function ($scope, $) {
    
        jQuery('.aafe-bg-parallax-yes').each(function () {

            var speed = jQuery(this).data('parallax-speed') || 0.5;

            var disableMobile = jQuery(this).data('parallax-disable-mobile') || 'no';

            var args = {
                speed: speed
            };

            if ( 'yes' === disableMobile ) {
                args.disableParallax = /iPad|iPhone|iPod|Android/;
            }

            jQuery(this).jarallax(args);
        });


        jQuery('.aafe-ml-parallax-yes').each(function () {
            var layers = jQuery(this).find('.aafe-ml-parallax-layer');

            layers.each(function () {
                var settings = jQuery(this).data('parallax-settings');

                console.log(settings);

                // get the effect type
                var effect = settings.effect || 'move';

                if ( 'move' === effect ) {
                    var layer = jQuery(this);

                    jQuery(this).closest('.aafe-ml-parallax-yes').on('mousemove', function(e) {
                        var offsetX = (e.pageX - jQuery(this).offset().left) / jQuery(this).outerWidth();
                        var offsetY = (e.pageY - jQuery(this).offset().top) / jQuery(this).outerHeight();

                        var moveX = (offsetX - 0.5) * settings.speed;
                        var moveY = (offsetY - 0.5) * settings.speed;
                        
                        layer.css({
                            'transform': 'translate(' + moveX + 'px, ' + moveY + 'px)'
                        });
                    });
                } else if ( 'scroll' === effect ) {
                    var layer = jQuery(this);
                    jQuery(window).on('scroll', function() {
                        var scrollTop = jQuery(window).scrollTop();
                        var offset = layer.offset().top;
                        var windowHeight = jQuery(window).height();
                        
                        if (scrollTop + windowHeight > offset && scrollTop < offset + layer.outerHeight()) {
                            var moveY = scrollTop * settings.speed / 300;
                            layer.css({
                                'transform': 'translateY(' + moveY + 'px)'
                            });
                        }
                    });
                } else if ( 'tilt' === effect ) {
                    var layer = jQuery(this);
                    jQuery(this).closest('.aafe-ml-parallax-yes').on('mousemove', function(e) {
                        var offsetX = (e.pageX - jQuery(this).offset().left) / jQuery(this).outerWidth();
                        var offsetY = (e.pageY - jQuery(this).offset().top) / jQuery(this).outerHeight();
                
                        var tiltX = (offsetX - 0.5) * settings.speed;
                        var tiltY = (offsetY - 0.5) * settings.speed;
                
                        layer.css({
                            'transform': 'rotateX(' + tiltY + 'deg) rotateY(' + tiltX + 'deg)'
                        });
                    });
                }
            });

        });
    };

    // Elementor editor compatibility
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/section", aThemesAddonsParallax);
        elementorFrontend.hooks.addAction("frontend/element_ready/container", aThemesAddonsParallax);
    });


})(jQuery);