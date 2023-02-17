<?php
/* 
 * Retrieve page values with:
 * $ec_webhooks_options = get_option( 'rmc_webhooks_option_name' ); // Array of All Options
 * $endpoint_url_0 = $rmc_webhooks_options['endpoint_url_0']; // Endpoint URL
 * $token_1 = $rmc_webhooks_options['token_1']; // Token
 */

class RMCWebhooks {
	private $rmc_webhooks_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'rmc_webhooks_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'rmc_webhooks_page_init' ) );
	}

	public function rmc_webhooks_add_plugin_page() {
		add_menu_page(
			'RMC Webhooks', // page_title
			'RMC Webhooks', // menu_title
			'manage_options', // capability
			'rmc-webhooks', // menu_slug
			array( $this, 'rmc_webhooks_create_admin_page' ), // function
			'dashicons-analytics', // icon_url
		);
	}

	public function rmc_webhooks_create_admin_page() {
		$this->ec_webhooks_options = get_option( 'rmc_webhooks_option_name' ); ?>

		<div class="wrap">
			<h2>RMC Webhooks</h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'rmc_webhooks_option_group' );
					do_settings_sections( 'rmc-webhooks-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function rmc_webhooks_page_init() {
		register_setting(
			'rmc_webhooks_option_group', // option_group
			'rmc_webhooks_option_name', // option_name
			array( $this, 'rmc_webhooks_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'rmc_webhooks_setting_section', // id
			'Settings', // title
			array( $this, 'rmc_webhooks_section_info' ), // callback
			'rmc-webhooks-admin' // page
		);

		add_settings_field(
			'endpoint_url_0', // id
			'Endpoint URL', // title
			array( $this, 'endpoint_url_0_callback' ), // callback
			'rmc-webhooks-admin', // page
			'rmc_webhooks_setting_section' // section
		);

		add_settings_field(
			'token_1', // id
			'Token', // title
			array( $this, 'token_1_callback' ), // callback
			'rmc-webhooks-admin', // page
			'rmc_webhooks_setting_section' // section
		);
	}

	public function rmc_webhooks_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['endpoint_url_0'] ) ) {
			$sanitary_values['endpoint_url_0'] = sanitize_text_field( $input['endpoint_url_0'] );
		}

		if ( isset( $input['token_1'] ) ) {
			$sanitary_values['token_1'] = sanitize_text_field( $input['token_1'] );
		}

		return $sanitary_values;
	}

	public function rmc_webhooks_section_info() {
		
	}

	public function endpoint_url_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="rmc_webhooks_option_name[endpoint_url_0]" id="endpoint_url_0" value="%s">',
			isset( $this->rmc_webhooks_options['endpoint_url_0'] ) ? esc_attr( $this->rmc_webhooks_options['endpoint_url_0']) : ''
		);
	}

	public function token_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="rmc_webhooks_option_name[token_1]" id="token_1" value="%s">',
			isset( $this->rmc_webhooks_options['token_1'] ) ? esc_attr( $this->rmc_webhooks_options['token_1']) : ''
		);
	}

}
if ( is_admin() )
	$rmc_webhooks = new RMCWebhooks();