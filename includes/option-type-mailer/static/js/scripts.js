"use strict";
(function($, _, fwe){
	fwe.on('fw:options:init', function (data) {

		var $methodSelect = $('.fw-option-type-mailer select[data-select-method=select-method]'),
			$smtpGroup = $('.fw-option-type-mailer div[data-method=smtp]');

		$methodSelect.on('change', function() {
			if ('smtp' === this.value) {
				$smtpGroup.show();
			} else {
				$smtpGroup.hide();
			}
		}).trigger('change');
	});
})(jQuery, _, fwEvents);