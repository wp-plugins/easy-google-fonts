<?php 
/**
 * Text Transform Select Control
 *
 * Outputs a select control containing all of the available
 * text transform options.
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
<span class="customize-control-title"><?php _e( 'Text Transform', 'easy-google-fonts' ); ?></span>
<select class="tt-text-transform" data-default-value="<?php echo $default_value; ?>" autocomplete="off">
	<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'easy-google-fonts' ); ?></option>
	<?php foreach ( $text_transform_options as $value => $name ) : ?>
		<option value="<?php echo $value; ?>" <?php selected( $current_value, $value ); ?>><?php echo $name; ?></option>
	<?php endforeach; ?>
</select>