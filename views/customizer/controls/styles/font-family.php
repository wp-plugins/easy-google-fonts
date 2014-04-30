<?php 
/**
 * Font Family Select Control
 *
 * Outputs a select control containing all of the available
 * fonts. Added support for different subsets of fonts in 
 * this version.
 * 
 * @package   Easy_Google_Fonts
 * @author    Sunny Johal - Titanium Themes <support@titaniumthemes.com>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/easy-google-fonts/
 * @copyright Copyright (c) 2014, Titanium Themes
 * @version   1.3.1
 * 
 */
?>
<span class="customize-control-title"><?php _e( 'Font Family', 'easy-google-fonts' ); ?></span>
<select class="tt-font-family" data-default-value="<?php echo $default_value ?>" autocomplete="off">
	<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'easy-google-fonts' ); ?></option>
	
	<!-- Default Fonts -->
	<optgroup label="<?php _e( 'Standard Web Fonts', 'easy-google-fonts' ); ?>" class="css_label">
		<?php foreach ( $default_fonts as $id => $properties ) : ?>
			<option value="<?php echo $id; ?>" data-font-type="default" <?php selected( $current_value, $id ); ?>><?php echo $properties['name']; ?></option>
		<?php endforeach; ?>
	</optgroup>
	
	<!-- Google Fonts -->
	<optgroup label="<?php _e( 'Google Fonts', 'easy-google-fonts' ) ?>" class="google_label">
		<?php foreach ( $google_fonts as $id => $properties ) : ?>
			<?php if ( in_array( $selected_subset, $properties['subsets'] ) || ( 'all' == $selected_subset ) ) : ?>
				<option value="<?php echo $id; ?>" data-font-type="google" <?php selected( $current_value, $id ); ?>><?php echo $properties['name']; ?></option>
			<?php endif; ?>
		<?php endforeach; ?>
	</optgroup>
</select>