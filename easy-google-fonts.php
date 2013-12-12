<?php
/**
 * Plugin Name: Easy Google Fonts
 * Description: A simple and easy way to add google fonts to your WordPress theme.
 * Version: 1.1.1
 * Author: Titanium Themes
 * Author URI: http://www.titaniumthemes.com
 * License: GPL2
 * 
 */

/**
 * Theme Font Generator
 *
 * This file is responsible for enabling custom google
 * fonts to be generated in the WordPress Admin Area. 
 * 
 * @package     WordPress
 * @subpackage  WordPress_Google_Fonts
 * @author      Sunny Johal - Titanium Themes
 * @copyright   Copyright (c) 2013, Titanium Themes
 * @version     1.1
 * 
 */

/**
 * Load Plugin Files
 *
 * Load all of the font interface functions and classes
 * and loads the translation text domain.
 *
 * @link    http://codex.wordpress.org/Function_Reference/load_plugin_textdomain     load_plugin_textdomain()
 * @link    http://codex.wordpress.org/Function_Reference/plugin_basename            plugin_basename()
 * @link    http://codex.wordpress.org/Function_Reference/plugin_dir_path            plugin_dir_path()
 *
 * @since 1.0
 * @version 1.1
 * 
 */
function tt_font_load_plugin_files() {
    
	// Load Plugin Translations
	load_plugin_textdomain( 'theme-translate', false, dirname( plugin_basename( __FILE__ ) ) );

    // Load Plugin Functions
    require_once ( plugin_dir_path(__FILE__) . '/includes/theme-font-functions.php' ); 
    require_once ( plugin_dir_path(__FILE__) . '/includes/theme-font-options.php' );    
    require_once ( plugin_dir_path(__FILE__) . '/includes/theme-font-customizer.php' ); 
    require_once ( plugin_dir_path(__FILE__) . '/includes/theme-font-frontend-functions.php' ); 
    require_once ( plugin_dir_path(__FILE__) . '/includes/theme-font-ajax-functions.php' );	
}
add_action( 'init', 'tt_font_load_plugin_files', 0 ); // High Priority Loading

/**
 * Create the Theme Fonts Admin Page
 *
 * Registers a new theme page with WordPress which will
 * display settings for this plugin. Will only create
 * the admin page if the user has the 'edit_theme_options'
 * capability.
 *
 * @link    http://codex.wordpress.org/Function_Reference/add_theme_page            add_theme_page() 
 * @link    http://codex.wordpress.org/Function_Reference/add_action                add_action() 
 *
 * @since 1.0
 * @version 1.1
 * 
 */
function tt_font_add_admin_page() {

    global $custom_theme_fonts;

    if ( current_user_can( 'edit_theme_options' ) ) {
        
        $custom_theme_fonts = add_options_page( 
            __( 'Google Fonts', 'theme-translate' ), 
            __( 'Google Fonts', 'theme-translate' ), 
            'edit_theme_options', 
            'custom_theme_fonts', 
            'tt_font_output_admin_page' );

        /*
         * Use the retrieved $custom_theme_fonts to hook the function that enqueues our styles/scripts.
         * This hook invokes the function only on our plugin administration screen,
         * see: http://codex.wordpress.org/Administration_Menus#Page_Hook_Suffix
         */
        add_action('admin_print_scripts-' . $custom_theme_fonts, 'tt_font_enqueue_admin_page_styles');
        add_action('admin_print_scripts-' . $custom_theme_fonts, 'tt_font_enqueue_admin_page_scripts');

        /*
         * Use the retrieved $custom_theme_fonts to hook the function that enqueues our contextual help tabs.
         * This hook invokes the function only on our plugin administration screen,
         * see: http://codex.wordpress.org/Administration_Menus#Page_Hook_Suffix
         */
        add_action('load-'. $custom_theme_fonts, 'tt_font_add_admin_page_help_tabs');
        add_action('load-'. $custom_theme_fonts, 'tt_font_add_admin_page_options_tab');        
    }

}
add_action( 'admin_menu', 'tt_font_add_admin_page' );

/**
 * Generate Google Font Generator Settings Page
 *
 * This function is responsible for generating and outputting
 * the html settings page for the google font generator.
 *
 * @since 1.0
 * @version 1.1
 * 
 */
function tt_font_output_admin_page() {
   require_once ( plugin_dir_path(__FILE__) . '/includes/theme-font-admin-page.php' );
}

/**
 * Load Theme Font Generator Admin Page JavaScript
 *
 * Will only load scripts on the specific admin page of the website. Hooks 
 * into the admin_print_scripts-custom_theme_fonts action which is 
 * defined in the tt_font_add_admin_page() function.
 *
 * @link    http://codex.wordpress.org/Function_Reference/wp_deregister_script  wp_deregister_script()
 * @link    http://codex.wordpress.org/Function_Reference/wp_register_script    wp_register_script()
 * @link    http://codex.wordpress.org/Function_Reference/wp_enqueue_script     wp_enqueue_script()
 * @link    http://codex.wordpress.org/Function_Reference/wp_localize_script    wp_localize_script()
 * @link    http://codex.wordpress.org/Function_Reference/wp_is_mobile          wp_is_mobile()
 * @link    http://codex.wordpress.org/Function_Reference/plugins_url           plugins_url()
 * 
 * @since 1.0
 * @version 1.1
 *
 */
function tt_font_enqueue_admin_page_scripts() {
    
    // Load jQuery and jQuery UI
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'utils' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-effects-core' );
    wp_enqueue_script( 'jquery-effects-fade' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-autocomplete' );
    wp_enqueue_script( 'jquery-ui-position' );
    wp_enqueue_script( 'jquery-ui-widget' );
    wp_enqueue_script( 'jquery-ui-mouse' );
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'jquery-ui-droppable' );

    // Load PostBox
    wp_enqueue_script( 'postbox' );

    if ( wp_is_mobile() ) 
            wp_enqueue_script( 'jquery-touch-punch' );

    // Load Tag It
    wp_deregister_script( 'tag-it' );
    wp_register_script( 
        'tag-it', 
        plugins_url( 'easy-google-fonts' ) . '/js/tag-it.js', 
        array('jquery', 'jquery-ui-core', 'jquery-ui-widget'), 
        '1.0',  
        false 
    );
    wp_enqueue_script( 'tag-it' );

    // Load Tag It
    wp_deregister_script( 'tt-font-admin' );
    wp_register_script( 
        'tt-font-admin', 
        plugins_url( 'easy-google-fonts' ) . '/js/tt-font-admin.js', 
        array('jquery', 'jquery-ui-core', 'jquery-ui-widget'), 
        '1.0', 
        false 
    );
    wp_enqueue_script( 'tt-font-admin' );

    // Load in customizer control javascript object
    $controlsL10n = array(
        'ajax_url'         => admin_url( 'admin-ajax.php' ),
        'confirmation'     => __( 'This page is asking you to confirm that you want to leave - data you have entered may not be saved.', 'theme-translate' ),
        'deleteAllWarning' => __( "Warning! You are about to permanently delete all font controls. 'Cancel' to stop, 'OK' to delete.", 'theme-translate' ),
        'deleteWarning'    => __( "You are about to permanently delete this font control. 'Cancel' to stop, 'OK' to delete.", 'theme-translate' ),

    );
    wp_localize_script( 'tt-font-admin', 'ttFontl10n', $controlsL10n );

}

/**
 * Load Theme Font Generator Admin Page Styles
 *
 * Load CSS on the specific admin page of the website. Hooks into the 
 * admin_print_scripts-custom_theme_fonts action which is defined 
 * in the tt_font_add_admin_page() function.
 * 
 * @link    http://codex.wordpress.org/Function_Reference/wp_deregister_style   wp_deregister_style()
 * @link    http://codex.wordpress.org/Function_Reference/wp_register_style     wp_register_style()
 * @link    http://codex.wordpress.org/Function_Reference/wp_enqueue_style      wp_enqueue_style()
 * @link    http://codex.wordpress.org/Function_Reference/plugins_url           plugins_url()
 *
 * @since 1.0
 * @version  1.0
 * 
 */
function tt_font_enqueue_admin_page_styles() {
    wp_deregister_style( 'font-admin-css' );
    wp_register_style( 
        'font-admin-css', 
        plugins_url( 'easy-google-fonts' ) . '/css/font-admin-page.css', 
        null, 
        '1.0', 
        false 
    );
    wp_enqueue_style( 'font-admin-css' );  
}

/**
 * Add Help Tabs To The Font Generator Admin Page
 *
 * Adds contextual help tabs to the custom themes fonts page.
 * This function is attached to an action that ensures that the
 * help tabs are only displayed on the custom admin page.
 *
 * @uses global $custom_theme_fonts
 * @link    http://codex.wordpress.org/Function_Reference/get_current_screen      get_current_screen()
 * @link    http://codex.wordpress.org/Function_Reference/add_help_tab            add_help_tab()
 *
 * @since 1.0
 * @version 1.1
 * 
 */
function tt_font_add_admin_page_help_tabs(){
    global $custom_theme_fonts;

     $screen = get_current_screen();

    /*
     * Don't add help tab if the current screen is not 
     * the custom fonts page
     */
    if ( $screen->id != $custom_theme_fonts )
        return;

    // Overview Tab
    $overview  = '<p>' . __( 'This screen is used for managing your custom font controls. It provides a way to create a custom font controls for any type of content in your theme.', 'theme-translate' ) . '</p>';
    $overview .= '<p>' . __( 'From this screen you can:' ) . '</p>';
    $overview .= '<ul><li>' . __( 'Create, edit, and delete custom font controls.', 'theme-translate' ) . '</li>';
    $overview .= '<li>' . __( 'Manage all of your custom font controls.', 'theme-translate' ) . '</li>';
    $overview .= '<li>' . __( 'Add a Google API key in order to enable automatic font updates.', 'theme-translate' ) . '</li></ul>';
    $overview .= '<p><strong>' . __( 'Please Note: ', 'theme-translate' ) . '</strong>';
    $overview .= __( 'This screen is used to manage/create new font controls. To preview fonts for each control please visit the typography section in the ', 'theme-translate' );
    $overview .= '<a href="' . admin_url( 'customize.php' ) . '">' . __( 'customizer', 'theme-translate' ) . '</a></p>';

    
    $screen->add_help_tab( array(
        'id'      => 'overview',
        'title'   => __('Overview', 'theme-translate'),
        'content' => $overview,
    ) );

    $edit_content  = '';
    $edit_content .= '<p>' . 'This screen is used for creating and managing individual custom font controls.'  . '</p>';
    $edit_content .= '<p>' . __( 'From this screen you can:' ) . '</p>';
    $edit_content .= '<ul><li>' . __( 'Create, edit, and delete custom font controls.', 'theme-translate' ) . '</li>';
    $edit_content .= '<li>' . __( 'Add CSS Selectors: Add any CSS selectors/styles that you want this custom font control to manage.', 'theme-translate' ) . '</li>';
    $edit_content .= '<li>' . __( "Force Styles Override (Optional): If your theme is forcing any styles in it's stylesheet for any styles managed by this control then check this option to force a stylesheet override.", 'theme-translate' ) . '</li></ul>';
    $edit_content .= '<p><strong>' . __( 'Please Note: ', 'theme-translate' ) . '</strong>';
    $edit_content .= __( 'This screen is used to manage/create new font controls. To preview fonts for each control please visit the typography section in the ', 'theme-translate' );
    $edit_content .= '<a href="' . admin_url( 'customize.php' ) . '">' . __( 'customizer', 'theme-translate' ) . '</a></p>';

    
    $screen->add_help_tab( array(
        'id'      => 'edit-controls',
        'title'   => __( 'Edit Font Controls', 'theme-translate'),
        'content' => $edit_content,
    ) );

    $manage_content  = '';
    $manage_content .= '<p>' . 'This screen is used for managing all of your custom font controls.'  . '</p>';
    $manage_content .= '<p>' . __( 'From this screen you can:' ) . '</p>';
    $manage_content .= '<ul><li>' . __( 'View all of your custom font controls and the CSS selectors they are managing.', 'theme-translate' ) . '</li>';
    $manage_content .= '<li>' . __( 'Delete any/all custom font controls.', 'theme-translate' ) . '</li>';
    $manage_content .= '<li>' . __( "Force Styles Override (Optional): If your theme is forcing any styles in it's stylesheet for any styles managed by this control then check this option to force a stylesheet override.", 'theme-translate' ) . '</li></ul>';
    $manage_content .= '<p><strong>' . __( 'Please Note: ', 'theme-translate' ) . '</strong>';
    $manage_content .= __( 'This screen is used to manage/create new font controls. To preview fonts for each control please visit the typography section in the ', 'theme-translate' );
    $manage_content .= '<a href="' . admin_url( 'customize.php' ) . '">' . __( 'customizer', 'theme-translate' ) . '</a></p>';

    $screen->add_help_tab( array(
        'id'      => 'manage-controls',
        'title'   => __( 'Manage Font Controls', 'theme-translate'),
        'content' => $manage_content,
    ) );

    $api_content  = '<p>' . __( 'To acquire an API key, visit the <a href="https://code.google.com/apis/console" target="_blank">APIs Console</a>. In the Services pane, activate the Google Fonts Developer API; if the Terms of Service appear, read and accept them.', 'theme-translate' ) . '</p>';
    $api_content .= '<p>' . __( 'Next, go to the API Access pane. The API key is near the bottom of that pane, in the section titled "Simple API Access."', 'theme-translate' ) . '</p>';
    $api_content .= '<p>' . __( 'After you have an API key, please enter it in the Google Fonts API Key text field on the "Advanced" settings page.', 'theme-translate' ) . '</p>';
    $api_content .= '<p>' . __( 'Once you have entered a valid API key this plugin will automatically update itself with the latest fonts from google.', 'theme-translate' ) . '</p>';

    $screen->add_help_tab( array(
        'id'      => 'api-key',
        'title'   => __( 'Advanced', 'theme-translate'),
        'content' => $api_content,
    ) );

    $screen->set_help_sidebar(
        '<p><strong>' . __('For more information:', 'theme-translate') . '</strong></p>' .
        '<p><a href="http://www.google.com/fonts#AboutPlace:about" target="_blank">' . __('Documentation on Google Fonts', 'theme-translate') . '</a></p>' .
        '<p><a href="https://code.google.com/apis/console" target="_blank">' . __( 'Get Google Fonts API Key', 'theme-translate' ) . '</a></p>'
    );
}

/**
 * Get Help Tab Options
 *
 * This function has been created in order to give developers
 * a hook by which to add their own screen options.
 *
 * @since 1.0
 * @version 1.1
 * 
 */
function tt_font_add_admin_page_options_tab() {
   
    global $custom_theme_fonts;

    $screen = get_current_screen();

    /*
     * Don't add help tab if the current screen is not 
     * the custom sidebar page
     */
    if ( $screen->id != $custom_theme_fonts )
        return;
    
    // Developers: Add Options Below
  
}
