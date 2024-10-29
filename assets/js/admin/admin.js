"use strict";

;
(function ($, window, document, undefined) {
	'use strict';

	var athemes_addons_elementor = athemes_addons_elementor || {};
	$(document).ready(function () {

		$('.athemes-addons-module-page-button-action-activate, .athemes-addons-module-page-button-action-deactivate').on('click', function (e) {
			var $button = $(this);
			var isActivate = $button.hasClass('athemes-addons-module-page-button-action-activate');
		
			$button.toggleClass('athemes-addons-module-page-button-action-activate athemes-addons-module-page-button-action-deactivate');
		
			setTimeout(() => {
				$button.find('.saved-label').css('opacity', '1');
				setTimeout(() => {
					$button.find('.saved-label').css('opacity', '0');
				}, 2000);
			}, 1000);
		
			var ajaxAction = isActivate ? 'athemes_addons_module_activate' : 'athemes_addons_module_deactivate';
		
			window.wp.ajax.post(ajaxAction, {
				module: $button.data('module'),
				nonce: window.athemes_addons_elementor.nonce
			}).done(function () {
				var isModuleEnabled = isActivate;
				$('body').toggleClass('athemes-addons-module-disabled', !isModuleEnabled).toggleClass('athemes-addons-module-enabled', isModuleEnabled);
				$('.athemes-addons-module-action').toggleClass('athemes-addons-enabled', isModuleEnabled);
				$('.athemes-addons-module-question-list-dropdown').toggleClass('athemes-addons-show', !isModuleEnabled);
			});
		});	


		// Notifications Sidebar
		var $notificationsSidebar = $('.athemes-addons-notifications-sidebar');
		if ($notificationsSidebar.length) {
			var $notifications = $('.athemes-addons-notifications');
			$notifications.on('click', function (e) {
				e.preventDefault();
				var $notification = $(this);
				var latestNotificationDate = $notificationsSidebar.find('.athemes-addons-notification:first-child .athemes-addons-notification-date').data('raw-date');
				$notificationsSidebar.toggleClass('opened');
				if (!$notification.hasClass('read')) {
					$.post(window.athemes_addons_elementor.ajax_url, {
						action: 'athemes_addons_notifications_read',
						nonce: window.athemes_addons_elementor.nonce,
						latest_notification_date: latestNotificationDate
					}, function (response) {
						if (response.success) {
							setTimeout(function () {
								$notification.addClass('read');
							}, 2000);
						}
					});
				}
			});
			$(window).on('scroll', function () {
				if (window.pageYOffset > 60) {
					$notificationsSidebar.addClass('closing');
					setTimeout(function () {
						$notificationsSidebar.removeClass('opened');
						$notificationsSidebar.removeClass('closing');
					}, 300);
				}
			});

			// Close Sidebar
			$('.athemes-addons-notifications-sidebar-close').on('click', function (e) {
				e.preventDefault();
				$notificationsSidebar.addClass('closing');
				setTimeout(function () {
					$notificationsSidebar.removeClass('opened');
					$notificationsSidebar.removeClass('closing');
				}, 300);
			});
		}

		// Tabs Navigation.
		var tabs = $('.athemes-addons-dashboard-tabs-nav');
		if (tabs.length) {
			var tabsNav = tabs.find('a');
			tabsNav.on('click', function (e) {
				e.preventDefault();
				var $tabContent = $(this).data('tab');

				$(this).addClass('active').siblings().removeClass('active');

				$('.athemes-addons-dashboard-tab-page').each(function () {
					if ($(this).attr('id') === $tabContent) {
						$(this).addClass('active');
					} else {
						$(this).removeClass('active');
					}
				});
			});
		}

		// Filters.
		var appliedFilters = {
			category: 'all',
			status: 'all',
			freePro: 'all'
		};

		// Category filter.
		var categoryFilter = $('.athemes-addons-modules-filter-button-category');
		categoryFilter.on('change', function (e) {
			var category = $(this).val();
			appliedFilters.category = category;
			filterModules();
		});

		// Status filter.
		var statusFilter = $('input[name="athemes-addons-status-filter"]');
		statusFilter.on('change', function (e) {
			var status = $(this).val();
			appliedFilters.status = status;
			filterModules();
		});

		// Free/pro filter.
		var freeProFilter = $('input[name="athemes-addons-free-pro-filter"]');
		freeProFilter.on('change', function (e) {
			var freePro = $(this).val();
			appliedFilters.freePro = freePro;
			filterModules();
		});

		// Function to filter modules based on applied filters
		function filterModules() {
			var $modules = $('.athemes-addons-modules-list-item');

			$modules.hide();

			$modules.each(function () {
				var module = $(this);
				var meetsCategoryFilter = (appliedFilters.category === 'all') || (module.data('category') === appliedFilters.category);
				var meetsStatusFilter = (appliedFilters.status === 'all') || (module.data('status') === appliedFilters.status);
				var meetsFreeProFilter = (appliedFilters.freePro === 'all') || (module.data('type') === appliedFilters.freePro);

				if (meetsCategoryFilter && meetsStatusFilter && meetsFreeProFilter) {
					module.fadeIn();
				}
			});
		}

		// Search.
		var searchInput = $('.athemes-addons-modules-search');
		searchInput.on('keyup', function (e) {
			var search = $(this).val().toLowerCase();
			var $modules = $('.athemes-addons-modules-list-item');

			$modules.hide();

			$modules.each(function () {
				var module = $(this);
				var title = module.data('title').toLowerCase();
				var description = module.data('keywords').toLowerCase();

				if (title.indexOf(search) > -1 || description.indexOf(search) > -1) {
					module.show();
				}
			});
		});

		// Settings page.
		$('.aafe-save-settings').on('click', function() {
			var fields = {};

			$(this).text(window.athemes_addons_elementor.saving);
	
			$('.athemes-addons-module-page-setting-fields input[type="text"]').each(function() {
				var fieldId = $(this).attr('name');
				var fieldValue = $(this).val();
				fields[fieldId] = fieldValue;
			});

			$('.athemes-addons-module-page-setting-field-multicheckbox').each(function() {
				var fieldId = $(this).find('input[type="hidden"]').attr('name');
				var fieldValue = $(this).find('input[type="hidden"]').val();
				fields[fieldId] = fieldValue;
			} );
	
			var data = {
				'action': 'aafe_save_settings',
				'nonce': window.athemes_addons_elementor.nonce,
				'fields': fields
			};
	
			$.ajax({
				type: 'POST',
				url: window.athemes_addons_elementor.ajax_url,
				data: data,
				success: function(response) {
					$('.aafe-save-settings').text(window.athemes_addons_elementor.saved);
					setTimeout(() => {
						$('.aafe-save-settings').text(window.athemes_addons_elementor.save);
					}, 2000);
				},
				error: function(error) {
					console.error(error);
				}
			});
		});	

		// Multichecbox control.
		$( '.athemes-addons-module-page-setting-field-multicheckbox' ).each(function () {
			var $control = $(this);
			var $checkboxes = $control.find( 'input[type="checkbox"]' );

			$checkboxes.on( 'change', function () {
				var values = [];

				$checkboxes.each( function () {
					if ( $( this ).is( ':checked' ) ) {
						values.push( $( this ).val() );
					}
				} );

				$control.find( 'input[type="hidden"]' ).val( values.join( ',' ) ).trigger( 'change' );
			} );
		} );
		
		// Display conditions.
		//Display conditions
		$(document).on('athemes-addons-display-conditions-select2-initalize', function (event, item) {
			var $item = $(item);
			var $control = $item.closest('.athemes-addons-display-conditions-control');
			var $typeSelectWrap = $item.find('.athemes-addons-display-conditions-select2-type');
			var $typeSelect = $typeSelectWrap.find('select');
			var $conditionSelectWrap = $item.find('.athemes-addons-display-conditions-select2-condition');
			var $conditionSelect = $conditionSelectWrap.find('select');
			var $idSelectWrap = $item.find('.athemes-addons-display-conditions-select2-id');
			var $idSelect = $idSelectWrap.find('select');
			$typeSelect.select2({
			  width: '100%',
			  minimumResultsForSearch: -1
			});
			$typeSelect.on('select2:select', function (event) {
			  $typeSelectWrap.attr('data-type', event.params.data.id);
			});
			$conditionSelect.select2({
			  width: '100%'
			});
			$conditionSelect.on('select2:select', function (event) {
			  var $element = $(event.params.data.element);
		
			  if ($element.data('ajax')) {
				$idSelectWrap.removeClass('hidden');
			  } else {
				$idSelectWrap.addClass('hidden');
			  }

			  $idSelect.val(null).trigger('change');
			});
			var isAjaxSelected = $conditionSelect.find(':selected').data('ajax');

			if (isAjaxSelected) {
			  $idSelectWrap.removeClass('hidden');
			}
		
			$idSelect.select2({
			  width: '100%',
			  placeholder: '',
			  allowClear: true,
			  minimumInputLength: 1,
			  ajax: {
				url: window.athemes_addons_elementor.ajax_url,
				dataType: 'json',
				delay: 250,
				cache: true,
				data: function data(params) {
				  return {
					action: 'athemes_addons_templates_display_conditions_select_ajax',
					term: params.term,
					nonce: window.athemes_addons_elementor.nonce,
					source: $conditionSelect.val()
				  };
				},
				processResults: function processResults(response, params) {

				  if (response.success) {
					return {
					  results: response.data
					};
				  }
		
				  return {};
				}
			  }
			});
		  });
		  $(document).on('click', '.athemes-addons-display-conditions-modal-toggle', function (event) {
			event.preventDefault();
			var $button = $(this);
			var template = wp.template('athemes-addons-display-conditions-template');
			var $control = $button.closest('.athemes-addons-display-conditions-control');
			var $modal = $control.find('.athemes-addons-display-conditions-modal');
		
			if (!$modal.data('initialized')) { 
			
			var $conditionSettings = $control.data('condition-settings');

			  $control.append(template($conditionSettings));
			  var $items = $control.find('.athemes-addons-display-conditions-modal-content-list-item').not('.hidden');
		
			  if ($items.length) {
				$items.each(function () {
				  $(document).trigger('athemes-addons-display-conditions-select2-initalize', this);
				});
			  }
		
			  $modal = $control.find('.athemes-addons-display-conditions-modal');
			  $modal.data('initialized', true);
			  $modal.addClass('open');
			} else {
			  $modal.toggleClass('open');
			}
		  });
		  $(document).on('click', '.athemes-addons-display-conditions-modal', function (event) {
			event.preventDefault();
			var $modal = $(this);
		
			if ($(event.target).is($modal)) {
			  $( '.athemes-addons-display-conditions-modal' ).removeClass('open');
			}
		  });
		  $(document).on('click', '.athemes-addons-display-conditions-modal-add', function (event) {
			event.preventDefault();
			var $button = $(this);
			var $control = $button.closest('.athemes-addons-display-conditions-control');
			var $modal = $control.find('.athemes-addons-display-conditions-modal');
			var $list = $modal.find('.athemes-addons-display-conditions-modal-content-list');
			var $item = $modal.find('.athemes-addons-display-conditions-modal-content-list-item').first().clone();
			var conditionGroup = $button.data('condition-group');
			$item.removeClass('hidden');
			$item.find('.athemes-addons-display-conditions-select2-condition').not('[data-condition-group="' + conditionGroup + '"]').remove();
			$list.append($item);
			$(document).trigger('athemes-addons-display-conditions-select2-initalize', $item);
		  });
		  $(document).on('click', '.athemes-addons-display-conditions-modal-remove', function (event) {
			event.preventDefault();
			var $item = $(this).closest('.athemes-addons-display-conditions-modal-content-list-item');
			$item.remove();
		  });
		  $(document).on('click', '.athemes-addons-display-conditions-modal-save', function (event) {
			event.preventDefault();
			var data = [];
			var $button = $(this);
			var $control = $button.closest('.athemes-addons-display-conditions-control');
			var $modal = $control.find('.athemes-addons-display-conditions-modal');
			var $textarea = $control.find('.athemes-addons-display-conditions-textarea');
			var $items = $modal.find('.athemes-addons-display-conditions-modal-content-list-item').not('.hidden');
			$items.each(function () {
			  var $item = $(this);
			  data.push({
				type: $item.find('select[name="type"]').val(),
				condition: $item.find('select[name="condition"]').val(),
				id: $item.find('select[name="id"]').val()
			  });
			});
			$textarea.val(JSON.stringify(data)).trigger('change');
			$(document).trigger('athemes-addons-display-conditions-update', $textarea);
		});	
		
		
		/**
		 * Theme builder.
		 */
		// Display conditions.
		$(document).on('athemes-addons-display-conditions-update', function (event, item) {
			var $item = $(item);
			var $conditions = $item.val();

			$.post(window.athemes_addons_elementor.ajax_url, {
				action: 'athemes_addons_update_template_conditions',
				nonce: window.athemes_addons_elementor.nonce,
				template_id: $item.parents( '.athemes-addons-tb-element' ).data('template-id'),
				conditions: $conditions
			}, function (response) {
				if (response.success) {
					console.log(response);
				}
			});
		});

		// Delete template.
		$(document).on('click', '.aafe-delete-template', function (e) {
			e.preventDefault();
			var $button = $(this);
			var templateId = $button.parents( '.athemes-addons-tb-element' ).data('template-id');

			$button.children('.dashicons').addClass( 'dashicons-update-alt' ).removeClass( 'dashicons-edit' ).css( 'animation', 'aafe-spin 2s infinite' );

			$.post(window.athemes_addons_elementor.ajax_url, {
				action: 'athemes_addons_delete_template',
				nonce: window.athemes_addons_elementor.nonce,
				template_id: templateId
			}, function (response) {
				if (response.success) {
					$button.closest('.athemes-addons-tb-element').fadeOut();
				}
			});
		});

		// Edit template.
		$(document).on('click', '.aafe-edit-template', function (e) {
			e.preventDefault();
			var $button = $(this);
			var templateId = $button.parents( '.athemes-addons-tb-element' ).data('template-id');

			$button.children('.dashicons').toggleClass( 'dashicons-edit dashicons-update-alt' ).css( 'animation', 'aafe-spin 2s infinite' );

			var elementorUrl = window.athemes_addons_elementor.admin_url + 'post.php?post=' + templateId + '&action=elementor';

			var iframe = $('#athemes-addons-elementor-iframe');

			iframe.siblings('.athemes-addons-iframe-loader').show();

			iframe.attr( 'src', elementorUrl );

			iframe.parent().addClass( 'open' );

			iframe.siblings('.athemes-addons-iframe-loader').fadeOut();

			iframe.on( 'load', function() {
				$button.children('.dashicons').addClass( 'dashicons-edit' ).removeClass( 'dashicons-update-alt' ).css( 'animation', 'none' );
			} );

		});

		// Create template.
		$(document).on('click', '.aafe-create-template', function (e) {
			e.preventDefault();
			var $button 		= $(this);
			var templateType 	= $button.data('template-type');
			var templateLabel 	= $button.data('template-label');
			var iframe 			= $('#athemes-addons-elementor-iframe');

			iframe.siblings('.athemes-addons-iframe-loader').show();

			$.post(window.athemes_addons_elementor.ajax_url, {
				action: 'athemes_addons_create_template',
				nonce: window.athemes_addons_elementor.nonce,
				template_type: templateType,
				template_label: templateLabel
			}, function (response) {
				if (response.success) {
					$button.children('.dashicons').toggleClass( 'dashicons-plus-alt2 dashicons-update-alt' ).css( 'animation', 'none' );

					var templateId = response.data.template_id;

					var elementorUrl = window.athemes_addons_elementor.admin_url + 'post.php?post=' + templateId + '&action=elementor';

					iframe.attr( 'src', elementorUrl );

					iframe.parent().addClass( 'open' );

					iframe.siblings('.athemes-addons-iframe-loader').fadeOut();

					iframe.on( 'load', function() {
						$.post(window.athemes_addons_elementor.ajax_url, {
							action: 'athemes_addons_get_templates',
							nonce: window.athemes_addons_elementor.nonce
						}, function (response) {
							if (response.success) {
								$('.athemes-addons-theme-builder-elements[data-type="templates"]').html(response.data);
							}
						});
					});
				}
			});
		});		

		// Close iframe.
		$(document).on('click', '.athemes-addons-close-modal', function (e) {
			e.preventDefault();
			$('#athemes-addons-elementor-iframe').parent().removeClass( 'open' );

			//remove src
			$('#athemes-addons-elementor-iframe').attr( 'src', '' );
		});

		// Header type
		$(document).on('change', '.aafe-header-type-select', function (e) {
			var $select = $(this);
			var headerType = $select.val();
			var templateId = $select.parents( '.athemes-addons-tb-element' ).data('template-id');

			$.post(window.athemes_addons_elementor.ajax_url, {
				action: 'athemes_addons_header_type',
				nonce: window.athemes_addons_elementor.nonce,
				template_id: templateId,
				header_type: headerType
			}, function (response) {
				if (response.success) {
					setTimeout(() => {
						$select.prev('.saved-label').css('opacity', '1');
						setTimeout(() => {
							$select.prev('.saved-label').css('opacity', '0');
						}, 2000);
					}, 500);
				}
			});
		} );

		// Upgrade plugin from theme builder.
		$(document).on('click', '.aafe-upgrade', function (e) {
			e.preventDefault();

			window.open('https://athemes.com/addons/', '_blank');
		} );
	});
})(jQuery, window, document);