<?php

namespace Altis\Consent;

use Altis\Consent\Settings;
use WP_CONSENT_API;
use WP_Error;

/**
 * Load the cookie consent banner if consent hasn't been saved previously.
 */
function load_consent_banner() {
	// Check if we need to load the banner.
	if ( ! consent_saved() ) {
		load_template(
			/**
			 * Allow other plugins or themes to update the path for the consent banner.
			 *
			 * If this path is changed, all the subsequent template parts that are used can be customized.
			 *
			 * @var string The path to the consent banner template.
			 */
			apply_filters( 'altis.consent.consent_banner_path', plugin_dir_path( __DIR__ ) . 'tmpl/consent-banner.php' )
		);
	}
}

/**
 * Check if consent has already been saved on the client machine.
 *
 * @return bool Return true if consent has been given previously.
 */
function consent_saved() : bool {
	$categories = WP_CONSENT_API::$config->consent_categories();

	// Loop through all of the categories.
	foreach ( $categories as $category ) {
		// Skip functional cookies preference.
		if ( $category === 'functional' ) {
			continue;
		}

		// If we have any consent cookies at all, consent has been saved, so just wait until these expire to ask again.
		if ( isset( $_COOKIE[ "wp_consent_$category" ] ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Return an array of all the categories that a user has consented to.
 *
 * @return array An array of allowed cookie categories.
 */
function get_consented_categories() : array {
	$has_consent = [];
	$categories  = WP_CONSENT_API::$config->consent_categories();

	/**
	 * Cookie categories that are always allowed.
	 *
	 * @var array Array of always-allowed cookie categories.
	 */
	$allowlist = apply_filters( 'altis.consent.allowlisted_categories', [ 'functional', 'statistics-anonymous' ] );

	foreach ( $categories as $category ) {
		if ( in_array( $category, $allowlist, true ) ) {
			$has_consent[] = $category;
			continue;
		}

		if ( wp_has_consent( $category ) ) {
			$has_consent[] = $category;
		}
	}

	return $has_consent;
}

/**
 * Register a cookie with the consent API. If required arguments are not passed, this function will return a WP_Error.
 *
 * Registering a cookie through this function, as opposed to directly through wp_add_cookie_info, ensures that all cookies expire at the same time, and consent is requested when those cookies expire.
 *
 * @param array $args An array of arguments to pass to wp_add_cookie_info.
 *  $args['name'] (string)                    (required) The name of the cookie.
 *  $args['plugin_or_service'] (string)       (required) Plugin or service that sets cookie (e.g. Google Maps).
 *  $args['category'] (string)                (required) One of 'functional', 'preferences', 'statistics-anonymous', 'statistics', or 'marketing'.
 *  $args['expires'] (string)                 (required) Time until the cookie expires.
 *  $args['function'] (string)                (required) What the cookie is meant to do (e.g. 'Store a unique User ID').
 *  $args['collected_personal_data'] (string) Type of personal data that is collected. Only needs to be filled in if `$is_personal_data` is `true`.
 *  $args['member_cookie'] (bool)             Whether the cookie is relevant for members of the site only.
 *  $args['administrator_cookie'] (bool)      Whether the cookie is relevant for administrators only.
 *  $args['type'] (string)                    One of 'HTTP', 'LOCALSTORAGE', or 'API'.
 *  $args['domain'] (string|bool)             Optional. Domain on which the cookie is set. Defaults to the current site URL.
 *
 * @return void|WP_Error                      Either no return or a WP_Error if a parameter was not passed to the function.
 */
function register_cookie( $args = [] ) {
	$cookie_expiration = Settings\get_consent_option( 'cookie_expiration', 30 );
	$required_args     = [ 'name', 'plugin_or_service', 'category', 'function' ];

	$cookie_info = wp_parse_args( $args, [
		'name'                    => false,
		'plugin_or_service'       => false,
		'category'                => false,
		'expires'                 => $cookie_expiration,
		'function'                => false,
		'collected_personal_data' => '',
		'member_cookie'           => false,
		'administrator_cookie'    => false,
		'type'                    => 'HTTP',
		'domain'                  => false,
	] );

	// Make sure we have all the required args.
	foreach ( $required_args as $required_arg ) {
		if ( ! $cookie_info[ $required_arg ] ) {
			// Translators: %s is the register_cookie parameter that was not passed.
			return new WP_Error( 'cookie_not_registered', esc_html( sprintf( __( 'Cookie not registered. The %s argument is required.', 'altis-consent' ), $required_arg ) ) );
		}
	}

	// Register the cookie with the consent API.
	wp_add_cookie_info( $cookie_info['name'], $cookie_info['plugin_or_service'], $cookie_info['category'], $cookie_info['expires'], $cookie_info['function'], $cookie_info['collected_personal_data'], $cookie_info['member_cookie'], $cookie_info['administrator_cookie'], $cookie_info['type'], $cookie_info['domain'] );
}
