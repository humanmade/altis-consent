<?php

namespace Altis\Consent\Settings;

function bootstrap() {
	add_action( 'admin_init', __NAMESPACE__ . '\\register_consent_settings' );
	add_action( 'admin_menu', __NAMESPACE__ . '\\add_altis_privacy_page' );
}

function add_altis_privacy_page() {
	add_options_page(
		__( 'Privacy Settings', 'altis-consent' ),
		'Altis Privacy', // Calling this Altis Privacy for now, so it's a distinct page from the existing WP Core Privacy page.
		'manage_options',
		'altis_privacy',
		__NAMESPACE__ . '\\render_altis_privacy_page'
	);
}

function get_cookie_consent_settings_fields() {
	$fields = [
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
			'id' => 'banner_text',
			'title' => __( 'Banner Message', 'altis-consent' ),
			'callback' => __NAMESPACE__ . '\\render_banner_message',
		],
		[
			'id' => 'cookie_policy_page',
			'title' => __( 'Cookie Policy Page', 'altis-consent' ),
			'callback' => __NAMESPACE__ . '\\render_cookie_policy_page',
		],
	];

	return apply_filters( 'altis.consent.consent_settings_fields', $fields );
}

function register_consent_settings() {
	$page    = 'altis_privacy';
	$section = 'cookie_consent';

	register_setting( 'cookie_consent_options', 'cookie_consent_options', 'validate_some_stuff' );

	add_settings_section(
		$section,                                  // New settings section
		__( 'Cookie Consent', 'altis-consent' ),   // Section title
		__NAMESPACE__ . '\\altis_consent_section', // Callback function
		$page                                      // Settings Page.

	);

	$fields = get_cookie_consent_settings_fields();

	foreach ( $fields as $field ) {
		add_settings_field( $field['id'], $field['title'], $field['callback'], $page, $section );
	}
}

function privacy_policy_page_settings( string $page ) {
	// If a Privacy Policy page ID is available, make sure the page actually exists. If not, display an error.
	$privacy_policy_page_exists = false;
	$privacy_policy_page_id     = (int) get_option( 'wp_page_for_privacy_policy' );

	if ( ! empty( $privacy_policy_page_id ) ) {

		$privacy_policy_page = get_post( $privacy_policy_page_id );

		if ( ! $privacy_policy_page instanceof \WP_Post ) {
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

	$label = $privacy_policy_page_exists ? __( 'Change your Privacy Policy page', 'altis-consent' ) : __( 'Select a Privacy Policy page', 'altis-consent' );

	add_settings_field( 'wp_page_for_privacy_policy', $label, __NAMESPACE__ . '\\render_privacy_policy_page_setting', $page, 'privacy_policy' );
}

function get_privacy_policy_text() {
	ob_start();
	?>
	<p>
		<?php _e( 'As a website owner, you may need to follow national or international privacy laws. For example, you may need to create and display a Privacy Policy.' ); ?>
		<?php _e( 'If you already have a Privacy Policy page, please select it below. If not, please create one.' ); ?>
	</p>
	<p>
		<?php _e( 'The new page will include help and suggestions for your Privacy Policy.' ); ?>
		<?php _e( 'However, it is your responsibility to use those resources correctly, to provide the information that your Privacy Policy requires, and to keep that information current and accurate.' ); ?>
	</p>
	<p>
		<?php _e( 'After your Privacy Policy page is set, we suggest that you edit it.' ); ?>
		<?php _e( 'We would also suggest reviewing your Privacy Policy from time to time, especially after installing or updating any themes or plugins. There may be changes or new suggested information for you to consider adding to your policy.' ); ?>
	</p>
	<?php

	$privacy_message = ob_get_clean();

	return apply_filters( 'altis.consent.privacy_policy_message', $privacy_message );
}

function validate_some_stuff( $stuff ) {
	return $stuff;
}

function render_altis_privacy_page() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'cookie_consent_options' );
			do_settings_sections( 'altis_privacy' );
			?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
		</form>
	</div>
	<?php
}

function altis_consent_section() {
}

function cookie_expiration() {
	$options = get_option( 'cookie_consent_options' );
	$expiration = ! empty( $options['cookie_expiration'] ) ? $options['cookie_expiration'] : 14;
	?>
	<input id="cookie_consent_expiration" name="cookie_consent_options[cookie_expiration]" type="number" value="<?php echo absint( $expiration ); ?>" />
	<p class="description">
		How long, in days, cookies should be stored.
	</p>
	<?php
}

function render_banner_options() {
	$options  = get_option( 'cookie_consent_options' );
	$selected = $options['banner_options'] ?: '';
	?>
	<select name="cookie_consent_options[banner_options]" id="banner_options" value="<?php echo esc_attr( $selected ); ?>">
		<option value="" <?php selected( $selected, '' ); ?>>&mdash; Please select an option &mdash;</option>
		<option value="all-categories" <?php selected( $selected, 'all-categories' ); ?>>All Cookie Categories</option>
		<option value="none" <?php selected( $selected, 'none' ); ?>>Allow/Deny All Cookies</option>
	</select>
	<p class="description">
		If you would like to display an option to configure each cookie category in the consent banner, select All Cookie Categories.<br />
		If you would like to only display an option to accept or deny all non-functional cookies, select Allow/Deny All Cookies.
	</p>
	<?php
}

function render_banner_message() {
	$options = get_option( 'cookie_consent_options' );
	$message = $options['banner_message'] ?: 'This site uses cookies to provide a better user experience.';

	wp_editor( wp_kses_post( $message ), 'banner_message', [
		'textarea_name' => 'cookie_consent_options[banner_message]',
		'teeny'         => true,
		'textarea_rows' => 5,
	] );
}

function render_cookie_policy_page() {
	$options = get_option( 'cookie_consent_options' );
	$page_id = $options['policy_page'] ?: 0;
	$has_pages = (bool) get_posts( [
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'post_status'    => [ 'publish', 'draft' ],
	] );

	if ( $has_pages ) {
		wp_dropdown_pages( [
			'name' => 'cookie_consent_options[policy_page]',
			'show_option_none' => '&mdash; Select an option &mdash;',
			'option_none_value' => '0',
			'selected'          => $page_id,
			'post_status'       => [ 'draft', 'publish' ],
		] );
	} else {
		esc_html_e( 'There are no pages.' );
	}
}

bootstrap();