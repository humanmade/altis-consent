<?php
/**
 * Consent updated message
 *
 * @package Altis-Consent
 */

?>
<div class="consent-updated-message">
	<p class="message">
		<span class="preferences-updated-message">
			<?php echo wp_kses_post( apply_filters( 'altis.consent.preferences_updated_message', __( 'Cookie preferences updated.', 'altis-consent' ) ) ); ?>
		</span>
		<button class="close-message" id="consent-close-updated-message"><?php esc_html_e( 'Close', 'altis-consent' ); ?></button>
	</p>
</div>
