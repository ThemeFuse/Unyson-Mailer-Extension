<?php if (!defined('FW')) die('Forbidden');

class FW_Ext_Mailer_Email {
	protected $from_name = '';
	protected $from = '';
	protected $subject = '';
	protected $body = '';

	// 'john@smith.com' => 'John Smith'
	protected $to = array();
	protected $cc = array();
	protected $bcc = array();

	/**
	 * @var string|array array('email' => 'Name')
	 * @since 1.2.4
	 */
	protected $reply_to = '';

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

	/**
	 * @return string|array
	 * @since 1.2.4
	 */
	public function get_reply_to() {
		return $this->reply_to;
	}

	/**
	 * @param string|array $reply_to
	 * @since 1.2.4
	 */
	public function set_reply_to($reply_to) {
		$this->reply_to = $reply_to;
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @since 1.2.10
	 */
	public function add_cc($email, $name = '') {
		$this->cc[ $email ] = $name;
	}

	/**
	 * @return array
	 * @since 1.2.10
	 */
	public function get_cc() {
		return $this->cc;
	}

	/**
	 * @param string $email
	 * @param string $name
	 * @since 1.2.10
	 */
	public function add_bcc($email, $name = '') {
		$this->bcc[ $email ] = $name;
	}

	/**
	 * @return array
	 * @since 1.2.10
	 */
	public function get_bcc() {
		return $this->bcc;
	}
}
