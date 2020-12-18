<?php
/**
 * Helper functions for the Altis Consent module.
 *
 * @package altis/consent
 */

namespace Altis\Consent;

use Altis\Consent\Settings;

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
	return apply_filters( 'altis.consent.cookie_prefix', '_altis_consent' );
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
		// Trigger an error if an invalid item type was passed.
		trigger_error( esc_html( sprintf( 'The item type, %s, is not a valid item type to send to validate_consent_item. The item type must be \'types\', \'categories\' or \'values\'.', $item_type ) ), E_USER_WARNING );
		return false;
	}

	// Use a variable function name to check the matching item type.
	$haystack = __NAMESPACE__ . '\\consent_' . $item_type;
	if ( in_array( $item, $haystack(), true ) ) {
		return $item;
	}

	// Trigger an error if the item doesn't validate.
	trigger_error( esc_html( sprintf( 'The item %1$s was not a valid item of type %2$s.', $item, $item_type ) ), E_USER_WARNING );
	return false;
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
		$cookie_policy_page_url = get_page_link( $cookie_policy_page_id );
	}

	/**
	 * Allow the cookie policy page url to be filtered.
	 *
	 * @var string The cookie policy page url.
	 */
	return apply_filters( 'altis.consent.cookie_policy_url', $cookie_policy_page_url );
}
