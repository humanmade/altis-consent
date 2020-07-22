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
	wp_enqueue_style( 'altis-consent', plugin_dir_url( __DIR__ ) . 'assets/css/styles.css', [], '0.0.1-' . time(), 'screen' );
}

function banner_shortcode() : string {
	$options = get_option( 'cookie_consent_options' );
	$consent_policy = $options['policy_page'] ?: false;
	$button_wrap = '<div class="button-row">';
	$buttons  = '<button class="give-consent">';
	$buttons .= apply_filters( 'altis.consent.accept_all_cookies_button_text', esc_html__( 'Accept all cookies', 'altis-consent' ) );
	$buttons .= '</button>';
	$buttons .= '<button class="revoke-consent">';
	$buttons .= apply_filters( 'altis.consent.accept_only_functional_cookies_button_text', esc_html__( 'Accept only functional cookies', 'altis-consent' ) );
	$buttons .= '</button>';
	ob_start();

	switch ( $options['banner_options'] ) {
		case '' :
			// Do some error if no options were set.
			$button_wrap .= sprintf(
				__( 'No consent option has been set. Please visit the <a href="%s">Privacy Settings page</a> and set the consent banner option.', 'altis-consent.' ),
				admin_url( 'options-general.php?page=altis_privacy' )
			);
			break;
		case 'none' :
			$button_wrap .= $buttons;
			break;
		case 'all-categories' :
			$categories = WP_CONSENT_API::$config->consent_categories();
			$buttons .= '<button class="view-preferences">';
			$buttons .= apply_filters( 'altis.consent.cookie_preferences_button_text', esc_html__( 'Cookie preferences', 'altis-consent' ) );
			$buttons .= '</button>';
			$buttons .= '<div class="cookie-preferences">';
			foreach ( $categories as $category ) {
				// Validate the consent category.
				if ( ! wp_validate_consent_category( $category ) ) {
					continue;
				}

				// Skip anonymous statistics category, don't need to ask permission explicitly.
				if ( 'statistics-anonymous' === $category ) {
					continue;
				}

				$buttons .= '<label for="cookie-preference-' . esc_attr( $category ) . '">';
				$buttons .= '<input type="checkbox" name="cookie-preferences[' . esc_attr( $category ) . ']" value="' . esc_attr( $category ) . '"';
				if ( 'functional' === $category ) {
					$buttons .= ' checked="checked" disabled="disabled" ';
				}
				$buttons .= '/>';
				$buttons .= ucfirst( esc_attr( $category ) );
				$buttons .= '</label>';
			}
			$buttons .= '<button class="apply-cookie-preferences">';
			$buttons .= apply_filters( 'altis.consent.apply_cookie_preferences_button_text', esc_html__( 'Apply Changes', 'altis-consent' ) );
			$buttons .= '</button>';
			$buttons .= '</div>';
			$button_wrap .= $buttons;
			break;
	}

	$button_wrap .= '</div>';

	?>
	<div id="cookie-consent-banner" data-consentcategory="all" class="consent-banner">
		<div class="functional-content">
			<h3>No consent has been given yet for category <?php echo $category?>. </h3>
		</div>
		<div class="marketing-content" style="display:none">
			<h3>Woohoo! consent has been given for category <?php echo $category?> :)</h3>
		</div>

		<?php echo wp_kses_post( $button ); ?>

		<?php if ( $consent_policy ) : ?>
			<div class="cookie-consent-policy">
				<a href="<?php echo esc_url( get_permalink( (int) $consent_policy ) ); ?>">
					<?php
					/**
					 * Allow the cookie consent policy link text to be filtered.
					 *
					 * @var string $consent_policy_link_text The
					 */
					echo apply_filters( 'altis.consent.cookie_consent_policy_link_text', esc_html__( 'Read our cookie policy', 'altis-consent' ) );
					?>
				</a>
			</div>
		<?php endif; ?>
	</div>

	<?php
	return ob_get_clean();
}
