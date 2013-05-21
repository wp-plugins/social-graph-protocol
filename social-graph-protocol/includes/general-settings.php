<?php	$options = get_option( 'sgp_settings' );	$description = ( $options['description'] ? $options['description'] : '' );	$image = ( $options['image'] ? $options['image'] : '' );	$fb_uids = ( $options['fbuids'] ? $options['fbuids'] : '' );?>
<div class="wrap sgp-settings">
	<h2><?php _e( 'Social Graph Protocol Version 2.0.0 - General Settings', $this->textdomain ); ?></h2>
	<?php if( $_GET['settings-updated'] ) : ?>
		<div id="message" class="updated fade">
			<p><?php _e( 'Your Settings Have Been Successfully Saved', $this->textdomain ) ?>!</p>
		</div>
	<?php endif; ?>
	<form action="options.php" method="post">
		<?php settings_fields( 'sgp_group' ); ?>		<table class="form-table">			<tbody>
				<tr valign="top">
					<th scope="row"><label for="description"><?php _e( 'Website Description', $this->textdomain ); ?></label></th>
					<td>
						<input id="description" name="sgp_settings[description]" type="text" class="regular-text" value="<?php echo $description ?>" maxlength="150" /> <span class="optional">Optional But Recommended</span>
						<p class="description"><?php _e( 'A short 150 character description or summary of your website as a whole. If left blank the plugin will use your websites tagline.', $this->textdomain ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="image"><?php _e( 'Website Image', $this->textdomain ); ?></label></th>
					<td>
						<input id="image" name="sgp_settings[image]" type="text" class="regular-text" value="<?php echo $image ?>" /> <span class="required">Required</span>
						<p class="description"><a id="imgup" class="button" href="">Upload Image</a> <?php _e( 'URL to a screenshot of your website as a whole. Must be 200px in each dimension or bigger', $this->textdomain ) ?> - <a href="http://www.codehooks.com/social-graph-protocol-version-2-0-0-documentation/#imageres" target="_blank"><?php _e( 'See Image Restrictions', $this->textdomain ); ?></a></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="fbuids"><?php _e( 'Facebook UIDs', $this->textdomain ); ?></label></th>
					<td>
						<input id="fbuids" name="sgp_settings[fbuids]" type="text" class="regular-text" value="<?php echo $fb_uids ?>" /> <span class="required">Strongly Recommended</span>
						<p class="description"><?php _e( 'Your Facebook user id or a comma seperated list of the people who have administration rights to your blog', $this->textdomain ); ?> - <a href="http://www.codehooks.com/social-graph-protocol-version-2-0-0-documentation/#facebook-uid" target="_blank"><?php _e( 'See User IDs', $this->textdomain ); ?></a></p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e( 'Save Changes', $this->textdomain ); ?>" /></p>
	</form>
	<p class="description">
		<?php _e( 'Tip: For additional details on these settings click the help link in the upper right hand corner or read our documentation at ', $this->textdomain ); ?>
		<a href="http://www.codehooks.com/social-graph-protocol-version-2-0-0-documentation/" target="_blank">www.CodeHooks.com</a>
	</p>
</div>