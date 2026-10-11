<?php
/**
 * Removes the plugin's settings when it is deleted.
 *
 * @package Better_RTL_Support
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'brtls_settings' );
