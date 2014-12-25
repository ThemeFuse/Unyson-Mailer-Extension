<?php if (!defined('FW')) die('Forbidden');

class FW_Extension_Mailer extends FW_Extension
{
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
			$this->get_declared_URI('/static/js/scripts.js'),
			array('jquery'),
			false,
			true
		);
	}

	public function send($to, $subject, $message)
	{
		$sender = new FW_Ext_Mailer_Sender(
			$this->get_db_settings_option()
		);

		return $sender->send($to, $subject, $message);
	}

	/**
	 * Check if extension settings options are valid
	 * @return bool
	 */
	public function is_configured()
	{
		$sender = new FW_Ext_Mailer_Sender(
			$this->get_db_settings_option()
		);

		return (bool)$sender->get_prepared_config();
	}
}
