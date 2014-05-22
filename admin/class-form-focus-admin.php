<?php
/**
 * Form Focus main admin class.
 *
 * @package   Form_Focus
 * @author    Julien Liabeuf <web@themeavenue.net>
 * @license   GPL-2.0+
 * @link      http://themeavenue.net
 * @copyright 2014 ThemeAvenue
 */
class Form_Focus_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	public function __construct() {

		/**
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = Form_Focus::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Settings API Settings
		add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load resources
		add_action( 'admin_print_scripts', array( $this, 'load_scripts' ) );
		add_action( 'admin_print_styles', array( $this, 'load_styles' ) );

		// Add version in footer
		add_action( 'admin_footer_text', array( $this, 'footer_text' ) );

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

	public function load_styles() {

		wp_enqueue_style( 'wp-color-picker' );

	}

	public function load_scripts() {

		wp_enqueue_script( $this->plugin_slug . '-main', FF_URL . 'admin/assets/js/admin.js', array( 'wp-color-picker' ), Form_Focus::VERSION, true );

	}

	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_submenu_page( 'options-general.php', __( 'FormFocus', $this->plugin_slug ), __( 'FormFocus', $this->plugin_slug ), 'manage_options', 'form-focus', array($this, 'display_plugin_admin_page') );

	}

	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Register plugin settings
	 *
	 * This function dynamically registers plugin
	 * settings based on the options provided in
	 * includes/settings.php
	 */
	public function register_plugin_settings() {

		$settings = $this->get_options();

		register_setting( $this->plugin_slug . '_options', $this->plugin_slug . '_options' );

		foreach( $settings as $key => $section ) {
			/* We add the sections and then loop through the corresponding options */
			add_settings_section( $section['id'], $section['title'], false, $this->plugin_slug . '_options' );

			/* Get the options now */
			foreach( $section['options'] as $k => $option ) {

				if( !isset($option['desc']) ) $option['desc'] = '';
				if( !isset($option['opts']) ) $option['opts'] = array();
				$field_args = array(
					'name' 		=> $option['id'],
					'title' 	=> $option['title'],
					'type' 		=> $option['type'],
					'desc' 		=> $option['desc'],
					'options' 	=> $option['opts'],
					'group' 	=> $this->plugin_slug . '_options'
				);

				add_settings_field( $option['id'], $option['title'], array( $this, 'outputSettingsFields' ), $this->plugin_slug . '_options', $section['id'], $field_args );
			}
		}
	}

	/**
	 * Calls field output function
	 * 
	 * @param (array) $args Arguments list for this setting
	 */
	public function outputSettingsFields( $args ) {
		form_focus_output_option( $args );
	}

	public function get_options() {

		$options = array(
			array(
				'id'        => 'formfocus_general',
				'title'     => __( 'General Settings', 'form-focus' ),
				'options'   => array(
					array(
						'id'    => 'enable',
						'title' => __( 'Enable', 'form-focus' ),
						'type'  => 'radio',
						'opts'  => array(
							'all'      => __( 'All Forms', 'form-focus' ),
							'specific' => __( 'Specific Forms (see below)', 'form-focus' ),
						),
					),
					array(
						'id'    => 'specific_forms',
						'title' => __( 'Specific Forms', 'form-focus' ),
						'desc'  => __( 'If you only want the focus on specific forms, please specify the classes or IDs in this field. Classes should be of the form <code>.class</code> and IDs <code>#id</code>. Separate each class/ID by a comma.', 'form-focus' ),
						'type'  => 'text'
					),
					array(
						'id'    => 'overlay_color',
						'title' => __( 'Overlay Color', 'form-focus' ),
						'type'  => 'colorpicker',
						'opts'  => array(
							'line_highlight'    => __( 'Line Highlight', 'form-focus' ),
							'line_numbers'      => __( 'Line Numbers', 'form-focus' ),
							'show_invisibles'   => __( 'Show Invisibles', 'form-focus' ),
							'autolinker'        => __( 'Autolinker', 'form-focus' ),
							'webplateform_docs' => __( 'WebPlatform Docs', 'form-focus' ),
							'file_highlight'    => __( 'File Highlight', 'form-focus' ),
							)
					),
					array(
						'id'    => 'overlay_opacity',
						'title' => __( 'Overlay Opacity', 'form-focus' ),
						'type'  => 'range'
					),
					array(
						'id'    => 'overlay_speed',
						'title' => __( 'Overlay Speed', 'form-focus' ),
						'desc'  => __( 'Must be in <code>miliseconds</code>.', 'form-focus' ),
						'type'  => 'smalltext'
					),
				)
			)
		);

		$options = apply_filters( 'form-focus_edit_plugin_options', $options );

		return $options;
	}

	/**
	 * Add the plugin version in footer.
	 *
	 * @since  1.0.0
	 */
	public function footer_text() {

		if( isset( $_GET['page'] ) && 'form-focus' == $_GET['page'] ) {
			printf( __( 'Form Focus version %s by <a href="%s" target="_blank">ThemeAvenue</a>.', 'form-focus' ), Form_Focus::VERSION, 'http://themeavenue.net/?utm_source=plugin&utm_medium=footer%20link&utm_campaign=FormFocus' );
		}
	}

}