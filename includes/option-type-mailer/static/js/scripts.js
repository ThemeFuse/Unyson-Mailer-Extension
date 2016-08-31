"use strict";
(function($, fwe){
	fwe.on('fw:options:init', function (data) {
		data.$elements.find('.fw-option-type-mailer:not(.initialized)')
			.each(function(){
				var $option = $(this);

				$option.find('select[data-select-method]')
					.on('change', function(){
						$option.find('.fw-backend-options-group[data-method]').hide();
						$option.find('.fw-backend-options-group[data-method="'+ this.value +'"]').show();
					})
					.trigger('change');

				$option.on('click', '.test-connection button', function(){
					console.log('OK');
				});
			})
			.addClass('initialized');
	});
})(jQuery, fwEvents);