<?php 
/**
 * Border Controls
 *
 * Outputs four custom controls to control the top, bottom,
 * left, right border of an element.
 * 
 * @package   Easy_Google_Fonts
 * @author    Sunny Johal - Titanium Themes <support@titaniumthemes.com>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/easy-google-fonts/
 * @copyright Copyright (c) 2014, Titanium Themes
 * @version   1.3.1
 *
 * TODO: Complete Border Controls by the 1.4 release
 * 
 */
?>
<div class="tt-font-toggle">
	<div class="toggle-section-title">
		<span class="customize-control-title inner-control-title"><?php _e( 'Border', 'easy-google-fonts' ); ?></span>
	</div>
	<div class="toggle-section-content">
		<?php 
			/**
			 * Border Top Control
			 * 
			 */
		?>
		<div class="tt-font-border-control border-top-slider">
			<span class="customize-control-title">
				<?php _e( 'Top', 'easy-google-fonts' ); ?>
				<div class="tt-font-slider-display">
					<span>
						
					</span> | 
					<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'easy-google-fonts' ); ?></a>
					<div class="clearfix"></div>
				</div><!-- END .tt-slider-display -->
			</span><!-- END .customize-control-title -->

			<select name="" id="">
				<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'easy-google-fonts' ); ?></option>
				<option value="dashed"><?php _e( 'Dashed', 'easy-google-fonts' ); ?></option>
				<option value="dotted"><?php _e( 'Dotted', 'easy-google-fonts' ); ?></option>
				<option value="double"><?php _e( 'Double', 'easy-google-fonts' ); ?></option>
				<option value="groove"><?php _e( 'Groove', 'easy-google-fonts' ); ?></option>
				<option value="inset"><?php _e( 'Inset', 'easy-google-fonts' ); ?></option>
				<option value="outset"><?php _e( 'Outset', 'easy-google-fonts' ); ?></option>
				<option value="ridge"><?php _e( 'Ridge', 'easy-google-fonts' ); ?></option>
				<option value="solid"><?php _e( 'Solid', 'easy-google-fonts' ); ?></option>
			</select>

		</div><!-- END .tt-font-border-control -->
		<?php 
			/**
			 * Border Bottom Control
			 * 
			 */
		?>
		<div class="tt-font-border-control border-bottom-slider">
			<span class="customize-control-title">
				<?php _e( 'Bottom', 'easy-google-fonts' ); ?>
				<div class="tt-font-slider-display">
					<span>
						
					</span> | 
					<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'easy-google-fonts' ); ?></a>
					<div class="clearfix"></div>
				</div><!-- END .tt-slider-display -->
			</span><!-- END .customize-control-title -->

		</div><!-- END .tt-font-border-control -->
		<?php 
			/**
			 * Border Left Control
			 * 
			 */
		?>
		<div class="tt-font-border-control border-left-slider">
			<span class="customize-control-title">
				<?php _e( 'Left', 'easy-google-fonts' ); ?>
				<div class="tt-font-slider-display">
					<span>
						
					</span> | 
					<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'easy-google-fonts' ); ?></a>
					<div class="clearfix"></div>
				</div><!-- END .tt-slider-display -->
			</span><!-- END .customize-control-title -->
		</div><!-- END .tt-font-border-control -->
		<?php 
			/**
			 * Border Right Control
			 * 
			 */
		?>
		<div class="tt-font-border-control border-right-slider">
			<span class="customize-control-title">
				<?php _e( 'Right', 'easy-google-fonts' ); ?>
				<div class="tt-font-slider-display">
					<span>
						
					</span> | 
					<a class="tt-font-slider-reset" href="#"><?php _e( 'Reset', 'easy-google-fonts' ); ?></a>
					<div class="clearfix"></div>
				</div><!-- END .tt-slider-display -->
			</span><!-- END .customize-control-title -->
		</div><!-- END .tt-font-border-control -->
	</div>
</div>