<?php
/**
 * Class: EGF_Frontend
 *
 * This file is responsible for retrieving all of
 * the options and outputting any appropriate styles
 * for the theme.
 *
 * @package   Easy_Google_Fonts
 * @author    Sunny Johal - Titanium Themes <support@titaniumthemes.com>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/easy-google-fonts/
 * @copyright Copyright (c) 2014, Titanium Themes
 * @version   1.3.3
 * 
 */
if ( ! class_exists( 'EGF_Frontend' ) ) :
	class EGF_Frontend {
		
		/**
		 * Instance of this class.
		 * 
		 * @var      object
		 * @since    1.2
		 *
		 */
		protected static $instance = null;

		/**
		 * Slug of the plugin screen.
		 * 
		 * @var      string
		 * @since    1.2
		 *
		 */
		protected $plugin_screen_hook_suffix = null;
		
		/**
		 * Constructor Function
		 * 
		 * Initialize the plugin by loading admin scripts & styles and adding a
		 * settings page and menu.
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		function __construct() {
			/**
			 * Call $plugin_slug from public plugin class.
			 *
			 */
			$plugin = Easy_Google_Fonts::get_instance();
			$this->plugin_slug = $plugin->get_plugin_slug();
			$this->register_actions();		
			$this->register_filters();
		}	

		/**
		 * Return an instance of this class.
		 * 
		 * @return    object    A single instance of this class.
		 *
		 * @since 1.2
		 * @version 1.3.3
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
		 * We add a high action to wp_head to ensure
		 * that our styles are outputted as late as 
		 * possible.
		 * 
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		public function register_actions() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_stylesheets' ) );
			add_action( 'wp_head', array( $this, 'output_styles' ), 999 );
		}

		/**
		 * Register Custom Filters
		 *
		 * Add any custom filters in this function.
		 * 
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		public function register_filters() {

		}

		/**
		 * Enqueue Font Stylesheets
		 *
		 * Enqueues the required stylesheet for the selected google
		 * fonts. By using wp_enqueue_style() we can ensure that 
		 * the stylesheet for each font is only being included on
		 * the page once. 
		 * 
		 * Update: This function now combines the call to 
		 *     google in one http request. It now uses 
		 *     esc_url_raw()
		 *
		 * @link http://codex.wordpress.org/Function_Reference/wp_register_style 	wp_register_style()
		 *
		 * @global $wp_customize
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		public function enqueue_stylesheets() {
			global $wp_customize;

			$transient         = isset( $wp_customize ) ? false : true;
			$options           = EGF_Register_Options::get_options( $transient );
			$stylesheet_handle = 'tt-easy-google-fonts';
			$font_families     = array();
			$font_family_sets  = array();
			$subsets           = array();
			$protocol          = is_ssl() ? 'https' : 'http';

			if ( $options ) {

				foreach ( $options as $option ) {

					// Convert option to array if it is in JSON format
					if ( is_string( $option ) ) {
						$option = json_decode( $option );
					}

					// Convert option to array if it is a StdClass Object
					if ( is_object( $option ) ) {
						$option = $this->object_to_array( $option );
					}

					/**
					 * Check Font Type:
					 *
					 * If the current font is a google font then we
					 * add it to the $font_families array and enqueue
					 * the font after we have gone through all of the
					 * $options. Otherwise, if this font is a custom
					 * font enqueued by a developer then we enqueue
					 * it straight away. This allows developers to hook
					 * into the custom filters in this plugin and make
					 * local fonts available in the customizer which
					 * will automatically enqueue on the frontend.
					 * 
					 */
					if ( ! empty( $option['stylesheet_url'] ) ) {

						if ( strpos( $option['stylesheet_url'], 'fonts.googleapis' ) !== false ) {
							
							// Generate array key
							$key = str_replace( ' ', '+', $option['font_name'] );

							// Initialise the font array if this is a new font
							if ( ! isset( $font_families[ $key ] ) ) {
								$font_families[ $key ] = array();
							}

							/**
							 * Add the font weight to the font family if
							 * it hasn't been added already.
							 */
							if ( ! in_array( $option['font_weight_style'], $font_families[ $key ] ) ) {
								$font_families[ $key ][] = $option['font_weight_style'];
							}

							// Populate subset
							if ( ! empty( $option['subset'] ) && ! in_array( $option['subset'], $subsets ) ) {
								$subsets[] = $option['subset'];
							}

						} else {
							
							// Fallback enqueue method
							$subset = empty( $option['subset'] ) ? '' : '&subset=' . $option['subset'];
							$handle = "{$option['font_id']}-{$option['font_weight_style']}";

							if ( ! empty( $option['subset'] ) ) {
								$handle .= '-' . $option['subset'];
							}

							// Enqueue custom font using wp_enqueue_style()
							wp_deregister_style( $handle );
							wp_register_style( $handle, $option['stylesheet_url'] . $subset );
							wp_enqueue_style( $handle );
						}
					}					
				}

				/**
				 * Check if Google Fonts Exist:
				 * 
				 * Checks if the user has selected any google fonts
				 * to enqueue on the frontend and requests the fonts
				 * from Google in a single http request.
				 * 
				 */
				if ( ! empty( $font_families ) && is_array( $font_families ) ) {

					foreach ( $font_families as $font_family => $variants ) {
						$font_family_sets[] = $font_family . ':' . implode( ',', $variants );
					}

					$query_args = array(
						'family' => implode( '|', $font_family_sets ),
						'subset' => implode( ',', array_unique( $subsets ) ),
					);

					$request_url = add_query_arg( $query_args, "{$protocol}://fonts.googleapis.com/css" );

					wp_deregister_style( $stylesheet_handle );
					wp_register_style( $stylesheet_handle, esc_url_raw( $request_url ) );
					wp_enqueue_style( $stylesheet_handle );
				}
			}
		}

		/**
		 * Output Inline Styles in Head
		 *
		 * Hooks into the 'wp_head' action and outputs specific
		 * inline styles relevant to each font option.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_action 	add_action()
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		public function output_styles() {
			
			global $wp_customize;

			$transient       = isset( $wp_customize ) ? false : true;
			$options         = EGF_Register_Options::get_options( $transient );
			$default_options = EGF_Register_Options::get_option_parameters();
			?>
			
			<?php if ( ! isset( $wp_customize ) ) : ?>
				<style id="tt-easy-google-font-styles" type="text/css">
			<?php endif; ?>

			<?php foreach ( $options as $key => $value ) : ?>
				<?php
					$force_styles = isset( $default_options[ $key ]['properties']['force_styles'] ) ? $default_options[ $key ]['properties']['force_styles'] : false;
				?>
				<?php if ( isset( $wp_customize ) && ! empty( $options[ $key ] ) ) : ?>
					<?php echo $this->generate_customizer_css( $options[ $key ], $default_options[ $key ]['properties']['selector'], $key, $force_styles ); ?>
				<?php else : ?>
					<?php if ( ! empty( $default_options[ $key ] ) ) : ?>
						<?php echo $default_options[ $key ]['properties']['selector']; ?> {
							<?php echo $this->generate_css( $options[ $key ], $force_styles ); ?>
						}
					<?php endif; ?>			
				<?php endif; ?>
			<?php endforeach; ?>
			
			<?php if ( ! isset( $wp_customize ) ) : ?>
				</style>
			<?php endif; ?>
			<?php
		}

		/**
		 * Generate Inline Font CSS
		 *
		 * Takes a font option array as a parameter and
		 * return a string of inline styles.
		 * 
		 * @param  array $option 	Font option array
		 * @return string $output 	Inline styles
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		public function generate_css( $option, $force_styles = false ) {
			$output     = '';
			$importance = $force_styles ? '!important' : ''; 

			// If properties are a json string decode them into an array
			if ( is_string( $option ) ) {
				$option = json_decode( $option );
			}

			// Typecast properties as array if we are already customizing (as it has turned into a stdClass object)
			if ( is_object( $option ) ) {
				$option = $this->object_to_array( $option );
			}
			
			// Font Family
			if ( ! empty( $option['font_name'] ) ) {
				$output .= "font-family: {$option['font_name']}{$importance}; ";
			}

			// Background Color
			if ( ! empty( $option['background_color'] ) ) {
				$output .= "background-color: {$option['background_color']}{$importance}; ";
			}

			// Color
			if ( ! empty( $option['font_color'] ) ) {
				$output .= "color: {$option['font_color']}{$importance}; ";
			}

			// Font Weight
			if ( ! empty( $option['font_weight'] ) ) {
				$output .= "font-weight: {$option['font_weight']}{$importance}; ";
			}

			// Font Style
			if ( ! empty( $option['font_style'] ) ) {
				$output .= "font-style: {$option['font_style']}{$importance}; ";
			}

			// Text Decoration
			if ( ! empty( $option['text_decoration'] ) ) {
				$output .= "text-decoration: {$option['text_decoration']}{$importance}; ";
			}

			// Text Decoration
			if ( ! empty( $option['text_transform'] ) ) {
				$output .= "text-transform: {$option['text_transform']}{$importance}; ";
			}

			// Line Height
			if ( ! empty( $option['line_height'] ) ) {
				$output .= "line-height: {$option['line_height']}{$importance}; ";
			}

			// Font Size
			if ( ! empty( $option['font_size']['amount'] ) ) {
				$output .= "font-size: {$option['font_size']['amount']}{$option['font_size']['unit']}{$importance}; ";
			}

			// Letter Spacing
			if ( ! empty( $option['letter_spacing']['amount'] ) ) {
				$output .= "letter-spacing: {$option['letter_spacing']['amount']}{$option['letter_spacing']['unit']}{$importance}; ";
			}

			// Margin Top
			if ( ! empty( $option['margin_top']['amount'] ) ) {
				$output .= "margin-top: {$option['margin_top']['amount']}{$option['margin_top']['unit']}{$importance}; ";
			}

			// Margin Right
			if ( ! empty( $option['margin_right']['amount'] ) ) {
				$output .= "margin-right: {$option['margin_right']['amount']}{$option['margin_right']['unit']}{$importance}; ";
			}

			// Margin Bottom
			if ( ! empty( $option['margin_bottom']['amount'] ) ) {
				$output .= "margin-bottom: {$option['margin_bottom']['amount']}{$option['margin_bottom']['unit']}{$importance}; ";
			}	

			// Margin Left
			if ( ! empty( $option['margin_left']['amount'] ) ) {
				$output .= "margin-left: {$option['margin_left']['amount']}{$option['margin_left']['unit']}{$importance}; ";
			}	

			// Padding Top
			if ( ! empty( $option['padding_top']['amount'] ) ) {
				$output .= "padding-top: {$option['padding_top']['amount']}{$option['padding_top']['unit']}{$importance}; ";
			}

			// Padding Right
			if ( ! empty( $option['padding_right']['amount'] ) ) {
				$output .= "padding-right: {$option['padding_right']['amount']}{$option['padding_right']['unit']}{$importance}; ";
			}

			// Padding Bottom
			if ( ! empty( $option['padding_bottom']['amount'] ) ) {
				$output .= "padding-bottom: {$option['padding_bottom']['amount']}{$option['padding_bottom']['unit']}{$importance}; ";
			}	

			// Padding Left
			if ( ! empty( $option['padding_left']['amount'] ) ) {
				$output .= "padding-left: {$option['padding_left']['amount']}{$option['padding_left']['unit']}{$importance}; ";
			}

			// Display
			if ( ! empty( $option['display'] ) ) {
				$output .= "display: {$option['display']}{$importance}; ";
			}

			return $output;
		}

		/**
		 * Generate Customizer Preview Inline Font CSS
		 *
		 * Outputs compatible <style> tags that are necessary in
		 * order to facilitate the live preview. By outputting the
		 * styles in their own <style> tag we are able to use the
		 * font-customizer-preview.js to revert back to theme 
		 * defaults without refreshing the page.
		 * 
		 * @param  array $option 	Font option array
		 * @return string $output 	Inline styles
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		public function generate_customizer_css( $option, $selector, $id = '', $force_styles = false ) {
			$output     = '';
			$importance = $force_styles ? '!important' : '';

			// If properties are a json string decode them into an array
			if ( is_string( $option ) ) {
				$option = json_decode( $option );
			}

			// Typecast properties as array if we are already customizing (as it has turned into a stdClass object)
			if ( is_object( $option ) ) {
				$option = $this->object_to_array( $option );
			}

			// Font Family
			if ( ! empty( $option['font_name'] ) ) {
				$output .= "<style id='tt-font-{$id}-font-family' type='text/css'>{$selector}{";
				$output .= "font-family: {$option['font_name']}{$importance}; ";
				$output .= "}</style>";
			}

			// Background Color
			if ( ! empty( $option['background_color'] ) ) {
				$output .= "<style id='tt-font-{$id}-background-color' type='text/css'>{$selector}{";
				$output .= "background-color: {$option['background_color']}{$importance}; ";
				$output .= "}</style>";
			}

			// Color
			if ( ! empty( $option['font_color'] ) ) {
				$output .= "<style id='tt-font-{$id}-color' type='text/css'>{$selector}{";
				$output .= "color: {$option['font_color']}{$importance}; ";
				$output .= "}</style>";
			}

			// Font Weight
			if ( ! empty( $option['font_weight'] ) ) {
				$output .= "<style id='tt-font-{$id}-font-weight' type='text/css'>{$selector}{";
				$output .= "font-weight: {$option['font_weight']}{$importance}; ";
				$output .= "}</style>";
			}

			// Font Style
			if ( ! empty( $option['font_style'] ) ) {
				$output .= "<style id='tt-font-{$id}-font-style' type='text/css'>{$selector}{";
				$output .= "font-style: {$option['font_style']}{$importance}; ";
				$output .= "}</style>";
			}

			// Text Decoration
			if ( ! empty( $option['text_decoration'] ) ) {
				$output .= "<style id='tt-font-{$id}-text-decoration' type='text/css'>{$selector}{";
				$output .= "text-decoration: {$option['text_decoration']}{$importance}; ";
				$output .= "}</style>";
			}

			// Text Transform
			if ( ! empty( $option['text_transform'] ) ) {
				$output .= "<style id='tt-font-{$id}-text-transform' type='text/css'>{$selector}{";
				$output .= "text-transform: {$option['text_transform']}{$importance}; ";
				$output .= "}</style>";
			}

			// Line Height
			if ( ! empty( $option['line_height'] ) ) {
				$output .= "<style id='tt-font-{$id}-line-height' type='text/css'>{$selector}{";
				$output .= "line-height: {$option['line_height']}{$importance}; ";
				$output .= "}</style>";
			}

			// Font Size
			if ( ! empty( $option['font_size']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-font-size' type='text/css'>{$selector}{";
				$output .= "font-size: {$option['font_size']['amount']}{$option['font_size']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Letter Spacing
			if ( ! empty( $option['letter_spacing']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-letter-spacing' type='text/css'>{$selector}{";
				$output .= "letter-spacing: {$option['letter_spacing']['amount']}{$option['letter_spacing']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Margin Top
			if ( ! empty( $option['margin_top']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-margin-top' type='text/css'>{$selector}{";
				$output .= "margin-top: {$option['margin_top']['amount']}{$option['margin_top']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Margin Right
			if ( ! empty( $option['margin_right']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-margin-right' type='text/css'>{$selector}{";
				$output .= "margin-right: {$option['margin_right']['amount']}{$option['margin_right']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Margin Bottom
			if ( ! empty( $option['margin_bottom']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-margin-bottom' type='text/css'>{$selector}{";
				$output .= "margin-bottom: {$option['margin_bottom']['amount']}{$option['margin_bottom']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Margin Left
			if ( ! empty( $option['margin_left']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-margin-left' type='text/css'>{$selector}{";
				$output .= "margin-left: {$option['margin_left']['amount']}{$option['margin_left']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Padding Top
			if ( ! empty( $option['padding_top']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-padding-top' type='text/css'>{$selector}{";
				$output .= "padding-top: {$option['padding_top']['amount']}{$option['padding_top']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Padding Right
			if ( ! empty( $option['padding_right']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-padding-right' type='text/css'>{$selector}{";
				$output .= "padding-right: {$option['padding_right']['amount']}{$option['padding_right']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Padding Bottom
			if ( ! empty( $option['padding_bottom']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-padding-bottom' type='text/css'>{$selector}{";
				$output .= "padding-bottom: {$option['padding_bottom']['amount']}{$option['padding_bottom']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Padding Left
			if ( ! empty( $option['padding_left']['amount'] ) ) {
				$output .= "<style id='tt-font-{$id}-padding-left' type='text/css'>{$selector}{";
				$output .= "padding-left: {$option['padding_left']['amount']}{$option['padding_left']['unit']}{$importance}; ";
				$output .= "}</style>";
			}

			// Display
			if ( ! empty( $option['display'] ) ) {
				$output .= "<style id='tt-font-{$id}-display' type='text/css'>{$selector}{";
				$output .= "display: {$option['display']}{$importance}; ";
				$output .= "}</style>";
			}

			return $output;	
		}

		/**
		 * Recursive Function: Object to Array
		 * 
		 * @param  class $obj The object we want to convert
		 * @return array $arr The object converted into an associative array
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		public function object_to_array( $obj ) {
			$arrObj = is_object( $obj ) ? get_object_vars( $obj ) : $obj;

			$arr = array();
			
			foreach ( $arrObj as $key => $val ) {
				$val = ( is_array( $val ) || is_object( $val ) ) ? $this->object_to_array( $val ) : $val;
				$arr[$key] = $val;
			}
			return $arr;
		}
	}
endif;