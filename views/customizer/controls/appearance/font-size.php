<?php 
/**
 * Font Size Slider Control
 *
 * Outputs the new font size slider control which is
 * designed to be used with jQuery UI. This is used 
 * to control the font size of a particular font.
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
<div class="tt-font-slider-control font-size-slider">
	<span class="customize-control-title">
		<span class="tt-slider-title">
			<?php _e( 'Font Size', 'easy-google-fonts' ); ?>
		</span><!-- END .tt-slider-title -->
		<div class="tt-font-slider-display">
			<span>
				
			</span> | 
			<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'easy-google-fonts' ); ?></a>
			<div class="clearfix"></div>
		</div><!-- END .tt-slider-display -->
	</span><!-- END .customize-control-title -->

	<div class="tt-slider" 
		data-default-value="<?php echo $default_amount; ?>" 
		data-step="<?php echo $step; ?>" 
		data-default-unit="<?php echo $default_unit; ?>" 
		data-min-range="<?php echo $min_range; ?>" 
		data-max-range="<?php echo $max_range; ?>">
	</div>
	
	<input autocomplete="off" class="tt-font-slider-amount" type="hidden" data-default-value="<?php echo $default_amount; ?>" value="<?php echo $current_amount; ?>" />
	<input autocomplete="off" class="tt-font-slider-unit" type="hidden" data-default-value="<?php echo $default_unit; ?>" value="<?php echo $default_unit; ?>" />
	
	<div class="clearfix"></div>

</div><!-- END .tt-font-slider-control -->