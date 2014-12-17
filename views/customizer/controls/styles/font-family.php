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
 * @version   1.3.3
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

	<!-- Google Serif -->
	<?php if ( ! empty( $google_subsets['serif'] ) ): ?>
		<optgroup label="<?php _e( 'Google Serif Fonts', 'easy-google-fonts' ) ?>" class="google_label">
			<?php foreach ( $google_subsets['serif'] as $id => $properties ) : ?>
				<?php if ( in_array( $selected_subset, $properties['subsets'] ) || ( 'all' == $selected_subset ) ) : ?>
					<option value="<?php echo $id; ?>" data-font-type="google" <?php selected( $current_value, $id ); ?>><?php echo $properties['name']; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>	
		</optgroup>
	<?php endif; ?>

	<!-- Google Sans Serif -->
	<?php if ( ! empty( $google_subsets['sans-serif'] ) ): ?>
		<optgroup label="<?php _e( 'Google Sans Serif Fonts', 'easy-google-fonts' ) ?>" class="google_label">
			<?php foreach ( $google_subsets['sans-serif'] as $id => $properties ) : ?>
				<?php if ( in_array( $selected_subset, $properties['subsets'] ) || ( 'all' == $selected_subset ) ) : ?>
					<option value="<?php echo $id; ?>" data-font-type="google" <?php selected( $current_value, $id ); ?>><?php echo $properties['name']; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>	
		</optgroup>
	<?php endif; ?>

	<!-- Google Display -->
	<?php if ( ! empty( $google_subsets['display'] ) ): ?>
		<optgroup label="<?php _e( 'Google Display Fonts', 'easy-google-fonts' ) ?>" class="google_label">
			<?php foreach ( $google_subsets['display'] as $id => $properties ) : ?>
				<?php if ( in_array( $selected_subset, $properties['subsets'] ) || ( 'all' == $selected_subset ) ) : ?>
					<option value="<?php echo $id; ?>" data-font-type="google" <?php selected( $current_value, $id ); ?>><?php echo $properties['name']; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>	
		</optgroup>
	<?php endif; ?>

	<!-- Google Handwriting -->
	<?php if ( ! empty( $google_subsets['handwriting'] ) ): ?>
		<optgroup label="<?php _e( 'Google Handwriting Fonts', 'easy-google-fonts' ) ?>" class="google_label">
			<?php foreach ( $google_subsets['handwriting'] as $id => $properties ) : ?>
				<?php if ( in_array( $selected_subset, $properties['subsets'] ) || ( 'all' == $selected_subset ) ) : ?>
					<option value="<?php echo $id; ?>" data-font-type="google" <?php selected( $current_value, $id ); ?>><?php echo $properties['name']; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>	
		</optgroup>
	<?php endif; ?>

	<!-- Google Monospace -->
	<?php if ( ! empty( $google_subsets['monospace'] ) ): ?>
		<optgroup label="<?php _e( 'Google Monospace Fonts', 'easy-google-fonts' ) ?>" class="google_label">
			<?php foreach ( $google_subsets['monospace'] as $id => $properties ) : ?>
				<?php if ( in_array( $selected_subset, $properties['subsets'] ) || ( 'all' == $selected_subset ) ) : ?>
					<option value="<?php echo $id; ?>" data-font-type="google" <?php selected( $current_value, $id ); ?>><?php echo $properties['name']; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>	
		</optgroup>
	<?php endif; ?>
</select>