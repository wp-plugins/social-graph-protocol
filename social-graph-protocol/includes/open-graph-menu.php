<?php
	// Get any values already set by the user
	$description = get_option( 'og:description' );
	$image = get_option( 'og:image' );
	$admins = get_option( 'fb:admins' );
	$type = get_option( 'og:type' );
	$disable_namespaces = get_option( 'disable_namespaces' );
?>

<div class="wrap">
	<h2>Open Graph Protocol</h2>
	
	<?php if( isset( $message ) ) : ?>
		<div id="message" style="margin-top:12px;" class="updated fade">
			<p><?php echo $message; ?></p>
		</div>
	<?php endif; ?>
	
	<form style="margin-top: 12px;" action="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']; ?>" method="POST">
		<div id="facebook-open-graph">
			<div class="heading">
				<h3>General Settings</h3>
				<input name="save_fb_open_graph_settings" id="save_fb_open_graph_settings" type="submit" class="button" size="40" value="Save Settings" />
			</div>
			
			<div class="row">
				<div class="column-one">
					<label for="og_description">Site Description: </label>
				</div>
				<div class="column-two">
					<input name="og_description" id="og_description" type="text" size="40" value="<?php echo $description; ?>" maxlength="255" />
				</div>
				<div class="column-three">
					<span>Enter in a short one to two sentence description of your website (Defaults to your tagline)</span>
				</div>
			</div>
			
			<div class="row">
				<div class="column-one">
					<label for="og_image">Site Image: </label>
				</div>
				<div class="column-two">
					<input name="og_image" id="og_image" type="text" size="40" value="<?php echo $image; ?>" />
				</div>
				<div class="column-three">
					<span>Image must be at least 50px by 50px and have a maximum aspect ratio of 3:1</span>
				</div>
			</div>
			
			<div class="row">
				<div class="column-one">
					<label for="fb_admins">Facebook UIDs: </label>
				</div>
				<div class="column-two">
					<input name="fb_admins" id="fb_admins" type="text" size="40" value="<?php echo $admins; ?>" />
				</div>
				<div class="column-three">
					<span>Your Facebook UID or a comma seperated list of the ids of people who will have admin rights.</span>
				</div>
			</div>

			<div class="row">
				<div class="column-one">
					<label for="og_type">Type: </label>
				</div>
				<div class="column-two">
					<select name="og_type" id="og_type">
						<option value="blog"<?php if( $type == 'blog' ) echo ' selected="true"'; ?>>Blog</option>
						<option value="website"<?php if( $type == 'website' ) echo ' selected="true"'; ?>>Website</option>
					</select>
				</div>
				<div class="column-three">
					<span>If all you do is blog simply select blog, if you think you have a more traditional website, select website.</span>
				</div>
			</div>
			
			<div class="row">
				<div class="column-one" style="width:17%;">
					<label for="disable_namespaces">Disable Namespaces: </label> 
					<input name="disable_namespaces" id="disable_namespaces"<?php if( $disable_namespaces == 1 ) echo ' checked="true"'; ?> type="checkbox" value="1" />
				</div>
				<div class="column-two" style="width:70%;color:#666;font-size:12px;">
					<span>Check this option is you have hardcoded the xmlns:og and xmlns:fb namespaces into your theme or if you notice they are double entries on your head tag.</span>
				</div>
			</div>
		</div>
	</form>
	
	<p style="width:960px;font-size:13px;"><strong>TIP: </strong>If you need help with any of these settings click the help button in the upper right corner or visit the 
	<a href="http://www.bizzylabs.com/facebook-open-graph-plugin/" target="_blank">Facebook Open Graph Plugin</a> site for additional information.</p>
</div>