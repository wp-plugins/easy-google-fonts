<?php 
/**
 * Font Weight/Style Select Control
 *
 * Outputs a select control containing all of the available
 * fonts weights and variants. Added support for different 
 * subsets of fonts in this version.
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
<span class="customize-control-title"><?php _e( 'Font Weight/Style', 'easy-google-fonts' ); ?></span>
<select class="tt-font-weight" autocomplete="off" data-default-value="<?php echo $default_font_weight_style; ?>">
	<?php if ( $font ) : ?>
		<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'easy-google-fonts' ); ?></option>
		<?php foreach ( $font['font_weights'] as $key => $value ) : ?>
			<?php 
				$default_font_weight = '';

				// Set font style and weight
				$style_data = 'normal';
				$weight     = 400;
				
				if ( strpos( $value, 'italic' ) !== false ) {
					$style_data = 'italic';
				}

				if ( $value !== 'regular' && $value !== 'italic' ) {
					$weight = (int) substr( $value, 0, 3 );
				}	
			?>
			<option value="<?php echo $value ?>" data-stylesheet-url="<?php echo $font['urls'][ $value ] ?>" data-font-weight="<?php echo $weight; ?>" data-font-style="<?php echo $style_data; ?>">
				<?php echo $value; ?>
			</option>
		<?php endforeach; ?>
	<?php else : ?>
		<option value=""><?php _e( '&mdash; Theme Default &mdash;', 'easy-google-fonts' ); ?></option>
	<?php endif; ?>
</select>