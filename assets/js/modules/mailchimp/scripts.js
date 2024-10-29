(function ($) {

	var aThemesAddonsMailchimp = function ($scope, $) {
		var $mailchimp = $('.aafe-mailchimp-form-wrapper', $scope),
		$mailchimp_id = $mailchimp.data('mc-id') !== undefined ? $mailchimp.data('mc-id') : '',
		$button_text = $mailchimp.data('button-text') !== undefined ? $mailchimp.data('button-text') : '',
		$success_text = $mailchimp.data('success') !== undefined ? $mailchimp.data('success') : '',
		$loading_text = $mailchimp.data('loading') !== undefined ? $mailchimp.data('loading') : '';
		$list_id = $mailchimp.data('list-id') !== undefined ? $mailchimp.data('list-id') : '';
	
		$('#aafe-mailchimp-form-' + $mailchimp_id, $scope).on('submit', function (e) {
			e.preventDefault();
	
			var _this = $(this);
	
			$('.aafe-mc-response', _this).css('display', 'none').html('');
			$('.aafe-mailchimp-subscribe', _this).addClass('button--loading');
			$('.aafe-mailchimp-subscribe span', _this).html($loading_text);
	
			$.ajax({
				url: AAFESettings.ajaxurl,
				type: 'POST',
				data: {
				  action: 'aafe_mailchimp_subscribe',
				  nonce: AAFESettings.nonce_mailchimp,
				  fields: _this.serialize(),
				  listId: $list_id
				},
				success: function success(data) {
					console.log( _this.serialize() );
				  if (data.status == 'subscribed') {
					$('input[type=text], input[type=email], textarea', _this).val('');
					$('.aafe-mc-response', _this).css('display', 'block').html('<p>' + $success_text + '</p>');
				  } else {
					$('.aafe-mc-response', _this).css('display', 'block').html('<p>' + data.status + '</p>');
				  }
				  $('.aafe-mailchimp-subscribe', _this).removeClass('button--loading');
				  $('.aafe-mailchimp-subscribe span', _this).html($button_text);
				}
			});			
		});
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/athemes-addons-mailchimp.default', aThemesAddonsMailchimp );
	});

})(jQuery);