<?php
/**
 * Form Focus main public class.
 *
 * @package   Form_Focus
 * @author    Julien Liabeuf <web@themeavenue.net>
 * @license   GPL-2.0+
 * @link      http://themeavenue.net
 * @copyright 2014 ThemeAvenue
 */
class Form_Focus {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * @TODO - Rename "plugin-name" to the name of your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'form-focus';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	public function __construct() {

		add_action( 'wp_print_scripts', array( $this, 'load_scripts' ) );
		add_action( 'wp_print_styles', array( $this, 'load_styles' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Run on plugin activation.
	 *
	 * @since  1.0.0
	 */
	public function activate() {

		$defaults = array(
			'enable'          => 'all',
			'specific_forms'  => '',
			'overlay_color'   => '#000',
			'overlay_opacity' => '75',
			'overlay_speed'   => '400'
		);

		update_option( 'form-focus_options', $defaults );

	}

	/**
	 * Load public facing scripts.
	 *
	 * @since  1.0.0
	 */
	public function load_scripts() {

		$speed    = formfocus_get_option( 'overlay_speed' );
		$selector = ( 'all' == ( $s = formfocus_get_option( 'enable' ) ) ) ? 'form' : formfocus_get_option( 'specific_forms' );

		wp_enqueue_script( $this->plugin_slug . '-main', FF_URL . 'public/assets/js/form-focus.min.js', array( 'jquery' ), self::VERSION, true );
		wp_localize_script( $this->plugin_slug . '-main', 'formfocus', array( 'speed' => $speed, 'selector' => $selector ) );

	}

	public function load_styles() {

		$style = $this->inline_style();
		echo "<style type='text/css'>$style</style>";

	}

	public function inline_style() {

		$opacity    = formfocus_get_option( 'overlay_opacity' );
		$background = formfocus_get_option( 'overlay_color' );
		$style      = ".formbox-active{z-index:1000;position:relative}.formbox-overlay{display:none;width:100%;height:100%;position:fixed;top:0;right:0;bottom:0;left:0;background:$background;opacity:0.$opacity}";

		return $style;

	}

}

function formfocus_get_option( $option, $default = '' ) {

	/*
	 * Call $plugin_slug from public plugin class.
	 */
	$plugin = Form_Focus::get_instance();
	$plugin_slug = $plugin->get_plugin_slug();

	$key     = $plugin_slug . '_options';
	$options = get_option( $key, $default );

	return isset( $options[$option] ) ? $options[$option] : $default;

}