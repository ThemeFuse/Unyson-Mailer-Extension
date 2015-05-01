<?php if (!defined('FW')) die('Forbidden');

class FW_Extension_Mailer extends FW_Extension
{
	private $is_configured_cache = null;

	/**
	 * @var FW_Ext_Mailer_Send_Method[]
	 */
	private $send_methods;

	/**
	 * @internal
	 */
	protected function _init()
	{
		if (is_admin()) {
			add_action(
				'fw_extension_settings_form_render:'. $this->get_name(),
				array($this, '_action_extension_settings_form_render')
			);
		}
	}

	/**
	 * @internal
	 */
	public function _action_extension_settings_form_render()
	{
		wp_enqueue_script(
			'fw_option_email_settings',
			$this->get_uri('/static/js/scripts.js'),
			array('jquery'),
			false,
			true
		);
	}

	public function send($to, $subject, $message, $data = array())
	{
		$send_method = $this->get_send_method(
			$this->get_db_settings_option('method')
		);

		if (!$send_method) {
			return array(
				'status'  => 0,
				'message' => __('Invalid send method', 'fw')
			);
		}

		if (is_wp_error(
			$send_method_configuration = $send_method->prepare_settings_options_values(
				$this->get_db_settings_option($send_method->get_id())
			)
		)) {
			return array(
				'status'  => 0,
				'message' => $send_method_configuration->get_error_message()
			);
		}

		$email = new FW_Ext_Mailer_Email();
		$email->set_to($to);
		$email->set_subject($subject);
		$email->set_body($message);

		$result = $send_method->send(
			$email,
			$this->get_db_settings_option($send_method->get_id()),
			$data
		);

		return is_wp_error($result)
			? array(
				'status'  => 0,
				'message' => $result->get_error_message()
			)
			: array(
				'status'  => 1,
				'message' => __('The message has been successfully sent!', 'fw')
			);
	}

	/**
	 * Check if extension settings options are valid
	 * @return bool
	 */
	public function is_configured()
	{
		if (is_null($this->is_configured_cache)) {
			$send_method = $this->get_send_method(
				$this->get_db_settings_option('method')
			);

			if ($send_method) {
				$this->is_configured_cache = !is_wp_error(
					$send_method->prepare_settings_options_values(
						$this->get_db_settings_option($send_method->get_id())
					)
				);
			} else {
				$this->is_configured_cache = false;
			}
		}

		return $this->is_configured_cache;
	}

	public function get_send_methods()
	{
		if (empty($this->send_methods)) {
			require_once dirname(__FILE__) . '/includes/classes/class-fw-ext-mailer-email.php';
			require_once dirname(__FILE__) . '/includes/classes/class-fw-ext-mailer-send-method.php';

			$this->send_methods = array();
			foreach (apply_filters('fw_ext_mailer_send_methods', array()) as $send_method) {
				$this->send_methods[ $send_method->get_id() ] = $send_method;
			}
		}

		return $this->send_methods;
	}

	public function get_send_method($method_id)
	{
		$this->get_send_methods(); // init cache

		if (isset($this->send_methods[$method_id])) {
			return $this->send_methods[$method_id];
		} else {
			return null;
		}
	}
}
