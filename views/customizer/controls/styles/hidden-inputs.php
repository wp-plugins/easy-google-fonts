<?php 
/**
 * Hidden Font Styles Inputs
 *
 * Outputs a set of hidden text inputs used to control
 * and store the following:
 *
 *     - Stylesheet URL
 *     - Font Weight
 *     - Font Style
 *     - Font Name
 * 
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
<input autocomplete="off" class="tt-font-stylesheet-url" type="hidden" data-default-value="<?php echo $default_stylesheet_url; ?>" value="<?php echo $current_stylesheet_url; ?>" >
<input autocomplete="off" class="tt-font-weight-val" type="hidden" data-default-value="<?php echo $default_font_weight; ?>" value="<?php echo $current_font_weight; ?>" >
<input autocomplete="off" class="tt-font-style-val" type="hidden" data-default-value="<?php echo $default_font_style; ?>" value="<?php echo $current_font_style; ?>" >
<input autocomplete="off" class="tt-font-name-val" type="hidden" data-default-value="<?php echo $default_font_name; ?>" value="<?php echo $current_font_name; ?>" >