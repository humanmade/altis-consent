<?php
/**
 * Plugin Name: Altis Consent
 * Plugin URI: https://github.com/humanmade/altis-consent
 * Description: Hooks into the Consent API to provide basic settings and a cookie consent banner for Altis.
 * Version: 0.0.1
 * Text Domain: altis-consent
 * Domain Path: /languages
 * Author: Human Made
 * Author URI: https://altis-dxp.com
 */

namespace Altis\Consent;

require_once __DIR__ . '/inc/namespace.php';
require_once __DIR__ . '/inc/settings.php';

$plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ), false );
define( 'CONSENT_PLUGIN_VERSION', $plugin_data['Version'] );
define( 'PLUGIN', plugin_basename( __FILE__ ) );

bootstrap();
Settings\bootstrap();
