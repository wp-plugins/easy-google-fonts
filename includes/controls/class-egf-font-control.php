<?php
/**
 * Class: EGF_Font_Control
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
if ( ! class_exists( 'EGF_Font_Control' ) && class_exists( 'WP_Customize_Control' ) ) :
	class EGF_Font_Control extends WP_Customize_Control {

		/**
		 * @access public
		 * @var string
		 */
		public $type = 'font';
		public $selector;

		/**
		 * @access public
		 * @var boolean
		 */
		public $force_styles;

		/**
		 * @access public
		 * @var array
		 */
		public $statuses;

		/**
		 * @access protected
		 * @var string
		 */
		protected $option_name;
		protected static $slug = 'easy-google-fonts';

		/**
		 * @access protected
		 * @var array
		 */
		protected $option;
		protected $font_properties;
		protected $font_defaults;
		protected $tabs = array();


		/**
		 * Constructor.
		 *
		 * If $args['settings'] is not defined, use the $id as the setting ID.
		 *
		 * @since 3.4.0
		 * @uses WP_Customize_Upload_Control::__construct()
		 *
		 * @param WP_Customize_Manager $manager
		 * @param string $id
		 * @param array $args
		 */
		public function __construct( $manager, $id, $args = array() ) {

			// Set option name, properties and default values
			$this->option_name     = "tt_font_theme_options[{$id}]";
			$this->option          = $args['option'];
			$this->font_properties = $args['option']['properties'];
			$this->font_defaults   = $args['option']['default'];

			// // Set variables
			$this->selector     = $args['option']['properties']['selector'];
			$this->force_styles = $args['option']['properties']['force_styles'];
			
			$this->manager      = $manager;
			$this->id           = $id;

			parent::__construct( $manager, $id, $args );

			$this->add_tab( 'font-styles',      __( 'Styles', 'easy-google-fonts' ), array( $this, 'get_style_controls' ), true );
			$this->add_tab( 'font-appearance',  __( 'Appearance', 'easy-google-fonts' ), array( $this, 'get_appearance_controls' ) );
			$this->add_tab( 'font-positioning', __( 'Positioning', 'easy-google-fonts' ), array( $this, 'get_positioning_controls' ) );
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @since 1.2
		 */
		public function enqueue() {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
		}

		/**
		 * Render the data link parameter for a setting
		 *
		 * @since 3.4.0
		 * @uses WP_Customize_Control::get_link()
		 *
		 * @param string $setting_key
		 */
		public function option_link( $setting_key = 'default', $property = '', $property_two = '' ) {
			echo $this->get_option_link( $setting_key, $property, $property_two );
		}

		/**
		 * Get the data link parameter for a setting.
		 *
		 * @since 3.4.0
		 *
		 * @param string $setting_key
		 * @return string Data link parameter, if $setting_key is a valid setting, empty string otherwise.
		 */
		public function get_option_link( $setting_key = 'default', $property = '', $property_two = '' ) {

			if ( ! isset( $this->settings[ $setting_key ] ) ) {
				return '';
			}

			$property     = $property ? "[{$property}]" : '';
			$property_two = $property_two ? "[{$property_two}]" : '';

			return "data-customize-setting-link='" . esc_attr( $this->settings[ $setting_key ]->id ) . "{$property}{$property_two}'";
		}

		/**
		 * Add a tab to the control.
		 *
		 * @param string $id
		 * @param string $label
		 * @param mixed $callback
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function add_tab( $id, $label, $callback, $selected = false ) {
			$this->tabs[ $id ] = array(
				'label'    => $label,
				'callback' => $callback,
				'selected' => $selected,
			);
		}

		/**
		 * Remove a tab from the control.
		 *
		 * @param string $id
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function remove_tab( $id ) {
			unset( $this->tabs[ $id ] );
		}

		/**
		 * Get Font Control Title
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_control_title() {
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/control-title.php' );
		}

		/**
		 * [get_control_start description]
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_control_start() {
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/control-start.php' );
		}

		/**
		 * [get_control_start description]
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_control_end() {
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/control-end.php' );
		}

		/**
		 * [get_control_start description]
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_control_properties_start() {
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/properties-start.php' );
		}

		/**
		 * [get_control_start description]
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_control_properties_end() {
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/properties-end.php' );
		}

		/**
		 * [get_control_start description]
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_control_toggle() {
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/control-toggle.php' );
		}

		/**
		 * [get_control_tabs description]
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_control_tabs() {
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/control-tabs.php' );
		}

		/**
		 * Get Tab Panes
		 *
		 * Gets the tab panes that belong to each font
		 * control. Currently these are:
		 *     - Styles
		 *     - Appearence
		 *     - Positioning
		 *
		 * @link http://codex.wordpress.org/Function_Reference/plugin_dir_path 	plugin_dir_path()
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_control_tab_panes() {
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/control-tab-panes.php' );
		}

		/**
		 * Get Style Controls
		 * 
		 * Controls:
		 *     - Font Family
		 *     - Font Weight
		 *     - Text Decoration
		 *     - Text Transform
		 *     - Display
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_style_controls() {
			$this->get_subset_control();
			$this->get_font_family_control();
			$this->get_font_weight_control();
			$this->get_text_decoration_control();
			$this->get_text_transform_control();
			$this->get_hidden_style_controls();
		}

		/**
		 * Get Appearance Controls
		 * 
		 * Controls:
		 *     - Font Color
		 *     - Background Color
		 *     - Font Size
		 *     - Line Height
		 *     - Letter Spacing
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_appearance_controls() {
			$this->get_font_color_control();
			$this->get_background_color_control();
			$this->get_font_size_control();
			$this->get_line_height_control();
			$this->get_letter_spacing_control();
		}

		/**
		 * Get Positioning Controls
		 *
		 * Controls:
		 *     - Padding ( Top, Bottom, Left, Right )
		 *     - Margin  ( Top, Bottom, Left, Right )
		 *     - Border  ( Top, Bottom, Left, Right ) - Coming Soon
		 *
		 * @todo Implement Border Controls in the next release
		 * 
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_positioning_controls() {
			$this->get_margin_controls();
			$this->get_padding_controls();
			// $this->get_border_controls();
			$this->get_display_control();
		}

		/**
		 * Get Font Subset Control
		 *
		 * Gets the font subset select control.
		 *
		 * Custom Filters
		 *     - tt_font_subset_options
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_subset_control() {

			// Get defaults and current value
			$this_value    = $this->value();

			$default_value = $this->font_defaults['subset'];
			$current_value = empty( $this_value['subset'] ) ? $default_value : $this_value['subset'];

			// Text decoration options
			$font_subset_options = array(
				'all'          => __( 'All Subsets' , 'easy-google-fonts' ),
				'latin'        => __( 'Latin' , 'easy-google-fonts' ),
				'latin-ext'    => __( 'Latin Extended' , 'easy-google-fonts' ),
				'cyrillic'     => __( 'Cyrillic', 'easy-google-fonts' ),
				'cyrillic-ext' => __( 'Cyrillic Extended', 'easy-google-fonts' ),
				'greek'        => __( 'Greek', 'easy-google-fonts' ),	
				'greek-ext'    => __( 'Greek Extended', 'easy-google-fonts' ),
				'khmer'        => __( 'Khmer', 'easy-google-fonts' ),
				'vietnamese'   => __( 'Vietnamese', 'easy-google-fonts' ),
			);
			$font_subset_options = apply_filters( 'tt_font_subset_options', $font_subset_options );

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/styles/subsets.php' );
		}

		/**
		 * Get Font Family Control
		 *
		 * Gets the font family select control. Will only show
		 * the fonts from the applicable subset if it has been
		 * selected.
		 *
		 * @uses EGF_Font_Utilities::get_google_fonts() 	defined in includes\class-egf-font-utilities
		 * @uses EGF_Font_Utilities::get_default_fonts() 	defined in includes\class-egf-font-utilities
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_font_family_control() {
			
			// Get defaults and current value
			$this_value      = $this->value();
			$default_value   = $this->font_defaults['font_id'];
			$current_value   = isset( $this_value['font_id'] ) ? $this_value['font_id'] : $default_value;
			$selected_subset = empty( $this_value['subset'] )  ? $this->font_defaults['subset'] : $this_value['subset'];
			$selected_subset = str_replace( 'latin,', '', $selected_subset );

			// Get all font families
			$google_fonts  = EGF_Font_Utilities::get_google_fonts();
			$default_fonts = EGF_Font_Utilities::get_default_fonts();
	
			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/styles/font-family.php' );
		}

		/**
		 * Get Font Weight Control
		 *
		 * Gets the font family select control. Preselects the 
		 * appropriate font weight if is has been selected.
		 *
		 * @uses EGF_Font_Utilities::get_font() 	defined in includes\class-egf-font-utilities
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_font_weight_control() {
			// Get values
			$this_value                = $this->value();
			$font_id                   = isset( $this_value['font_id'] ) ? $this_value['font_id'] : '';
			$font                      = EGF_Font_Utilities::get_font( $font_id );
			$default_font_weight_style = $this->font_defaults['font_weight_style'];
			$font_weight_style         = empty( $this_value['font_weight_style'] ) ? $default_font_weight_style : $this_value['font_weight_style'];

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/styles/font-weight.php' );
		}

		/**
		 * Get Text Decoration Control
		 *
		 * Gets the text decotation select control.
		 *
		 * Custom Filters
		 *     - tt_font_text_decoration_options
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_text_decoration_control() {
			
			// Get current and default values
			$this_value    = $this->value();
			$default_value = $this->font_defaults['text_decoration'];
			$current_value = empty( $this_value['text_decoration'] ) ? $default_value : $this_value['text_decoration'];

			// Text decoration options
			$text_decoration_options = array(
				'none'			 => __( 'None', 'easy-google-fonts' ),
				'underline'		 => __( 'Underline', 'easy-google-fonts' ),
				'line-through' 	 => __( 'Line-through', 'easy-google-fonts' ),
				'overline'		 => __( 'Overline', 'easy-google-fonts' ),				
			);

			$text_decoration_options = apply_filters( 'tt_font_text_decoration_options', $text_decoration_options );

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/styles/text-decoration.php' );
		}

		/**
		 * Get Text Decoration Control
		 *
		 * Gets the text decotation select control.
		 *
		 * Custom Filters:
		 *     - tt_font_text_transform_options
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_text_transform_control() {

			// Get current and default values
			$this_value    = $this->value();
			$default_value = $this->font_defaults['text_transform'];
			$current_value = empty( $this_value['text_transform'] ) ? $default_value : $this_value['text_transform'];

			// Text decoration options
			$text_transform_options = array(
				'none'			 => __( 'None' , 'easy-google-fonts' ),
				'uppercase'		 => __( 'Uppercase' , 'easy-google-fonts' ),
				'lowercase' 	 => __( 'Lowercase', 'easy-google-fonts' ),
				'capitalize'	 => __( 'Capitalize', 'easy-google-fonts' ),			
			);
			$text_transform_options = apply_filters( 'tt_font_text_transform_options', $text_transform_options );

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/styles/text-transform.php' );
		}

		/**
		 * Get Hidden Style Controls
		 *
		 * Outputs a set of hidden text inputs used to control
		 * and store the following:
		 *
		 *     - Stylesheet URL
		 *     - Font Weight
		 *     - Font Style
		 *     - Font Name
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_hidden_style_controls() {

			// Get defaults and current value
			$this_value    = $this->value();
			
			// Get default values
			$default_stylesheet_url = $this->font_defaults['stylesheet_url'];
			$default_font_weight    = $this->font_defaults['font_weight'];
			$default_font_style     = $this->font_defaults['font_style'];
			$default_font_name      = $this->font_defaults['font_name'];

			// Get current values
			$current_stylesheet_url = isset( $this_value['stylesheet_url'] ) ? $this_value['stylesheet_url'] : $default_stylesheet_url;
			$current_font_weight    = isset( $this_value['font_weight'] )    ? $this_value['font_weight']    : $default_font_weight;
			$current_font_style     = isset( $this_value['font_style'] )     ? $this_value['font_style']     : $default_font_style;
			$current_font_name      = isset( $this_value['font_name'] )      ? $this_value['font_name']      : $default_font_name;

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/styles/hidden-inputs.php' );
		}

		/**
		 * Get Font Color Control
		 *
		 * Gets the font color input control.
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_font_color_control() {
			// Variables used in view
			$value         = $this->value();
			$default_color = $this->font_defaults['font_color'];
			$current_color = isset( $value['font_color'] ) ? $value['font_color'] : $default_color;

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/appearance/font-color.php' );
		}

		/**
		 * Get Background Color Control
		 *
		 * Gets the background color input control.
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_background_color_control() {
			// Variables used in view
			$value         = $this->value();
			$default_color = $this->font_defaults['background_color'];
			$current_color = isset( $value['background_color'] ) ? $value['background_color'] : $default_color;

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/appearance/background-color.php' );			
		}

		/**
		 * Get Font Size Control
		 *
		 * Gets the font size slider input control.
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_font_size_control() {

			// Variables used in view
			$value          = $this->value();
			$step           = $this->font_properties['font_size_step'];
			$min_range      = $this->font_properties['font_size_min_range'];
			$max_range      = $this->font_properties['font_size_max_range'];
			$default_amount = $this->font_defaults['font_size']['amount'];
			$default_unit   = $this->font_defaults['font_size']['unit'];
			
			$current_amount = isset( $value['font_size']['amount'] ) ? $value['font_size']['amount'] : $default_amount;
			$current_unit   = $default_unit;
			
			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/appearance/font-size.php' );
		}

		/**
		 * Get Line Height Control
		 *
		 * Gets the line height slider input control.
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */	
		public function get_line_height_control() {
			
			// Variables used in view
			$value          = $this->value();
			$step           = $this->font_properties['line_height_step'];
			$min_range      = $this->font_properties['line_height_min_range'];
			$max_range      = $this->font_properties['line_height_max_range'];
			$default_amount = $this->font_defaults['line_height'];
			$current_amount = isset( $value['line_height'] ) ? $value['line_height'] : '';

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/appearance/line-height.php' );			
		}

		/**
		 * Get Line Height Control
		 *
		 * Gets the line height slider input control.
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */	
		public function get_letter_spacing_control() {

			// Variables used in view
			$value          = $this->value();
			$step           = $this->font_properties['letter_spacing_step'];
			$min_range      = $this->font_properties['letter_spacing_min_range'];
			$max_range      = $this->font_properties['letter_spacing_max_range'];
			$default_amount = $this->font_defaults['letter_spacing']['amount'];
			$default_unit   = $this->font_defaults['letter_spacing']['unit'];
			$current_amount = isset( $value['letter_spacing']['amount'] ) ? $value['letter_spacing']['amount'] : $default_amount;
			$current_unit   = $default_unit;
			
			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/appearance/letter-spacing.php' );
		}

		/**
		 * Get Display Control
		 *
		 * Gets the display select control.
		 *
		 * @since 1.2
		 * @version 1.3.1
		 */
		public function get_display_control() {
			
			// Display options
			$display_options = array(
				'block'        => __( 'Block', 'easy-google-fonts' ),
				'inline-block' => __( 'Inline Block', 'easy-google-fonts' ),
			);

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/positioning/display.php' );
		}
		
		/**
		 * Get Margin Controls
		 *
		 * Gets the controls for margin top, bottom,
		 * left and right.
		 *
		 * @since 1.2
		 * @version 1.3.1
		 */
		public function get_margin_controls() {
			$value     = $this->value();
			$min_range = $this->font_properties['margin_min_range'];
			$max_range = $this->font_properties['margin_max_range'];
			$step      = $this->font_properties['margin_step'];

			// Get default amounts for each margin control
			$default_amount_top    = $this->font_defaults['margin_top']['amount'];
			$default_amount_bottom = $this->font_defaults['margin_bottom']['amount'];
			$default_amount_left   = $this->font_defaults['margin_left']['amount'];
			$default_amount_right  = $this->font_defaults['margin_right']['amount'];

			$default_unit_top    = $this->font_defaults['margin_top']['unit'];
			$default_unit_bottom = $this->font_defaults['margin_bottom']['unit'];
			$default_unit_left   = $this->font_defaults['margin_left']['unit'];
			$default_unit_right  = $this->font_defaults['margin_right']['unit'];

			// Get current values for each margin control
			$current_amount_top    = isset( $value['margin_top']['amount'] )    ? $value['margin_top']['amount']    : $default_amount_top;
			$current_amount_bottom = isset( $value['margin_bottom']['amount'] ) ? $value['margin_bottom']['amount'] : $default_amount_bottom;
			$current_amount_left   = isset( $value['margin_left']['amount'] )   ? $value['margin_left']['amount']   : $default_amount_left;
			$current_amount_right  = isset( $value['margin_right']['amount'] )  ? $value['margin_right']['amount']  : $default_amount_right;

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/positioning/margin.php' );
		}

		/**
		 * Get Padding Controls
		 *
		 * Gets the controls for padding top, bottom,
		 * left and right.
		 *
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_padding_controls() {

			$value     = $this->value();
			$min_range = $this->font_properties['padding_min_range'];
			$max_range = $this->font_properties['padding_max_range'];
			$step      = $this->font_properties['padding_step'];

			// Get default amounts for each padding control
			$default_amount_top    = $this->font_defaults['padding_top']['amount'];
			$default_amount_bottom = $this->font_defaults['padding_bottom']['amount'];
			$default_amount_left   = $this->font_defaults['padding_left']['amount'];
			$default_amount_right  = $this->font_defaults['padding_right']['amount'];

			$default_unit_top    = $this->font_defaults['padding_top']['unit'];
			$default_unit_bottom = $this->font_defaults['padding_bottom']['unit'];
			$default_unit_left   = $this->font_defaults['padding_left']['unit'];
			$default_unit_right  = $this->font_defaults['padding_right']['unit'];

			// Get current values for each padding control
			$current_amount_top    = isset( $value['padding_top']['amount'] )    ? $value['padding_top']['amount']    : $default_amount_top;
			$current_amount_bottom = isset( $value['padding_bottom']['amount'] ) ? $value['padding_bottom']['amount'] : $default_amount_bottom;
			$current_amount_left   = isset( $value['padding_left']['amount'] )   ? $value['padding_left']['amount']   : $default_amount_left;
			$current_amount_right  = isset( $value['padding_right']['amount'] )  ? $value['padding_right']['amount']  : $default_amount_right;

			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/positioning/padding.php' );
		}

		/**
		 * Get Border Controls
		 *
		 * Gets the controls for border top, bottom,
		 * left and right.
		 * 
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_border_controls() {
			// Get control view
			include( plugin_dir_path( dirname(__FILE__) ) . '../views/customizer/controls/positioning/border.php' );
		}

		/**
		 * Get Hidden Control Input
		 *
		 * This hidden input is used to store all of the
		 * settings that belong to this current font
		 * control.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/wp_parse_args 	wp_parse_args()
		 * 
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function get_hidden_control_input() {
			$value = wp_parse_args( $this->value(), $this->font_defaults );
			?>
			<input type="hidden" id="<?php echo $this->id; ?>-settings" name="<?php echo $this->id; ?>" value="<?php $this->value(); ?>" data-customize-setting-link="<?php echo $this->option_name; ?>"/>
			<?php
		}

		/**
		 * Render Control Content
		 *
		 * Renders the control in the WordPress Customizer.
		 * Each section of the control has been split up
		 * in functions in order to make them easier to
		 * manage and update.
		 * 
		 * @since 1.2
		 * @version 1.3.1
		 * 
		 */
		public function render_content() {
			$this->get_control_title();
			$this->get_control_start();
			$this->get_control_toggle();
			$this->get_control_properties_start();
			$this->get_control_tabs();
			$this->get_control_tab_panes();
			$this->get_hidden_control_input();
			$this->get_control_properties_end();
			$this->get_control_end();
		}
	}
endif;