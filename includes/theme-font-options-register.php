<?php
/**
 * Theme Options Settings API
 *
 * This file implements the WordPress Settings API for the Font
 * Options in this theme. It also performs the necessary data
 * sanitization for each option.
 * 
 * 
 * @package     WordPress
 * @subpackage  WordPress_Google_Fonts
 * @author      Sunny Johal - Titanium Themes
 * @copyright   Copyright (c) 2013, Titanium Themes
 * @version     1.0
 * 
 */

/**
 * Register Theme Settings
 * 
 * Register tt_font_theme_options array to hold
 * all Theme options.
 * 
 * @link	http://codex.wordpress.org/Function_Reference/register_setting 		register_setting()
 * 
 * @param	string		$option_group		Unique Settings API identifier; passed to settings_fields() call
 * @param	string		$option_name		Name of the wp_options database table entry
 * @param	callback	$sanitize_callback	Name of the callback function in which user input data are sanitized
 *
 * @since 1.0
 * @version 1.0
 */
register_setting( 
	'tt_font_theme_options', 	
	'tt_font_theme_options',
	'tt_font_options_validate' 
);

/**
 * Theme register_setting() sanitize callback
 * 
 * Validate and whitelist user-input data before updating Theme 
 * Font Options in the database. Only whitelisted options are passed
 * back to the database, and user-input data for all whitelisted
 * options are sanitized.
 *
 * @todo Continually update validation/sanitization for new option fields
 * @todo  decrease dependancy on global $validation call and check why wordpress calls it 15 times.
 * 
 * @link	http://codex.wordpress.org/Data_Validation	 Data Validation
 * 
 * @param	array	$input	Raw user-input data submitted via the Theme Customizer page
 * @return	array	$input	Sanitized user-input data passed to the database
 *
 * @since 1.0
 * @version 1.0
 */
/**
 * Performance Enhancement: Store Validation Function Call Count
 *
 * This global variable is used to ensure that the validation
 * callback below is only called once. As the custom font control
 * registers 14 settings within it, WordPress calls the validation
 * function 15 times which causes a big performance bottleneck.
 * 
 */
global $validation_called;
$validation_called = false;

function tt_font_options_validate( $input ) {

	global $validation_called;

	if ( ! $validation_called ) {
		global $wp_customize;

		$validation_called = true;

		$valid_input       = tt_font_get_options( false ); 		// This is the "whitelist": current settings (without transient)
		$settings_by_tab   = tt_font_get_settings_by_tab(); 	// Get the array of Theme settings, by Settings Page tab
		$option_parameters = tt_font_get_option_parameters(); 	// Get the array of option parameters
		$option_defaults   = tt_font_get_option_defaults(); 	// Get the array of option defaults

		$tabs = tt_font_get_settings_page_tabs();

		// Determine what type of submit was input
		$submit_type = 'submit';	
		foreach ( $tabs as $tab ) {
			$reset_name = 'reset-' . $tab['name'];
			if ( ! empty( $input[ $reset_name ] ) ) {
				$submit_type = 'reset';
			}
		}

		// Determine what tab was input
		$submit_tab = 'typography';	
		foreach ( $tabs as $tab ) {
			$submit_name = 'submit-' . $tab['name'];
			$reset_name  = 'reset-' . $tab['name'];
			if ( ! empty( $input[ $submit_name ] ) || ! empty( $input[ $reset_name ] ) ) {
				$submit_tab = $tab['name'];
			}
		}	

		// Get settings by tab
		$tab_settings = ( isset ( $wp_customize ) ? $settings_by_tab['all'] : $settings_by_tab[ $submit_tab ] );	

	// print_r($input);
		/**
		 * Validate Each Setting
		 *
		 * Loops through the options array, determines the option
		 * type and performs the necessary validation.
		 * 
		 */
		foreach ( $tab_settings as $setting ) {
			// If no option is selected, set the default
			$valid_input[ $setting ] = ( ! isset( $input[ $setting ] ) ? $option_defaults[ $setting ] : $input[ $setting ] );

			// If submit, validate/sanitize $input
			if ( 'submit' == $submit_type ) {
				
				// Get the setting details from the defaults array
				$option_details = $option_parameters[ $setting ];

				// Get the array of valid options, if applicable
				$valid_options = ( isset( $option_details['valid_options'] ) ? $option_details['valid_options'] : false );

				/**
				 * Font Field Validation
				 * ----------------------
				 */
				
				if ( isset( $input[ $setting ] ) ) {
				

					if ( 'font' ==  $option_details['type'] ) {

						// Sanitize font values
						$valid_input[ $setting ]['font_id']                  = esc_attr( $input[ $setting ]['font_id'] );
						$valid_input[ $setting ]['font_name']                = esc_attr( $input[ $setting ]['font_name'] );
						$valid_input[ $setting ]['font_color']               = sanitize_hex_color( $input[ $setting ]['font_color'] );
						$valid_input[ $setting ]['font_weight']              = esc_attr( $input[ $setting ]['font_weight'] );
						$valid_input[ $setting ]['font_style']               = esc_attr( $input[ $setting ]['font_style'] );
						$valid_input[ $setting ]['font_weight_style']        = esc_attr( $input[ $setting ]['font_weight_style'] );
						$valid_input[ $setting ]['stylesheet_url']           = esc_url( $input[ $setting ]['stylesheet_url'] );
						$valid_input[ $setting ]['text_decoration']          = esc_attr( $input[ $setting ]['text_decoration'] );
						$valid_input[ $setting ]['text_transform']           = esc_attr( $input[ $setting ]['text_transform'] );
						$valid_input[ $setting ]['line_height']              = esc_attr( $input[ $setting ]['line_height'] );
						$valid_input[ $setting ]['font_size']['amount']      = esc_attr( $input[ $setting ]['font_size']['amount'] );
						$valid_input[ $setting ]['font_size']['unit']        = $option_defaults[ $setting ]['font_size']['unit'];
						$valid_input[ $setting ]['letter_spacing']['amount'] = esc_attr( $input[ $setting ]['letter_spacing']['amount'] );
						$valid_input[ $setting ]['letter_spacing']['unit']   = $option_defaults[ $setting ]['letter_spacing']['unit'];		

					} 

					/**
					 * Font Basic Control is here in the event
					 * that we decide to implement a lightweight
					 * control for this plugin.
					 */
					else if ( 'font_basic' ==  $option_details['type'] ) {

						// Sanitize font values
						$valid_input[ $setting ]['font_id']                  = esc_attr( $input[ $setting ]['font_id'] );
						$valid_input[ $setting ]['font_name']                = esc_attr( $input[ $setting ]['font_name'] );
						$valid_input[ $setting ]['font_color']               = sanitize_hex_color( $input[ $setting ]['font_color'] );
						$valid_input[ $setting ]['font_weight']              = esc_attr( $input[ $setting ]['font_weight'] );
						$valid_input[ $setting ]['font_style']               = esc_attr( $input[ $setting ]['font_style'] );
						$valid_input[ $setting ]['font_weight_style']        = esc_attr( $input[ $setting ]['font_weight_style'] );
						$valid_input[ $setting ]['stylesheet_url']           = esc_url( $input[ $setting ]['stylesheet_url'] );
						$valid_input[ $setting ]['text_decoration']          = esc_attr( $input[ $setting ]['text_decoration'] );
						$valid_input[ $setting ]['text_transform']           = esc_attr( $input[ $setting ]['text_transform'] );

					}

					// Validate custom fields
					else if ( 'custom' == $option_details['type'] ) {
						//$valid_input[ $setting ] = $input[ $setting ];
					}
				} // end if isset()
			}
			// If reset, reset defaults
			elseif( 'reset' == $submit_type ) {
				// Set $setting to the default value
				$valid_input[ $setting ] = $option_defaults[ $setting ];
			}
		} // endforeach

		return $valid_input;
	} else {
		return $input;
	}
}

/**
 * Globalize the variable that holds 
 * the Font Settings Page tab definitions
 * 
 * @global	array	Settings Page Tab definitions
 *
 * @since 1.0
 * @version 1.0
 * 
 */
global $tt_font_tabs;
$tt_font_tabs = tt_font_get_settings_page_tabs();

/**
 * Call add_settings_section() for each Settings 
 * 
 * Loop through each Theme Font Settings page tab, and add 
 * a new section to the Theme Settings page for each 
 * section specified for each tab.
 * 
 * @link	http://codex.wordpress.org/Function_Reference/add_settings_section		add_settings_section()
 * 
 * @param	string		$sectionid	Unique Settings API identifier; passed to add_settings_field() call
 * @param	string		$title		Title of the Settings page section
 * @param	callback	$callback	Name of the callback function in which section text is output
 * @param	string		$pageid		Name of the Settings page to which to add the section; passed to do_settings_sections()
 *
 * @since 1.0
 * @version 1.0
 * 
 */
foreach ( $tt_font_tabs as $tab ) {
	$tab_name     = $tab['name'];
	$tab_sections = $tab['sections'];

	foreach ( $tab_sections as $section ) {
		$section_name  = $section['name'];
		$section_title = $section['title'];

		// Add settings section
		add_settings_section(
			"tt_font_{$section_name}_section", 		// $sectionid
			$section_title,							// $title
			'tt_font_sections_callback',			// $callback
			"tt_font_{$tab_name}_tab"				// $pageid
		);				
	}
}

/**
 * Callback for add_settings_section()
 * 
 * Generic callback to output the section text
 * for each Plugin settings section. 
 * 
 * @uses	tt_font_get_settings_page_tabs()	Defined in \includes\theme-font-functions.php
 * 
 * @param	array	$section_passed	Array passed from add_settings_section()
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_sections_callback( $section_passed ) {
	global $tt_font_tabs;
	$tt_font_tabs = tt_font_get_settings_page_tabs();
	
	foreach ( $tt_font_tabs as $tab_name => $tab ) {
		$tab_sections = $tab['sections'];
		foreach ( $tab_sections as $section_name => $section ) {
			if ( "tt_font_{$section_name}_section" == $section_passed['id'] ) {
				?>
				<p><?php echo $section['description']; ?></p>
				<?php
			}
		}
	}
}

/**
 * Globalize the variable that holds 
 * all the Theme option parameters
 * 
 * @global	array	Theme options parameters
 *
 * @since 1.0
 * @version 1.0
 * 
 */
global $tt_font_parameters;
$tt_font_parameters = tt_font_get_option_parameters();