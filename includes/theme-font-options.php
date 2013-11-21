<?php 
/**
 * Theme Font Options
 *
 * This file defines the Options for this Theme. The theme options 
 * structure is heavily based on the theme options structure in Chip 
 * Bennett's Oenology Theme. We take the same stance as Automattic 
 * and exclusively use the Customizer for theme options instead of 
 * creating theme option pages. 
 * 
 * Theme Options Functions
 * 
 *  - Define Default Theme Options
 *  - Register/Initialize Theme Options
 * 
 * @package     WordPress
 * @subpackage  WordPress_Google_Fonts
 * @author      Sunny Johal - Titanium Themes
 * @copyright   Copyright (c) 2013, Titanium Themes
 * @version     1.0
 * 
 */

/**
 * Globalize the variable that holds the Theme Font Options
 * 
 * @global	array	$tt_font_options	holds Theme options
 */
global $tt_font_options;

/**
 * Theme Settings API Implementation
 *
 * Implement the WordPress Settings API for the 
 * Theme Font Settings.
 * 
 * @link	http://codex.wordpress.org/Settings_API	Codex Reference: Settings API
 * @link	http://ottopress.com/2009/wordpress-settings-api-tutorial/	Otto
 * @link	http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/	Ozh
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_register_options() {
	require( plugin_dir_path(__FILE__) . '/theme-font-options-register.php' );
}
add_action( 'admin_init', 'tt_font_register_options' );

/**
 * Theme Font Option Defaults
 * 
 * Returns an associative array that holds all of the default 
 * values for all of the theme font options.
 * 
 * @link    http://codex.wordpress.org/Function_Reference/apply_filters      apply_filters()
 * 
 * @uses	tt_font_get_option_parameters()	defined in \includes\theme-font-options.php
 * 
 * @return	array	$defaults	associative array of option defaults
 * 
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_get_option_defaults() {
	
	// Get the array that holds all theme font option parameters
	$tt_font_parameters = tt_font_get_option_parameters();
	
	// Initialize the array to hold the default values for 
	// all the font options
	$tt_font_defaults = array();
	
	// Loop through the font option parameters array
	foreach ( $tt_font_parameters as $tt_font_parameter ) {
		
		/*
		 * Add an associative array key to the defaults array for each
		 * option in the parameters array
		 */ 
		$name                      = $tt_font_parameter['name'];
		$tt_font_defaults[ $name ] = $tt_font_parameter['default'];
	}
	// Return the defaults array
	return apply_filters( 'tt_font_option_defaults', $tt_font_defaults );	
}

/**
 * Get Theme Font Option Parameters
 * 
 * Array that holds parameters for all default font options in this theme. 
 * The 'type' key is used to generate the proper form field markup and to 
 * sanitize the user-input data properly. The 'tab' key determines the 
 * Settings Page on which the option appears, and the 'section' tab 
 * determines the section of the Settings Page tab in which the option 
 * appears.
 *
 * @uses	tt_font_get_google_fonts()	defined in \includes\theme-font-functions.php
 * @uses	tt_font_get_default_fonts()	defined in \includes\theme-font-functions.php
 * 
 * @return	array	$options	array of arrays of option parameters
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_get_option_parameters() {
	
	// Get fonts once and reuse for performance
	$google_fonts  = tt_font_get_google_fonts();
	$default_fonts = tt_font_get_default_fonts();

	$options = array(
		
		/**
		 * Typography Tab Options
		 *
		 * The following options are used to register controls
		 * that will appear in the 'Typography' section in the
		 * Customizer
		 *
		 * @since 1.0
		 * @version 1.0
		 */
		'tt_default_body' => array(
			'name'        => 'tt_default_body',
			'title'       => __( 'Paragraphs', 'theme-translate' ),
			'type'        => 'font',
			'description' => __( "Please select a font for the theme's body and paragraph text", 'theme-translate' ),
			'section'     => 'default',
			'tab'         => 'typography',
			'transport'   => 'postMessage',
			'since'       => '1.0',
			'properties'  => array(
								'selector'                 => 'p',
								'force_styles'             => false,
								'font_size_min_range'      => '10',
								'font_size_max_range'      => '100',
								'font_size_step'           => '1',
								'line_height_min_range'    => '0.8',
								'line_height_max_range'    => '4',
								'line_height_step'         => '0.1',
								'letter_spacing_min_range' => '-5',
								'letter_spacing_max_range' => '20',
								'letter_spacing_step'      => '1',
								'google_fonts'             => $google_fonts,
								'default_fonts'            => $default_fonts,
							),
			'default'     => array(
								'font_id'             => '',
								'font_name'           => '',
								'font_color'          => '',
								'font_weight'         => '',
								'font_style'          => '',
								'font_weight_style'   => '',
								'stylesheet_url'      => '',
								'text_decoration'     => '',
								'text_transform'      => '',
								'line_height'         => '',
								'font_size'           => array( 
															'amount' => '',
															'unit'   => 'px'
														),
								
								'letter_spacing'      => array( 
															'amount' => '',
															'unit'   => 'px'
														),
							)
		),

		'tt_default_heading_1' => array(
			'name'        => 'tt_default_heading_1',
			'title'       => __( 'Heading 1', 'theme-translate' ),
			'type'        => 'font',
			'description' => __( "Please select a font for the theme's heading 1 styles", 'theme-translate' ),
			'section'     => 'default',
			'tab'         => 'typography',
			'transport'   => 'postMessage',
			'since'       => '1.0',
			'properties'  => array(
								'selector'                 => 'h1',
								'force_styles'             => false,
								'font_size_min_range'      => '10',
								'font_size_max_range'      => '100',
								'font_size_step'           => '1',
								'line_height_min_range'    => '0.8',
								'line_height_max_range'    => '4',
								'line_height_step'         => '0.1',
								'letter_spacing_min_range' => '-5',
								'letter_spacing_max_range' => '20',
								'letter_spacing_step'      => '1',
								'google_fonts'             => $google_fonts,
								'default_fonts'            => $default_fonts,
							),
			'default'     => array(
								'font_id'             => '',
								'font_name'           => '',
								'font_color'          => '',
								'font_weight'         => '',
								'font_style'          => '',
								'font_weight_style'   => '',
								'stylesheet_url'      => '',
								'text_decoration'     => '',
								'text_transform'      => '',
								'line_height'         => '',
								'font_size'           => array( 
															'amount' => '',
															'unit'   => 'px'
														),
								
								'letter_spacing'      => array( 
															'amount' => '',
															'unit'   => 'px'
														),
							)
		),

		'tt_default_heading_2' => array(
			'name'        => 'tt_default_heading_2',
			'title'       => __( 'Heading 2', 'theme-translate' ),
			'type'        => 'font',
			'description' => __( "Please select a font for the theme's heading 2 styles", 'theme-translate' ),
			'section'     => 'default',
			'tab'         => 'typography',
			'transport'   => 'postMessage',
			'since'       => '1.0',
			'properties'  => array(
								'selector'                 => 'h2',
								'force_styles'             => false,
								'font_size_min_range'      => '10',
								'font_size_max_range'      => '100',
								'font_size_step'           => '1',
								'line_height_min_range'    => '0.8',
								'line_height_max_range'    => '4',
								'line_height_step'         => '0.1',
								'letter_spacing_min_range' => '-5',
								'letter_spacing_max_range' => '20',
								'letter_spacing_step'      => '1',
								'google_fonts'             => $google_fonts,
								'default_fonts'            => $default_fonts,
							),
			'default'     => array(
								'font_id'             => '',
								'font_name'           => '',
								'font_color'          => '',
								'font_weight'         => '',
								'font_style'          => '',
								'font_weight_style'   => '',
								'stylesheet_url'      => '',
								'text_decoration'     => '',
								'text_transform'      => '',
								'line_height'         => '',
								'font_size'           => array( 
															'amount' => '',
															'unit'   => 'px'
														),
								
								'letter_spacing'      => array( 
															'amount' => '',
															'unit'   => 'px'
														),
							)
		),

		'tt_default_heading_3' => array(
			'name'        => 'tt_default_heading_3',
			'title'       => __( 'Heading 3', 'theme-translate' ),
			'type'        => 'font',
			'description' => __( "Please select a font for the theme's heading 3 styles", 'theme-translate' ),
			'section'     => 'default',
			'tab'         => 'typography',
			'transport'   => 'postMessage',
			'since'       => '1.0',
			'properties'  => array(
								'selector'                 => 'h3',
								'force_styles'             => false,
								'font_size_min_range'      => '10',
								'font_size_max_range'      => '100',
								'font_size_step'           => '1',
								'line_height_min_range'    => '0.8',
								'line_height_max_range'    => '4',
								'line_height_step'         => '0.1',
								'letter_spacing_min_range' => '-5',
								'letter_spacing_max_range' => '20',
								'letter_spacing_step'      => '1',
								'google_fonts'             => $google_fonts,
								'default_fonts'            => $default_fonts,
							),
			'default'     => array(
								'font_id'             => '',
								'font_name'           => '',
								'font_color'          => '',
								'font_weight'         => '',
								'font_style'          => '',
								'font_weight_style'   => '',
								'stylesheet_url'      => '',
								'text_decoration'     => '',
								'text_transform'      => '',
								'line_height'         => '',
								'font_size'           => array( 
															'amount' => '',
															'unit'   => 'px'
														),
								
								'letter_spacing'      => array( 
															'amount' => '',
															'unit'   => 'px'
														),
							)
		),

		'tt_default_heading_4' => array(
			'name'        => 'tt_default_heading_4',
			'title'       => __( 'Heading 4', 'theme-translate' ),
			'type'        => 'font',
			'description' => __( "Please select a font for the theme's heading 4 styles", 'theme-translate' ),
			'section'     => 'default',
			'tab'         => 'typography',
			'transport'   => 'postMessage',
			'since'       => '1.0',
			'properties'  => array(
								'selector'                 => 'h4',
								'force_styles'             => false,
								'font_size_min_range'      => '10',
								'font_size_max_range'      => '100',
								'font_size_step'           => '1',
								'line_height_min_range'    => '0.8',
								'line_height_max_range'    => '4',
								'line_height_step'         => '0.1',
								'letter_spacing_min_range' => '-5',
								'letter_spacing_max_range' => '20',
								'letter_spacing_step'      => '1',
								'google_fonts'             => $google_fonts,
								'default_fonts'            => $default_fonts,
							),
			'default'     => array(
								'font_id'             => '',
								'font_name'           => '',
								'font_color'          => '',
								'font_weight'         => '',
								'font_style'          => '',
								'font_weight_style'   => '',
								'stylesheet_url'      => '',
								'text_decoration'     => '',
								'text_transform'      => '',
								'line_height'         => '',
								'font_size'           => array( 
															'amount' => '',
															'unit'   => 'px'
														),
								
								'letter_spacing'      => array( 
															'amount' => '',
															'unit'   => 'px'
														),
							)
		),

		'tt_default_heading_5' => array(
			'name'        => 'tt_default_heading_5',
			'title'       => __( 'Heading 5', 'theme-translate' ),
			'type'        => 'font',
			'description' => __( "Please select a font for the theme's heading 5 styles", 'theme-translate' ),
			'section'     => 'default',
			'tab'         => 'typography',
			'transport'   => 'postMessage',
			'since'       => '1.0',
			'properties'  => array(
								'selector'                 => 'h5',
								'force_styles'             => false,
								'font_size_min_range'      => '10',
								'font_size_max_range'      => '100',
								'font_size_step'           => '1',
								'line_height_min_range'    => '0.8',
								'line_height_max_range'    => '4',
								'line_height_step'         => '0.1',
								'letter_spacing_min_range' => '-5',
								'letter_spacing_max_range' => '20',
								'letter_spacing_step'      => '1',
								'google_fonts'             => $google_fonts,
								'default_fonts'            => $default_fonts,
							),
			'default'     => array(
								'font_id'             => '',
								'font_name'           => '',
								'font_color'          => '',
								'font_weight'         => '',
								'font_style'          => '',
								'font_weight_style'   => '',
								'stylesheet_url'      => '',
								'text_decoration'     => '',
								'text_transform'      => '',
								'line_height'         => '',
								'font_size'           => array( 
															'amount' => '',
															'unit'   => 'px'
														),
								
								'letter_spacing'      => array( 
															'amount' => '',
															'unit'   => 'px'
														),
							)
		),

		'tt_default_heading_6' => array(
			'name'        => 'tt_default_heading_6',
			'title'       => __( 'Heading 6', 'theme-translate' ),
			'type'        => 'font',
			'description' => __( "Please select a font for the theme's heading 6 styles", 'theme-translate' ),
			'section'     => 'default',
			'tab'         => 'typography',
			'transport'   => 'postMessage',
			'since'       => '1.0',
			'properties'  => array(
								'selector'                 => 'h5',
								'force_styles'             => false,
								'font_size_min_range'      => '10',
								'font_size_max_range'      => '100',
								'font_size_step'           => '1',
								'line_height_min_range'    => '0.8',
								'line_height_max_range'    => '4',
								'line_height_step'         => '0.1',
								'letter_spacing_min_range' => '-5',
								'letter_spacing_max_range' => '20',
								'letter_spacing_step'      => '1',
								'google_fonts'             => $google_fonts,
								'default_fonts'            => $default_fonts,
							),
			'default'     => array(
								'font_id'             => '',
								'font_name'           => '',
								'font_color'          => '',
								'font_weight'         => '',
								'font_style'          => '',
								'font_weight_style'   => '',
								'stylesheet_url'      => '',
								'text_decoration'     => '',
								'text_transform'      => '',
								'line_height'         => '',
								'font_size'           => array( 
															'amount' => '',
															'unit'   => 'px'
														),
								
								'letter_spacing'      => array( 
															'amount' => '',
															'unit'   => 'px'
														),
							)
		),
	);

	return apply_filters( 'tt_font_get_option_parameters', $options );
}

/**
 * Get Theme Options
 * 
 * Array that holds all of the defined values for the current 
 * theme options. If the user has not specified a value for a 
 * given Theme option, then the option's default value is
 * used instead. This function uses the WordPress Transients API
 * in order to increase speed performance. Please make sure
 * that you refresh the transient if you modify this function.
 *
 * Uses the following transient: 'tt_font_theme_options'
 *
 * Note: In order to refresh the transient that is set in this
 * function please visit the Customizer. This will automatically
 * refresh the transient.
 *
 * @link    http://codex.wordpress.org/Function_Reference/get_option 			get_option() 
 * @link    http://codex.wordpress.org/Function_Reference/wp_parse_args 		wp_parse_args() 
 * @link	http://codex.wordpress.org/Function_Reference/get_transient 		get_transient()
 * @link	http://codex.wordpress.org/Function_Reference/set_transient 		set_transient()
 *
 * @uses 	global 	$wp_customize
 * @uses	tt_font_get_option_defaults()	defined in \includes\theme-font-options.php
 *  
 * @return	array	$tt_font_options	current values for all Theme options
 * 
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_get_options( $with_transient = true ) {

	// Get the global customize variable
	global $wp_customize;

	// Get the option defaults
	$option_defaults = tt_font_get_option_defaults();

	// Globalize the variable that holds the Theme options
	global $tt_font_options;
	
	// Check if a transient exists, if it doesn't or we are in customize mode then reset the transient
	if ( ! $with_transient || isset( $wp_customize ) || false === ( $tt_font_options = get_transient( 'tt_font_theme_options' ) ) ) {
	
		// Parse the stored options with the defaults
		$tt_font_options = wp_parse_args( get_option( 'tt_font_theme_options', array() ), $option_defaults );

		// Remove redundant options
		foreach ( $tt_font_options as $key => $value ) {
			if ( ! isset( $option_defaults[ $key ] ) ) {
				unset( $tt_font_options[ $key ] );
			}
		}

		// Set the transient
		set_transient( 'tt_font_theme_options', $tt_font_options, 0 );

	}
		
	// Return the parsed array
	return $tt_font_options;
}

/**
 * Separate Settings By Tab
 * 
 * Returns an array of tabs, each of which is an indexed 
 * array of settings included with the specified tab.
 *
 * @uses	tt_font_get_option_parameters()	defined in \includes\theme-font-options.php
 * @uses	tt_font_get_settings_page_tabs()	defined in \includes\theme-font-options.php
 * 
 * @return	array	$settings_by_tab	array of arrays of settings by tab
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_get_settings_by_tab() {

	// Get the list of settings page tabs
	$tabs = tt_font_get_settings_page_tabs();

	// Initialize an array to hold an indexed array of tabnames
	$settings_by_tab = array();

	// Loop through the array of tabs
	foreach ( $tabs as $tab ) {

		// Add an indexed array key to the settings-by-tab 
		// array for each tab name
		$tab_name          = $tab['name'];
		$settings_by_tab[] = $tab_name;
	}

	// Get the array of option parameters
	$option_parameters = tt_font_get_option_parameters();

	// Loop through the option parameters array
	foreach ( $option_parameters as $option_parameter ) {
		$option_tab  = $option_parameter['tab'];
		$option_name = $option_parameter['name'];

		/* 
		 * Add an indexed array key to the settings-by-tab array 
		 * for each setting associated with each tab
		 */
		$settings_by_tab[ $option_tab ][] = $option_name;
		$settings_by_tab['all'][]         = $option_name;
	}

	return $settings_by_tab;
}

/**
 * Theme Admin Settings Page Tabs
 * 
 * Array that holds all of the tabs for the Admin Theme Font
 * Options Page. Each tab key holds an array that defines 
 * the sections for each tab, including the description text.
 * 
 * @return	array	$tabs	array of arrays of tab parameters
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_get_settings_page_tabs() {
	$tabs = array(
		'typography'=> array(
			'name'     => 'typography',
			'title'    => __( 'Typography', 'theme-translate' ),
			'sections' => array(
				// Test section
				'default' => array(
					'name'        => 'default',
					'title'       => __( 'Default Theme Fonts', 'theme-translate' ),
					'description' => __( 'Default theme font options', 'theme-translate' ),
				),
			)
		),	

		'theme-typography'=> array(
			'name'     => 'theme-typography',
			'title'    => __( 'Theme Typography', 'theme-translate' ),
			'sections' => array(
				// Test section
				'custom' => array(
					'name'        => 'custom',
					'title'       => __( 'Custom Theme Fonts', 'theme-translate' ),
					'description' => __( 'Custom theme font options', 'theme-translate' ),
				),
			)
		),
	);

	return apply_filters( 'tt_font_get_settings_page_tabs', $tabs );
}

/**
 * [tt_font_custom_option_parameters description]
 * @param  [type] $options [description]
 * @return [type]          [description]
 */
function tt_font_custom_option_parameters( $options ) {

	$query  = tt_font_get_all_font_controls();
	$custom_options = array();

	// Get fonts once and reuse for performance
	$google_fonts  = tt_font_get_google_fonts();
	$default_fonts = tt_font_get_default_fonts();

	if ( $query ) {
		while( $query->have_posts() ) {

			$query->the_post();
			
			// Extract font control properties
			$control_id      = get_post_meta( get_the_ID(), 'control_id', true );
			$selectors_array = get_post_meta( get_the_ID(), 'control_selectors', true );
			$description     = get_post_meta( get_the_ID(), 'control_description', true );
			$force_styles    = get_post_meta( get_the_ID(), 'force_styles', true );

			if ( empty( $force_styles ) ) {
				$force_styles = false;
			}
			
			// Build selectors
			$selectors = '';

			foreach ( $selectors_array as $selector ) {
				$selectors .= $selector . ',';
			}

			while ( substr( $selectors, -1 ) == ',' ) {
				$selectors = rtrim( $selectors, "," );
			}				
	

			// Add control
			if ( $control_id ) {
				$custom_options[ $control_id ] = array(
					'name'        => $control_id,
					'title'       => get_the_title(),
					'type'        => 'font',
					'description' => $description,
					'section'     => 'default',
					'tab'         => 'typography',
					'transport'   => 'postMessage',
					'since'       => '1.0',
					'properties'  => array(
						'selector'                 => $selectors,
						'force_styles'             => $force_styles,
						'font_size_min_range'      => '10',
						'font_size_max_range'      => '100',
						'font_size_step'           => '1',
						'line_height_min_range'    => '0.8',
						'line_height_max_range'    => '4',
						'line_height_step'         => '0.1',
						'letter_spacing_min_range' => '-5',
						'letter_spacing_max_range' => '20',
						'letter_spacing_step'      => '1',
						'google_fonts'             => $google_fonts,
						'default_fonts'            => $default_fonts,
					),
					'default'     => array(
						'font_id'             => '',
						'font_name'           => '',
						'font_color'          => '',
						'font_weight'         => '',
						'font_style'          => '',
						'font_weight_style'   => '',
						'stylesheet_url'      => '',
						'text_decoration'     => '',
						'text_transform'      => '',
						'line_height'         => '',
						'font_size'           => array( 
													'amount' => '',
													'unit'   => 'px'
												),
						'letter_spacing'      => array( 
													'amount' => '',
													'unit'   => 'px'
												),
					)

				);
			}

		} //endwhile
		
		// Reset the query globals
		wp_reset_postdata();
	}

	return array_merge( $options, $custom_options );
}
add_filter( 'tt_font_get_option_parameters', 'tt_font_custom_option_parameters', 0 );
