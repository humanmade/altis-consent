<?php
/**
 * Altis Consent
 *
 * The main namespace for the Altis Consent module.
 *
 * @package altis/consent
 */

namespace Altis\Consent;

use Altis;

/**
 * Kick everything off.
 */
function bootstrap() {
	// If the consent api doesn't exist, load the local composer autoload file which should include it.
	if ( ! defined( 'WP_CONSENT_API_URL' ) ) {
		trigger_error( 'The WP Consent Level API plugin must be installed and activated', E_USER_WARNING );
		return;
	}

	// Register this plugin with the consent API.
	add_filter( 'wp_consent_api_registered_' . plugin_basename( __FILE__ ), '__return_true' );

	// Default Altis consent type to "opt-in".
	add_filter( 'wp_get_consent_type', function() {
		return 'optin';
	} );

	// Define the consent types. This is filterable using altis.consent.types.
	add_filter( 'wp_consent_types', __NAMESPACE__ . '\\consent_types' );

	// Set the cookie prefix to the one we define. This is filterable using altis.consent.cookie_prefix.
	add_filter( 'wp_consent_cookie_prefix', __NAMESPACE__ . '\\cookie_prefix' );

	// Check the admin setting to determine if we need to load the banner html and js.
	if ( ! is_admin() && should_display_banner() ) {
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );
		add_action( 'wp_footer', __NAMESPACE__ . '\\load_consent_banner' );
	}
}

/**
 * Enqueue css and js.
 */
function enqueue_assets() {
	$js  = plugin_dir_url( __DIR__ ) . 'dist/js/main.js';
	$css = plugin_dir_url( __DIR__ ) . 'dist/css/styles.css';

	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$plugin = get_plugin_data( PLUGIN_FILE, false, false );
	$ver = $plugin['Version'];

	if ( Altis\get_environment_type() === 'local' ) {
		// If working locally, load the unminified version of the js file.
		$js = plugin_dir_url( __DIR__ ) . 'assets/js/main.js';

		// Break the cache on local.
		$ver .= '-' . filemtime( plugin_dir_path( __DIR__ ) . 'dist/css/styles.css' );
	}

	wp_enqueue_script( 'altis-consent', $js, [ 'altis-consent-api' ], $ver, true );
	wp_enqueue_style( 'altis-consent', $css, [], $ver, 'screen' );

	wp_localize_script( 'altis-consent', 'altisConsent', [
		/**
		 * Allow the array of categories that are always consented to be filtered.
		 *
		 * @var array An array of default categories to consent to automatically.
		 */
		'alwaysAllowCategories' => get_always_allowed_categories(),
		'cookiePrefix' => cookie_prefix(),
		'types' => consent_types(),
		'categories' => consent_categories(),
		'values' => consent_values(),
		'shouldDisplayBanner' => should_display_banner(),
	] );
}
