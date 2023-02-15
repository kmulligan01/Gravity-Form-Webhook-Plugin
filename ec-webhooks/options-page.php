<?php
/* 
 * Retrieve page values with:
 * $ec_webhooks_options = get_option( 'ec_webhooks_option_name' ); // Array of All Options
 * $endpoint_url_0 = $ec_webhooks_options['endpoint_url_0']; // Endpoint URL
 * $token_1 = $ec_webhooks_options['token_1']; // Token
 */

class ECWebhooks {
	private $ec_webhooks_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ec_webhooks_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ec_webhooks_page_init' ) );
	}

	public function ec_webhooks_add_plugin_page() {
		add_menu_page(
			'EC Webhooks', // page_title
			'EC Webhooks', // menu_title
			'manage_options', // capability
			'ec-webhooks', // menu_slug
			array( $this, 'ec_webhooks_create_admin_page' ), // function
			'dashicons-analytics', // icon_url
		);
	}

	public function ec_webhooks_create_admin_page() {
		$this->ec_webhooks_options = get_option( 'ec_webhooks_option_name' ); ?>

		<div class="wrap">
			<h2>EC Webhooks</h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'ec_webhooks_option_group' );
					do_settings_sections( 'ec-webhooks-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function ec_webhooks_page_init() {
		register_setting(
			'ec_webhooks_option_group', // option_group
			'ec_webhooks_option_name', // option_name
			array( $this, 'ec_webhooks_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'ec_webhooks_setting_section', // id
			'Settings', // title
			array( $this, 'ec_webhooks_section_info' ), // callback
			'ec-webhooks-admin' // page
		);

		add_settings_field(
			'endpoint_url_0', // id
			'Endpoint URL', // title
			array( $this, 'endpoint_url_0_callback' ), // callback
			'ec-webhooks-admin', // page
			'ec_webhooks_setting_section' // section
		);

		add_settings_field(
			'token_1', // id
			'Token', // title
			array( $this, 'token_1_callback' ), // callback
			'ec-webhooks-admin', // page
			'ec_webhooks_setting_section' // section
		);
	}

	public function ec_webhooks_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['endpoint_url_0'] ) ) {
			$sanitary_values['endpoint_url_0'] = sanitize_text_field( $input['endpoint_url_0'] );
		}

		if ( isset( $input['token_1'] ) ) {
			$sanitary_values['token_1'] = sanitize_text_field( $input['token_1'] );
		}

		return $sanitary_values;
	}

	public function ec_webhooks_section_info() {
		
	}

	public function endpoint_url_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="ec_webhooks_option_name[endpoint_url_0]" id="endpoint_url_0" value="%s">',
			isset( $this->ec_webhooks_options['endpoint_url_0'] ) ? esc_attr( $this->ec_webhooks_options['endpoint_url_0']) : ''
		);
	}

	public function token_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="ec_webhooks_option_name[token_1]" id="token_1" value="%s">',
			isset( $this->ec_webhooks_options['token_1'] ) ? esc_attr( $this->ec_webhooks_options['token_1']) : ''
		);
	}

}
if ( is_admin() )
	$ec_webhooks = new ECWebhooks();