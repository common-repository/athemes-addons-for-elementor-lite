(function ($) {

	var aThemesAddonsWooProductGrid = function ($scope, $) {
		
		var $wrapper 		= $( '.athemes-addons-products-grid', $scope );

		var $categories 	= $wrapper.data('categories') !== undefined ? $wrapper.data('categories') : '';
		var $display_mode 	= $wrapper.data('display-mode') !== undefined ? $wrapper.data('display-mode') : '';
		var $widget_id 		= $wrapper.data('widget-id') !== undefined ? $wrapper.data('widget-id') : '';
		var $page_id 		= $wrapper.data('page-id') !== undefined ? $wrapper.data('page-id') : '';
		var $posts_per_page = $wrapper.data('posts-per-page') !== undefined ? $wrapper.data('posts-per-page') : 3;
		var $offset 		= $wrapper.data('offset') !== undefined ? $wrapper.data('offset') : 0;
		var $orderby 		= $wrapper.data('orderby') !== undefined ? $wrapper.data('orderby') : 'date';
		var $order 			= $wrapper.data('order') !== undefined ? $wrapper.data('order') : 'DESC';
		var $max_pages 		= $wrapper.data('max-pages') !== undefined ? $wrapper.data('max-pages') : 1;
		var $current_page 	= 0;

		$('.product-filter span', $scope).on('click', function (e) {
			e.preventDefault();
	
			var _this = $(this);

			$('.product-filter span').removeClass('active');
			_this.addClass('active');
			
			$term = _this.data('filter') !== undefined ? _this.data('filter') : '',

			$.ajax({
				url: AAFESettings.ajaxurl,
				type: 'POST',
				data: {
					action: 'aafe_product_filter',
					nonce: AAFESettings.nonce_product_filter,
					term: $term,
					categories: $categories,
					posts_per_page: $posts_per_page,
					offset: $offset,
					orderby: $orderby,
					order: $order,
					display_mode: $display_mode,
					widget_id: $widget_id,
					page_id: $page_id,
				},
				beforeSend: function beforeSend() {
					$('.elementor-element-' + $widget_id + ' .product-grid-inner' ).find('.product-grid-loader').addClass('show');
                },
				success: function success(data) {
					var $content = $(data);
					
					$('.elementor-element-' + $widget_id + ' .product-grid-inner ul' ).empty().append( $content );

					$max_pages = $('.elementor-element-' + $widget_id + ' .product-grid-inner ul' ).find( '.maxpages' ).data( 'maxpages' );

					if ( $current_page + 1 == $max_pages ) {
						$scope.find('.product-grid-load-more').hide();
					} else {
						$scope.find('.product-grid-load-more').show();
					}
				},
				complete: function complete() {
					$('.elementor-element-' + $widget_id + ' .product-grid-inner' ).find('.product-grid-loader').removeClass('show');				
				},
                error: function error(data) {
                    console.log(data);
                }
			});

			$(document).trigger('athemes-addons-woo-product-grid-filter-changed' );
		});

		// Load more
		$('.load-more-button', $scope).on('click', function (e) {
			e.preventDefault();

			var _this = $(this);

			var $active_filter = $('.product-filter span.active', $scope);
			$term = $active_filter.data('filter') !== undefined ? $active_filter.data('filter') : 'all';

			$current_page++;

			$(document).on('athemes-addons-woo-product-grid-filter-changed', function () {
				$current_page = 0;
				_this.show();
			});

			$.ajax({
				url: AAFESettings.ajaxurl,
				type: 'POST',
				data: {
					action: 'aafe_product_filter',
					nonce: AAFESettings.nonce_product_filter,
					term: $term,
					categories: $categories,
					posts_per_page: $posts_per_page,
					offset: $offset,
					orderby: $orderby,
					order: $order,
					display_mode: $display_mode,
					widget_id: $widget_id,
					page_id: $page_id,
					max_pages: $max_pages,
					current_page: $current_page,
					load_more: true
				},
				beforeSend: function beforeSend() {
					_this.find( 'span' ).show();
				},
				success: function success(data) {
					var $content = $(data);
					
					$('.elementor-element-' + $widget_id + ' .product-grid-inner ul' ).append( $content );
				},
				complete: function complete() {
					_this.find( 'span' ).hide();
					
					if ( $current_page >= $max_pages ) {
						_this.hide();
					}
				},
				error: function error(data) {
					console.log(data);
				}
			});			
		});
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-woo-product-grid.default', aThemesAddonsWooProductGrid );
	});

})(jQuery);