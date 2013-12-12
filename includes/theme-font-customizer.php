<?php 
/**
 * Google Font Options Theme Customizer Integration
 *
 * This file integrates the Theme Customizer for this Theme. 
 * All options in this theme are managed in the live customizer. 
 * We believe that themes should only alter the display of content 
 * and should not add any additional functionality that would be 
 * better suited for a plugin. Since all options are presentation 
 * centered, they should all be controllable by the Customizer.
 * 
 * @package     WordPress
 * @subpackage  WordPress_Google_Fonts
 * @author      Sunny Johal - Titanium Themes
 * @copyright   Copyright (c) 2013, Titanium Themes
 * @version     1.1.1
 * 
 */
/**
 * Load Customizer Control Scripts
 *
 * Loads the required js for the custom controls in the live 
 * theme previewer. This is hooked into the live previewer 
 * using the action: 'customize_controls_enqueue_scripts'.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_register_script  			wp_register_script()
 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script  			wp_enqueue_script()
 * @link http://codex.wordpress.org/Function_Reference/plugins_url  				plugins_url()
 * @link http://codex.wordpress.org/Function_Reference/add_action  					add_action()
 *  
 * @return void
 *
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_customize_control_scripts() {

	// Load WordPress media lightbox
	wp_enqueue_media();

	// Load js for live customizer control
	wp_deregister_script( 'tt-font-customizer-controls-js' );
	wp_register_script( 
		'tt-font-customizer-controls-js', 
		plugins_url( 'easy-google-fonts' ) . '/js/font-customizer-controls.js', 
		false, 
		'1.0', 
		false 
	);
	wp_enqueue_script( 'tt-font-customizer-controls-js' );

	// Load in customizer control javascript object
	$previewl10n = tt_font_customize_live_preview_l10n();
	wp_localize_script( 'tt-font-customizer-controls-js', 'ttFontCustomizeSettings', $previewl10n );

	$translationl10n = tt_font_customize_control_l10n();
	wp_localize_script( 'tt-font-customizer-controls-js', 'ttFontTranslation', $translationl10n );

}
add_action( 'customize_controls_enqueue_scripts', 'tt_font_customize_control_scripts' );

/**
 * Load Customizer Live Preview Scripts
 *
 * Loads the required js for the live theme previewer. This
 * is hooked into the live previewer using the action:
 * 'customize_preview_init'. Updates options visually in the
 * live previewer without refreshing the page.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_register_script  			wp_register_script()
 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script  			wp_enqueue_script()
 * @link http://codex.wordpress.org/Function_Reference/plugins_url  				plugins_url()
 * @link http://codex.wordpress.org/Function_Reference/add_action  					add_action()
 *  
 * @return void
 *
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_customize_live_preview_scripts() {
	global $wp_customize;

	// Load js for live customizer control
	wp_deregister_script( 'tt-font-customizer-preview-js' );
	wp_register_script( 
		'tt-font-customizer-preview-js', 
		plugins_url( 'easy-google-fonts' ) . '/js/font-customizer-preview.js', 
		false, 
		'1.0', 
		false 
	);
	wp_enqueue_script( 'tt-font-customizer-preview-js' );

	// Load in customizer control javascript object
	$previewl10n = tt_font_customize_live_preview_l10n();
	wp_localize_script( 'tt-font-customizer-preview-js', 'ttFontPreviewControls', $previewl10n );
}
add_action( 'customize_preview_init', 'tt_font_customize_live_preview_scripts' );

/**
 * Load Customizer Translation JS Object
 * 
 * @return void
 *
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_customize_control_l10n() {
	$translations = array(
		'themeDefault' => '&mdash; ' . __( 'Theme Default', 'theme-translate' ) . ' &mdash;',
	);

	return $translations;
}

/**
 * Load Custom Customizer JS Object
 *
 * Copies the $wp_customize->controls() object and enqueues
 * it onto the page so that we are able to use the values
 * without affecting the main previewer.
 * 
 * @return array $controls 	Control properties which will be enqueues as a JSON object on the page
 *
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_customize_live_preview_l10n() {

	$controls = array();

	global $wp_customize;

	if ( isset( $wp_customize ) ) {
		$controls = $wp_customize->controls();

		foreach ( $controls as $key => $value ) {

			$font_control = ( $value->type == 'font' || $value->type == 'font_basic' ) ? true : false;

			if ( ! $font_control ) {
				unset( $controls[ $key ] );
			}
		}	
	}
	
	return $controls;
}

/**
 * Load Customizer Styles
 *
 * Loads the required css for the live theme previewer. It is used
 * as a way to style the custom customizer controls on the live
 * preview screen. This is hooked into the live previewer using the 
 * action: 'customize_register'.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_register_style  			wp_register_style()
 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_style  			wp_enqueue_style()
 * @link http://codex.wordpress.org/Function_Reference/plugins_url  				plugins_url()
 * @link http://codex.wordpress.org/Function_Reference/add_action  					add_action()
 *  
 * @return void
 *
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_customize_preview_styles() {

	// Load CSS to style custom customizer controls
	wp_register_style( 
		'tt-font-customizer-css', 
		plugins_url( 'easy-google-fonts' ) . '/css/font-customizer.css', 
		false, 
		1.0 
	);
	wp_enqueue_style( 'tt-font-customizer-css' );
}
add_action( 'customize_register', 'tt_font_customize_preview_styles' ); 

/**
 * Customizer Save Action Hook
 *
 * Remove / refresh any stored tranients that have 
 * become stale due to the user changing options.
 * This function can also be used to add any function
 * that you wish to run after the options have been
 * saved. 
 *
 * @link http://codex.wordpress.org/Function_Reference/delete_transient  			delete_transient()
 * @link http://codex.wordpress.org/Function_Reference/add_action  					add_action()
 * 
 * @since 1.0
 * @version 1.1.1
 * 
 */
function tt_font_customize_save_after() {
	delete_transient( 'tt_font_dynamic_styles' );
	delete_transient( 'tt_font_theme_options' );
}
add_action( 'customize_save_after', 'tt_font_customize_save_after' );

/**
 * Load Custom WordPress Customizer Controls
 *
 * Loads all of the custom control classes that are used
 * in the WordPress Customizer live preview.
 * 
 * @link http://codex.wordpress.org/Function_Reference/get_template_directory_uri  	get_template_directory_uri()
 * 
 * @return void
 */
function tt_font_get_custom_controls() {

	// Define directory location of custom control modules
	
	$controls = plugin_dir_path( __FILE__ ) . 'classes/controls';
	
	include_once( "{$controls}/class-titanium-font-control.php" );
}

/**
 * Theme Settings Theme Customizer Implementation
 *
 * Implement the Theme Customizer for the Theme Settings
 * in this theme.
 * 
 * @link	http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/	Otto
 *
 * @uses	tt_font_get_options()				defined in \includes\theme-font-options.php
 * @uses	tt_font_get_option_parameters()		defined in \includes\theme-font-options.php
 * @uses	tt_font_get_settings_page_tabs()	defined in \includes\theme-font-options.php
 * 
 * @see final class WP_Customize_Manager 	defined in \{root}\wp-includes\class-wp-customize-manager.php 
 * 
 * @param 	object	$wp_customize	Object that holds the customizer data
 * 
 *
 * @since 1.0
 * @version 1.1.1
 * 
 */

function tt_font_register_theme_customizer( $wp_customize ) {

	// Failsafe is safe
	if ( ! isset( $wp_customize ) ) {
		return;
	}
	

	global $tt_font_options;
	$tt_font_options = tt_font_get_options( false );

	// Get Custom Controls
	tt_font_get_custom_controls();

	// Get the array of option parameters
	$option_parameters = tt_font_get_option_parameters();

	// Get list of tabs
	$tabs = tt_font_get_settings_page_tabs();	

	/**
	 * Add Each Customizer Section 
	 * 
	 * Add each customizer section based on each $tab section
	 * from tt_font_get_settings_page_tabs()
	 * 
	 */
	foreach ( $tabs as $tab ) {
		// Add $tab section
		$wp_customize->add_section( 'tt_font_' . $tab['name'], array(
			'title'	 => $tab['title'] 
		) );
	}

	/*
	 * Add Settings to Sections
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
		 * further properties are registered below.
		 * 
		 */
		$wp_customize->add_setting( 'tt_font_theme_options[' . $option_parameter['name'] . ']', array(
			'default'        => $option_parameter['default'],
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

				// Default Values
				$default_font_id = isset( $option_parameter['default']['font_id'] ) ? $option_parameter['default']['font_id'] : '';

				$option_arr_name = 'tt_font_theme_options[' . $option_parameter['name'] . ']';

				// Holds all font settings
				$font_settings_arr = array( 
					array( 'name' => 'font_id', 			'default_value' => $option_parameter['default']['font_id'], 			'has_unit' => false ),
					array( 'name' => 'font_name', 			'default_value' => $option_parameter['default']['font_name'], 			'has_unit' => false ),
					array( 'name' => 'font_color', 			'default_value' => $option_parameter['default']['font_color'], 			'has_unit' => false ),
					array( 'name' => 'background_color', 	'default_value' => $option_parameter['default']['background_color'], 	'has_unit' => false ),
					array( 'name' => 'font_weight', 		'default_value' => $option_parameter['default']['font_weight'], 		'has_unit' => false ),
					array( 'name' => 'font_weight_style', 	'default_value' => $option_parameter['default']['font_weight_style'], 	'has_unit' => false ),
					array( 'name' => 'font_style', 			'default_value' => $option_parameter['default']['font_style'], 			'has_unit' => false ),
					array( 'name' => 'text_decoration', 	'default_value' => $option_parameter['default']['text_decoration'], 	'has_unit' => false ),
					array( 'name' => 'text_transform', 		'default_value' => $option_parameter['default']['text_transform'], 		'has_unit' => false ),
					array( 'name' => 'stylesheet_url', 		'default_value' => $option_parameter['default']['stylesheet_url'], 		'has_unit' => false ),
					array( 'name' => 'line_height', 		'default_value' => $option_parameter['default']['line_height'], 		'has_unit' => false ),
					array( 'name' => 'font_size', 			'default_value' => $option_parameter['default']['font_size'], 			'has_unit' => true ),
					array( 'name' => 'letter_spacing', 		'default_value' => $option_parameter['default']['letter_spacing'], 		'has_unit' => true ),
					// array( 'name' => 'margin_top', 			'default_value' => $option_parameter['default']['margin_top'], 			'has_unit' => true ),
					// array( 'name' => 'margin_right', 		'default_value' => $option_parameter['default']['margin_right'], 		'has_unit' => true ),
					// array( 'name' => 'margin_bottom', 		'default_value' => $option_parameter['default']['margin_bottom'],		'has_unit' => true ),
					// array( 'name' => 'margin_left', 		'default_value' => $option_parameter['default']['margin_left'], 		'has_unit' => true ),
					// array( 'name' => 'padding_top', 		'default_value' => $option_parameter['default']['padding_top'], 		'has_unit' => true ),
					// array( 'name' => 'padding_right', 		'default_value' => $option_parameter['default']['padding_right'], 		'has_unit' => true ),
					// array( 'name' => 'padding_bottom', 		'default_value' => $option_parameter['default']['padding_bottom'],		'has_unit' => true ),
					// array( 'name' => 'padding_left', 		'default_value' => $option_parameter['default']['padding_left'], 		'has_unit' => true ),
				);
				

				// Register each font setting with the customizer
				foreach ( $font_settings_arr as $setting ) {

					if ( true === $setting['has_unit'] ) {
						// print_r( '<pre>'. $option_arr_name . '[' . $setting['name'] . '][amount]' . '</pre>');
						// print_r( '<pre>'. $option_arr_name . '[' . $setting['name'] . '][unit]' . '</pre>');

						$wp_customize->add_setting(  $option_arr_name . '[' . $setting['name'] . '][amount]', array(
							'default'        => $setting['default_value']['amount'],
							'type'           => 'option',
							'transport'      => $transport,
						));

						$wp_customize->add_setting(  $option_arr_name . '[' . $setting['name'] . '][unit]', array(
							'default'        => $setting['default_value']['unit'],
							'type'           => 'option',
							'transport'      => $transport,
						));

					} else {
						
						$wp_customize->add_setting(  $option_arr_name . '[' . $setting['name'] . ']', array(
							'default'        => $setting['default_value'],
							'type'           => 'option',
							'transport'      => $transport,
						));

					}
				}

				// Register Control
				$wp_customize->add_control(
					new TT_Font_Control(
						$wp_customize,
						$option_parameter['name'],
						array(
							'label'                         => $option_parameter['title'],
							'section'                       => 'tt_font_' . $option_parameter['tab'],
							'settings'                      => 'tt_font_theme_options['. $option_parameter['name'] . ']',			
							'priority'                      => $priority,
							'default_values'                => $option_parameter['default'],
							'selector'                      => $option_parameter['properties']['selector'],
							'force_styles'                  => $option_parameter['properties']['force_styles'],
							'font_size_min_range'           => $option_parameter['properties']['font_size_min_range'],
							'font_size_max_range'           => $option_parameter['properties']['font_size_max_range'],
							'font_size_step'                => $option_parameter['properties']['font_size_step'],
							'line_height_min_range'         => $option_parameter['properties']['line_height_min_range'],
							'line_height_max_range'         => $option_parameter['properties']['line_height_max_range'],
							'line_height_step'              => $option_parameter['properties']['line_height_step'],	
							'letter_spacing_min_range'      => $option_parameter['properties']['letter_spacing_min_range'],
							'letter_spacing_max_range'      => $option_parameter['properties']['letter_spacing_max_range'],
							'letter_spacing_step'           => $option_parameter['properties']['letter_spacing_step'],
							'margin_min_range'              => $option_parameter['properties']['margin_min_range'],
							'margin_max_range'              => $option_parameter['properties']['margin_max_range'],
							'margin_step'                   => $option_parameter['properties']['margin_step'],
							'padding_min_range'             => $option_parameter['properties']['padding_min_range'],
							'padding_max_range'             => $option_parameter['properties']['padding_max_range'],
							'padding_step'                  => $option_parameter['properties']['padding_step'],
							'google_fonts'                  => $option_parameter['properties']['google_fonts'],
							'default_fonts'                 => $option_parameter['properties']['default_fonts'],
							'font_id'                       => $option_parameter['default']['font_id'],
							'font_name'                     => $option_parameter['default']['font_name'],
							'font_color'                    => $option_parameter['default']['font_color'],
							'font_weight'                   => $option_parameter['default']['font_weight'],
							'font_style'                    => $option_parameter['default']['font_style'],
							'stylesheet_url'                => $option_parameter['default']['stylesheet_url'],
							'text_decoration'               => $option_parameter['default']['text_decoration'],
							'text_transform'                => $option_parameter['default']['text_transform'],
							'line_height'                   => $option_parameter['default']['line_height'],
							'default_font_size_amount'      => $option_parameter['default']['font_size']['amount'],
							'default_font_size_unit'        => $option_parameter['default']['font_size']['unit'],
							'default_letter_spacing_amount' => $option_parameter['default']['letter_spacing']['amount'],
							'default_letter_spacing_unit'   => $option_parameter['default']['letter_spacing']['unit'],


						)
					)
				);

				break;

			default:
				# code...
				break;
		} // end switch

	} // endforeach


}

// Settings API options initilization and validation
add_action( 'customize_register', 'tt_font_register_theme_customizer', 0 );

