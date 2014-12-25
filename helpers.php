<?php if (!defined('FW')) die('Forbidden');

function fw_ext_mailer_send_mail($to, $subject, $message) {
	return fw()->extensions->get('mailer')->send($to, $subject, $message);
}

function fw_ext_mailer_is_configured() {
	return fw()->extensions->get('mailer')->is_configured();
}
