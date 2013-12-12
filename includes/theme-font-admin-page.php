<?php 
/**
 * Theme Font Generator Admin Page Output
 *
 * This file is responsible for generating the admin 
 * page output for the google fonts settings page. It
 * should only be included from within a function.
 * 
 * @package     WordPress
 * @subpackage  WordPress_Google_Fonts
 * @author      Sunny Johal - Titanium Themes
 * @copyright   Copyright (c) 2013, Titanium Themes
 * @version     1.1.1
 * 
 */

/**
 * Check User Permissions and Theme Support
 * 
 * Checks if the user has the required privileges. It will 
 * die if these conditions are not met.
 *
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 			current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/current_theme_supports		current_theme_supports()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 				    	wp_die()
 *
 * @since 1.0
 * @version  1.0
 * 
 */
	if ( ! current_user_can('edit_theme_options') )
		wp_die( __( 'Cheatin&#8217; uh?' ) );

/**
 * Set Up URL Variables
 *
 * Declares and sets up all of the variables that are 
 * necessary to display the correct options screen on 
 * the admin page.
 *
 * @link http://codex.wordpress.org/Function_Reference/esc_url 				esc_url()
 * @link http://codex.wordpress.org/Function_Reference/add_query_arg 		add_query_arg()
 *
 * @since 1.0
 * @version  1.0
 * 
 */
	// Declare URL Variables
	$admin_page_name = 'custom_theme_fonts';
	
	// Admin Page URL
	$admin_url       = esc_url(
							add_query_arg( 
								array( 
									'page' => $admin_page_name 
								), 
								admin_url( 'options-general.php' ) 
							) 
						);
	
	// Manage Controls URL
	$manage_url      = esc_url( 
							add_query_arg( 
								array( 
									'screen' => 'manage_controls' 
								), 
								$admin_url 
							) 
						);

	// Advanced URL
	$advanced_url    = esc_url( 
							add_query_arg( 
								array( 
									'screen' => 'advanced' 
								), 
								$admin_url 
							) 
						);

	// Advanced URL
	$create_url    = esc_url( 
							add_query_arg( 
								array( 
									'screen' => 'edit_controls',
									'action' => 'create' 
								), 
								$admin_url 
							) 
						);

/**
 * Get and Initialise Custom Font Controls
 *
 * Get all font control instances so that they can be used
 * throughout the admin pages.
 *
 * @since 1.0
 * @version  1.0
 * 
 */
	$control_instances = tt_font_get_all_font_controls();
	$custom_controls        = array();
	$no_controls            = true;
	$first_control          = false;

	if ( $control_instances ) {
		$no_controls = false;
		
		/**
		 * Get all custom font controls and initialise the first 
		 * custom control as the one to edit on this screen. This 
		 * will be the contorl to edit if no other control id has 
		 * been passed in the URL.
		 * 
		 */
		$count = 0;
		$current_control_id;

		while ( $control_instances->have_posts() ) {
			
			// Loop through the post
			$control_instances->the_post();

			// Add this control to the $custom_controls array
			$id                     = get_post_meta( get_the_ID(), 'control_id', true );
			$custom_controls[ $id ] = get_the_title();

			// Set curent control id to the first control
			if( 0 == $count ) {
				$current_control_id = $id;
				$first_control      = tt_font_get_font_control( $id );
			}

			$count++;
		}

		// Restore original Post Data
		wp_reset_postdata();
	}

	// Update current control id if it is passed in the URL
	if ( isset( $_GET['control'] ) ) {
		$current_control_id = $_GET['control'];
	}

/**
 * Determine Current Screen
 *
 * Checks the global $_GET object in order to determine
 * the current screen. These variables are used throughout
 * this admin page in conditional statements.
 *
 * @since 1.0
 * @version  1.0
 * 
 */
	$edit_screen     = ( ! isset( $_GET['screen'] ) || isset( $_GET['screen'] ) && 'edit_controls' == $_GET['screen'] ) ? true : false; 
	$manage_screen   = ( isset( $_GET['screen'] ) && 'manage_controls' == $_GET['screen'] ) ? true : false;
	$advanced_screen = ( isset( $_GET['screen'] ) && 'advanced' == $_GET['screen'] ) ? true : false;

/**
 * Output Admin Page HTML
 *
 * Generate and output all of the required HTML
 * in order to enable the admin options page
 * functionality.
 *
 * @since 1.0
 * @version  1.0
 * 
 */

//$options = tt_font_get_options( false );
?>
<div class="wrap">
	<!-- Screen Navigation -->
	<?php screen_icon(); ?>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo $admin_url; ?>" class="nav-tab <?php if ( $edit_screen ) { echo 'nav-tab-active'; } ?>"> 
			<?php esc_html_e( 'Edit Font Controls', 'theme-translate' ); ?>
		</a>
		<a href="<?php echo $manage_url; ?>" class="nav-tab <?php if ( $manage_screen ) { echo 'nav-tab-active'; } ?>">
			<?php esc_html_e( 'Manage Font Controls', 'theme-translate' ); ?>
		</a>
		<a href="<?php echo $advanced_url; ?>" class="nav-tab <?php if ( $advanced_screen ) { echo 'nav-tab-active'; } ?>">
			<?php esc_html_e( 'Advanced', 'theme-translate' ); ?>
		</a>
	</h2>

	<?php 
		/**
		 * EDIT CONTROLS SCREEN
		 * ==================================
		 * 
		 * Generate and output all of the required HTML
		 * for the Edit Font Controls Screen.
		 *
		 * @since 1.0
		 * @version  1.0
		 * 
		 */
	?>
	<?php if ( $edit_screen ) : ?>
		<?php 
			/**
			 * Get URL Parameters and Determine Action to take
			 *
			 * Get the parameters passed in the url and determine
			 * the action to take based on the values.
			 *
			 * @since 1.0
			 * @version  1.0
			 * 
			 */
			
			// Allowed actions 'create', 'edit', ( 'delete' gets handled by ajax )
			$action = isset( $_GET['action'] ) ? $_GET['action'] : false;

			// The control id of the current control being edited - Note this is a string representation of '0', not an integer
			$control_selected_id = isset( $_GET['control'] ) ? $_GET['control'] : '0';

			// Attempt to get a control instance if it exists 
			$control_instance = tt_font_get_font_control( $control_selected_id );

			// edit and and no control but has first control
			if ( 'edit' == $action ) {
				if ( ! isset( $_GET['control'] ) && $first_control ) {
					$control_instance    = $first_control;
					$control_selected_id = get_post_meta( $control_instance->ID, 'control_id', true );
					$action              = 'edit';
				} 
			}

			/**
			 * Initialise screen action if no action has been set
			 * in the parameter.
			 */
			if ( ! $action ) {
				
				if ( $first_control ) {
					$control_instance    = $first_control;
					$control_selected_id = get_post_meta( $control_instance->ID, 'control_id', true );
					$action              = 'edit';
				} else {
					$action = 'create';
				}

			} else {
				
				/**
				 * PHP Switch to determine what action to take
				 * upon screen initialisation.
				 */
				switch ( $action ) {
					case 'edit':
						// Change action if we are creating a new font control
						if ( '0' == $control_selected_id ) {
							 $action = 'create';
						} else {

							// Change action if the control instance doesn't exist
							if ( ! $control_instance ) {
								$action = 'create';
							}
						}
						break;

					case 'create':
						// The control id of the current control being edited - Note this is a string representation of '0', not an integer
						$control_selected_id = '0';
						break;
				}	
			}

			/**
			 * Initialise Variables to use on this screen
			 *
			 * Now that the action has been determined the next
			 * stage is to initialise/set up the variables so 
			 * that they can be used on the page.
			 *
			 * @since  1.0
			 * @version  1.0
			 * 
			 */
			$messages  = array();          // Container for any messages displayed to the user

			// Define Variables
			$control_name        = '';
			$control_description = '';

			if ( 'edit' == $action ) {
				$control_name        = $control_instance->post_title;
				$control_selectors   = get_post_meta( $control_instance->ID, 'control_selectors', true );
				$control_description = get_post_meta( $control_instance->ID, 'control_description', true );
			}
		?>
		<?php 
			/**
			 * Updated Control Message 
			 *
			 * Message to display to the user if this
			 * font control has been updated.
			 * 
			 */
		?>
		<?php if ( isset( $_GET['dialog'] ) ) : ?>
			<?php if ( 'updated' == $_GET['dialog'] ) : ?>
				<?php $updated_control_name =  isset( $_GET['name'] ) ? $_GET['name'] : __( 'Font Control', 'theme-translate' ); ?>
				<div class="updated below-h2" id="update_message">
					<p>
						<?php printf( __( '%1$s has been updated. Please visit the %2$s to manage this control.', 'theme-translate' ), "<strong id='updated_control_name'>{$updated_control_name}</strong>", "<strong><a href='" . admin_url( 'customize.php' ) . "'>customizer</a></strong>" ); ?>
					</p>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php 
			/**
			 * Deleted Control Dialog Message 
			 * 
			 * Checks if a font control has just been deleted and
			 * outputs a feedback to the message if it has.
			 * 
			 */
		?>
		<?php if ( isset( $_GET['dialog'] ) ) : ?>
			<?php if ( $_GET['dialog'] == 'deleted' ) : ?>
				<?php $deleted_control_name = isset( $_GET['name'] ) ? $_GET['name'] : __( 'Font Control', 'theme-translate' ); ?>
				<div class="updated below-h2" id="delete_message">
					<p><?php printf( __( '%1$s has been deleted.', 'theme-translate' ), "<strong>{$deleted_control_name}</strong>" ) ?></p>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<div class="manage-controls">
			<form autocomplete="off" id="" action="" method="get" enctype="multipart/form-data">
				<?php if ( ! empty( $custom_controls ) ) : ?>
					<input type="hidden" name="page" value="<?php echo $admin_page_name; ?>">
					<input name="action" type="hidden" value="edit">
					<label class="selected-control" for="control"><?php _e('Select a font control to edit:', 'theme-translate'); ?></label>
					<select autocomplete="off" name="control" id="control">
						<?php foreach ( $custom_controls as $custom_control_id => $custom_control_name ) : ?>
							<option value="<?php echo $custom_control_id; ?>" <?php if( $custom_control_id == $control_selected_id ) : ?>selected<?php endif; ?>><?php echo $custom_control_name; ?></option>
						<?php endforeach; ?>
					</select>
					<?php submit_button( __( 'Select', 'theme-translate' ), 'secondary', '', false ); ?>
					<span class="add-new-control-action">
						or <a href="<?php echo $create_url; ?>">create a new font control</a>		
					</span><!-- /add-new-control-action -->					
				<?php elseif ( 'create' == $action ) : ?>
					<label><?php _e( 'Create a new Font Control.', 'theme-translate' ); ?></label>
				<?php endif; ?>
			</form>	
		</div><!-- END .manage-controls -->

		<div id="edit-controls-wrap">
			<form id="update-control" action="" method="post">
				<div class="control-edit">
					
					<!-- Header -->
					<div id="edit-control-header">
						<div class="major-publishing-actions">
							<label for="menu-name" class="custom-control-label menu-name-label howto open-label">
								<span><?php _e( 'Control Name', 'theme-translate' ); ?></span>
								<input autocomplete="off" type="text" value="<?php echo $control_name; ?>" title="<?php _e( 'Enter control name here', 'theme-translate' ) ?>" class="custom-control-name regular-text menu-item-textbox input-with-default-title" id="custom-control-name" name="custom-control-name">
							</label>
							<div class="publishing-action">
								<span class="spinner"></span>
								<?php if ( 'create' == $action ) : ?>

									<?php 
										/**
										 * Build Edit Redirect Link URL
										 * 
										 * Generate the first part of the URL and store it
										 * in a data attribute. This URL will have the rest
										 * of the query variables appended to it via AJAX.
										 *
										 * @since 1.0
										 * @version 1.1.1
										 * 
										 */
										$edit_redirect_link = esc_url( 
														add_query_arg( 
															array( 
																'page'    => $admin_page_name,
																'action'  => 'edit'
															), 
															admin_url( 'options-general.php' ) 
														) 
													);

										// Create submit button
										submit_button( 
											__( 'Create Font Control', 'theme-translate'), 
											'primary', 
											'submit', 
											false, 
											array( 
												'id'                => 'create_control_header',
												'data-redirect-url' => $edit_redirect_link
											) 
										); 
									?>

								<?php else: ?>

									<?php
										/**
										 * Build Save Redirect Link URL
										 * 
										 * Generate the first part of the URL and store it
										 * in a data attribute. This URL will have the rest
										 * of the query variables appended to it via AJAX.
										 *
										 * @since 1.0
										 * @version 1.1.1
										 * 
										 */
										$save_redirect_link = esc_url( 
																add_query_arg( 
																	array( 
																		'page'    => $admin_page_name,
																		'action'  => 'edit',
																		'dialog'  => 'updated',
																		'control' => $control_selected_id
																	), 
																	admin_url( 'options-general.php' ) 
																) 
															); 

										submit_button( 
											__( 'Save Font Control', 'theme-translate'), 
											'primary', 
											'submit', 
											false, 
											array( 
												'id' => 'save_control_header', 
												'data-control-id' => $control_selected_id,
												'data-redirect-url' => $save_redirect_link
											) 
										); 
									?>

								<?php endif; ?>						
							</div><!-- END .publishing-action -->
							<div class="clear"></div>
						</div><!-- END .major-publishing-actions -->
					</div>
					
					<!-- Body -->
					<div id="post-body">
						<div id="post-body-content">
							<?php if ( 'create' == $action ) : ?>
								<p class="post-body-plain"><?php _e( 'Give your font control a name above, then click Create Font Control.', 'theme-translate' ) ?></p>
							<?php else : ?>
								<h3><?php _e( 'Add CSS Selectors', 'theme-translate' ); ?></h3>
								<div class="drag-instructions post-body-plain">
									<p>Type each CSS selector that you would like this font control to manage in the box below. Use the tab key to separate each selector.</p>
								</div>
								<div>
									<ul id="tt-font-tags">
										<?php if ( 'edit' == $action ) : ?>
											<?php $selectors = get_post_meta( get_the_ID(), 'control_selectors', true ); ?>
											<?php if ( $selectors ) : ?>
												<?php foreach ( $selectors as $selector ) : ?>
													<li><?php echo $selector; ?></li>
												<?php endforeach; ?>
											<?php endif; ?>
										<?php endif; ?>
									</ul>
								</div>

								<h3><?php _e( 'Force Styles Override (Optional)', 'theme-translate' ); ?></h3>
								<p><?php _e( "Please check the box below if you wish to override all of the styles for the selectors above that are forced in your theme's stylesheet.", 'theme-translate' ); ?></p>
								<?php $force_styles = get_post_meta( get_the_ID(), 'force_styles', true ); ?>
								<input id="control-force-styles" type="checkbox" <?php checked( $force_styles, true ); ?>>
							<?php endif; ?>

						
						</div>
					</div>

					<!-- Footer -->
					<div id="edit-control-footer">
						<div class="major-publishing-actions">
							<?php
								/**
								 * Build Delete Link URL
								 * 
								 * Generate a unique edit URL for each custom
								 * font control.
								 * 
								 */
								$delete_link = '';
								$delete_link = esc_url( 
													add_query_arg( 
														array( 
															'page'    => $admin_page_name,
															'action'  => 'edit',
															'dialog'  => 'deleted',
															'name'    =>  str_replace ( ' ', '+', $control_name )
														), 
														admin_url( 'options-general.php' ) 
													) 
												);
							?>
							<span class="delete-action">
								<?php if( 'create' == $action ) : ?>
									<?php $delete_link = $admin_url; ?>
								<?php endif; ?>
								<a data-redirect-url="<?php echo $delete_link; ?>" data-control-id="<?php echo $control_selected_id; ?>" id="delete-control" href="#" class="submitdelete deletion menu-delete"><?php _e( 'Delete Control', 'theme-translate' ); ?></a>
							</span><!-- END .delete-action -->
							<div class="publishing-action">
								<span class="spinner"></span>
								<?php if ( 'create' == $action ) : ?>

									<?php 
										/**
										 * Build Edit Redirect Link URL
										 * 
										 * Generate the first part of the URL and store it
										 * in a data attribute. This URL will have the rest
										 * of the query variables appended to it via AJAX.
										 *
										 * @since 1.0
										 * @version 1.1.1
										 * 
										 */
										$edit_redirect_link = esc_url( 
														add_query_arg( 
															array( 
																'page'    => $admin_page_name,
																'action'  => 'edit'
															), 
															admin_url( 'options-general.php' ) 
														) 
													);

										// Create submit button
										submit_button( 
											__( 'Create Font Control', 'theme-translate'), 
											'primary', 
											'submit', 
											false, 
											array( 
												'id'                => 'create_control_header',
												'data-redirect-url' => $edit_redirect_link
											) 
										); 
									?>

								<?php else: ?>

									<?php
										/**
										 * Build Save Redirect Link URL
										 * 
										 * Generate the first part of the URL and store it
										 * in a data attribute. This URL will have the rest
										 * of the query variables appended to it via AJAX.
										 *
										 * @since 1.0
										 * @version 1.1.1
										 * 
										 */
										$save_redirect_link = esc_url( 
																add_query_arg( 
																	array( 
																		'page'    => $admin_page_name,
																		'action'  => 'edit',
																		'dialog'  => 'updated',
																		'control' => $control_selected_id
																	), 
																	admin_url( 'options-general.php' ) 
																) 
															); 

										submit_button( 
											__( 'Save Font Control', 'theme-translate'), 
											'primary', 
											'submit', 
											false, 
											array( 
												'id' => 'save_control_header', 
												'data-control-id' => $control_selected_id,
												'data-redirect-url' => $save_redirect_link
											) 
										); 
									?>

								<?php endif; ?>							
							</div><!-- END .publishing-action -->
							<div class="clear"></div>
						</div><!-- END .major-publishing-actions -->
					</div>

				</div><!-- END .control-edit -->
				<?php 
					/**
					 * Create Font Control Nonce Fields for Security
					 * 
					 * This ensures that the request to modify controls 
					 * was an intentional request from the user. Used in
					 * the Ajax request for validation.
					 *
					 * @link http://codex.wordpress.org/Function_Reference/wp_nonce_field 	wp_nonce_field()
					 * 
					 */
					wp_nonce_field( 'tt_font_edit_control_instance', 'tt_font_edit_control_instance_nonce' );
					wp_nonce_field( 'tt_font_delete_control_instance', 'tt_font_delete_control_instance_nonce' );
					wp_nonce_field( 'tt_font_create_control_instance', 'tt_font_create_control_instance_nonce' );
				?>	
			</form><!-- END #update-control -->
		</div><!-- END #edit-controls-wrap -->
	<?php endif; ?>

	<?php 
		/**
		 * MANAGE CONTROLS SCREEN
		 * ==================================
		 * 
		 * Generate and output all of the required HTML
		 * for the Manage Font Controls Screen.
		 *
		 * @since 1.0
		 * @version  1.0
		 * 
		 */
	?>
	<?php if ( $manage_screen ) : ?>
		<form autocomplete="off" method="post" action="<?php echo esc_url( add_query_arg( array( 'screen' => 'edit_controls' ), $admin_url ) ); ?>">
			<?php 
				/**
				 * Output New Font Control Dialog Message
				 * 
				 * If there are no font control output a dialog message
				 * to prompt the user to create a new custom control.
				 * 
				 */
			?>
			<?php if ( $no_controls ) : ?>
				<div class="manage-controls no-controls">
					<label><?php _e( 'Create a new font control for your theme:', 'theme-translate' ); ?></label>
					<?php submit_button( __( 'Create a New Font Control', 'theme-translate'), 'secondary', 'create_new_control', false, array( 'data-create-control-url' => $create_url ) ); ?>	
				</div>
				<?php 
					/**
					 * Output Custom Font Controls Table
					 * 
					 * If there are existing font controls output a table that
					 * displays all custom font control instances.
					 * 
					 */
				?>	
			<?php else : ?>
					<div class="manage-controls control-dialog no-controls">
						<label class="manage-label"><?php _e( 'Manage your custom font controls here or:', 'theme-translate' ); ?></label>
						<label class="new-label"><?php _e( 'Create a new font control for your theme:', 'theme-translate' ); ?></label>
						<?php submit_button( __( 'Create a New Font Control', 'theme-translate'), 'secondary', 'create_new_control', false, array( 'data-create-control-url' => $create_url ) ); ?>				
					</div>	

					<!-- Font Controls Table -->
					<table id="font-controls-table" class="widefat fixed" cellspacing="0">
						<thead>
							<tr>
								<th class="manage-column column-controls "><?php _e( 'Font Control Name', 'theme-translate' ); ?></th>
								<th class="manage-column column-controls"><?php _e( 'CSS Selectors', 'theme-translate' ) ?></th>
								<th class="manage-column column-controls"><?php _e( 'Force Styles', 'theme-translate' ) ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $row_count = 0; ?>
							<?php while ( $control_instances->have_posts() ) : $control_instances->the_post(); ?>
								<?php
									$row_class       = ( $row_count % 2 == 0 ) ? 'alternate' : '';
									$selectors       = get_post_meta( get_the_ID(), 'control_selectors', true );
									$selector_output = '';
									$control_id      = get_post_meta( get_the_ID(), 'control_id', true );
									$force_styles    = get_post_meta( get_the_ID(), 'force_styles', true );

									$edit_link = esc_url( 
										add_query_arg( 
											array( 
												'screen'  => 'edit_controls',
												'action'  => 'edit',
												'control' => $control_id
											), 
											$admin_url 
										) 
									);

									if ( $selectors ) {
										foreach ( $selectors as $selector ) {
											$selector_output .= "{$selector}, ";
										}
									}
								?>
								<tr class="<?php echo $row_class; ?>">
									<td class="post-title page-title column-title">
										<div>
											<strong><a href="#" class="row-title"><?php the_title(); ?></a></strong>
										</div>
										<div class="row-actions">
											<a data-control-reference="<?php echo $control_id; ?>" class="control-edit-link" href="<?php echo $edit_link; ?>"><?php _e( 'Edit', 'theme-translate' ); ?></a> | <a data-control-reference="<?php echo $control_id; ?>" class="control-delete-link" href="#"><?php _e( 'Delete', 'theme-translate' ); ?></a>
										</div>
									</td>
									<td class=""><?php echo $selector_output; ?></td>
									<td class=""><input autocomplete="off" data-control-reference="<?php echo $control_id; ?>" class="tt-force-styles" type="checkbox" <?php checked( $force_styles, 'true' ); ?>></td>
									<td><span class="spinner" style=""></span></td>	
								</tr>
								<?php $row_count++; ?>
							<?php endwhile; ?>
						</tbody>
					</table>
					<?php 
						/**
						 * Create Delete All Controls Link
						 *
						 * Creates a button that will delete all custom
						 * controls created by the user.
						 */
					?>
					<a href="#" id="delete_all_controls"><?php _e( 'Delete All Controls', 'theme-translate' ) ?></a>
			<?php endif; ?>
			<?php 
				/**
				 * Create Font Control Nonce Fields for Security
				 * 
				 * This ensures that the request to modify controls 
				 * was an intentional request from the user. Used in
				 * the Ajax request for validation.
				 *
				 * @link http://codex.wordpress.org/Function_Reference/wp_nonce_field 	wp_nonce_field()
				 * 
				 */
				wp_nonce_field( 'tt_font_delete_control_instance', 'tt_font_delete_control_instance_nonce' );
				wp_nonce_field( 'tt_font_edit_control_instance', 'tt_font_edit_control_instance_nonce' );
			?>
		</form>
	<?php endif; ?>

	<?php 
		/**
		 * ADVANCED CONTROLS SCREEN
		 * ==================================
		 * 
		 * Generate and output all of the required HTML
		 * for the Manage Font Controls Screen.
		 *
		 * @since 1.0
		 * @version  1.0
		 * 
		 */
	?>
	<?php if ( $advanced_screen ) : ?>
		<?php
			/**
			 * Setup Advanced Variables
			 * 
			 */
			$valid_api_key = tt_font_is_valid_google_api_key( tt_font_get_google_api_key() );
			$validity      = $valid_api_key ? 'valid-key' : 'invalid-key';
		?>
		<h3 class="title"><?php _e( 'Google Fonts API Key', 'theme-translate' ); ?></h3>
		<p><?php _e( 'Please enter your google fonts api key in the box below and click the Save Google API Key button.', $domain = 'default' ) ?></p>
		<div class="manage-controls manage-google-key <?php echo $validity; ?>">
			<form enctype="multipart/form-data" method="get" action="" id="" autocomplete="off">
				<input id="google-api-key" type="text" class="" value="<?php echo tt_font_get_google_api_key(); ?>">
				<p class="key-feedback howto">
					<span class="valid-key"><?php _e( 'Your Google API Key is valid and automatic font updates are enabled.', 'theme-translate' ); ?></span>
					<span class="invalid-key"><?php _e( 'Please enter a valid Google API Key', 'theme-translate' ); ?></span>
				</p>
				<?php 
					/**
					 * Create Font Control Nonce Fields for Security
					 * 
					 * This ensures that the request to modify controls 
					 * was an intentional request from the user. Used in
					 * the Ajax request for validation.
					 *
					 * @link http://codex.wordpress.org/Function_Reference/wp_nonce_field 	wp_nonce_field()
					 * 
					 */
					wp_nonce_field( 'tt_font_edit_control_instance', 'tt_font_edit_control_instance_nonce' );
					wp_nonce_field( 'tt_font_delete_control_instance', 'tt_font_delete_control_instance_nonce' );
					wp_nonce_field( 'tt_font_create_control_instance', 'tt_font_create_control_instance_nonce' );
				?>	
			</form>
		</div>
		<?php 
			submit_button( 
				__( 'Save Google API Key', 'theme-translate'), 
				'primary', 
				'submit', 
				false, 
				array( 
					'id' => 'save_api_key',
					'data-redirect-url' => $advanced_url
				) 
			); 
		?>
		<div class="spinner spinner-left"></div>
		<div class="clearfix"></div>

		<div class="google-feedback">
			<div class="valid-key">
				<h3><?php _e( 'What happens after I enter a valid Google API key?', 'theme-translate' ); ?></h3>
				<p><?php _e( 'Your theme will update itself with the latest google fonts automatically.', 'theme-translate' ); ?></p>
			</div>
		</div>

		
	<?php endif; ?>
	
</div><!-- END .wrap -->