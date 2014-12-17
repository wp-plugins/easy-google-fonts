<?php 
/**
 * Background Color Select Control
 *
 * Outputs the new font color control from Automattic.
 * This is used to control the background of a particular 
 * font.
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
<span class="customize-control-title"><?php _e( 'Background Color', 'easy-google-fonts' ); ?></span>
<div class="customize-control-content tt-background-color-container">
	<input autocomplete="off" class="tt-color-picker-hex" data-default-color="<?php echo $default_color; ?>" value="<?php echo $current_color; ?>" type="text" maxlength="7" placeholder="<?php esc_attr_e( 'Hex Value', 'easy-google-fonts' ); ?>" />
</div>
<input class="tt-font-background-color" type="hidden" <?php $this->option_link( 'default', 'background_color' ); ?> />
<div class="clearfix"></div>