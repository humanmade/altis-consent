<?php
$categories              = WP_CONSENT_API::$config->consent_categories();
$options                 = get_option( 'cookie_consent_options' );
$no_option_saved_message = sprintf(
	// Translators: %s is the link to the admin Privacy setting page.
	__( 'No consent option has been set. Please visit the <a href="%s">Privacy Settings page</a> and set the consent banner option.', 'altis-consent.' ),
	admin_url( 'options-general.php?page=altis_privacy' )
);
?>

<div id="cookie-consent-banner" data-consentcategory="all" class="consent-banner">
	<?php
	if ( '' === $options['banner_options'] ) :
		echo wp_kses_post(
			/**
			 * Allow the no option saved message to be filtered.
			 *
			 * @var string $no_option_saved_message The message to output when no consent option has been saved.
			 */
			apply_filters( 'altis.consent.no_option_saved_message', $no_option_saved_message )
		);
	else :
		?>
		<div class="functional-content">
			<?php
				$category_list = implode( ', ', $categories );
			?>
			<h3>No consent has been given yet for category <?php echo esc_attr( $category_list ); ?>. </h3>
		</div>
		<div class="marketing-content" style="display:none">
			<h3>Woohoo! consent has been given for category <?php echo esc_attr( $category_list ); ?> :)</h3>
		</div>

		<?php load_template( __DIR__ . '/cookie-preferences.php' ); ?>
		<?php load_template( __DIR__ . '/button-row.php' ); ?>
	<?php endif; ?>
</div>
