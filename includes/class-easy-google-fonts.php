<?php 
if ( ! class_exists( 'Easy_Google_Fonts' ) ) :
	class Easy_Google_Fonts {
		/**
		 * Plugin version, used for cache-busting of style and script file references.
		 * 
		 * @var      string
		 * @since 	 1.2
		 */
		const VERSION = '1.2';

		/**
		 * Unique identifier for this plugin
		 *
		 *
		 * The variable name is used as the text domain when internationalizing strings
		 * of text. Its value should match the Text Domain file header in the main
		 * plugin file.
		 *
		 * @since 1.2
		 * @version 1.2.1
		 *
		 */
		protected $plugin_slug = 'easy-google-fonts';

		/**
		 * Instance of this class.
		 *
		 * @var      object
		 *
		 * @since 1.2
		 * @version 1.2.1
		 *
		 */
		protected static $instance = null;

		function __construct() {
			$this->register_actions();		
			$this->register_filters();
		}

		/**
		 * Return the plugin slug.
		 *
		 * @since    1.0.0
		 *
		 *@return    Plugin slug variable.
		 */
		public function get_plugin_slug() {
			return $this->plugin_slug;
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
		 * Register Custom Actions
		 *
		 * Add any custom actions in this function.
		 * 
		 * @since 1.2
		 * @version 1.2.1
		 * 
		 */
		public function register_actions() {
			
		}

		/**
		 * Register Custom Filters
		 *
		 * Add any custom filters in this function.
		 * 
		 * @since 1.2
		 * @version 1.2.1
		 * 
		 */
		public function register_filters() {

		}

		/**
		 * Activation Event
		 * 
		 * Fired when the plugin is activated.
		 *
		 * @param    boolean    $network_wide    True if WPMU superadmin uses
		 *                                       "Network Activate" action, false if
		 *                                       WPMU is disabled or plugin is
		 *                                       activated on an individual blog.
		 *
		 * @since 1.2
		 * @version 1.2.1
		 * 
		 */
		public static function activate( $network_wide ) {

			if ( function_exists( 'is_multisite' ) && is_multisite() ) {

				if ( $network_wide  ) {

					// Get all blog ids
					$blog_ids = self::get_blog_ids();

					foreach ( $blog_ids as $blog_id ) {
						switch_to_blog( $blog_id );
						self::single_activate();
					}

					restore_current_blog();

				} else {
					self::single_activate();
				}

			} else {
				self::single_activate();
			}
		}

		/**
		 * Deactivation Event
		 * 
		 * Fired when the plugin is deactivated.
		 * 
		 * @param    boolean    $network_wide    True if WPMU superadmin uses
		 *                                       "Network Deactivate" action, false if
		 *                                       WPMU is disabled or plugin is
		 *                                       deactivated on an individual blog.
		 *
		 * @since 1.2
		 * @version 1.2.1
		 * 
		 */
		public static function deactivate( $network_wide ) {

			if ( function_exists( 'is_multisite' ) && is_multisite() ) {

				if ( $network_wide ) {

					// Get all blog ids
					$blog_ids = self::get_blog_ids();

					foreach ( $blog_ids as $blog_id ) {

						switch_to_blog( $blog_id );
						self::single_deactivate();

					}

					restore_current_blog();

				} else {
					self::single_deactivate();
				}

			} else {
				self::single_deactivate();
			}
		}

		/**
		 * WMPU Activation Event
		 * 
		 * Fired when a new site is activated with a WPMU environment.
		 *
		 * @param    int    $blog_id    ID of the new blog.
		 *
		 * @since 1.2
		 * @version 1.2.1
		 * 
		 */
		public function activate_new_site( $blog_id ) {

			if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
				return;
			}

			switch_to_blog( $blog_id );
			self::single_activate();
			restore_current_blog();
		}

		/**
		 * Get all blog ids of blogs in the current network that are:
		 * - not archived
		 * - not spam
		 * - not deleted
		 *
		 * @return   array|false    The blog ids, false if no matches.
		 *
		 * @since 1.2
		 * @version 1.2.1
		 * 
		 */
		private static function get_blog_ids() {

			global $wpdb;

			// get an array of blog ids
			$sql = "SELECT blog_id FROM $wpdb->blogs
				WHERE archived = '0' AND spam = '0'
				AND deleted = '0'";

			return $wpdb->get_col( $sql );
		}

		/**
		 * Define Activation Functionality
		 * 
		 * Anything in this function is fired for each blog 
		 * when the plugin is activated.
		 *
		 * @since 1.2
		 * @version 1.2.1
		 * 
		 */
		private static function single_activate() {
			delete_transient( 'tt_font_default_fonts' );
			delete_transient( 'tt_font_google_fonts' );
		}

		/**
		 * Define Deactivation Functionality
		 * 
		 * Anything in this function is fired for each blog 
		 * when the plugin is deactivated.
		 *
		 * @since 1.2
		 * @version 1.2.1
		 * 
		 */
		private static function single_deactivate() {

		}

	}
endif;