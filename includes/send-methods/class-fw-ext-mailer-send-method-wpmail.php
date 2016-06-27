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
		{
			$headers = array();

			$headers[] = 'Content-type: text/html; charset=utf-8';

			if (trim($email->get_from())) {
				$headers[] = 'From:'
					. (trim($email->get_from_name()) ? ' '. htmlspecialchars($email->get_from_name(), null, 'UTF-8') : '')
					.' <'. htmlspecialchars($email->get_from(), null, 'UTF-8') .'>';
			}

			if (method_exists($email, 'get_reply_to') && $email->get_reply_to()) {
				if (is_array($email->get_reply_to())) {
					foreach ($email->get_reply_to() as $to_address => $to_name) {
						$headers[] = 'Reply-To: '. htmlspecialchars($to_name, null, 'UTF-8')
						             .' <'. htmlspecialchars($to_address, null, 'UTF-8') .'>';
					}
				} else {
					$headers[] = 'Reply-To: '. htmlspecialchars($email->get_reply_to(), null, 'UTF-8');
				}
			}
		}

		$result = wp_mail(
			$email->get_to(),
			$email->get_subject(),
			$email->get_body(),
			$headers
		);

		return $result
			? true
			: new WP_Error(
				'failed',
				__('Could not send the email', 'fw')
			);
	}

}
