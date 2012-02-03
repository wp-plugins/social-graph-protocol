<?php
	// Get any values already set by the user
	$description = get_option( 'og:description' );
	$image = get_option( 'og:image' );
	$admins = get_option( 'fb:admins' );
	$type = get_option( 'og:type' );
	$disable_namespaces = get_option( 'disable_namespaces' );
?>

<div class="wrap">
	<h2><?php _e( 'Social Graph Protocol', 'social-graph-protocol' ); ?></h2>
	
	<?php if( isset( $message ) ) : ?>
		<div id="message" style="margin-top:12px;" class="updated fade">
			<p><?php echo $message; ?></p>
		</div>
	<?php endif; ?>
	
	<form style="margin-top: 12px;" action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="POST">
		<div id="facebook-open-graph">
			<div class="heading">
				<h3><?php _e( 'General Settings', 'social-graph-protocol' ); ?></h3>
				<input name="save_fb_open_graph_settings" id="save_fb_open_graph_settings" type="submit" class="button" size="40" value="Save Settings" />
			</div>
			
			<div class="row">
				<div class="column-one">
					<label for="og_description"><?php _e( 'Site Description', 'social-graph-protocol' ); ?>: </label>
				</div>
				<div class="column-two">
					<input name="og_description" id="og_description" type="text" size="40" value="<?php echo $description; ?>" maxlength="255" />
				</div>
				<div class="column-three">
					<span><?php _e( 'Enter in a short one to two sentence description of your website (Defaults to your tagline)', 'social-graph-protocol' ); ?></span>
				</div>
			</div>
			
			<div class="row">
				<div class="column-one">
					<label for="og_image"><?php _e( 'Site Image', 'social-graph-protocol' ); ?>: </label>
				</div>
				<div class="column-two">
					<input name="og_image" id="og_image" type="text" size="40" value="<?php echo $image; ?>" />
				</div>
				<div class="column-three">
					<span><?php _e( 'Image must be at least', 'social-graph-protocol' ) ?> 50px <?php _e( 'by', 'social-graph-protocol' ); ?> 50px <?php _e( 'and have a maximum aspect ratio of', 'social-graph-protocol' ); ?> 3:1</span>
				</div>
			</div>
			
			<div class="row">
				<div class="column-one">
					<label for="fb_admins"><?php _e( 'Facebook', 'social-graph-protocol' ); ?> UIDs: </label>
				</div>
				<div class="column-two">
					<input name="fb_admins" id="fb_admins" type="text" size="40" value="<?php echo $admins; ?>" />
				</div>
				<div class="column-three">
					<span><?php _e( 'Your Facebook', 'social-graph-protocol' ); ?> UID <?php _e( 'or a comma seperated list of the ids of people who will have admin rights.', 'social-graph-protocol' ); ?></span>
				</div>
			</div>
			
			<div class="row">
				<div class="column-one" style="width:17%;">
					<label for="disable_namespaces"><?php _e( 'Disable Namespaces', 'social-graph-protocol' ); ?>: </label> 
					<input name="disable_namespaces" id="disable_namespaces"<?php if( $disable_namespaces == 1 ) echo ' checked="true"'; ?> type="checkbox" value="1" />
				</div>
				<div class="column-two" style="width:70%;color:#666;font-size:12px;">
					<span><?php _e( 'Check this option if you have hardcoded the', 'social-graph-protocol' ); ?> xmlns:og <?php _e( 'and', 'social-graph-protocol' ); ?> xmlns:fb <?php _e( 'namespaces into your theme or if you notice they are double entries on your head tag.', 'social-graph-protocol' ); ?></span>
				</div>
			</div>
		</div>
	</form>
	
	<p style="width:960px;font-size:13px;"><strong><?php _e( 'TIP', 'social-graph-protocol' ); ?>: </strong><?php _e( 'If you need help with any of these settings click the help button in the upper right corner or visit the', 'social-graph-protocol' ); ?> 
	<a href="http://www.bizzylabs.com/facebook-open-graph-plugin/" target="_blank"><?php _e( 'Social Graph Plugin', 'social-graph-protocol' ); ?></a> <?php _e( 'site for additional information.', 'social-graph-protocol' ); ?></p>
</div>