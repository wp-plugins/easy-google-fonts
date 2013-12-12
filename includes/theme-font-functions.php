<?php 
/**
 * Theme Font Functions
 *
 * This file is responsible for declaring and managing
 * all of the fonts that are used as part of the live
 * font previewer on the theme options and customizer.
 * 
 * 
 * @package 	WordPress
 * @subpackage  WordPress_Google_Fonts
 * @author 		Sunny Johal - Titanium Themes
 * @copyright 	Copyright (c) 2013, Titanium Themes
 * @version 	1.0
 * 
 */

/**
 * FONT CONTROL POSTTYPE FUNCTIONS
 * =================================
 */

/**
 * Register Font Control Posttype
 * 
 * Register the font control posttype in the same fashion that
 * WordPress registers nav-menus internally. This will be used
 * to store any font control instances. Created when the 'init' 
 * action is fired.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/register_post_type 	register_post_type()
 *
 * @since 1.0
 * @version 1.1.1
 * 
 */
function tt_font_register_google_font_post_type() {
    register_post_type( 'tt_font_control', array(
        'labels' => array(
            'name'          => __( 'Google Font Controls', 'theme-translate' ),
            'singular_name' => __( 'Google Font Control',  'theme-translate' )
        ),
        'public'           => false,
        'hierarchical'     => false,
        'rewrite'          => false,
        'delete_with_user' => false,
        'query_var'        => false 
    ) );
}
add_action( 'init', 'tt_font_register_google_font_post_type' ); // highest priority

/**
 * Add Custom Font Control
 * 
 * Create a post for the 'tt_font_control' posttype which 
 * will use the custom post meta WordPress functionality to store 
 * all of the necessary attributes for each custom font control. 
 * Note: The control_id is different to the actual post id for each 
 * font control instance.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/wp_insert_post 		wp_insert_post()
 * @link http://codex.wordpress.org/Function_Reference/update_post_meta 	update_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta        get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * 
 * @param  string $control_name     The name for this custom font control item.
 * @param  array  $selectors 		An array of css selectors that will be managed by this font control
 * @param  string $description    	The description text for this font control.
 * 
 * @return $post  The ID of the post if the post is successfully added to the database or 0 on failure.
 *
 * @since 1.0
 * @version 1.1.1
 *
 */
function tt_font_add_font_control( $control_name, $selectors = array(), $description = '', $force_styles = false ) {

	// Remove stale data
	delete_transient( 'tt_font_theme_options' );

	// Generate ID and make sure its unique
	$control_count  = rand( 1, 100 );
	$control_id     = 'tt-font-' . $control_count;

	// Generate an array of existing font control ids and names
	$existing_control_ids   = array();
	$existing_control_names = array();
	$control_id_exists      = true;
	$control_name_exists    = true;

	$params = array(
		'post_type'      => 'tt_font_control',
		'posts_per_page' => -1
	);
	$query = new WP_Query( $params );

	while( $query->have_posts() ) {
		$query->the_post();
		$existing_control_ids[]   = get_post_meta( get_the_ID(), 'control_id', true );
		$existing_control_names[] = get_the_title();
	}

	// Make sure the ID doesn't already exist
	while ( $control_id_exists ) {
		if ( in_array( $control_id, $existing_control_ids ) ) {
			$control_count++;
			$control_id = "tt-font-{$control_count}";
		} else {
			$control_id_exists = false;
		}
	}

	// Strip any unallowed characters from the post title
	$control_name = str_replace( array( '#', "'", '"', '&', "{", "}" ), '', $control_name );

	// Give the post a title if it is an empty string
	if ( '' == $control_name ) {
		$control_name = __( 'Font Control', 'theme-translate' );
	}

	// Make sure the name doesn't already exist
	$name_count    = 1;
	$original_name = $control_name;

	while ( $control_name_exists ) {
		if ( in_array( $control_name, $existing_control_names ) ) {
			$name_count++;
			$control_name = "{$original_name} {$name_count}";
		} else {
			$control_name_exists = false;
		}		
	} 	

	// Remove the save_post action to prevent capabilities error
	// as wp_insert_post triggers this action when called
	$hook_name = 'save_post';
	global $wp_filter;
	$save_post_functions   = $wp_filter[$hook_name];
	$wp_filter[$hook_name] = array();

	$postarr = array(
		'post_type'   => 'tt_font_control',
		'post_title'  => $control_name,
		'post_status' => 'publish' 
	); 
	$post = wp_insert_post( $postarr );
	
	/*
	 * Sanitize Selectors 
	 */
	for ( $i=0; $i < count( $selectors ); $i++ ) {
		while ( substr( $selectors[ $i ], -1 ) == ',' ) {
			$selectors[ $i ] = rtrim( $selectors[ $i ], ',' );
		}
	}

	// Update the post meta to hold the custom font control properties
	update_post_meta( $post, 'control_id', 	$control_id );
	update_post_meta( $post, 'control_selectors', $selectors );
	update_post_meta( $post, 'control_description', sanitize_text_field( $description ) );
	update_post_meta( $post, 'force_styles', $force_styles );

	// Restore all save post functions
	$wp_filter[$hook_name] = $save_post_functions;

	return $post;

}

/**
 * Update Font Control Instance
 *
 * Updates an existing font control instance with the values 
 * passed into the parameter. If a font control instance is
 * not found a new font control instance would be created.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/wp_insert_post 		wp_insert_post()
 * @link http://codex.wordpress.org/Function_Reference/update_post_meta 	update_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta        get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * 
 * @param  string $control_id     The ID for the control we wish to update. Note: This is NOT the post id but the control_id meta value.
 * @param  string $control_name   The name for this custom font control item.
 * @param  array  $selectors 	  An array of css selectors that will be managed by this font control
 * @param  string $description    The description text for this custom font control.
 * 
 * @return string $post_id The post ID of the updated/created post.
 *
 * @since 1.0
 * @version 1.1.1
 * 
 */
function tt_font_update_font_control( $control_id, $control_name, $selectors = array(), $description = '', $force_styles = false ) {

	// Remove stale data
	delete_transient( 'tt_font_theme_options' );

	$params = array(
		'post_type'  => 'tt_font_control',
		'meta_key'   => 'control_id',
		'meta_value' => $control_id
	);

	$query = new WP_Query( $params );
	
	/*
	 * Remove the save_post action to prevent any capabilities 
	 * error as wp_insert_post triggers this action when called
	 */
	$hook_name = 'save_post';
	global $wp_filter;
	$save_post_functions     = $wp_filter[ $hook_name ];
	$wp_filter[ $hook_name ] = array();

	// Strip any unallowed characters from the post title
	$control_name = str_replace( array( '#', "'", '"', '&', "}", "{" ), '', $control_name );

	// Give the post a title if it is an empty string
	if ( '' == $control_name ) {
		$control_name = __( 'Font Control', 'theme-translate' );
	}

	if( $query->found_posts > 0 ) {
		$query->the_post();
		$post_id = get_the_ID();

		// Make sure no other font control has the same name
		if ( tt_font_control_exists( $control_name, $control_id ) ) {
			
			$control_name_exists = true;
			$name_count          = 1;
			$original_name       = $control_name;

			while ( $control_name_exists ) {
				
				$control_name = "{$original_name} {$name_count}";
				
				if ( tt_font_control_exists( $control_name, $control_id ) ) {
					$name_count++;
				} else {
					$control_name_exists = false;
				}
			}
		}

		// Update the post object
		$post_arr = array(
			'ID'         => $post_id,
			'post_title' => $control_name
		);
		wp_update_post( $post_arr );

	} else {		
		$new_post = tt_font_add_font_control( $control_name, $selectors, $description );
		$post_id  = $new_post;
	}
	
	// Reset the query globals
	wp_reset_postdata();

	/*
	 * Sanitize Selectors 
	 */
	for ( $i=0; $i < count( $selectors ); $i++ ) {
		while ( substr( $selectors[ $i ], -1 ) == ',' ) {
			$selectors[ $i ] = rtrim( $selectors[ $i ], ',' );
		}
	}

	/*
	 * Update other post meta properties to hold
	 * the custom font control properties.
	 */	
	update_post_meta( $post_id, 'control_selectors', $selectors );
	update_post_meta( $post_id, 'control_description', sanitize_text_field( $description ) );
	update_post_meta( $post_id, 'force_styles', $force_styles );

	/*
	 * Restore the save_post action so any functions
	 * that are hooked to it will execute as intended.
	 */	
	$wp_filter[ $hook_name ] = $save_post_functions;	

	return $post_id;
}

/**
 * Font Control Name Exists
 *
 * Takes the font control name to check and the control id to 
 * exclude and returns true if there are any other font control
 * instances that have this name. (Boolean Function)
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/have_posts           have_posts()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * @link http://codex.wordpress.org/Function_Reference/get_the_title        get_the_title()
 * 
 * @param  string $control_name           The font control name we wish to check
 * @param  string $control_exclusion_id   The font control id to exclude in the search
 * @return boolean - true if there is another font control instance that has a matching $control_name
 *
 * @since 1.0
 * @version 1.1.1
 * 
 */
function tt_font_control_exists( $control_name, $control_exclusion_id ) {
	
	$control_name_exists = false;

	$params = array(
		'post_type'      => 'tt_font_control',
		'posts_per_page' => -1
	);
	$query = new WP_Query( $params );

	// Check if the font control name exists
	while ( $query->have_posts() ) {

		$query->the_post();
		$control_id = get_post_meta( get_the_ID(), 'control_id', true );

		if ( $control_id ) {
			if ( $control_id != $control_exclusion_id ) {
				if ( $control_name == get_the_title() ) {
					$control_name_exists = true;
				}
			}
		}
	}

	wp_reset_postdata();

	return $control_name_exists;
}

/**
 * Get Font Control
 *
 * Takes the control id as a parameter and returns the
 * post object if it's 'control_id' meta value matches 
 * the control id passed in the parameter. Returns false
 * if no matches have been found.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/have_posts           have_posts()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_post             get_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * 
 * @param  string $control_id The ID of the font control we wish to check
 * @return post object if found otherwise false
 *
 * @since 1.0
 * @version 1.1.1
 * 
 */
function tt_font_get_font_control( $control_id ) {
	$params = array(
		'post_type'  => 'tt_font_control',
		'meta_key'   => 'control_id',
		'meta_value' => $control_id
	);
	$query = new WP_Query( $params );

	if( $query->have_posts() ) {
		$query->the_post();
		return get_post( get_the_ID() );
	} else {
		return false;
	}
}

/**
 * Get All Font Control Posts
 *
 * Returns all of the 'tt_font_control' posttypes objects
 * in alphabetical order by default. This function will return 
 * false if there are no 'tt_font_control' posts in the 
 * database.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/have_posts           have_posts()
 * 
 * @return array $query if post exists and 
 *         boolean if there are no posts.
 *
 * @since 1.0
 * @version 1.1.1
 * 
 */
function tt_font_get_all_font_controls( $orderby = 'title', $order = 'ASC' ) {

	$params = array(
		'post_type'      => 'tt_font_control',
		'posts_per_page' => -1,
		'orderby'        => $orderby,
		'order'          => $order
	);
	
	$query = new WP_Query( $params );

	if( $query->have_posts() ) {
		return $query;
	} else {
		return false;
	}
}

/**
 * Delete Custom Font Control Instance
 *
 * Looks for a custom control instance with the id that is 
 * passed as a string in the parameter and deletes it.
 * Returns false if no matches have been found. 
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query              WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/wp_reset_postdata     wp_reset_postdata()
 * 
 * @param  string  $sidebar_id    The id of the control we want to delete. Note: This is NOT the post id but the control_id meta value.
 * 
 * @return boolean $deleted       True if the control has been located and deleted, false otherwise.
 *
 * @since 1.0
 * @version 1.1.1
 * 
 */
function tt_font_delete_font_control( $control_id ) {
	
	// Remove stale data
	delete_transient( 'tt_font_theme_options' );

	$params = array(
			'post_type'      => 'tt_font_control',
			'posts_per_page' => -1,
			'meta_key'       => 'control_id',
			'meta_value'     => $control_id
		);
	$query   = new WP_Query( $params );
	$deleted = false;

	// If no posts are found set deleted to true as it doesn't exist
	if ( 0 == $query->found_posts ) {
		$deleted = true;
	}

	// Delete the post if it exists
	while ( $query->have_posts() ) {
		$query->the_post();
		wp_delete_post( get_the_ID(), true );
		$deleted = true;
	}

	// Reset postdata as we have used the_post()
	wp_reset_postdata();

	return $deleted;	
}

/**
 * Delete All Font Controls
 * 
 * A function used to delete all posts in the 'tt_font_control'
 * custom posttype, which will remove all custom font controls
 * generated by the user.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * @link http://codex.wordpress.org/Function_Reference/wp_delete_post 		wp_delete_post()
 * @link http://codex.wordpress.org/Function_Reference/wp_reset_postdata    wp_reset_postdata()
 *
 * @since 1.0
 * @version 1.1.1
 * 
 */
function tt_font_delete_all_font_controls() {
	
	// Remove stale data
	delete_transient( 'tt_font_theme_options' );

	$params = array(
			'post_type'      => 'tt_font_control',
			'posts_per_page' => -1
		);

	$query  = new WP_Query($params);

	while ( $query->have_posts() ) {
		$query->the_post();
		wp_delete_post( get_the_ID(), true );
	}

	// Reset postdata as we have used the_post()
	wp_reset_postdata();	
}

/**
 * FONT HELPER FUNCTIONS
 * =================================
 */

/**
 * Get Default Websafe Fonts
 *
 * Defines a list of default websafe fonts and generates
 * an array with all of the necessary properties. Returns
 * all of the fonts as an array to the user.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_transient 	get_transient()
 * @link http://codex.wordpress.org/Function_Reference/set_transient 	set_transient()
 * @link http://codex.wordpress.org/Function_Reference/apply_filters  	apply_filters()
 *
 * @return array $fonts - All websafe fonts with their properties
 *
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_get_default_fonts() {

	if ( false === get_transient( 'tt_font_default_fonts' ) ) {

		// Declare default font list
		$font_list = array(
				'Arial'               => array( 'weights' => array( '400', '400italic' ) ),
				'Century Gothic'      => array( 'weights' => array( '400', '400italic' ) ),
				'Courier New'         => array( 'weights' => array( '400', '400italic' ) ),
				'Georgia'             => array( 'weights' => array( '400', '400italic' ) ),
				'Helvetica'           => array( 'weights' => array( '400', '400italic' ) ),
				'Impact'              => array( 'weights' => array( '400', '400italic' ) ),
				'Lucida Console'      => array( 'weights' => array( '400', '400italic' ) ),
				'Lucida Sans Unicode' => array( 'weights' => array( '400', '400italic' ) ),
				'Palatino Linotype'   => array( 'weights' => array( '400', '400italic' ) ),
				'sans-serif'          => array( 'weights' => array( '400', '400italic' ) ),
				'serif'               => array( 'weights' => array( '400', '400italic' ) ),
				'Tahoma'              => array( 'weights' => array( '400', '400italic' ) ),
				'Trebuchet MS'        => array( 'weights' => array( '400', '400italic' ) ),
				'Verdana'             => array( 'weights' => array( '400', '400italic' ) ),
		);
	
		// Build font list to return
		$fonts = array();
		foreach ( $font_list as $font => $attributes ) {

			$urls = array();

			// Get font properties from json array.
			foreach ( $attributes['weights'] as $variant ) {
				$urls[ $variant ] = "";
			}			

			// Create a font array containing it's properties and add it to the $fonts array
			$atts = array(
					'name'         => $font,
					'font_type'    => 'default',
					'font_weights' => $attributes['weights'],
					'files'        => array(),
					'urls'         => $urls,
			);

			// Add this font to all of the fonts
			$id           = strtolower( str_replace( ' ', '_', $font ) );
			$fonts[ $id ] = $atts;
		}

		// Filter to allow us to modify the fonts array before saving the transient
		$fonts = apply_filters( 'tt_font_default_fonts_array', $fonts );
		
		// Set transient for google fonts (for 2 weeks)
		set_transient( 'tt_font_default_fonts', $fonts, 14 * DAY_IN_SECONDS );

	} else {
		$fonts = get_transient( 'tt_font_default_fonts' );
	}

	// Return the font list
	return apply_filters( 'tt_font_get_default_fonts', $fonts );
}

/**
 * Get Google Fonts List Using API
 *
 * Fetches all of the current fonts as a JSON object using
 * the google font API and outputs it as a PHP Array. This 
 * is an internal function designed to flag outdated and 
 * new fonts so that we can update the fonts array list
 * accordingly. Falls back to retrieving a manual list if 
 * the json request was unsuccessful.
 * 
 * DEVELOPER NOTE: For this function to work correctly you 
 * would need to sign up for a google API Key and enter it 
 * into the settings page.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_transient 				get_transient()
 * @link http://codex.wordpress.org/Function_Reference/set_transient 				set_transient()
 * @link http://codex.wordpress.org/Function_Reference/wp_remote_get 				wp_remote_get()
 * @link http://codex.wordpress.org/Function_Reference/wp_remote_fopen 				wp_remote_fopen()
 * @link http://codex.wordpress.org/Function_Reference/is_wp_error 					is_wp_error()
 * @link http://codex.wordpress.org/Function_Reference/apply_filters 				apply_filters()
 * @link http://codex.wordpress.org/Function_Reference/get_template_directory_uri 	get_template_directory_uri()
 *
 * @todo  Use the Filesystem API in future releases to overwrite the webfonts.json file automatically
 * 
 * @return [type] [description]
 * 
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_get_google_fonts() {
	/**
	 * Google Fonts API Key
	 *
	 * Please enter the developer API Key for unlimited requests
	 * to google to retrieve all fonts. If you do not enter an API
	 * key google will
	 * 
	 * {@link https://developers.google.com/fonts/docs/developer_api}
	 */
	
	$api_key = tt_font_get_google_api_key();
	$api_url = $api_key ? "&key={$api_key}" : "";

	// Variable to hold fonts;
	$fonts = array();
	$json  = array();
	
	// Check if transient is set
	if ( false === get_transient( 'tt_font_google_fonts' ) ) {

		/*
		 * First we want to try to update the font transient with the
		 * latest fonts if possible by sending an API request to google. 
		 * If this is not possible then the theme will just use the 
		 * current list of webfonts.
		 */

		// Get list of fonts as a JSON Object from Google's server
		$response = wp_remote_get( "https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha{$api_url}", array( 'sslverify' => false ) );	

		/*
		 * Now we want to check that the request has a valid response
		 * from google. If the request is not valid then we fall back
		 * to the webfonts.json file.
		 */
		// Check it is a valid request
		if ( ! is_wp_error( $response ) ) {

			$font_list = json_decode( $response['body'], true );

			// Make sure that the valid response from google is not an error message
			if ( ! isset( $font_list['error'] ) ) {
				$json = $response['body'];

			} else {
				$json  = wp_remote_fopen( plugins_url( 'easy-google-fonts' ) . '/includes/fonts/webfonts.json' );	
			}

		} else {
			$json  = wp_remote_fopen( plugins_url( 'easy-google-fonts' ) . '/includes/fonts/webfonts.json' );
		}


		$font_output = json_decode( $json, true );

		foreach ( $font_output['items'] as $item ) {
				
			$urls = array();

			// Get font properties from json array.
			foreach ( $item['variants'] as $variant ) {

				$name = str_replace( ' ', '+', $item['family'] );
				$urls[ $variant ] = "https://fonts.googleapis.com/css?family={$name}:{$variant}";

			}

			$atts = array( 
				'name'         => $item['family'],
				'font_type'    => 'google',
				'font_weights' => $item['variants'],
				'files'        => $item['files'],
				'urls'         => $urls
			);

			// Add this font to the fonts array
			$id           = strtolower( str_replace( ' ', '_', $item['family'] ) );
			$fonts[ $id ] = $atts;

		}

		// Filter to allow us to modify the fonts array before saving the transient
		$fonts = apply_filters( 'tt_font_google_fonts_array', $fonts );
		
		// Set transient for google fonts
		set_transient( 'tt_font_google_fonts', $fonts, 14 * DAY_IN_SECONDS );

	} else {
		$fonts = get_transient( 'tt_font_google_fonts' );
	}

	return apply_filters( 'tt_font_get_google_fonts', $fonts );
}

/**
 * Get Google Font API Key
 *
 * Returns the google api key if it has been set.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_option 	get_option()
 *
 * @return string $api_key - The Google API Key
 * 
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_get_google_api_key() {
	$api_key = "";
	$api_key = get_option( 'tt-font-google-api-key', '' );

	return $api_key;
}

/**
 * Set Google Font API Key
 *
 * Sets the google api key with the value passed in
 * the parameter.
 *
 * @link http://codex.wordpress.org/Function_Reference/update_option 	update_option()
 *
 * @return string $api_key - The Google API Key
 * 
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_set_google_api_key( $api_key ) {
	update_option( 'tt-font-google-api-key', $api_key );
}

/**
 * Google Font API Key Validation
 *
 * Boolean function that checks the validity of a google
 * api key and returns true if it is valid and false if
 * it is not a valid api key.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_remote_get 	wp_remote_get()
 *
 * @return string $api_key - The Google API Key
 * 
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_is_valid_google_api_key( $api_key = '' ) {
	$api_url  = $api_key ? "&key={$api_key}" : "";	
	$response = wp_remote_get( "https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha{$api_url}", array( 'sslverify' => false ) );

	// Check it is a valid request
	if ( ! is_wp_error( $response ) ) {
		
		$font_list = json_decode( $response['body'], true );

		// Make sure that the valid response from google is not an error message
		if ( ! isset( $font_list['error'] ) ) {
			return true;
		} else {
			return false;
		}

	} else {
		return false;
	}
}

/**
 * Delete Default Websafe Fonts
 *
 * Defines a list of default websafe fonts and generates
 * an array with all of the necessary properties. Returns
 * all of the fonts as an array to the user.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_transient 	get_transient()
 * @link http://codex.wordpress.org/Function_Reference/set_transient 	set_transient()
 * @link http://codex.wordpress.org/Function_Reference/apply_filters  	apply_filters()
 *
 * @return array $fonts - All websafe fonts with their properties
 *
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_delete_font_transients() {
	delete_transient( 'tt_font_default_fonts' );
	delete_transient( 'tt_font_google_fonts' );
}

/**
 * Get Individual Fonts
 *
 * Takes an id and returns the corresponding font.
 *
 * @link http://codex.wordpress.org/Function_Reference/apply_filters  	apply_filters()
 *
 * @uses tt_font_get_default_fonts() 	defined in \inc\admin\theme-options\font-functions.php
 * @uses tt_font_get_google_fonts() 	defined in \inc\admin\theme-options\font-functions.php
 *  
 * @return array $fonts - All websafe fonts with their properties
 *
 * @since  1.0
 * @version 1.1.1
 * 
 */
function tt_font_get_font( $id ) {
	
	// Get all fonts
	$default_fonts = tt_font_get_default_fonts();
	$google_fonts  = tt_font_get_google_fonts();

	// Check if it is set and return if found
	if ( isset( $default_fonts[ $id ] ) ) {
		
		// Return default font from array if set
		return apply_filters( 'tt_font_get_font', $default_fonts[ $id ] );

	} else if ( isset( $google_fonts[ $id ] ) ) {

		// Return google font from array if set
		return apply_filters( 'tt_font_get_font', $google_fonts[ $id ] );

	} else {
		return false;
	}
}
