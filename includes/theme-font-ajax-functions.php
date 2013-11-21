<?php 
/**
 * Theme Font AJAX Functions
 *
 * This file contains all of the AJAX functionality
 * that is used by this plugin.
 * 
 * @package     WordPress
 * @subpackage  WordPress_Google_Fonts
 * @author      Sunny Johal - Titanium Themes
 * @copyright   Copyright (c) 2013, Titanium Themes
 * @version     1.0
 * 
 */

/**
 * Force Styles for Control - Ajax Function
 *
 * Updates the 'force_styles' meta option for a 
 * particular font control instance. If this is
 * set to true then the !important modifer will
 * be added to the styles upon output.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/update_post_meta 		update_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 *
 * @uses tt_font_get_font_control() 	defined in \includes\theme-font-functions.php
 * 
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_control_force_styles() {
	// Check admin nonce for security
	check_ajax_referer( 'tt_font_edit_control_instance', 'tt_font_edit_control_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	if ( isset( $_POST['controlId'] ) ) {
		$control_id = $_POST['controlId'];
		$switch     = isset( $_POST['force-styles'] ) ? $_POST['force-styles'] : false;
		$control    = tt_font_get_font_control( $control_id );

		if ( $control ) {
			update_post_meta( $control->ID, 'force_styles', $switch );
		}		
	}	

	wp_die();
}
add_action( 'wp_ajax_tt_font_control_force_styles', 'tt_font_control_force_styles' );

/**
 * Create Font Control Instance - Ajax Function
 * 
 * Checks WordPress nonce and upon successful validation
 * creates a new font control instance. This function then 
 * constructs a new ajax response and sends it back to the
 * client.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta 			get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/WP_Ajax_Response 		WP_Ajax_Response
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 *
 * @uses tt_font_update_font_control() defined in \includes\theme-font-functions.php
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_ajax_create_control_instance() {
	
	// Check admin nonce for security
	check_ajax_referer( 'tt_font_edit_control_instance', 'tt_font_edit_control_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	// Get Control Name
	if( isset( $_POST['control_name'] ) ) {
		$control_name =  $_POST['control_name'];
	} else {
		$control_name = __( 'Custom Font Control', 'theme-translate' );
	}

	// Create the new font control and get the associated ID
	$new_control    = tt_font_update_font_control( '0', $control_name );
	$new_control_id = get_post_meta( $new_control, 'control_id', true );

	// Create array to hold additional xml data
	$supplimental_data = array(
		'new_control_id'     => $new_control_id
	);

	$data = array(
		'what'         => 'new_control',
		'id'           => 1,
		'data'         => '',
		'supplemental' => $supplimental_data
	);

	
	// Create a new WP_Ajax_Response obj and send the request
	$x = new WP_Ajax_Response( $data );
	$x->send();

	wp_die();
}
add_action( 'wp_ajax_tt_font_create_control_instance', 'tt_font_ajax_create_control_instance' );

/**
 * Update Font Control Instance - Ajax Function
 * 
 * Checks WordPress nonce and upon successful validation
 * updates a new font control instance. This function then 
 * constructs a new ajax response and sends it back to the
 * client.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta 			get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/WP_Ajax_Response 		WP_Ajax_Response
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 *
 * @uses tt_font_update_font_control() defined in \includes\theme-font-functions.php
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_ajax_update_control_instance() {

	// Check admin nonce for security
	check_ajax_referer( 'tt_font_edit_control_instance', 'tt_font_edit_control_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}
	
	// Get control attributes	
	$control_id   = isset( $_POST['controlId'] ) ? (string) $_POST['controlId'] : (string) '0';
	$control_name = isset( $_POST['controlName'] ) ? (string) $_POST['controlName'] : __( 'Custom Sidebar', 'theme-translate' );
	$force_styles = false;
	$description  = '';

	$selectors = array();

	if ( isset( $_POST['control-selectors'] ) ) {
		$selectors = (array) $_POST['control-selectors'];
	}

	if ( isset( $_POST['force-styles'] ) ) {
		$force_styles = ( 'true' == $_POST['force-styles'] ) ? true : false;
	}

	for ( $i=0; $i < count( $selectors ); $i++ ) {
		while ( substr( $selectors[ $i ], -1 ) == ',' ) {
			$selectors[ $i ] = rtrim( $selectors[ $i ], ',' );
		}
	}

	// Update control or create a new one if it doesn't exist
	$control = tt_font_update_font_control( $control_id, $control_name, $selectors, $description, $force_styles );

	// Create array to hold additional xml data
	$supplimental_data = array(
		'control_name'     => get_the_title( $control )
	);

	$data = array(
		'what'         => 'control',
		'id'           => 1,
		'data'         => '',
		'supplemental' => $supplimental_data
	);

	// Create a new WP_Ajax_Response obj and send the request
	$x = new WP_Ajax_Response( $data );
	$x->send();

	wp_die();
}
add_action( 'wp_ajax_tt_font_update_control_instance', 'tt_font_ajax_update_control_instance' );

/**
 * Delete Font Control Instance - Ajax Function
 * 
 * Checks WordPress nonce and upon successful validation
 * it deletes the font control instance from the database.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 * 
 * @uses tt_font_delete_font_control() 	defined in \includes\theme-font-functions.php
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_ajax_delete_control_instance() {

	// Check admin nonce for security
	check_ajax_referer( 'tt_font_delete_control_instance', 'tt_font_delete_control_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	if ( isset( $_POST['controlId'] ) ) {
		tt_font_delete_font_control( $_POST['controlId'] );
	}

	wp_die();
}
add_action( 'wp_ajax_tt_font_delete_control_instance', 'tt_font_ajax_delete_control_instance' );

/**
 * Delete All Font Control Instances - Ajax Function
 * 
 * Checks WordPress nonce and upon successful validation
 * it deletes all control instances from the database.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 * 
 * @uses tt_font_delete_all_font_controls() defined in \includes\theme-font-functions.php
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_ajax_delete_all_control_instances() {
	
	// Check admin nonce for security
	check_ajax_referer( 'tt_font_delete_control_instance', 'tt_font_delete_control_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	tt_font_delete_all_font_controls();

	wp_die();
}
add_action( 'wp_ajax_tt_font_delete_all_control_instances', 'tt_font_ajax_delete_all_control_instances' );

/**
 * Set Google Fonts API Key - Ajax Function
 * 
 * Checks WordPress nonce and upon successful validation
 * updates the Google API key.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 * 
 * @uses tt_font_set_google_api_key() defined in \includes\theme-font-functions.php
 *
 * @since 1.0
 * @version 1.0
 * 
 */
function tt_font_ajax_set_google_api_key() {

	// Check admin nonce for security
	check_ajax_referer( 'tt_font_edit_control_instance', 'tt_font_edit_control_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	if ( isset( $_POST['apiKey'] ) ) {
		$apiKey = $_POST['apiKey'];
		tt_font_set_google_api_key( $apiKey );
	}

	wp_die();	
}
add_action( 'wp_ajax_tt_font_set_google_api_key', 'tt_font_ajax_set_google_api_key' );