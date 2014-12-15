(function($) {

	var $methodSelect = $('#fw-option-method'),
		$smtpGroup = $('#fw-backend-options-group-smtp');

	$methodSelect.on('change', function() {
		if ('smtp' === this.value) {
			$smtpGroup.show();
		} else {
			$smtpGroup.hide();
		}
	}).trigger('change');

})(jQuery);
