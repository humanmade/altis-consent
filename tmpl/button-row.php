<?php
/**
 * Button row
 *
 * @package Altis-Consent
 */
$options        = get_option( 'cookie_consent_options' );
$banner_message = $options['banner_message'];
$policy_page    = $options['policy_page'] ?: false;
$all_categories = 'all-categories' === $options['banner_options'];
?>

<div class="button-row">
	<div class="cookie-consent-message">
		<?php echo wp_kses_post( $banner_message ); ?>
		<?php if ( $policy_page ) : ?>
			<?php
				/**
				 * Allow the cookie consent policy template path to be overridden so it can be customized individually. This template displays the link to the cookie policy page, but only if a cookie policy page has been set.
				 *
				 * @var string The path to the cookie consent policy template.
				 */
				$cookie_consent_policy_path = apply_filters( 'altis.consent.cookie_consent_policy_template_path', __DIR__ . '/cookie-consent-policy.php' );

				load_template( $cookie_consent_policy_path );
			?>
		<?php endif; ?>
	</div>

	<button class="give-consent">
		<?php echo esc_html( apply_filters( 'altis.consent.accept_all_cookies_button_text', __( 'Accept all cookies', 'altis-consent' ) ) ); ?>
	</button>

	<button class="revoke-consent">
		<?php echo esc_html( apply_filters( 'altis.consent.accept_only_functional_cookies_button_text', __( 'Accept only functional cookies', 'altis-consent' ) ) ); ?>
	</button>

	<?php if ( $all_categories ) : ?>
		<button class="view-preferences">
			<?php echo esc_html( apply_filters( 'altis.consent.cookie_preferences_button_text', __( 'Cookie preferences', 'altis-consent' ) ) ); ?>
		</button>
	<?php endif; ?>
</div>
