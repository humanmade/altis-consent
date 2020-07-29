<?php

namespace Altis\Consent;

use WP_CONSENT_API;

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
