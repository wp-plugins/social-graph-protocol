<?php $options = get_option( 'sgp_settings' ); ?>
<div class="wrap">
	<h2><?php _e( 'General Settings', $textdomain ); ?></h2>
	
	<?php if( $_GET['settings-updated'] ) : ?>
		<div id="message" style="margin-top:12px;" class="updated fade">
			<p>Your Settings Have Been Successfully Saved!</p>
		</div>
	<?php endif; ?>
	
	<form action="options.php" method="POST">
		<?php settings_fields( 'sgp_group' ); ?>
		
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="description"><?php _e( 'Site Description', $textdomain ); ?></label></th>
					<td>
						<input id="description" name="sgp_settings[description]" type="text" class="regular-text" value="<?php if($options['description']){echo $options['description'];} ?>" maxlength="150" />
						<p class="description"><?php _e( 'Enter in a short one to two sentence description of your website (Defaults to your tagline)', $textdomain ); ?></p>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><label for="image"><?php _e( 'Site Image', $textdomain ); ?></label></th>
					<td>
						<input id="image" name="sgp_settings[image]" type="text" class="regular-text" value="<?php if($options['image']){echo $options['image'];} ?>" />
						<p class="description"><?php _e( 'Image must be at least 50px by 50px and have a maximum aspect ratio of 3:1', $textdomain ) ?></p>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><label for="fbuids"><?php _e( 'Facebook UIDs', $textdomain ); ?></label></th>
					<td>
						<input id="fbuids" name="sgp_settings[fbuids]" type="text" class="regular-text" value="<?php if($options['fbuids']){echo $options['fbuids'];} ?>" />
						<p class="description"><?php _e( 'Your Facebook user id or a comma seperated list of the ids of people who will have admin rights.', $textdomain ); ?></p>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e( 'Disable Namespaces', $textdomain ); ?></th>
					<td>
						<fieldset>
							<label for="namespaces">
								<input id="namespaces" name="sgp_settings[namespaces]" type="checkbox" value="1"<?php if($options['namespaces']){checked( 1, $options['namespaces'] );} ?> />
								<span class="description"><?php _e( 'Check this option if you have hardcoded the xmlns:og and xmlns:fb namespaces into your theme or if you notice they are double entries on your head tag.', $textdomain ); ?></span>
							</label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e( 'Save Changes', $textdomain ); ?>" /></p>
	</form>
	
	<p class="description">
		<?php _e( 'Tip: For additional details on these settings click the help link in the upper right hand corner or read our write up at ', $textdomain ); ?>
		<a href="http://www.codehooks.com/social-graph-protocol-plugin-for-wordpress/" target="_blank">CodeHooks.com</a>
	</p>
</div>