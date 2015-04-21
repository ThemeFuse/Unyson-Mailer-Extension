<?php if (!defined('FW')) die('Forbidden');

class FW_Ext_Mailer_Send_Method_WPMail extends FW_Ext_Mailer_Send_Method {

	/**
	 * @return string
	 */
	public function get_id() {
		return 'wpmail';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return 'wp-mail';
	}

	/**
	 * @return array
	 */
	public function get_settings_options() {
		return array();
	}

	/**
	 * @param array $values
	 * @return array|WP_Error
	 */
	public function prepare_settings_options_values($values) {
		return array();
	}

	/**
	 * @param array $settings_options_values
	 * @param FW_Ext_Mailer_Email $email
	 * @param array $data
	 * @return bool|WP_Error
	 */
	public function send(FW_Ext_Mailer_Email $email, $settings_options_values, $data = array()) {
		$result = wp_mail(
			$email->get_to(),
			$email->get_subject(),
			$email->get_body(),
			array(
				'Content-type: text/html; charset=utf-8',
				'From:'. htmlspecialchars($email->get_from_name(), null, 'UTF-8')
				.' <'. htmlspecialchars($email->get_from(), null, 'UTF-8') .'>'
			)
		);

		return $result
			? true
			: new WP_Error(
				'failed',
				__('Could not send the email', 'fw')
			);
	}

}
