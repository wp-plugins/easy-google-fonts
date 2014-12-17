<?php 
/**
 * Font Control Tab Panes
 *
 * Ouputs the font control tab panes. Each panes contains
 * a set of controls to manage the theme's typography.
 * To edit a specific control look in the following folder:
 * views\customizer\controls
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
<?php foreach ( $this->tabs as $id => $tab ): ?>
	<div class="tt-font-content <?php echo $id; ?> <?php if ( $tab['selected'] ) : ?>selected<?php endif; ?>" data-customize-tab='<?php echo esc_attr( $id ); ?>'>
		<?php call_user_func( $tab['callback'] ); ?>
	</div>
<?php endforeach; ?>