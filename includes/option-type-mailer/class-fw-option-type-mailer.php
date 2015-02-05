<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Option_Type_Mailer extends FW_Option_Type {

	/**
	 * @internal
	 */
	public function _init() {
	}

	public function get_type() {
		return 'mailer';
	}

	/**
	 * @internal
	 */
	public function _get_backend_width_type() {
		return 'full';
	}

	/**
	 * @internal
	 */
	protected function _get_defaults() {
		return array(
			'label'         => false,
			'type'          => 'multi',
			'inner-options' => array(
				'method'  => array(
					'label'   => __( 'Send Method', 'fw' ),
					'desc'    => __( 'Select the send form method', 'fw' ),
					'type'    => 'short-select',
					'attr'    => array(
						'data-select-method' => 'select-method'
					),
					'value'   => 'wpmail',
					'choices' => array(
						'wpmail' => 'wp-mail',
						'smtp'   => 'SMTP',
					)
				),
				'smtp'    => array(
					'type'    => 'group',
					'options' => array(
						'smtp' => array(
							'label'         => false,
							'desc'          => false,
							'type'          => 'multi',
							'attr'          => array(
								'data-method' => 'smtp'
							),
							'inner-options' => array(
								'host'     => array(
									'label' => __( 'Server Address', 'fw' ),
									'desc'  => __( 'Enter your email server', 'fw' ),
									'type'  => 'text',
									'value' => '',
								),
								'username' => array(
									'label' => __( 'Username', 'fw' ),
									'desc'  => __( 'Enter your username', 'fw' ),
									'type'  => 'text',
									'value' => '',
								),
								'password' => array(
									'label' => __( 'Password', 'fw' ),
									'desc'  => __( 'Enter your password', 'fw' ),
									'type'  => 'password',
									'value' => '',
								),
								'secure'   => array(
									'label'   => __( 'Secure Connection', 'fw' ),
									'type'    => 'radio',
									'inline'  => true,
									'value'   => 'no',
									'choices' => array(
										'no'  => 'No',
										'ssl' => 'SSL',
										'tls' => 'TLS'
									)
								),
								'port'     => array(
									'label' => __( 'Custom Port', 'fw' ),
									'desc'  => __( 'Optional - SMTP port number to use.', 'fw' ),
									'help'  => __( 'Leave blank for default (SMTP - 25, SMTPS - 465)', 'fw' ),
									'type'  => 'text',
									'attr'  => array(
										'maxlength' => 5,
									),
									'value' => '',
								),
							),
							'value'         => array()
						),
					)
				),
				'general' => array(
					'label'         => false,
					'desc'          => false,
					'type'          => 'multi',
					'inner-options' => array(
						'from-group' => array(
							'type' => 'group',
							'options' => array(
								'from_name'    => array(
									'label' => __( 'From Name', 'fw' ),
									'desc'  => __( "The name you'll see in the From filed in your email client.", 'fw' ),
									'type'  => 'text',
									'value' => '',
								),
							)
						),
						'from_address' => array(
							'label' => __( 'From Address', 'fw' ),
							'desc'  => __( 'The form will look like was sent from this email address.', 'fw' ),
							'type'  => 'text',
							'value' => '',
						)
					),
					'value'         => array()
				)
			),
			'value'         => array()
		);
	}

	/**
	 * @internal
	 * {@inheritdoc}
	 */
	protected function _enqueue_static( $id, $option, $data ) {
		wp_enqueue_style(
			$this->get_type() . '-scripts',
			fw_ext( 'mailer' )->get_declared_URI() . '/includes/option-type-mailer/static/css/style.css'
		);

		wp_enqueue_script(
			$this->get_type() . '-scripts',
			fw_ext( 'mailer' )->get_declared_URI() . '/includes/option-type-mailer/static/js/scripts.js',
			array( 'fw-events' ),
			fw()->manifest->get_version(), true
		);
	}

	/**
	 * @internal
	 */
	protected function _render( $id, $option, $data ) {
		$data['value'] = fw_ext( 'mailer' )->get_db_settings_option();

		return fw()->backend->option_type( 'multi' )->render( $id, $option, $data );
	}

	/**
	 * @internal
	 *
	 * @param array $option
	 * @param array|null|string $input_value
	 *
	 * @return array|bool|int|string
	 */
	protected function _get_value_from_input( $option, $input_value ) {

		if ( is_array( $input_value ) && ! empty( $input_value ) ) {
			fw_ext( 'mailer' )->set_db_settings_option( null, $input_value );
		}

		return fw_ext( 'mailer' )->get_db_settings_option();
	}
}

FW_Option_Type::register( 'FW_Option_Type_Mailer' );