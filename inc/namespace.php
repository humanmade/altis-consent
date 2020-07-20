<?php

namespace Altis\Consent;

use WP_CONSENT_API;

function bootstrap() {
	/**
	 * Tell the consent API we're following the api
	 */
	add_filter( 'wp_consent_api_registered_' . plugin_basename( __FILE__ ), '__return_true' );

	// Default Altis consent type to "opt-in".
	add_filter( 'wp_get_consent_type', function() {
		return 'optin';
	} );

	// Enqueue the javascript handler.
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

	// Shortcode. Replace with an actual way to display a banner.
	add_shortcode( 'cookie-consent-banner', __NAMESPACE__ . '\\banner_shortcode' );
}

function enqueue_assets() {
	wp_enqueue_script( 'altis-consent', plugin_dir_url( __DIR__ ) . 'assets/js/main.js', [ 'jquery' ], '0.0.1', true );
}

function banner_shortcode() : string {
	$options = get_option( 'cookie_consent_options' );
	$consent_policy = $options['policy_page'] ?: false;
	$button = '<div class="button-row">';
	$categories = WP_CONSENT_API::$config->consent_categories();
	ob_start();

	switch ( $options['banner_options'] ) {
		case '' :
			// Do some error if no options were set.
			$button = sprintf(
				__( 'No consent option has been set. Please visit the <a href="%s">Privacy Settings page</a> and set the consent banner option.', 'altis-consent.' ),
				admin_url( 'options-general.php?page=altis_privacy' )
			);
			break;
		case 'none' :
			$button  = '<button class="give-consent">';
			$button .= apply_filters( 'altis.consent.accept_all_cookies_button_text', esc_html__( 'Accept all cookies', 'altis-consent' ) );
			$button .= '</button>';
			$button .= '<button class="revoke-consent">';
			$button .= apply_filters( 'altis.consent.accept_only_functional_cookies_button_text', esc_html__( 'Accept only functional cookies', 'altis-consent' ) );
			$button .= '</button>';
			break;
		case 'all-categories' :
			var_dump( $categories );
			break;
	}

	$button .= '</div>';

	// Come back to this if we're doing fuller consent preferences.
	$category = 'marketing';
	if (function_exists('wp_validate_consent_category')){
		$category = wp_validate_consent_category($atts['category']);
    }

	?>
	<div id="example-plugin-content" data-consentcategory="<?php echo $category?>">
		<div class="functional-content">
			<h3>No consent has been given yet for category <?php echo $category?>. </h3>
		</div>
		<div class="marketing-content" style="display:none">
			<h3>Woohoo! consent has been given for category <?php echo $category?> :)</h3>
		</div>

		<?php echo wp_kses_post( $button ); ?>

	</div>

	<?php
	return ob_get_clean();
}
