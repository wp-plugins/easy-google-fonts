<?php 
/**
 * Class: EGF_Customize_Manager
 *
 * Google Font Options Theme Customizer Integration
 *
 * This file integrates the Theme Customizer for this Theme. 
 * All options in this theme are managed in the live customizer. 
 * We believe that themes should only alter the display of content 
 * and should not add any additional functionality that would be 
 * better suited for a plugin. Since all options are presentation 
 * centered, they should all be controllable by the Customizer.
 * 
 *
 * @package   Easy_Google_Fonts_Admin
 * @author    Sunny Johal - Titanium Themes <support@titaniumthemes.com>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/easy-google-fonts/
 * @copyright Copyright (c) 2014, Titanium Themes
 * @version   1.3.1
 * 
 */
if ( ! class_exists( 'EGF_Customize_Manager' ) ) :
	class EGF_Customize_Manager {
		
		/**
		 * Instance of this class.
		 * 
		 * @var      object
		 * @since    1.3
		 *
		 */
		protected static $instance = null;

		/**
		 * Slug of the plugin screen.
		 * 
		 * @var      string
		 * @since    1.3
		 *
		 */
		protected $plugin_screen_hook_suffix = null;

		/**
		 * Plugin slug.
		 * 
		 * @var      string
		 * @since    1.3
		 *
		 */
		public static $slug = 'easy-google-fonts';

		/**
		 * Constructor Function
		 * 
		 * Initialize the plugin by loading admin scripts & styles and adding a
		 * settings page and menu.
		 *
		 * @since 1.3
		 * @version 1.3.1
		 * 
		 */
		function __construct() {

			$this->plugin_slug = 'easy-google-fonts';
			$this->include_control_class();

			$this->register_actions();		
			$this->register_filters();
		}

		/**
		 * Return an instance of this class.
		 * 
		 * @return    object    A single instance of this class.
		 *
		 * @since 1.3
		 * @version 1.3.1
		 * 
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Register Custom Actions
		 *
		 * Add any custom actions in this function.
		 * 
		 * @since 1.3
		 * @version 1.3.1
		 * 
		 */
		public function register_actions() {
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );
			add_action( 'customize_preview_init', array( $this, 'customize_live_preview_scripts' ) );
			add_action( 'customize_register', array( $this, 'customize_preview_styles' ) );
			add_action( 'customize_register', array( $this, 'register_controls' ) );
			add_action( 'customize_save_tt_font_theme_options', array( $this, 'customize_save_tt_font_theme_options' ) );
			add_action( 'customize_save_after', array( $this, 'customize_save_after' ) );
		}

		/**
		 * Register Custom Filters
		 *
		 * Add any custom filters in this function.
		 * 
		 * @since 1.3
		 * @version 1.3.1
		 * 
		 */
		public function register_filters() {
		}

		/**
		 * Include Required Control Classes
		 * 
		 * Only includes the classes required for this 
		 * control to function if they haven't been 
		 * loaded yet.
		 *
		 * @since 1.3
		 * @version 1.3.1
		 * 
		 */
		public function include_control_class() {

			if ( ! class_exists( 'WP_Customize_Control' ) ) {
				include_once( ABSPATH . WPINC . '/class-wp-customize-control.php' );
			}
			
			if ( ! class_exists( 'EGF_Font_Control' ) ) {
				include_once( Easy_Google_Fonts::get_includes_path() . '/controls/class-egf-font-control.php' );
			}
		}

		/**
		 * Load All Fonts in Customizer
		 *
		 * Loads the required fonts as a json object for the live
		 * theme previewer and customizer. By enqueuing the fonts
		 * on the screen we redice the number of ajax requests which
		 * increases performance dramatically.
		 * 
		 * @return array complete list of fonts
		 *
		 * @since  1.3
		 * @version 1.3.1
		 */
		public function customize_load_all_fonts() {
			return EGF_Font_Utilities::get_all_fonts();
		}

		/**
		 * Load Customizer Control Scripts
		 *
		 * Loads the required js for the custom controls in the live 
		 * theme previewer. This is hooked into the live previewer 
		 * using the action: 'customize_controls_enqueue_scripts'.
		 *  
		 * @return void
		 *
		 * @since  1.2
		 * @version 1.3.1
		 * 
		 */
		public function customize_controls_enqueue_scripts() {

			// Load JSON Library by Douglas Crockford
			wp_enqueue_script( 'json2' );

			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'iris' );

			// Load WordPress media lightbox
			wp_enqueue_media();

			// Load js for live customizer control
			wp_deregister_script( $this->plugin_slug . '-customizer-controls-js' );
			wp_register_script( 
				$this->plugin_slug . '-customizer-controls-js',
				Easy_Google_Fonts::get_js_url() . '/customizer-controls.js',
				array( 'jquery' ), 
				Easy_Google_Fonts::VERSION, 
				false 
			);
			wp_enqueue_script( $this->plugin_slug . '-customizer-controls-js' );

			// Load in customizer control javascript object
			$previewl10n = $this->customize_live_preview_l10n();
			wp_localize_script( $this->plugin_slug . '-customizer-controls-js', 'ttFontCustomizeSettings', $previewl10n );

			$translationl10n = $this->customize_control_l10n();
			wp_localize_script( $this->plugin_slug . '-customizer-controls-js', 'ttFontTranslation', $translationl10n );

			$all_fonts = $this->customize_load_all_fonts();
			wp_localize_script( $this->plugin_slug . '-customizer-controls-js', 'ttFontAllFonts', $all_fonts );

		}

		/**
		 * Load Customizer Live Preview Scripts
		 *
		 * Loads the required js for the live theme previewer. This
		 * is hooked into the live previewer using the action:
		 * 'customize_preview_init'. Updates options visually in the
		 * live previewer without refreshing the page.
		 *  
		 * @return void
		 *
		 * @since  1.3
		 * @version 1.3.1
		 * 
		 */
		public function customize_live_preview_scripts() {
			global $wp_customize;

			// Load JSON Library by Douglas Crockford
			wp_enqueue_script( 'json2' );

			// Load js for live customizer control
			wp_deregister_script( $this->plugin_slug . '-customizer-preview-js' );
			wp_register_script( 
				$this->plugin_slug . '-customizer-preview-js',
				Easy_Google_Fonts::get_js_url() . '/customizer-preview.js',
				false, 
				Easy_Google_Fonts::VERSION, 
				false 
			);
			wp_enqueue_script( $this->plugin_slug . '-customizer-preview-js' );

			$previewl10n = $this->customize_live_preview_l10n();
			wp_localize_script( $this->plugin_slug . '-customizer-preview-js', 'ttFontPreviewControls', $previewl10n );

			$all_fonts = $this->customize_load_all_fonts();
			wp_localize_script( $this->plugin_slug . '-customizer-preview-js', 'ttFontAllFonts', $all_fonts );
		}

		/**
		 * Load Customizer Styles
		 *
		 * Loads the required css for the live theme previewer. It is used
		 * as a way to style the custom customizer controls on the live
		 * preview screen. This is hooked into the live previewer using the 
		 * action: 'customize_register'.
		 *  
		 * @return void
		 *
		 * @since  1.3
		 * @version 1.3.1
		 * 
		 */
		public function customize_preview_styles() {

			wp_enqueue_style( 'wp-color-picker' );

			// Load CSS to style custom customizer controls
			wp_register_style( 
				$this->plugin_slug . '-customizer-css',
				Easy_Google_Fonts::get_css_url() . '/customizer.css',
				false, 
				Easy_Google_Fonts::VERSION 
			);
			wp_enqueue_style( $this->plugin_slug . '-customizer-css' );
		}

		/**
		 * Load Custom Customizer JS Object
		 *
		 * Major rewrite, now generates the array without 
		 * referencing the customizer manager, which increases
		 * performance and prevents recursion.
		 * 
		 * @return array $controls 	Control properties which will be enqueues as a JSON object on the page
		 *
		 * @since  1.3
		 * @version 1.3.1
		 * 
		 */
		public function customize_live_preview_l10n() {

			$controls      = array();
			$font_controls = array_merge( EGF_Register_Options::get_option_parameters(), EGF_Register_Options::get_custom_option_parameters( array() ) );

			foreach ( $font_controls as $key => $value ) {

				$controls[ $key ] = array(
					'id'           => $key,
					'label'        => $value['title'],
					'type'         => $value['type'],
					'section'      => 'tt_font_typography',
					'force_styles' => $value['properties']['force_styles'],
					'json'         => array(),
					'selector'     => $value['properties']['selector'],
					'setting'     => array(
							'capability'           => 'edit_theme_options',
							'id'                   => "tt_font_theme_options[{$key}]",
							'default'              => $value['default'],
							'sanitize_callback'    => '',
							'sanitize_js_callback' => '',
							'theme_supports'       => '',
							'transport'            => $value['transport'],
							'type'                 => 'option'	
					),				
				);
			}
			
			return $controls;
		}

		/**
		 * Load Customizer Translation JS Object
		 * 
		 * @return void
		 *
		 * @since  1.3
		 * @version 1.3.1
		 * 
		 */
		public function customize_control_l10n() {
			$translations = array(
				'themeDefault' => '&mdash; ' . __( 'Theme Default', 'easy-google-fonts' ) . ' &mdash;',

			);
			return $translations;
		}

		/**
		 * Customizer Save Action Hook
		 *
		 * Specifically add code that you want to execute when
		 * the font setting is being saved.
		 * 
		 * @since  1.3
		 * @version 1.3.1
		 * 
		 */
		public function customize_save_tt_font_theme_options() {
		}

		/**
		 * Customizer Save Action Hook
		 *
		 * Remove / refresh any stored tranients that have 
		 * become stale due to the user changing options.
		 * This function can also be used to add any function
		 * that you wish to run after the options have been
		 * saved.
		 * 
		 * @since  1.3
		 * @version 1.3.1
		 * 
		 */
		public function customize_save_after() {
			delete_transient( 'tt_font_dynamic_styles' );
			delete_transient( 'tt_font_theme_options' );
		}

		/**
		 * Theme Customizer Controls Implementation
		 *
		 * Implement the Theme Customizer for the Theme Settings
		 * in this theme.
		 * 
		 * @link	http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/	Otto
		 * 
		 * @see final class WP_Customize_Manager 	defined in \{root}\wp-includes\class-wp-customize-manager.php 
		 * 
		 * @param 	object	$wp_customize	Object that holds the customizer data
		 * 
		 * @since  1.3
		 * @version 1.3.1
		 * 
		 */
		public function register_controls( $wp_customize ) {

			if ( ! isset( $wp_customize ) ) {
				return;
			}
			
			$tt_font_options = EGF_Register_Options::get_options( false );
			
			// Get the array of option parameters
			$option_parameters = EGF_Register_Options::get_option_parameters();

			// Get list of tabs
			$tabs = EGF_Register_Options::get_setting_tabs();

			/**
			 * 1. Register Section
			 *
			 * Add Each Customizer Section: 
			 * Add each customizer section based on each $tab section
			 * from EGF_Register_Options::get_setting_tabs()
			 * 
			 */
			foreach ( $tabs as $tab ) {
				// Add $tab section
				$wp_customize->add_section( 'tt_font_' . $tab['name'], array(
					'title'       => $tab['title'],
					'description' => $tab['description']
				) );
			}

			/**
			 * 2. Add Settings to Sections
			 * 3. Register Control for Each Setting
			 *  
			 */
			$priority = 0;
			foreach ( $option_parameters as $option_parameter ) {
				/**
				 * Set Transport Method:
				 * 
				 * Default is to reload the iframe when the option is 
				 * modified in the customizer. 
				 * 
				 * DEVELOPER NOTE: To change the transport type for each 
				 * option modify the 'transport' value for the appropriate 
				 * option in the $options array found in tt_font_get_option_parameters()
				 * 
				 */
				$transport = empty( $option_parameter['transport'] ) ? 'refresh' : $option_parameter['transport'];

				/**
				 * Add Setting To Customizer:
				 * 
				 * Adds $option_parameter setting to customizer
				 * further properties are registered below. The
				 * color properties are registered as separate 
				 * settings for performance reasons.
				 * 
				 */
				$wp_customize->add_setting( 'tt_font_theme_options[' . $option_parameter['name'] . ']', array(
					'default'        => $option_parameter['default'],
					'type'           => 'option',
					'transport'      => $transport,
				) );

				$wp_customize->add_setting( 'tt_font_theme_options[' . $option_parameter['name'] . '][font_color]', array(
					'default'        => $option_parameter['default']['font_color'],
					'type'           => 'option',
					'transport'      => $transport,
				) );

				$wp_customize->add_setting( 'tt_font_theme_options[' . $option_parameter['name'] . '][background_color]', array(
					'default'        => $option_parameter['default']['background_color'],
					'type'           => 'option',
					'transport'      => $transport,
				) );

				/**
				 * Section Prefix:
				 *
				 * Add the 'tt_font_' prefix to prevent namespace
				 * collisions. Removes the prefix if we are adding
				 * this option to a default WordPress section.
				 *  
				 */
				$prefix = empty( $option_parameter['wp_section'] ) ? 'tt_font_' : '' ;

				// Set control $priority
				$priority += 20;



				switch ( $option_parameter['type'] ) {
					case 'font' :
						$wp_customize->add_control( 
							new EGF_Font_Control( 
								$wp_customize, 
								$option_parameter['name'], 
								array(
									'label'    => $option_parameter['title'],
									'section'  => 'tt_font_' . $option_parameter['tab'],
									'settings' => 'tt_font_theme_options['. $option_parameter['name'] . ']',
									'priority' => $priority,
									'option'   => $option_parameter,
								)
							) 
						);
						break;

					// Here in case we decide to implement an additional lightweight control
					case 'font_basic':
						break;
				}
			}

		}

	}
endif;