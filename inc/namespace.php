<?php

namespace Altis\Consent;

use Altis;
use WP_CONSENT_API;

/**
 * Kick everything off.
 */
function bootstrap() {
	// Register this plugin with the consent API.
	add_filter( 'wp_consent_api_registered_' . plugin_basename( __FILE__ ), '__return_true' );

	// Default Altis consent type to "opt-in".
	add_filter( 'wp_get_consent_type', function() {
		return 'optin';
	} );

	// Enqueue the javascript handler.
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

	// Shortcode. Not recommended but here in case it's needed.
	add_shortcode( 'cookie-consent-banner', __NAMESPACE__ . '\\render_consent_banner' );
}

/**
 * Enqueue css and js.
 */
function enqueue_assets() {
	$js  = plugin_dir_url( __DIR__ ) . 'dist/js/main.js';
	$css = plugin_dir_url( __DIR__ ) . 'dist/css/styles.css';
	$ver = '1.0.0';

	if ( Altis\get_environment_type() === 'local' ) {
		// If working locally, load the unminified version of the js file.
		$js = plugin_dir_url( __DIR__ ) . 'assets/js/main.js';

		// Break the cache on local.
		$ver .= '-' . filemtime( $css );
	}

	wp_enqueue_script( 'altis-consent', $js, [], $ver, true );
	wp_enqueue_style( 'altis-consent', $css, [], $ver, 'screen' );

	wp_localize_script( 'altis-consent', 'altisConsent', [
		'categories' => WP_CONSENT_API::$config->consent_categories(),
		/**
		 * Allow the array of categories that are always consented to be filtered.
		 *
		 * @var array An array of default categories to consent to automatically.
		 */
		'alwaysAllowCategories' => apply_filters( 'altis.consent.always_allow_categories', [ 'functional', 'statistics-anonymous' ] ),
	] );
}

/**
 * Output the consent banner.
 *
 * Output here is returned rather than explicitly loaded in case it needs to be loaded into a variable.
 *
 * @return string The consent banner html.
 */
function render_consent_banner() : string {
	ob_start();
	load_consent_banner();
	return ob_get_clean();
}
