<?php if (!defined('FW')) die('Forbidden');

function _action_fw_ext_mailer_option_types_init() {
	require_once dirname(__FILE__) . '/includes/option-type-mailer/class-fw-option-type-mailer.php';
}
add_action('fw_option_types_init', '_action_fw_ext_mailer_option_types_init');

function _filter_fw_ext_mailer_default_send_methods($send_methods) {
	require_once dirname(__FILE__) . '/includes/send-methods/class-fw-ext-mailer-send-method-wpmail.php';
	$send_methods[] = new FW_Ext_Mailer_Send_Method_WPMail;

	require_once dirname(__FILE__) . '/includes/send-methods/class-fw-ext-mailer-send-method-smtp.php';
	$send_methods[] = new FW_Ext_Mailer_Send_Method_SMTP;

	return $send_methods;
}
add_filter('fw_ext_mailer_send_methods', '_filter_fw_ext_mailer_default_send_methods');
