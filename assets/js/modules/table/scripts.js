(function ($) {

	var aThemesAddonsTable = function ($scope, $) {
        $(document).ready(function () {
			$( '.athemes-addons-table' ).each(function() {
				var $table 		= $( 'table', $scope);
				var $sorting  	= $( '.aafe-table-sorting', $scope);
				var $search 	= $( '.aafe-table-search', $scope);
	
				//sorting
				$sorting.find( 'th' ).on('click', function() {
					if ($(this).hasClass('asc')) {
						$(this).parent().find('th').removeClass('asc').removeClass('desc');
						$(this).addClass('desc');
					} else if ($(this).hasClass('desc')) {
						$(this).parent().find('th').removeClass('asc').removeClass('desc');
						$(this).addClass('asc');
					} else {
						$(this).parent().find('th').removeClass('asc').removeClass('desc');
						$(this).addClass('desc');
					}

					var $this = $(this);
					var $table = $this.closest('table');
					var $rows = $table.find('tbody .aafe-table-row').get();
					var $index = $this.index();
					var $direction = $this.hasClass('asc') ? 1 : -1;

					$rows.sort(function(a, b) {
						var keyA = parseFloat($(a).children('td').eq($index).text());
						var keyB = parseFloat($(b).children('td').eq($index).text());

						if (isNaN(keyA)) keyA = 0;
						if (isNaN(keyB)) keyB = 0;

						if (keyA < keyB) return -1 * $direction;
						if (keyA > keyB) return 1 * $direction;

						return 0;
					});

					$.each($rows, function(index, row) {
						$table.children('tbody').append(row);
					});
				});

				//search
				$search.find( 'input' ).on('keyup', function() {
					var $value = $(this).val().toLowerCase();
					$table.find('tbody .aafe-table-row').filter(function() {
						$(this).toggle($(this).text().toLowerCase().indexOf($value) > -1);
					});
				});

				//pagination
				$( '.aafe-table-pagination-button', $scope ).on('click', function() {
					var page = $( this ).data( 'page' );
					$( this ).addClass('active').siblings().removeClass('active');
					$( '.aafe-table-row' ).not( '.aafe-table-header-row' ).addClass( 'aafe-hidden-row' );
					$( '.aafe-table-row[data-page="' + page + '"]' ).removeClass( 'aafe-hidden-row' );
				});
			});	
        }); 
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-table.default', aThemesAddonsTable );
	});

})(jQuery);