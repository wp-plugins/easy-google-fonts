<?php 
/**
 * Font Control Toggle
 *
 * Markup for the toggle trigger in order to
 * show/hide additional font properties.
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
<div tabindex="0" class="dropdown preview-thumbnail egf-font-toggle-trigger">
	<div class="dropdown-content">
		<div class="dropdown-status" style="display: block;">
			<?php _e( 'Edit Font', self::$slug ); ?>
		</div>
	</div>
	<div class="dropdown-arrow"></div>
</div>
<div class="clearfix"></div>