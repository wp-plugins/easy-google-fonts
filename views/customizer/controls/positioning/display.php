<?php 
/**
 * Display Controls
 *
 * Outputs a select control in order to change the 
 * display properties of an element.
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
		<span class="customize-control-title inner-control-title"><?php _e( 'Display', 'easy-google-fonts' ); ?></span>
	</div>
	<div class="toggle-section-content">
		<select class="tt-display-element" data-default-value="" autocomplete="off">
			<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'easy-google-fonts' ); ?></option>
			<?php foreach ( $display_options as $value => $name ) : ?>
				<option value="<?php echo $value; ?>"><?php echo $name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>