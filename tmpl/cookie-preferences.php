<?php
/**
 * Cookie Preferences
 *
 * @package Altis-Consent
 */

$categories = WP_CONSENT_API::$config->consent_categories();
?>

<div class="cookie-preferences">
	<?php
	foreach ( $categories as $category ) {
		// Validate the consent category.
		if ( ! wp_validate_consent_category( $category ) ) {
			continue;
		}

		// Skip anonymous statistics category, don't need to ask permission explicitly.
		if ( 'statistics-anonymous' === $category ) {
			continue;
		}
		?>

		<label for="cookie-preference-<?php echo esc_attr( $category ); ?>">
			<input type="checkbox" name="cookie-preferences[<?php echo esc_attr( $category ); ?>]" class="category-input" value="<?php echo esc_attr( $category ); ?>"
				<?php if ( 'functional' === $category ) : ?>
					checked="checked" disabled="disabled"
				<?php endif; ?>
			/>
			<?php echo esc_attr( ucfirst( $category ) ); ?>
		</label>
	<?php } ?>

	<button class="apply-cookie-preferences">
		<?php echo esc_html( apply_filters( 'altis.consent.apply_cookie_preferences_button_text', __( 'Apply Changes', 'altis-consent' ) ) ); ?>
	</button>
</div>
