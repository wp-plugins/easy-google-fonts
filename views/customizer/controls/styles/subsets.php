<?php 
/**
 * Font Subset Select Control
 *
 * Outputs a select control which allows the user to 
 * narrow down a list of available fonts based on the
 * subset.
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
<span class="customize-control-title"><?php _e( 'Script/Subset', 'easy-google-fonts' ); ?></span>
<select class="tt-font-subsets" data-default-value="<?php echo $default_value; ?>" autocomplete="off">
	<?php foreach ( $font_subset_options as $value => $name ) : ?>
		<?php $subset = ( $value && 'khmer' != $value && 'latin' != $value ) ? 'latin,' : ''; ?>
		<option value="<?php echo $subset . $value; ?>" data-subset="<?php echo $value ? $value : 'all'; ?>" <?php selected( $current_value, $subset . $value ); ?>><?php echo $name; ?></option>
	<?php endforeach; ?>
</select>