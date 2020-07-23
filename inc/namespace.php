<?php

namespace Altis\Consent;

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
	add_shortcode( 'cookie-consent-banner', __NAMESPACE__ . '\\render_consent_banner' );
}

function enqueue_assets() {
	wp_enqueue_script( 'altis-consent', plugin_dir_url( __DIR__ ) . 'assets/js/main.js', [ 'jquery' ], '0.0.1', true );
	wp_enqueue_style( 'altis-consent', plugin_dir_url( __DIR__ ) . 'assets/css/styles.css', [], '0.0.1-' . time(), 'screen' );
}

function render_consent_banner() : string {
	ob_start();

	load_template( plugin_dir_path( __DIR__ ) . 'tmpl/consent-banner.php' );

	return ob_get_clean();
}
