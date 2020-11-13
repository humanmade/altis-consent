<?php
/**
 * Helper functions for the Altis Consent module.
 *
 * @package altis/consent
 */

namespace Altis\Consent;

use Altis\Consent\Settings;
use WP_CONSENT_API;
use WP_Error;

/**
 * Load the cookie consent banner if consent hasn't been saved previously.
 */
function load_consent_banner() {
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

/**
 * Determine whether we should display the banner.
 *
 * Checks the `display_banner` setting, but also allows that to be hijacked.
 *
 * @return bool Whether the banner should be displayed.
 */
function should_display_banner() : bool {
	/**
	 * Allow the check whether to display the banner at all to be hijacked externally.
	 *
	 * @var bool Defaults to the option on the options page, but can be overridden externally based on other logic.
	 */
	return (bool) apply_filters( 'altis.consent.should_display_banner', Settings\get_consent_option( 'display_banner', false ) );
}

/**
 * Return the cookie policy page url.
 *
 * @return string The cookie policy page url.
 */
function get_cookie_policy_url() : string {
	$cookie_policy_page_url = '';
	$cookie_policy_page_id  = (int) Settings\get_consent_option( 'policy_page', 0 );

	// Make sure the cookie policy page ID is an actual page and it's public.
	if ( get_post_type( $cookie_policy_page_id ) === 'page' && get_post_status( $cookie_policy_page_id ) === 'publish' ) {
		$cookie_policy_page_url = get_page_uri( $cookie_policy_page_id );
	}

	/**
	 * Allow the cookie policy page url to be filtered.
	 *
	 * @var string The cookie policy page url.
	 */
	return apply_filters( 'altis.consent.cookie_policy_url', $cookie_policy_page_url );
}
