(function ($) {

	var aThemesAddonsEventsCalendar = function($scope, $) {

		const wrapper = $scope.find('.athemes-addons-events-calendar').eq(0);
		const calendarEl = wrapper.find( '#aafe-events-calendar' ).get(0);

		const initialView 	= $( calendarEl ).data( 'initial-view' ),
			apiKey 			= $( calendarEl ).data( 'api-key' ),
			calendarId 		= $( calendarEl ).data( 'calendar-id' ),
			time_format 	= $( calendarEl ).data('format-24') == 'yes' ? true : false,
			locale 			= $( calendarEl ).data('locale'),
			firstDay 		= $( calendarEl ).data('first-day');

		const calendar = new FullCalendar.Calendar( calendarEl, {
			initialView: initialView,
			editable: false,
			locale: locale,
			firstDay: firstDay,
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay'
			},		
			eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: !time_format
            },
			googleCalendarApiKey: apiKey,
			eventSources: [
				{
					googleCalendarId: calendarId,
				}
			],
			eventMouseEnter: function( info ) {

				if ( navigator.userAgent.indexOf('Firefox') > -1 && parseInt( navigator.userAgent.match(/Firefox\/([0-9]+)\./)[1] ) < 125 ) {
					return; // Firefox 125 and below doesn't support popovers
				}

				var eventEl = $( info.el );

				var id = 'popover-' + Math.floor( Math.random() * 10000 );

				var buttonEl = $( '<button>' ).addClass( eventEl.attr('class') ).html( eventEl.html() ).attr('popovertarget', id );
		
				eventEl.replaceWith( buttonEl );

				var popoverContent = createPopoverContent( info, id );

				if ( !buttonEl.next().is( '#' + id ) ) {
					buttonEl.after( popoverContent );
				}
				
			}		
		} );

		calendar.render()
	}

	function createPopoverContent( info, id ) {
		
		var popoverContent = $('<div class="event-modal" id="' + id + '" popover>'
			+ '<div class="modal-header">'
			+   '<h3>' + info.event.title + '</h3>'
			+   '<button class="close-modal" popovertarget="' + id + '" popovertargetaction="hide">X</button>'
			+ '</div>'
			+ '<div class="modal-body">'
			+ '<div class="modal-meta">'
			+   '<span class="event-time">📅 ' + info.event.start.toLocaleString() + ' - ' + info.event.end.toLocaleString() + '</span>'
			+   (info.event.extendedProps.location ? '<span class="event-location">🗺️ ' + info.event.extendedProps.location + '</span>' : '')
			+ '</div>'	
			+   (info.event.extendedProps.description ? '<p class="event-description">' + info.event.extendedProps.description + '</p>' : '')
			+ '</div>'
			+ '<div class="modal-footer">'
			+   '<a href="' + info.event.url + '" class="view-event">' + AAFESettings.view_event + ' &xrarr;</a>'
			+ '</div>'
		+ '</div>');
	
		return popoverContent;
	}

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-events-calendar.default', aThemesAddonsEventsCalendar );
	});

})(jQuery);