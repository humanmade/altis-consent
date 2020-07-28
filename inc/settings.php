<?php

namespace Altis\Consent\Settings;

use WP_Post;

/**
 * Kick off all the things.
 */
function bootstrap() {
	add_action( 'admin_init', __NAMESPACE__ . '\\register_consent_settings' );
	add_action( 'admin_init', __NAMESPACE__ . '\\update_privacy_policy_page' );
	add_action( 'admin_menu', __NAMESPACE__ . '\\add_altis_privacy_page' );
	add_action( 'admin_menu', __NAMESPACE__ . '\\remove_core_privacy_page' );
}

/**
 * Remove the WordPress core Privacy page from the Settings menu.
 */
function remove_core_privacy_page() {
	global $submenu;

	unset( $submenu['options-general.php'][45] );
}

/**
 * Update the Privacy Policy Page setting.
 *
 * Handles updating the privacy page option.
 * Since this page and setting is not registered like a normal WordPress option, we need to intercept the $_POST data if the privacy policy page setting was updated, and update it manually.
 */
function update_privacy_policy_page() {
	if (
		// Validate the nonce.
		! isset( $_POST['_altis_privacy_policy_page_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( $_POST['_altis_privacy_policy_page_nonce'] ), 'altis.privacy_policy_page' ) ||
		// Bail early if we're not on the consent page.
		! isset( $_POST['option_page'] ) ||
		'cookie_consent_options' !== esc_attr( $_POST['option_page'] )
	) {
		return;
	}

	$privacy_option         = 'wp_page_for_privacy_policy';
	$privacy_policy_page_id = (int) get_option( $privacy_option );
	$updated_id             = esc_attr( $_POST[ $privacy_option ] );

	if ( absint( $updated_id ) == $privacy_policy_page_id ) {
		return;
	}

	update_option( $privacy_option, $updated_id );
}

/**
 * Register the Altis Privacy submenu page.
 */
function add_altis_privacy_page() {
	add_options_page(
		__( 'Privacy Settings', 'altis-consent' ),
		__( 'Privacy', 'altis-consent' ),
		'manage_options',
		'altis_privacy',
		__NAMESPACE__ . '\\render_altis_privacy_page'
	);
}

/**
 * Return an array of Altis Consent settings.
 */
function get_cookie_consent_settings_fields() {
	$fields = [
		[
			'id'       => 'display_banner',
			'title'    => __( 'Display Cookie Consent Banner', 'altis-consent' ),
			'callback' => __NAMESPACE__ . '\\render_display_banner',
		],
		[
			'id'       => 'cookie_expiration',
			'title'    => __( 'Cookie Expiration', 'altis-consent' ),
			'callback' => __NAMESPACE__ . '\\cookie_expiration',
		],
		[
			'id'       => 'banner_options',
			'title'    => __( 'Consent Banner Options', 'altis-consent' ),
			'callback' => __NAMESPACE__ . '\\render_banner_options',
		],
		[
			'id'       => 'banner_text',
			'title'    => __( 'Banner Message', 'altis-consent' ),
			'callback' => __NAMESPACE__ . '\\render_banner_message',
		],
		[
			'id'       => 'cookie_policy_page',
			'title'    => __( 'Cookie Policy Page', 'altis-consent' ),
			'callback' => __NAMESPACE__ . '\\render_cookie_policy_page',
		],
	];

	/**
	 * Allow these fields to be filtered. New options fields can be added in the above format.
	 *
	 * @var array $fields An array of settings fields with unique IDs, titles and callback functions.
	 */
	return apply_filters( 'altis.consent.consent_settings_fields', $fields );
}

/**
 * Return an array of cookie banner options.
 */
function get_cookie_banner_options() {
	$options = [
		[
			'value' => '',
			'label' => '&mdash; ' . __( 'Please select an option', 'altis-consent' ) . ' &mdash;',
		],
		[
			'value' => 'all-categories',
			'label' => __( 'All Cookie Categories', 'altis-consent' ),
		],
		[
			'value' => 'none',
			'label' => __( 'Allow/Deny All Cookies', 'altis-consent' ),
		],
	];

	return apply_filters( 'altis.consent.banner_options', $options );
}

/**
 * Register the Altis Consent settings.
 */
function register_consent_settings() {
	$page    = 'altis_privacy';
	$section = 'cookie_consent';

	register_setting( 'cookie_consent_options', 'cookie_consent_options', __NAMESPACE__ . '\\validate_privacy_options' );

	add_settings_section(
		'privacy_policy',
		__( 'Privacy Policy', 'altis-consent' ),
		__NAMESPACE__ . '\\altis_privacy_section',
		$page
	);

	// Pull in the privacy policy page settings.
	privacy_policy_page_settings();

	add_settings_section(
		$section,                                  // New settings section
		__( 'Cookie Consent', 'altis-consent' ),   // Section title
		__NAMESPACE__ . '\\altis_consent_section', // Callback function
		$page                                      // Settings Page.
	);

	// Get the Altis Consent settings and loop through them, registering each.
	$fields = get_cookie_consent_settings_fields();
	foreach ( $fields as $field ) {
		add_settings_field( $field['id'], $field['title'], $field['callback'], $page, $section );
	}
}

/**
 * Handle the Privacy Policy page settings.
 *
 * Much of this code is copy pasta from the WordPress core Privacy page.
 */
function privacy_policy_page_settings() {
	// If a Privacy Policy page ID is available, make sure the page actually exists. If not, display an error.
	$privacy_policy_page_exists = false;
	$privacy_policy_page_id     = (int) get_option( 'wp_page_for_privacy_policy' );

	if ( ! empty( $privacy_policy_page_id ) ) {

		$privacy_policy_page = get_post( $privacy_policy_page_id );

		if ( ! $privacy_policy_page instanceof WP_Post ) {
			add_settings_error(
				'page_for_privacy_policy',
				'page_for_privacy_policy',
				__( 'The currently selected Privacy Policy page does not exist. Please create or select a new page.' ),
				'error'
			);
		} else {
			if ( 'trash' === $privacy_policy_page->post_status ) {
				add_settings_error(
					'page_for_privacy_policy',
					'page_for_privacy_policy',
					sprintf(
						/* translators: %s: URL to Pages Trash. */
						__( 'The currently selected Privacy Policy page is in the Trash. Please create or select a new Privacy Policy page or <a href="%s">restore the current page</a>.' ),
						'edit.php?post_status=trash&post_type=page'
					),
					'error'
				);
			} else {
				$privacy_policy_page_exists = true;
			}
		}
	}

	$label = $privacy_policy_page_exists ? __( 'Update your Privacy Policy page', 'altis-consent' ) : __( 'Select a Privacy Policy page', 'altis-consent' );

	add_settings_field( 'wp_page_for_privacy_policy', $label, __NAMESPACE__ . '\\render_privacy_policy_page_setting', 'altis_privacy', 'privacy_policy' );
}

/**
 * Return the Privacy Policy admin text.
 *
 * This text is copy pasta from WordPress core's Privacy page, but can be filtered to say whatever you want, or removed entirely.
 */
function get_privacy_policy_text() {
	ob_start();
	?>
	<p>
		<?php esc_html_e( 'As a website owner, you may need to follow national or international privacy laws. For example, you may need to create and display a Privacy Policy.', 'altis-consent' ); ?>
		<?php esc_html_e( 'If you already have a Privacy Policy page, please select it below. If not, please create one.', 'altis-consent' ); ?>
	</p>
	<p>
		<?php esc_html_e( 'The new page will include help and suggestions for your Privacy Policy.', 'altis-consent' ); ?>
		<?php esc_html_e( 'However, it is your responsibility to use those resources correctly, to provide the information that your Privacy Policy requires, and to keep that information current and accurate.', 'altis-consent' ); ?>
	</p>
	<p>
		<?php esc_html_e( 'After your Privacy Policy page is set, we suggest that you edit it.', 'altis-consent' ); ?>
		<?php esc_html_e( 'We would also suggest reviewing your Privacy Policy from time to time, especially after installing or updating any themes or plugins. There may be changes or new suggested information for you to consider adding to your policy.', 'altis-consent' ); ?>
	</p>
	<?php

	$privacy_message = ob_get_clean();

	/**
	 * Allow the privacy policy message to be filtered or removed.
	 *
	 * @var string $privacy_message The message to display above the Privacy Policy page setting.
	 */
	return apply_filters( 'altis.consent.privacy_policy_message', $privacy_message );
}

/**
 * Validation function that I need to flesh out still.
 *
 * @param array $dirty An array of unvalidated option data to validate.
 */
function validate_privacy_options( $dirty ) {
	$validated = [];

	// Make sure cookie_expiration is a number.
	$validated['cookie_expiration'] = is_numeric( $dirty['cookie_expiration'] ) ? $dirty['cookie_expiration'] : '14';

	// Make sure the banner_options is in the array of options we expect.
	$validated['banner_options'] = in_array( $dirty['banner_options'], wp_list_pluck( get_cookie_banner_options(), 'value' ) ) ? $dirty['banner_options'] : '';

	// Strip evil scripts from the message.
	$validated['banner_message'] = wp_kses_post( $dirty['banner_message'] );

	// Make sure the page exists.
	$page_exists = (bool) get_post( absint( $dirty['policy_page'] ) );
	$validated['policy_page'] = $page_exists ? $dirty['policy_page'] : '';

	$validated['display_banner'] = is_numeric( $dirty['display_banner'] ) ? (int) $dirty['display_banner'] : 1;

	/**
	 * Allow the validated data to be filtered.
	 * This is useful if additional settings are added to the page that need to be validated.
	 *
	 * @var array $validated An array of validated data.
	 * @var array $dirty     An array of unvalidated data.
	 */
	return apply_filters( 'altis.consent.validate_privacy_options', $validated, $dirty );
}

/**
 * Render the Altis Privacy page.
 */
function render_altis_privacy_page() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php

			settings_fields( 'cookie_consent_options' );
			do_settings_sections( 'altis_privacy' );
			?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save Privacy Settings', 'altis-consent' ); ?>" />
		</form>
	</div>
	<?php
}

/**
 * The Cookie Consent section. Nothing is here  yet but it could contain a notice or information about cookie consent policies.
 */
function altis_consent_section() {}

/**
 * The Privacy Policy page section.
 * This just adds a nonce that we can validate against when we intercept the $_POST data to update the privacy policy page setting.
 */
function altis_privacy_section() {
	$nonce = wp_create_nonce( 'altis.privacy_policy_page' );
	echo wp_kses_post( get_privacy_policy_text() );
	echo '<input type="hidden" name="_altis_privacy_policy_page_nonce" value="' . sanitize_text_field( $nonce ) . '" />'; // phpcs:ignore
}

/**
 * Get a specific consent option, if one exists. If no option value is passed, all saved cookie_consent_options will be returned.
 *
 * @param mixed $option  The option value to get from the options table.
 * @param mixed $default The default value for the given option.
 *
 * @return mixed         The value for the requested option, or all cookie_consent_options if nothing was passed.
 */
function get_consent_option( $option = '', $default = '' ) {
	$options = get_option( 'cookie_consent_options' );

	if ( empty( $option ) ) {
		return $options;
	}

	if ( ! isset( $options[ $option ] ) ) {
		return $default;
	}

	return $options[ $option ];
}

/**
 * Render the display cookie consent banner setting.
 */
function render_display_banner() {
	$display_banner = get_consent_option( 'display_banner', 1 );

	?>
	<select name="cookie_consent_options[display_banner]" id="display_banner" value="<?php echo absint( $display_banner ); ?>">
		<option value="0" <?php selected( $display_banner, 0 ); ?>>
			<?php esc_html_e( 'Do not display banner', 'altis-consent' ); ?>
		</option>
		<option value="1" <?php selected( $display_banner, 1 ); ?>>
			<?php esc_html_e( 'Display consent banner', 'altis-consent' ); ?>
		</option>
	</select>
	<?php
}

/**
 * Render the cookie expiration setting.
 */
function cookie_expiration() {
	$expiration = get_consent_option( 'cookie_expiration', 30 );
	?>
	<input id="cookie_consent_expiration" name="cookie_consent_options[cookie_expiration]" type="number" value="<?php echo absint( $expiration ); ?>" class="small-text" step="1" />
	<p class="description">
		<?php esc_html_e( 'How long, in days, cookies should be stored.', 'altis-consent' ); ?>
	</p>
	<?php
}

/**
 * Render the consent banner options setting.
 */
function render_banner_options() {
	$selected       = get_consent_option( 'banner_options' );
	$banner_options = get_cookie_banner_options();
	?>
	<select name="cookie_consent_options[banner_options]" id="banner_options" value="<?php echo esc_attr( $selected ); ?>">
		<?php
		// Loop through each of the options and render the html.
		foreach ( $banner_options as $option ) {
			?>
			<option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( $selected, $option['value'] ); ?>><?php echo esc_html( $option['label'] ); ?></option>
		<?php } ?>
	</select>
	<p class="description">
		<?php esc_html_e( 'If you would like to display an option to configure each cookie category in the consent banner, select All Cookie Categories.', 'altis-consent' ); ?><br />
		<?php esc_html_e( 'If you would like to only display an option to accept or deny all non-functional cookies, select Allow/Deny All Cookies.', 'altis-consent' ); ?>
	</p>
	<?php
}

/**
 * Render the banner message setting.
 */
function render_banner_message() {
	/**
	 * Allow the default cookie banner message to be filtered.
	 *
	 * @var string $default_message The default cookie consent banner message.
	 */
	$default_message = apply_filters( 'altis.consent.default_banner_message', esc_html__( 'This site uses cookies to provide a better user experience.', 'altis-consent' ) );
	$message         = get_consent_option( 'banner_message', $default_message );

	// Render a TinyMCE editor for the banner message.
	wp_editor( wp_kses_post( $message ), 'banner_message', [
		'textarea_name' => 'cookie_consent_options[banner_message]',
		'teeny'         => true,
		'textarea_rows' => 5,
	] );
}

/**
 * Render the cookie policy page setting.
 */
function render_cookie_policy_page() {
	$page_id = get_consent_option( 'policy_page', 0 );
	$page_id = sanitize_text_field( $options['policy_page'] ) ?: 0;

	// If there are pages, display the page dropdown mehu. Otherwise, display a message stating that there are no pages.
	if ( pages_exist() ) {
		wp_dropdown_pages( [
			'name'              => 'cookie_consent_options[policy_page]',
			'show_option_none'  => '&mdash; ' . esc_html__( 'Select an option', 'altis-consent' ) . ' &mdash;',
			'option_none_value' => '0',
			'selected'          => $page_id, // phpcs:ignore
			'post_status'       => [ 'draft', 'publish' ],
		] );
	} else {
		esc_html_e( 'There are no pages.', 'altis-consent' );
	}
}

/**
 * Render the privacy policy page setting.
 */
function render_privacy_policy_page_setting() {
	$privacy_policy_page_id = (int) get_option( 'wp_page_for_privacy_policy' );

	if ( pages_exist() ) {
		wp_dropdown_pages( [
			'name'              => 'wp_page_for_privacy_policy',
			'show_option_none'  => '&mdash; ' . esc_html__( 'Select an option', 'altis-consent' ) . ' &mdash;',
			'option_none_value' => '0',
			'selected'          => $privacy_policy_page_id, // phpcs:ignore
			'post_status'       => [ 'draft', 'publish' ],
		] );
	} else {
		esc_html_e( 'There are no pages.', 'altis-consent' );
	}
}

/**
 * Check if pages exist.
 *
 * @return bool True if pages exist, false if no pages exist. Checks if there are published or draft pages.
 */
function pages_exist() : bool {
	$has_pages = wp_cache_get( 'altis.privacy.has_pages', 'altis' );

	if ( ! $has_pages ) {
		$has_pages = (bool) get_posts( [
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'post_status'    => [ 'publish', 'draft' ],
		] );

		// Cache the result so we don't need to run another get_posts later.
		wp_cache_set( 'altis.privacy.has_pages', $has_pages, 'altis' );
	}

	return $has_pages;
}
