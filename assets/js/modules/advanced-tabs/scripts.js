(function ($) {

	var aThemesAdvancedTabs = function ($scope, $) {
		var $tabs = $scope.find('.athemes-addons-tabs').eq(0);

		// Get all the tab elements
		const tabTitles = $tabs.find('.athemes-tab-title');
	
		// Get all the tab content elements
		const tabContents = $tabs.find('.athemes-tab-content');
	
		// Loop through each tab and add event listener
		tabTitles.each((index, tabTitle) => {
			$(tabTitle).on('click', () => {
				// Remove the 'active-tab' class from all tab titles except the clicked one
				tabTitles.not(tabTitle).removeClass('active-tab').attr('aria-selected', 'false');
	
				// Add the 'active-tab' class to the clicked tab title
				$(tabTitle).addClass('active-tab').attr('aria-selected', 'true');
	
				// Hide all tab contents and remove the 'active-tab' class
				tabContents.attr('hidden', 'hidden').removeClass('active-tab');
	
				// Show the corresponding tab content based on the aria-controls attribute
				const tabId = $(tabTitle).attr('aria-controls');
				const tabContent = $tabs.find(`#${tabId}`);
				if (tabContent.length) {
					tabContent.removeAttr('hidden').addClass('active-tab');
				}
			});
		});
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-advanced-tabs.default', aThemesAdvancedTabs );
	});

})(jQuery);