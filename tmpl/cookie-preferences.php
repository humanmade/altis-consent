<?php
/**
 * Cookie Preferences
 *
 * @package Altis-Consent
 */

use Altis\Consent;

$categories = Consent\consent_categories();
?>

<div class="cookie-preferences">
	<?php
	$alwaysAllowedCategories = apply_filters( 'altis.consent.always_allow_categories', [] );
	foreach ( $categories as $category ) {
		// Validate the consent category.
		if ( ! Consent\validate_consent_item( $category, 'categories' ) ) {
			continue;
		}

		// Ensure the functional category always shows, hide the always allowed categories
		if ( 'functional' != $category && in_array( $category, $alwaysAllowedCategories, true ) ) {
			continue;
		}
		?>

		<label for="cookie-preference-<?php echo esc_attr( $category ); ?>">
			<input type="checkbox" name="cookie-preferences[<?php echo esc_attr( $category ); ?>]" class="category-input" value="<?php echo esc_attr( $category ); ?>" id="cookie-preference-<?php echo esc_attr( $category ); ?>"
				<?php if ( 'functional' === $category ) : ?>
					checked="checked" disabled="disabled"
				<?php endif; ?>
				data-consentcategory="<?php echo esc_attr( $category ); ?>"
			/>
			<?php echo esc_html( Consent\get_category_label( $category ) ); ?>
		</label>
	<?php } ?>

	<button class="apply-cookie-preferences">
		<?php echo esc_html( apply_filters( 'altis.consent.apply_cookie_preferences_button_text', __( 'Apply Changes', 'altis-consent' ) ) ); ?>
	</button>
</div>
