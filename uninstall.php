<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Form_Focus
 * @author    Julien Liabeuf <web@themeavenue.net>
 * @license   GPL-2.0+
 * @link      http://themeavenue.net
 * @copyright 2014 ThemeAvenue
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

if ( is_multisite() ) {

	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
	if ( $blogs ) {

	 	foreach ( $blogs as $blog ) {
			switch_to_blog( $blog['blog_id'] );
			delete_option( 'form-focus_options' );
			restore_current_blog();
		}
	}

} else {
	delete_option( 'form-focus_options' );
}
