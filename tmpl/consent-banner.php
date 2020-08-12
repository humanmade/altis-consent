<?php
$categories              = WP_CONSENT_API::$config->consent_categories();
$options                 = get_option( 'cookie_consent_options' );
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
	apply_filters( 'altis.consent.consent_updated_template_path', load_template( __DIR__ . '/consent-updated.php' ) );
	?>
	<div class="consent-banner">
		<?php
		if ( '' === $options['banner_options'] ) {
			echo wp_kses_post(
				/**
				 * Allow the no option saved message to be filtered.
				 *
				 * @var string $no_option_saved_message The message to output when no consent option has been saved.
				 */
				apply_filters( 'altis.consent.no_option_saved_message', $no_option_saved_message )
			);
		} else {
			/**
			 * Allow the cookie preferences template path to be overridden so it can be customized independently.
			 *
			 * @var string The path to the cookie preferences template.
			 */
			apply_filters( 'altis.consent.cookie_preferences_template_path', load_template( __DIR__ . '/cookie-preferences.php' ) );

			/**
			 * Allow the button row template path to be overridden so it can be customized independently. This is the main content template that includes the buttons and the messaging of the banner.
			 *
			 * @var string The path to the button row template.
			 */
			apply_filters( 'altis.consent.button_row_template_path', load_template( __DIR__ . '/button-row.php' ) );
		}
		?>
	</div>
</div>
