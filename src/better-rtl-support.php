<?php
/**
 * Plugin Name:       Better RTL Support
 * Plugin URI:        https://github.com/salar90/better-rtl-support
 * Description:       Make themes and plugins more readable in Persian and other RTL languages by changing fonts and tweaking styles.
 * Version:           3.0.0
 * Requires at least: 5.3
 * Requires PHP:      7.2
 * Author:            Salar Gholizadeh
 * Author URI:        https://salar.one
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       better-rtl-support
 * Domain Path:       /languages
 *
 * @package Better_RTL_Support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'BRTLS_VERSION', '3.0.0' );
define( 'BRTLS_FILE', __FILE__ );

require_once __DIR__ . '/class-better-rtl-support.php';

global $BRTLS;
$BRTLS = new Better_RTL_Support();
