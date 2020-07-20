<?php

namespace Altis\Consent;

function bootstrap() {
	/**
	 * Tell the consent API we're following the api
	 */
	add_filter( 'wp_consent_api_registered_' . plugin_basename( __FILE__ ), '__return_true' );

	// Default Altis consent type to "opt-in".
	add_filter( 'wp_get_consent_type', function() {
		return 'optin';
	} );

	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

	// Shortcode. Replace with an actual way to display a banner.
	add_shortcode( 'example-plugin-shortcode', __NAMESPACE__ . '\\example_plugin_load_document' );
}

function enqueue_assets() {
	wp_enqueue_script( 'altis-consent', plugin_dir_url( __DIR__ ) . 'assets/js/main.js', [ 'jquery' ], '0.0.1', true );
}

function example_plugin_load_document( $atts = [], $content = null, $tag = '' ) : string {
	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	ob_start();

	// override default attributes with user attributes
	$atts = shortcode_atts(array('category' => 'marketing'), $atts, $tag);
	//default
	$category = 'marketing';
	if (function_exists('wp_validate_consent_category')){
		$category = wp_validate_consent_category($atts['category']);
    }

	?>
	<div id="example-plugin-content" data-consentcategory="<?php echo $category?>">
		<div class="functional-content">
			<h1>No consent has been given yet for category <?php echo $category?>. </h1>
			<button class="give-consent">Give consent for <?php echo $category; ?></button>
		</div>
		<div class="marketing-content" style="display:none">
			<h1>Woohoo! consent has been given for category <?php echo $category?> :)</h1>
			<button class="revoke-consent">Revoke consent for <?php echo $category; ?></button>
		</div>

	</div>

	<?php
	return ob_get_clean();
}
