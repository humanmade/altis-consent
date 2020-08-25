<?php
/**
 * Cookie consent policy
 *
 * @package Altis-Consent
 */

use Altis\Consent\Settings;

$policy_page = (int) Settings\get_consent_option( 'policy_page' );
?>

<div class="cookie-consent-policy">
	<a href="<?php echo esc_url( get_permalink( $policy_page ) ); ?>">
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
