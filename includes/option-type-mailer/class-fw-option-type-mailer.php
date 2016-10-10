<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Option_Type_Mailer extends FW_Option_Type {

	/**
	 * @internal
	 */
	public function _init() {
		add_action('wp_ajax_fw_ext_mailer_test_connection', array($this, '_action_ajax_test_connection'));
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
			'label' => false,
			'value' => array(),
			'fw-storage' => array(
				'type' => 'wp-option',
				'wp-option' => 'fw_ext_settings_options:mailer',
			),
		);
	}

	private function get_inner_options() {
		$methods_choices = array();
		$methods_options = array();

		foreach (fw_ext('mailer')->get_send_methods() as $method) {
			/**
			 * @var FW_Ext_Mailer_Send_Method $method
			 */

			$methods_choices[ $method->get_id() ] = $method->get_title();

			$settings_options = $method->get_settings_options();

			if (!empty($settings_options)) {
				$methods_options['method-' . $method->get_id()] = array(
					'type' => 'group',
					'attr' => array(
						'data-method' => $method->get_id()
					),
					'options' => array(
						$method->get_id() => array(
							'label' => false,
							'desc' => false,
							'type' => 'multi',
							'inner-options' => $settings_options,
						),
					)
				);
			}
		}
		unset($settings_options);

		return array(
			'method'  => array(
				'label'   => __( 'Send Method', 'fw' ),
				'desc'    => __( 'Select the send form method', 'fw' ),
				'type'    => 'short-select',
				'attr'    => array(
					'data-select-method' => '~'
				),
				'choices' => $methods_choices
			),
			$methods_options,
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
			),
			'test-connection' => array(
				'label'         => false,
				'desc'          => false,
				'type'          => 'multi',
				'inner-options' => array(
					'test-connection' => array(
						'type' => 'html-fixed',
						'attr' => array(
							'class' => 'test-connection-wrapper'
						),
						'html' =>
							'<div class="test-connection">'.
							/**/'<div>'.
							/**//**/'<div>'.
							/**//**//**/'<input type="email" placeholder="'. esc_attr__('Test email destination', 'fw') .'" style="width:100%;">'.
							/**//**/'</div>'.
							/**//**/'<div>'.
							/**//**//**/'<button class="button" type="button">'. esc_html__('Send a test email', 'fw') .'</button>'.
							/**//**/'</div>'.
							/**/'</div>'.
							'</div>'
					),
				),
			),
		);
	}

	/**
	 * @internal
	 * {@inheritdoc}
	 */
	protected function _enqueue_static( $id, $option, $data ) {
		wp_enqueue_style(
			$this->get_type() . '-scripts',
			fw_ext( 'mailer' )->get_uri() . '/includes/option-type-mailer/static/css/style.css'
		);

		wp_enqueue_script(
			$this->get_type() . '-scripts',
			fw_ext( 'mailer' )->get_uri() . '/includes/option-type-mailer/static/js/scripts.js',
			array( 'fw-events' ),
			fw()->manifest->get_version(), true
		);
	}

	/**
	 * @internal
	 */
	protected function _render( $id, $option, $data ) {
		if (empty($data['value'])) {
			$data['value'] = fw_db_option_storage_load($id, $option, $data['value']);
		}

		$wrapper_attr = $option['attr'];
		unset($wrapper_attr['name'], $wrapper_attr['value']);

		return
		'<div '. fw_attr_to_html($wrapper_attr) .'>'.
			fw()->backend->option_type( 'multi' )->render( $id, array(
				'inner-options' => $this->get_inner_options(),
			), $data ) .
		'</div>';
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
		return (is_array( $input_value ) && ! empty( $input_value ))
			? fw_get_options_values_from_input($this->get_inner_options(), $input_value)
			: $option['value'];
	}

	/**
	 * @internal
	 */
	public function _action_ajax_test_connection() {
		if (!current_user_can('edit_posts')) {
			return wp_send_json_error(new WP_Error('forbidden', __('Forbidden', 'fw')));
		} elseif (!is_email($to = FW_Request::POST('to'))) {
			return wp_send_json_error(new WP_Error('forbidden', __('Invalid email', 'fw')));
		} elseif (!is_array($settings = FW_Request::POST('settings'))) {
			return wp_send_json_error(new WP_Error('forbidden', __('Invalid settings', 'fw')));
		}

		/** @var FW_Extension_Mailer $ext */
		$ext = fw_ext('mailer');

		$result = $ext->send(
			$to,
			__('Test Subject', 'fw'),
			'<strong>'. __('Test Message', 'fw') .'</strong>',
			array(),
			fw_get_options_values_from_input($this->get_inner_options(), $settings)
		);

		if ($result['status']) {
			wp_send_json_success();
		} else {
			wp_send_json_error(new WP_Error('fail', $result['message']));
		}
	}
}

FW_Option_Type::register( 'FW_Option_Type_Mailer' );
