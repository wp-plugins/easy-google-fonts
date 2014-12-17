<?php 
/**
 * Margin Slider Control
 *
 * Outputs four slider controls to control the top, bottom,
 * left, right margin of an element.
 * 
 * @package   Easy_Google_Fonts
 * @author    Sunny Johal - Titanium Themes <support@titaniumthemes.com>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/easy-google-fonts/
 * @copyright Copyright (c) 2014, Titanium Themes
 * @version   1.3.3
 * 
 */
?>
<div class="tt-font-toggle">
	<div class="toggle-section-title">
		<span class="customize-control-title inner-control-title"><?php _e( 'Margin', 'easy-google-fonts' ); ?></span>
	</div>
	<div class="toggle-section-content">
		<?php 
			/**
			 * Margin Top Control
			 * 
			 */
		?>
		<div class="tt-font-slider-control margin-slider margin-top-slider">
			<span class="customize-control-title">
				<?php _e( 'Top', 'easy-google-fonts' ); ?>
				<div class="tt-font-slider-display">
					<span>
						
					</span> | 
					<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'easy-google-fonts' ); ?></a>
					<div class="clearfix"></div>
				</div><!-- END .tt-slider-display -->
			</span><!-- END .customize-control-title -->

			<div class="tt-slider"
				data-step="<?php echo $step; ?>" 
				data-default-value="<?php echo $default_amount_top; ?>" 
				data-default-unit="<?php echo $default_unit_top; ?>" 
				data-min-range="<?php echo $min_range; ?>" 
				data-max-range="<?php echo $max_range; ?>">
			</div>
			<input autocomplete="off" class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_amount_top; ?>" value="<?php echo $current_amount_top; ?>" />
			<input autocomplete="off" class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_unit_top; ?>" value="<?php echo $default_unit_top; ?>" />
			<div class="clearfix"></div>

		</div><!-- END .tt-font-slider-control -->

		<?php 
			/**
			 * Margin Bottom Control
			 * 
			 */
		?>
		<div class="tt-font-slider-control margin-slider margin-bottom-slider">
			<span class="customize-control-title">
				<?php _e( 'Bottom', 'easy-google-fonts' ); ?>
				<div class="tt-font-slider-display">
					<span>
						
					</span> | 
					<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'easy-google-fonts' ); ?></a>
					<div class="clearfix"></div>
				</div><!-- END .tt-slider-display -->
			</span><!-- END .customize-control-title -->

			<div class="tt-slider"
				data-step="<?php echo $step; ?>" 
				data-default-value="<?php echo $default_amount_bottom; ?>" 
				data-default-unit="<?php echo $default_unit_bottom; ?>" 
				data-min-range="<?php echo $min_range; ?>" 
				data-max-range="<?php echo $max_range; ?>">
			</div>
			<input autocomplete="off" class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_amount_bottom; ?>" value="<?php echo $current_amount_bottom; ?>" />
			<input autocomplete="off" class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_unit_bottom; ?>" value="<?php echo $default_unit_bottom; ?>" />
			<div class="clearfix"></div>

		</div><!-- END .tt-font-slider-control -->

		<?php 
			/**
			 * Margin Left Control
			 * 
			 */
		?>
		<div class="tt-font-slider-control margin-slider margin-left-slider">
			<span class="customize-control-title">
				<?php _e( 'Left', 'easy-google-fonts' ); ?>
				<div class="tt-font-slider-display">
					<span>
						
					</span> | 
					<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'easy-google-fonts' ); ?></a>
					<div class="clearfix"></div>
				</div><!-- END .tt-slider-display -->
			</span><!-- END .customize-control-title -->

			<div class="tt-slider"
				data-step="<?php echo $step; ?>" 
				data-default-value="<?php echo $default_amount_left; ?>" 
				data-default-unit="<?php echo $default_unit_left; ?>" 
				data-min-range="<?php echo $min_range; ?>" 
				data-max-range="<?php echo $max_range; ?>">
			</div>
			<input autocomplete="off" class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_amount_left; ?>" value="<?php echo $current_amount_left; ?>" />
			<input autocomplete="off" class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_unit_left; ?>" value="<?php echo $default_unit_left; ?>" />
			<div class="clearfix"></div>

		</div><!-- END .tt-font-slider-control -->

		<?php 
			/**
			 * Margin Right Control
			 * 
			 */
		?>
		<div class="tt-font-slider-control margin-slider margin-right-slider">
			<span class="customize-control-title">
				<?php _e( 'Right', 'easy-google-fonts' ); ?>
				<div class="tt-font-slider-display">
					<span>
						
					</span> | 
					<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'easy-google-fonts' ); ?></a>
					<div class="clearfix"></div>
				</div><!-- END .tt-slider-display -->
			</span><!-- END .customize-control-title -->

			<div class="tt-slider"
				data-step="<?php echo $step; ?>" 
				data-default-value="<?php echo $default_amount_right; ?>" 
				data-default-unit="<?php echo $default_unit_right; ?>" 
				data-min-range="<?php echo $min_range; ?>" 
				data-max-range="<?php echo $max_range; ?>">
			</div>
			<input autocomplete="off" class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_amount_right; ?>" value="<?php echo $current_amount_right; ?>" />
			<input autocomplete="off" class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_unit_right; ?>" value="<?php echo $default_unit_right; ?>" />
			<div class="clearfix"></div>

		</div><!-- END .tt-font-slider-control -->
	</div>
</div>