<?php
/**
 * Cookie consent policy
 *
 * @package Altis-Consent
 */

$options     = get_option( 'cookie_consent_options' );
$policy_page = $options['policy_page'];
?>

<div class="cookie-consent-policy">
	<a href="<?php echo esc_url( get_permalink( (int) $policy_page ) ); ?>">
		<?php
		echo wp_kses_post(
			/**
			 * Allow the cookie consent policy link text to be filtered.
			 *
			 * @var string $consent_policy_link_text The link text for the cookie consent policy.
			 */
			apply_filters( 'altis.consent.cookie_consent_policy_link_text', esc_html__( 'Read our cookie policy', 'altis-consent' ) )
		);
		?>
	</a>
</div>
