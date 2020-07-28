<?php

namespace Altis\Consent;

use WP_CONSENT_API;

/**
 * Load the cookie consent banner if consent hasn't been saved previously.
 */
function load_consent_banner() {
	// Check if we need to load the banner.
	if ( ! consent_saved() ) {
		load_template( plugin_dir_path( __DIR__ ) . 'tmpl/consent-banner.php' );
	}
}

/**
 * Check if consent has already been saved on the client machine.
 *
 * @return bool Return true if consent has been given previously.
 */
function consent_saved() : bool {
	$categories = WP_CONSENT_API::$config->consent_categories();

	// If any of the
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