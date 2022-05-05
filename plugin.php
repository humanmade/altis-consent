<?php
/**
 * Plugin Name: Altis Consent
 * Plugin URI: https://github.com/humanmade/altis-consent
 * Description: Hooks into the Consent API to provide basic settings and a cookie consent banner for Altis.
 * Version: 1.0.15
 * Text Domain: altis-consent
 * Domain Path: /languages
 * Author: Human Made
 * Author URI: https://altis-dxp.com
 */

namespace Altis\Consent;

const PLUGIN_DIR = __DIR__;
const PLUGIN_FILE = __FILE__;

require_once __DIR__ . '/inc/cookie-policy.php';
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/namespace.php';
require_once __DIR__ . '/inc/settings.php';

bootstrap();
Settings\bootstrap();
