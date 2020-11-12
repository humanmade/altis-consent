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
use WP_CONSENT_API;

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
		$ver .= '-' . filemtime( plugin_dir_path( __DIR__ ) . 'dist/css/styles.css' );
	}

	wp_enqueue_script( 'altis-consent', $js, [ 'altis-consent-api' ], $ver, true );
	wp_enqueue_style( 'altis-consent', $css, [], $ver, 'screen' );

	wp_localize_script( 'altis-consent', 'altisConsent', [
		'categories' => WP_CONSENT_API::$config->consent_categories(),
		/**
		 * Allow the array of categories that are always consented to be filtered.
		 *
		 * @var array An array of default categories to consent to automatically.
		 */
		'alwaysAllowCategories' => apply_filters( 'altis.consent.always_allow_categories', [ 'functional', 'statistics-anonymous' ] ),
		'cookiePrefix' => cookie_prefix(),
		'types' => consent_types(),
		'categories' => consent_categories(),
		'values' => consent_values(),
	] );
}

/**
 * Returns the default consent cookie prefix.
 *
 * @return string The consent cookie prefix.
 */
function cookie_prefix() : string {
	/**
	 * Filterable consent cookie prefix.
	 *
	 * @param string $prefix The consent cookie prefix.
	 */
	return apply_filters( 'altis.consent.cookie_prefix', 'altis_consent' );
}

/**
 * Returns the active consent types.
 *
 * @return array An array of consent types.
 */
function consent_types() : array {
	/**
	 * Filterable list of active consent types.
	 *
	 * @param array $consent_types The array of allowed consent types.
	 */
	return apply_filters(
		'altis.consent.types', [
			'optin',
			'optout',
		]
	);
}

/**
 * Returns the active consent categories.
 *
 * @return array The consent categories.
 */
function consent_categories() : array {
	/**
	 * Filterable list of active consent categories.
	 *
	 * @param array $categories The allowed consent categories.
	 */
	return apply_filters( 'altis.consent.categories', [
		'functional',
		'preferences',
		'statistics',
		'statistics-anonymous',
		'marketing',
	] );
}

/**
 * Returns the active consent values.
 *
 * @return array The available consent values.
 */
function consent_values() : array {
	/**
	 * Filterable list of possible consent values.
	 *
	 * @param array $values The possible consent values.
	 */
	return apply_filters( 'altis.consent.values', [
		'allow',
		'deny',
	] );
}

/**
 * Validates a consent item (either a consent type, category or value).
 *
 * @param string $item The value to validate.
 * @param string $item_type The type of value to validate. Possible options are 'types' (consent types), 'categories' (consent categories) or 'values' (consent values).
 * @return string|bool The validated string or false.
 */
function validate_consent_item( string $item, string $item_type ) {
	if ( ! in_array( $item_type, [ 'types', 'categories', 'values' ], true ) ) {
		return false; // actually trigger an error here.
	}

	// Use a variable function name to check the matching item type.
	$haystack = 'consent_' . $item_type;
	if ( in_array( $item, $haystack(), true ) ) {
		return $item;
	}

	return false;
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
