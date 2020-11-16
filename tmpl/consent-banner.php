<?php

use Altis\Consent;
use Altis\Consent\Settings;

$categories              = Consent\consent_categories();
$banner_option           = Settings\get_consent_option( 'banner_options' );
$no_option_saved_message = sprintf(
	// Translators: %s is the link to the admin Privacy setting page.
	__( 'No consent option has been set. Please visit the <a href="%s">Privacy Settings page</a> and set the consent banner option.', 'altis-consent.' ),
	admin_url( 'options-general.php?page=altis_privacy' )
);
?>

<div id="cookie-consent-banner">
	<?php
	/**
	 * Allow the consent updated template path to be overridden so it can be customized independently. This template displays the "preferences updated" messaging after they have been saved.
	 *
	 * @var string The path to the consent updated template.
	 */
	$consent_updated_template_path = apply_filters( 'altis.consent.consent_updated_template_path', __DIR__ . '/consent-updated.php' );

	load_template( $consent_updated_template_path );
	?>
	<div class="consent-banner">
		<?php
		if ( '' === $banner_option ) {
			echo wp_kses_post(
				/**
				 * Allow the no option saved message to be filtered.
				 *
				 * @var string $no_option_saved_message The message to output when no consent option has been saved.
				 */
				apply_filters( 'altis.consent.no_option_saved_message', $no_option_saved_message )
			);
		} else {
			if ( 'none' !== $banner_option ) {
				/**
				 * Allow the cookie preferences template path to be overridden so it can be customized independently.
				 *
				 * @var string The path to the cookie preferences template.
				 */
				$cookie_preferences_template_path = apply_filters( 'altis.consent.cookie_preferences_template_path', __DIR__ . '/cookie-preferences.php' );

				load_template( $cookie_preferences_template_path );
			}

			/**
			 * Allow the button row template path to be overridden so it can be customized independently. This is the main content template that includes the buttons and the messaging of the banner.
			 *
			 * @var string The path to the button row template.
			 */
			$button_row_template_path = apply_filters( 'altis.consent.button_row_template_path', __DIR__ . '/button-row.php' );

			load_template( $button_row_template_path );
		}
		?>
	</div>
</div>
