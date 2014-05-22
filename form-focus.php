<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   Form_Focus
 * @author    Julien Liabeuf <web@themeavenue.net>
 * @license   GPL-2.0+
 * @link      http://themeavenue.net
 * @copyright 2014 ThemeAvenue
 *
 * @wordpress-plugin
 * Plugin Name:       Form Focus
 * Plugin URI:        http://themeavenue.net
 * Description:       Form Focus helps your visitors focus on the important actions. Once a form is active, the rest will be overlayed so that the visitor has no distraction while signing-up.
 * Version:           1.0.0
 * Author:            ThemeAvenue
 * Author URI:        http://themeavenue.net
 * Text Domain:       form-focus
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/ThemeAvenue/Form-Focus
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Define all the plugin constants */
define( 'FF_URL', plugin_dir_url( __FILE__ ) );
define( 'FF_PATH', plugin_dir_path( __FILE__ ) );
define( 'FF_BASENAME', plugin_basename(__FILE__) );

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
require_once( plugin_dir_path( __FILE__ ) . 'public/class-form-focus.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Form_Focus', 'activate' ) );


add_action( 'plugins_loaded', array( 'Form_Focus', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-plugin-name-admin.php` with the name of the plugin's admin file
 * - replace Plugin_Name_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	/* Load settings API related files */
	require_once( FF_PATH . 'admin/includes/rendering.php' );

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-form-focus-admin.php' );
	add_action( 'plugins_loaded', array( 'Form_Focus_Admin', 'get_instance' ) );

}