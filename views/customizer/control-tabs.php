<?php 
/**
 * Font Control Tabs
 *
 * Ouputs the font control tabs. Added a new tab called
 * font positioning in this version which contains 
 * controls to control the margin/padding/border.
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
<div class="tt-customizer-tabs">
	<ul>
		<?php foreach ( $this->tabs as $id => $tab ): ?>
			<li data-customize-tab='<?php echo esc_attr( $id ); ?>' class="<?php if ( $tab['selected'] ) : ?>selected<?php endif; ?> " tabindex='0'>
				<?php echo esc_html( $tab['label'] ); ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<div class="clearfix"></div>
</div>