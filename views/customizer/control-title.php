<?php 
/**
 * Font Control Title and Reset Button
 *
 * Title markup for the font controller in the
 * live preview
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
<div class="tt-font-control-title">
	<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	<a class="tt-reset-font" href="#"><?php _e( 'Reset', self::$slug ); ?></a>
	<div class="clearfix"></div>
</div>