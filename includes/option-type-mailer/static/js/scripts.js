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
					var $to = $option.find('.test-connection input[type="email"]:first'),
						to = $.trim($to.val());

					if (!to.length) {
						return $to.focus();
					}

					var $button = $(this).attr('disabled', 'disabled');

					{ // <input name="{prefix}...">
						var namePrefix = $option.find('.test-connection-wrapper input:first').attr('name');

						namePrefix = namePrefix.split('][');
						namePrefix.pop();
						namePrefix.pop();
						namePrefix = namePrefix.join('][');
						namePrefix += ']';
					}

					var vars = [
						{name: 'action', value: 'fw_ext_mailer_test_connection'},
						{name: 'to', value: to}
					];

					$.each($option.find('[name^="'+ namePrefix +'"]').serializeArray(), function(i, v) {
						v.name = v.name.split(namePrefix);
						v.name.shift();
						v.name = v.name.join(namePrefix);
						v.name = 'settings'+ v.name;

						vars.push(v);
					});

					$.ajax({
						url: ajaxurl,
						data: vars,
						method: 'post',
						dataType: 'json'
					}).done(function (r) {
						if (r.success) {
							fw.soleModal.show(
								'fw-option-mailer',
								'<span style="font-size: 7em;">&#10004;</span>',
								{}
							);
						} else {
							try {
								alert(r.data[0].message);
							} catch (e) {
								alert('Request failed');
							}
						}
					}).fail(function (jqXHR, textStatus, errorThrown) {
						alert('AJAX error: '+ String(errorThrown));
					}).always(function () {
						setTimeout(function () { // prevent user to click too often
							$button.removeAttr('disabled');
						}, 3000);
					});
				});
			})
			.addClass('initialized');
	});
})(jQuery, fwEvents);