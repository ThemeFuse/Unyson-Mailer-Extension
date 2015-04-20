<?php if (!defined('FW')) die('Forbidden');

class FW_Ext_Mailer_Email {
	protected $from_name = '';
	protected $from = '';
	protected $to = array();
	protected $subject = '';
	protected $body = '';

	public function __construct() {
		$this->set_from_name(
			fw_ext('mailer')->get_db_settings_option('general/from_name')
		);
		$this->set_from(
			fw_ext('mailer')->get_db_settings_option('general/from_address')
		);
	}

	public function get_from_name() {
		return $this->from_name;
	}

	public function set_from_name($from_name) {
		$this->from_name = $from_name;
	}

	public function get_from() {
		return $this->from;
	}

	public function set_from($from) {
		$this->from = $from;
	}

	public function get_to() {
		return $this->to;
	}

	public function set_to($to) {
		if (!is_array($to)) {
			$to = explode(',', $to);
		}

		$this->to = $to;
	}

	public function get_subject() {
		return $this->subject;
	}

	public function set_subject($subject) {
		$this->subject = $subject;
	}

	public function get_body() {
		return $this->body;
	}

	public function set_body($body) {
		$this->body = $body;
	}
}
