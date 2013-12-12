<?php 
/**
 * CLASS: TT_Font_Control
 *
 * A Custom WordPress Customizer control that enables
 * a text area control.
 *
 * Note: This file requires 
 * 
 * @package     WordPress
 * @subpackage  WordPress_Google_Fonts
 * @author      Sunny Johal - Titanium Themes
 * @copyright   Copyright (c) 2013, Titanium Themes
 * @version     1.1.1
 *
 * 
 */
if ( ! class_exists( 'TT_Font_Control' ) && class_exists( 'WP_Customize_Control' ) ) :	
	class TT_Font_Control extends WP_Customize_Control {
		
		/**
		 * @access public
		 * @var string
		 */
		public $type = 'font';
		public $default_values;
		public $selector;
		public $font_size_min_range;
		public $font_size_max_range;	
		public $font_size_step;	
		public $line_height_min_range;
		public $line_height_max_range;	
		public $line_height_step;
		public $letter_spacing_min_range;
		public $letter_spacing_max_range;	
		public $letter_spacing_step;	
		public $margin_min_range;
		public $margin_max_range;	
		public $margin_step;
		public $padding_min_range;
		public $padding_max_range;	
		public $padding_step;	

		/**
		 * @access public
		 * @var array
		 */
		public $default_fonts;
		public $google_fonts;
		
		/**
		 * @access public
		 * @var boolean
		 */
		public $force_styles;

		/**
		 * Constructor.
		 *
		 * If $args['settings'] is not defined, use the $id as the setting ID.
		 *
		 * @since 3.4.0
		 *
		 * @param WP_Customize_Manager $manager
		 * @param string $id
		 * @param array $args
		 */
		function __constructor( $manager, $id, $args = array() ) {

			$properties = $args['properties'];

			$this->default_values  = $properties['default_values'];

			$this->selector                 = isset( $properties['selector'] )					? $properties['selector']       			: '';
			$this->force_styles             = isset( $properties['force_styles'] )				? $properties['force_styles']       		: false;
			$this->font_size_min_range      = isset( $properties['font_size_min_range'] )		? $properties['font_size_min_range'] 		: 10;
			$this->font_size_max_range      = isset( $properties['font_size_max_range'] )		? $properties['font_size_max_range'] 		: 100;
			$this->font_size_step           = isset( $properties['font_size_step'] )			? $properties['font_size_step'] 			: 1;
			$this->line_height_min_range    = isset( $properties['line_height_min_range'] )		? $properties['line_height_min_range'] 		: 0.8;
			$this->line_height_max_range    = isset( $properties['line_height_max_range'] )		? $properties['line_height_max_range'] 		: 4;
			$this->line_height_step         = isset( $properties['line_height_step'] )			? $properties['line_height_step'] 			: 0.1;
			$this->letter_spacing_min_range = isset( $properties['letter_spacing_min_range'] )	? $properties['letter_spacing_min_range'] 	: 0.8;
			$this->letter_spacing_max_range = isset( $properties['letter_spacing_max_range'] )	? $properties['letter_spacing_max_range'] 	: 4;
			$this->letter_spacing_step      = isset( $properties['letter_spacing_step'] )		? $properties['letter_spacing_step'] 		: 0.1;

			$this->margin_min_range = isset( $properties['margin_min_range'] )	? $properties['margin_min_range'] 	: 0;
			$this->margin_max_range = isset( $properties['margin_max_range'] )	? $properties['margin_max_range'] 	: 400;
			$this->margin_step      = isset( $properties['margin_step'] )		? $properties['margin_step'] 		: 1;

			$this->padding_min_range = isset( $properties['padding_min_range'] )	? $properties['padding_min_range'] 	: 0;
			$this->padding_max_range = isset( $properties['padding_max_range'] )	? $properties['padding_max_range'] 	: 400;
			$this->padding_step      = isset( $properties['padding_step'] )			? $properties['padding_step'] 		: 1;			


			$this->google_fonts  = $properties['google_fonts'];
			$this->default_fonts = $properties['default_fonts'];	

			// Call parent constructor
			parent::__construct( $manager, $id, $args );			
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @since 3.4.0
		 */
		public function enqueue() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core', array( 'jquery' ) );
			wp_enqueue_script( 'jquery-ui-slider', array( 'jquery' ) );
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
		 * Render the control's content.
		 *
		 * @since 3.4.0
		 */
		public function render_content() {
			
			// Define control variables
			$this_value              = $this->value();
			$default_values          = $this->default_values;
			$default_fonts           = $this->default_fonts;
			$google_fonts            = $this->google_fonts;
			$font_id                 = $this_value['font_id'];
			$font_weight_style       = isset( $this_value['font_weight_style'] ) ? $this_value['font_weight_style'] : '';
			$font                    = tt_font_get_font( $font_id );
			
			$text_decoration_options = array(
				'none'			 => __( 'None' , 'theme-translate' ),
				'underline'		 => __( 'Underline' , 'theme-translate' ),
				'line-through' 	 => __( 'Line-through', 'theme-translate' ),
				'overline'		 => __( 'Overline', 'theme-translate' ),				
			);

			$text_transform_options = array(
				'none'			 => __( 'None' , 'theme-translate' ),
				'uppercase'		 => __( 'Uppercase' , 'theme-translate' ),
				'lowercase' 	 => __( 'Lowercase', 'theme-translate' ),
				'capitalize'	 => __( 'Capitalize', 'theme-translate' ),			
			);
			
			// print_r( $default_values );
			// print_r( $this_value );
			?>

			<!-- Font Control Title and Reset Button -->
			<div class="tt-font-control-title">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<a class="tt-reset-font" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
				<div class="clearfix"></div>
			</div><!-- END .tt-font-control-title -->
			
			<!-- Font Control -->
			<div class="tt-font-control" data-font-control-id="<?php echo $this->id; ?>">

				<!-- Font Control Toggle -->						
				<div tabindex="0" class="dropdown preview-thumbnail">
					<div class="dropdown-content">
						<div class="dropdown-status" style="display: block;">
							<?php _e( 'Edit Font Properties', 'theme-translate' ); ?>
						</div>
					</div>
					<div class="dropdown-arrow"></div>
				</div>
				<div class="clearfix"></div>
				
				<!-- Font Control Properties -->	
				<div class="tt-font-properties">
					<div class="tt-customizer-tabs">
						<ul>
							<li data-customize-tab="font-styles" class="selected"><?php _e( 'Styles', 'theme-translate' ); ?></li>
							<li data-customize-tab="font-appearence" class=""><?php _e( 'Appearence', 'theme-translate' ); ?></li>
						<!-- 	<li data-customize-tab="font-positioning" class=""><?php _e( 'Positioning', 'theme-translate' ); ?></li> -->
						</ul>
						<div class="clearfix"></div>
					</div>

					<?php 
						/**
						 * Font Styles Tab
						 *
						 * This tab contains the following controls:
						 * - Font family control
						 * - Font weight and style control
						 * - Text decoration control
						 * - Text transform control
						 *
						 * @version 1.1.1
						 * 
						 */
					?>
					<div class="tt-font-content selected" data-customize-tab="font-styles">

						<?php 
							/**
							 * Font Family Select Control
							 * 
							 * Get the saved font size value and output
							 * a select dropdown control.
							 *
							 */
						?>
						<span class="customize-control-title"><?php _e( 'Font Family', 'theme-translate' ); ?></span>
						<select class="tt-font-family" autocomplete="off" <?php $this->option_link( 'default', 'font_id' ); ?>>
							<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'theme-translate' ); ?></option>
							
							<!-- Default Fonts -->
							<optgroup label="Standard Web Fonts" class="css_label">
								<?php foreach ( $default_fonts as $id => $properties ) : ?>
									<option value="<?php echo $id; ?>" data-font-type="default"><?php echo $properties['name']; ?></option>
								<?php endforeach; ?>
							</optgroup>	

							<!-- Google Fonts -->
							<optgroup label="Google Fonts" class="google_label">
								<?php foreach ( $google_fonts as $id => $properties ) : ?>
								<option value="<?php echo $id; ?>" data-font-type="google"><?php echo $properties['name']; ?></option>
								<?php endforeach; ?>
							</optgroup>
						</select>

						<?php 
							/**
							 * Font Weight/Style Select Control
							 * 
							 * Get the saved font size value and output
							 * a select dropdown control.
							 *
							 */
						?>
						<span class="customize-control-title"><?php _e( 'Font Weight/Style', 'theme-translate' ); ?></span>
						<select class="tt-font-weight" autocomplete="off" <?php $this->option_link( 'default', 'font_weight_style' ); ?>>
							<?php if ( $font ) : ?>
								<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'theme-translate' ); ?></option>
								<?php foreach ( $font['font_weights'] as $key => $value ) : ?>
									<?php 
										$default_font_weight = '';

										// Set font style and weight
										$style_data = 'normal';
										$weight     = 400;
										
										if ( strpos( $value, 'italic' ) !== false ) {
											$style_data = 'italic';
										}

										if ( $value !== 'regular' && $value !== 'italic' ) {
											$weight = (int) substr( $value, 0, 3 );
										}	
									?>
									<option value="<?php echo $value ?>" data-stylesheet-url="<?php echo $font['urls'][ $value ] ?>" data-font-weight="<?php echo $weight; ?>" data-font-style="<?php echo $style_data; ?>">
										<?php echo $value; ?>
									</option>
								<?php endforeach; ?>
							<?php else : ?>
								<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'theme-translate' ); ?></option>
							<?php endif; ?>
						</select>
						
						<!-- Text Decoration -->
						<span class="customize-control-title"><?php _e( 'Text Decoration', 'theme-translate' ); ?></span>
						<select class="tt-text-decoration" data-default-value="<?php echo $default_values['text_decoration']; ?>" autocomplete="off" <?php $this->option_link( 'default', 'text_decoration' ); ?>>
								<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'theme-translate' ); ?></option>
								<?php foreach ( $text_decoration_options as $value => $name ) : ?>
									<option value="<?php echo $value; ?>"><?php echo $name; ?></option>
								<?php endforeach; ?>
						</select>

						<!-- Text Transform -->
						<span class="customize-control-title"><?php _e( 'Text Transform', 'theme-translate' ); ?></span>
						<select class="tt-text-transform" data-default-value="<?php echo $default_values['text_transform']; ?>" autocomplete="off" <?php $this->option_link( 'default', 'text_transform' ); ?>>
								<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'theme-translate' ); ?></option>
								<?php foreach ( $text_transform_options as $value => $name ) : ?>
									<option value="<?php echo $value; ?>"><?php echo $name; ?></option>
								<?php endforeach; ?>
						</select>

						<!-- Hidden Inputs -->
						<input autocomplete="off" class="tt-font-stylesheet-url" type="hidden" value="<?php echo $default_values['stylesheet_url']; ?>" <?php $this->option_link( 'default', 'stylesheet_url' ); ?>>
						<input autocomplete="off" class="tt-font-weight-val" type="hidden" value="<?php echo $default_values['font_weight']; ?>" <?php $this->option_link( 'default', 'font_weight' ); ?>>
						<input autocomplete="off" class="tt-font-style-val" type="hidden" value="<?php echo $default_values['font_style']; ?>" <?php $this->option_link( 'default', 'font_style' ); ?>>
						<input autocomplete="off" class="tt-font-name-val" type="hidden" value="<?php echo $default_values['font_name']; ?>" <?php $this->option_link( 'default', 'font_name' ); ?>>
					</div>

					<?php 
						/**
						 * Font Appearence Tab
						 *
						 * This tab contains the following controls:
						 * - Font family control
						 * - Font weight and style control
						 * - Text decoration control
						 * - Text transform control
						 *
						 * @version 1.1.1
						 * 
						 */
					?>
					<div class="tt-font-content" data-customize-tab="font-appearence">

						<?php 
							/**
							 * Font Color Control
							 * 
							 * Get the saved color value and the default
							 * color value and output the color picker 
							 * markup for Iris.
							 *
							 */
							$font_color = isset( $this_value['font_color'] ) ? $this_value['font_color'] : '';

						?>
						<span class="customize-control-title"><?php _e( 'Font Color', 'theme-translate' ); ?></span>
						<div class="customize-control-content tt-font-color-container">
							<input autocomplete="off" class="tt-color-picker-hex" data-default-color="<?php echo $default_values['font_color']; ?>" value="<?php echo $font_color; ?>" type="text" maxlength="7" placeholder="<?php esc_attr_e( 'Hex Value', 'theme-translate' ); ?>" <?php $this->option_link( 'default', 'font_color' ); ?>/>
						</div>
						<!-- Hidden Font Input Color -->
						<input class="tt-font-color" type="hidden" <?php $this->option_link( 'default', 'font_color' ); ?> />
						<div class="clearfix"></div>

						<?php 
							/**
							 * Background Color Control
							 * 
							 * Get the saved color value and the default
							 * color value and output the color picker 
							 * markup for Iris.
							 *
							 */
							$background_color = isset( $this_value['background_color'] ) ? $this_value['background_color'] : '';
						?>
<!-- 						<span class="customize-control-title"><?php _e( 'Background Color', 'theme-translate' ); ?></span>
						<div class="customize-control-content tt-font-background-color-container">
							<input autocomplete="off" class="tt-background-color-picker-hex" data-default-color="<?php echo $default_values['background_color']; ?>" value="<?php echo $background_color; ?>" type="text" maxlength="7" placeholder="<?php esc_attr_e( 'Hex Value', 'theme-translate' ); ?>" <?php $this->option_link( 'default', 'background_color' ); ?>/>
						</div> -->
						<!-- Hidden Font Input Color -->
<!-- 						<input class="tt-font-background-color" type="hidden" <?php $this->option_link( 'default', 'background_color' ); ?> />
						<div class="clearfix"></div> -->

						<?php 
							/**
							 * Font Size Slider Control
							 * 
							 * Get the saved font size value and output
							 * the slider markup.
							 *
							 */
						?>
						<div class="tt-font-slider-control font-size-slider">
							<span class="customize-control-title">
								<span class="tt-slider-title">
									<?php _e( 'Font Size', 'theme-translate' ); ?>
								</span><!-- END .tt-slider-title -->
								<div class="tt-font-slider-display">
									<span>
										
									</span> | 
									<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
									<div class="clearfix"></div>
								</div><!-- END .tt-slider-display -->
							</span><!-- END .customize-control-title -->
							
							<div class="tt-slider" 
								data-default-value="<?php echo $default_values['font_size']['amount']; ?>" 
								data-step="<?php echo $this->font_size_step; ?>" 
								data-default-unit="<?php echo $default_values['font_size']['unit']; ?>" 
								data-min-range="<?php echo $this->font_size_min_range; ?>" 
								data-max-range="<?php echo $this->font_size_max_range; ?>">
							</div>
							
							<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['font_size']['amount']; ?>" value="<?php echo $this_value['font_size']['amount']; ?>" <?php $this->option_link( 'default', 'font_size', 'amount' ); ?>/>
							<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['font_size']['unit']; ?>" value="<?php echo $this_value['font_size']['unit']; ?>" <?php $this->option_link( 'default', 'font_size', 'unit' ); ?>/>
							
							<div class="clearfix"></div>

						</div><!-- END .tt-font-slider-control -->

						<?php 
							/**
							 * Line Height Slider Control
							 * 
							 * Get the saved line height value and output
							 * the slider markup.
							 *
							 */
						?>
						<div class="tt-font-slider-control line-height-slider">
							<span class="customize-control-title">
								<span class="tt-slider-title">
									<?php _e( 'Line Height', 'theme-translate' ); ?>
								</span><!-- END .tt-slider-title -->
								<div class="tt-font-slider-display">
									<span>
										
									</span> | 
									<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
									<div class="clearfix"></div>
								</div><!-- END .tt-slider-display -->
							</span><!-- END .customize-control-title -->
							
							<div class="tt-slider"
								data-default-value="<?php echo $default_values['line_height'] ?>" 
								data-step="<?php echo $this->line_height_step; ?>" 
								data-default-unit="px" 
								data-min-range="<?php echo $this->line_height_min_range; ?>" 
								data-max-range="<?php echo $this->line_height_max_range; ?>">
							</div>

							<input name="" class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['line_height']; ?>" value="<?php echo $this_value['line_height'] ?>" <?php $this->option_link( 'default', 'line_height' ); ?>/>
							<div class="clearfix"></div>

						</div><!-- END .tt-font-slider-control -->

						<?php 
							/**
							 * Letter Spacing Slider Control
							 * 
							 * Get the saved letter spacing value and output
							 * the slider markup.
							 *
							 */
						?>
						<div class="tt-font-slider-control letter-spacing-slider">
							<span class="customize-control-title">
								<span class="tt-slider-title">
									<?php _e( 'Letter Spacing', 'theme-translate' ); ?>
								</span><!-- END .tt-slider-title -->
								<div class="tt-font-slider-display">
									<span>
										
									</span> | 
									<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
									<div class="clearfix"></div>
								</div><!-- END .tt-slider-display -->
							</span><!-- END .customize-control-title -->
							
							<div class="tt-slider"
								data-step="<?php echo $this->letter_spacing_step; ?>" 
								data-default-value="<?php echo $default_values['letter_spacing']['amount']; ?>" 
								data-default-unit="px" 
								data-min-range="<?php echo $this->letter_spacing_min_range; ?>" 
								data-max-range="<?php echo $this->letter_spacing_max_range; ?>">
							</div>
							<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['letter_spacing']['amount']; ?>" value="<?php echo $this_value['letter_spacing']['amount']; ?>" <?php $this->option_link( 'default', 'letter_spacing', 'amount' ); ?>/>
							<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['letter_spacing']['unit']; ?>" value="<?php echo $this_value['letter_spacing']['unit']; ?>" <?php $this->option_link( 'default', 'letter_spacing', 'unit' ); ?>/>
							<div class="clearfix"></div>

						</div><!-- END .tt-font-slider-control -->
					</div>

					<?php 
						/**
						 * Font Positioning Tab
						 *
						 * This tab contains the following controls:
						 * - Font Padding
						 * - Font Margin
						 *
						 * @version 1.1.1
						 * 
						 */
					?>
					<div class="tt-font-content tt-font-content-fullwidth" data-customize-tab="font-positioning">
						<div class="tt-font-toggle">

							<div class="toggle-section-title"><span class="customize-control-title inner-control-title"><?php _e( 'Padding', 'theme-translate' ); ?></span></div>
							<div class="toggle-section-content">
								<?php 
									/**
									 * Padding Top
									 */
								?>
								<div class="tt-font-slider-control padding-slider padding-top-slider">
									<span class="customize-control-title">
										<?php _e( 'Padding Top', 'theme-translate' ); ?>
										<div class="tt-font-slider-display">
											<span>
												
											</span> | 
											<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
											<div class="clearfix"></div>
										</div><!-- END .tt-slider-display -->
									</span><!-- END .customize-control-title -->
									
									<div class="tt-slider"
										data-step="<?php echo $this->padding_step; ?>" 
										data-default-value="<?php echo $default_values['padding_top']['amount']; ?>" 
										data-default-unit="<?php echo $default_values['padding_top']['unit']; ?>" 
										data-min-range="<?php echo $this->padding_min_range; ?>" 
										data-max-range="<?php echo $this->padding_max_range; ?>">
									</div>
									<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['padding_top']['amount']; ?>" value="<?php echo $this_value['padding_top']['amount']; ?>" <?php $this->option_link( 'default', 'padding_top', 'amount' ); ?>/>
									<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['padding_top']['unit']; ?>" value="<?php echo $this_value['padding_top']['unit']; ?>" <?php $this->option_link( 'default', 'padding_top', 'unit' ); ?>/>
									<div class="clearfix"></div>

								</div><!-- END .tt-font-slider-control -->

								<?php 
									/**
									 * Padding Bottom
									 */
								?>
								<div class="tt-font-slider-control padding-slider padding-bottom-slider">
									<span class="customize-control-title">
										<?php _e( 'Padding Bottom', 'theme-translate' ); ?>
										<div class="tt-font-slider-display">
											<span>
												
											</span> | 
											<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
											<div class="clearfix"></div>
										</div><!-- END .tt-slider-display -->
									</span><!-- END .customize-control-title -->
									
									<div class="tt-slider"
										data-step="<?php echo $this->padding_step; ?>" 
										data-default-value="<?php echo $default_values['padding_bottom']['amount']; ?>" 
										data-default-unit="<?php echo $default_values['padding_bottom']['unit']; ?>" 
										data-min-range="<?php echo $this->padding_min_range; ?>" 
										data-max-range="<?php echo $this->padding_max_range; ?>">
									</div>
									<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['padding_bottom']['amount']; ?>" value="<?php echo $this_value['padding_bottom']['amount']; ?>" <?php $this->option_link( 'default', 'padding_bottom', 'amount' ); ?>/>
									<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['padding_bottom']['unit']; ?>" value="<?php echo $this_value['padding_bottom']['unit']; ?>" <?php $this->option_link( 'default', 'padding_bottom', 'unit' ); ?>/>
									<div class="clearfix"></div>
									
								</div><!-- END .tt-font-slider-control -->

								<?php 
									/**
									 * Padding Left
									 */
								?>
								<div class="tt-font-slider-control padding-slider padding-left-slider">
									<span class="customize-control-title">
										<?php _e( 'Padding Left', 'theme-translate' ); ?>
										<div class="tt-font-slider-display">
											<span>
												
											</span> | 
											<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
											<div class="clearfix"></div>
										</div><!-- END .tt-slider-display -->
									</span><!-- END .customize-control-title -->
									
									<div class="tt-slider"
										data-step="<?php echo $this->padding_step; ?>" 
										data-default-value="<?php echo $default_values['padding_left']['amount']; ?>" 
										data-default-unit="<?php echo $default_values['padding_left']['unit']; ?>" 
										data-min-range="<?php echo $this->padding_min_range; ?>" 
										data-max-range="<?php echo $this->padding_max_range; ?>">
									</div>
									<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['padding_left']['amount']; ?>" value="<?php echo $this_value['padding_left']['amount']; ?>" <?php $this->option_link( 'default', 'padding_left', 'amount' ); ?>/>
									<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['padding_left']['unit']; ?>" value="<?php echo $this_value['padding_left']['unit']; ?>" <?php $this->option_link( 'default', 'padding_left', 'unit' ); ?>/>
									<div class="clearfix"></div>
									
								</div><!-- END .tt-font-slider-control -->

								<?php 
									/**
									 * Padding Right
									 */
								?>
								<div class="tt-font-slider-control padding-slider padding-right-slider">
									<span class="customize-control-title">
										<?php _e( 'Padding Right', 'theme-translate' ); ?>
										<div class="tt-font-slider-display">
											<span>
												
											</span> | 
											<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
											<div class="clearfix"></div>
										</div><!-- END .tt-slider-display -->
									</span><!-- END .customize-control-title -->
									
									<div class="tt-slider"
										data-step="<?php echo $this->padding_step; ?>" 
										data-default-value="<?php echo $default_values['padding_right']['amount']; ?>" 
										data-default-unit="<?php echo $default_values['padding_right']['unit']; ?>" 
										data-min-range="<?php echo $this->padding_min_range; ?>" 
										data-max-range="<?php echo $this->padding_max_range; ?>">
									</div>
									<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['padding_right']['amount']; ?>" value="<?php echo $this_value['padding_right']['amount']; ?>" <?php $this->option_link( 'default', 'padding_right', 'amount' ); ?>/>
									<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['padding_right']['unit']; ?>" value="<?php echo $this_value['padding_right']['unit']; ?>" <?php $this->option_link( 'default', 'padding_right', 'unit' ); ?>/>
									<div class="clearfix"></div>
									
								</div><!-- END .tt-font-slider-control -->

							</div>
						</div>
						<div class="tt-font-toggle">
							<div class="toggle-section-title"><span class="customize-control-title inner-control-title"><?php _e( 'Margin', 'theme-translate' ); ?></span></div>
							<div class="toggle-section-content">
								<?php 
									/**
									 * Margin Top
									 */
								?>
								<div class="tt-font-slider-control margin-slider margin-top-slider">
									<span class="customize-control-title">
										<?php _e( 'Margin Top', 'theme-translate' ); ?>
										<div class="tt-font-slider-display">
											<span>
												
											</span> | 
											<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
											<div class="clearfix"></div>
										</div><!-- END .tt-slider-display -->
									</span><!-- END .customize-control-title -->
									
									<div class="tt-slider"
										data-step="<?php echo $this->margin_step; ?>" 
										data-default-value="<?php echo $default_values['margin_top']['amount']; ?>" 
										data-default-unit="<?php echo $default_values['margin_top']['unit']; ?>" 
										data-min-range="<?php echo $this->margin_min_range; ?>" 
										data-max-range="<?php echo $this->margin_max_range; ?>">
									</div>
									<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['margin_top']['amount']; ?>" value="<?php echo $this_value['margin_top']['amount']; ?>" <?php $this->option_link( 'default', 'margin_top', 'amount' ); ?>/>
									<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['margin_top']['unit']; ?>" value="<?php echo $this_value['margin_top']['unit']; ?>" <?php $this->option_link( 'default', 'margin_top', 'unit' ); ?>/>
									<div class="clearfix"></div>

								</div><!-- END .tt-font-slider-control -->
								
								<?php 
									/**
									 * Margin Bottom
									 */
								?>
								<div class="tt-font-slider-control margin-slider margin-bottom-slider">
									<span class="customize-control-title">
										<?php _e( 'Margin Bottom', 'theme-translate' ); ?>
										<div class="tt-font-slider-display">
											<span>
												
											</span> | 
											<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
											<div class="clearfix"></div>
										</div><!-- END .tt-slider-display -->
									</span><!-- END .customize-control-title -->
									
									<div class="tt-slider"
										data-step="<?php echo $this->margin_step; ?>" 
										data-default-value="<?php echo $default_values['margin_bottom']['amount']; ?>" 
										data-default-unit="<?php echo $default_values['margin_bottom']['unit']; ?>" 
										data-min-range="<?php echo $this->margin_min_range; ?>" 
										data-max-range="<?php echo $this->margin_max_range; ?>">
									</div>
									<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['margin_bottom']['amount']; ?>" value="<?php echo $this_value['margin_bottom']['amount']; ?>" <?php $this->option_link( 'default', 'margin_bottom', 'amount' ); ?>/>
									<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['margin_bottom']['unit']; ?>" value="<?php echo $this_value['margin_bottom']['unit']; ?>" <?php $this->option_link( 'default', 'margin_bottom', 'unit' ); ?>/>
									<div class="clearfix"></div>

								</div><!-- END .tt-font-slider-control -->
								
								<?php 
									/**
									 * Margin Left
									 */
								?>
								<div class="tt-font-slider-control margin-slider margin-left-slider">
									<span class="customize-control-title">
										<?php _e( 'Margin Left', 'theme-translate' ); ?>
										<div class="tt-font-slider-display">
											<span>
												
											</span> | 
											<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
											<div class="clearfix"></div>
										</div><!-- END .tt-slider-display -->
									</span><!-- END .customize-control-title -->
									
									<div class="tt-slider"
										data-step="<?php echo $this->margin_step; ?>" 
										data-default-value="<?php echo $default_values['margin_left']['amount']; ?>" 
										data-default-unit="<?php echo $default_values['margin_left']['unit']; ?>" 
										data-min-range="<?php echo $this->margin_min_range; ?>" 
										data-max-range="<?php echo $this->margin_max_range; ?>">
									</div>
									<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['margin_left']['amount']; ?>" value="<?php echo $this_value['margin_left']['amount']; ?>" <?php $this->option_link( 'default', 'margin_left', 'amount' ); ?>/>
									<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['margin_left']['unit']; ?>" value="<?php echo $this_value['margin_left']['unit']; ?>" <?php $this->option_link( 'default', 'margin_left', 'unit' ); ?>/>
									<div class="clearfix"></div>

								</div><!-- END .tt-font-slider-control -->

								<?php 
									/**
									 * Margin Right
									 */
								?>
								<div class="tt-font-slider-control margin-slider margin-right-slider">
									<span class="customize-control-title">
										<?php _e( 'Margin Right', 'theme-translate' ); ?>
										<div class="tt-font-slider-display">
											<span>
												
											</span> | 
											<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'theme-translate' ); ?></a>
											<div class="clearfix"></div>
										</div><!-- END .tt-slider-display -->
									</span><!-- END .customize-control-title -->
									
									<div class="tt-slider"
										data-step="<?php echo $this->margin_step; ?>" 
										data-default-value="<?php echo $default_values['margin_right']['amount']; ?>" 
										data-default-unit="<?php echo $default_values['margin_right']['unit']; ?>" 
										data-min-range="<?php echo $this->margin_min_range; ?>" 
										data-max-range="<?php echo $this->margin_max_range; ?>">
									</div>
									<input class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_values['margin_right']['amount']; ?>" value="<?php echo $this_value['margin_right']['amount']; ?>" <?php $this->option_link( 'default', 'margin_right', 'amount' ); ?>/>
									<input class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_values['margin_right']['unit']; ?>" value="<?php echo $this_value['margin_right']['unit']; ?>" <?php $this->option_link( 'default', 'margin_right', 'unit' ); ?>/>
									<div class="clearfix"></div>

								</div><!-- END .tt-font-slider-control -->

								
							</div>
						</div>

					</div>

				</div><!-- END .tt-font-properties -->
			</div><!-- END .tt-font-control -->

			<?php
		}	
	}
endif;
