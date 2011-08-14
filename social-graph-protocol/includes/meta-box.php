<?php
	// An empty class to store various values
	$og = new stdClass();
	
	// Array to hold og:type values
	$og->types = array( 'actor' => 'Actor',
						'album' => 'Album',
						'activity' => 'Activity',
						'article' => 'Article',
						'athlete' => 'Athlete',
						'author' => 'Author',
						'band' => 'Band',
						'bar' => 'Bar',
						'book' => 'Book',
						'cause' => 'Cause',
						'company' => 'Company',
						'cafe' => 'Cafe',
						'city' => 'City',
						'country' => 'Country',
						'sport' => 'Sport',
						'hotel' => 'Hotel',
						'restaurant' => 'Restaurant',
						'sports_league' => 'Sports League',
						'sports_team' => 'Sports Team',
						'government' => 'Government',
						'non_profit' => 'Non Profit',
						'school' => 'School',
						'university' => 'University',
						'director' => 'Director',
						'musician' => 'Musician',
						'politician' => 'Politician',
						'public_figure' => 'Public Figure',
						'landmark' => 'Landmark',
						'state_province' => 'State/Province',
						'drink' => 'Drink',
						'food' => 'Food',
						'game' => 'Game',
						'product' => 'Product',
						'song' => 'Song',
						'movie' => 'Movie',
						'tv_show' => 'Tv Show'
				 );
				 
	asort( $og->types ); // Sort the $og->types array for output purposes
	$post_id = $_GET['post']; // Attempt to get the post/page ID from the URL
	
	// If there is no post/page id, this is a new post/page don't look for any values because they are not there
	if( !empty( $post_id ) ) {
		// This is the edit post/page screen, attempt to get any values previously added by the user
		$og->type = get_post_meta( $post_id, 'og:type', true );
		$og->title = get_post_meta( $post_id, 'og:title', true );
		$og->description = get_post_meta( $post_id, 'og:description', true );
		$og->image = get_post_meta( $post_id, 'og:image', true );
		$og->latitude = get_post_meta( $post_id, 'og:latitude', true );
		$og->longitude = get_post_meta( $post_id, 'og:longitude', true );
		$og->street_address = get_post_meta( $post_id, 'og:street-address', true );
		$og->locality = get_post_meta( $post_id, 'og:locality', true );
		$og->region = get_post_meta( $post_id, 'og:region', true );
		$og->postal_code = get_post_meta( $post_id, 'og:postal-code', true );
		$og->country_name = get_post_meta( $post_id, 'og:country-name', true );
		$og->email = get_post_meta( $post_id, 'og:email', true );
		$og->phone_number = get_post_meta( $post_id, 'og:phone_number', true );
		$og->fax_number = get_post_meta( $post_id, 'og:fax_number', true );
		$og->upc = get_post_meta( $post_id, 'og:upc', true );
		$og->isbn = get_post_meta( $post_id, 'og:isbn', true );
		$og->video = array( 'og:video' => get_post_meta( $post_id, 'og:video', true ), 'og:video:width' => get_post_meta( $post_id, 'og:video:width', true ),
			'og:video:height' => get_post_meta( $post_id, 'og:video:height', true ), 'og:video:type' => get_post_meta( $post_id, 'og:video:type', true ) );
		$og->audio = array( 'og:audio' => get_post_meta( $post_id, 'og:audio', true ), 'og:audio:title' => get_post_meta( $post_id, 'og:audio:title', true ), 
			'og:audio:artist' => get_post_meta( $post_id, 'og:audio:artist', true ), 'og:audio:album' => get_post_meta( $post_id, 'og:audio:album', true ), 
			'og:audio:type' => get_post_meta( $post_id, 'og:audio:type', true ) );
	}

	if( empty( $og->type ) ) {
		$og->type = 'article';
	}
?>
<table cellpadding="4" cellspacing="8">
	<tr>
		<td colspan="2">
			<p style="font-weight:bold;font-size:14px;">General Settings</p>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_type">Type:</label>
		</td>
		<td>
			<select id="og_type" name="og_type">
				<?php
					foreach( $og->types as $key => $value ) {
						if( $key == $og->type ) {
							echo "<option name='$key' value='$key' selected='true'>$value</option>";
							continue;
						}
						
						echo "<option name='$key' value='$key'>$value</option>";
					}
				?>
			</select> <small>(If Your Unsure Leave This Value Set To Article. <a href="http://www.bizzylabs.com/facebook-open-graph-plugin/" target="_blank">Learn More</a>)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_title">Title:</label>
		</td>
		<td>
			<input type="text" id="og_title" name="og_title" value="<?php echo $og->title; ?>" size="50" maxlength="255" /> <small>(Defaults To Post Title)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_description">Description:</label>
		</td>
		<td>
			<input type="text" id="og_description" name="og_description" value="<?php echo $og->description; ?>" size="50" maxlength="255" /> <small>(Defaults To Post Content)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_image">Image:</label>
		</td>
		<td>
			<input type="text" id="og_image" name="og_image" value="<?php echo $og->image; ?>" size="50" /> <small>(Defaults To Featured Image)</small>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p style="font-weight:bold;font-size:14px;">Location Settings</p>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_latitude">Latitude:</label>
		</td>
		<td>
			<input type="text" id="og_latitude" name="og_latitude" value="<?php echo $og->latitude; ?>" size="50" /> <small>(Example: 37.416343)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_longitude">Longitude:</label>
		</td>
		<td>
			<input type="text" id="og_longitude" name="og_longitude" value="<?php echo $og->longitude; ?>" size="50" /> <small>(Example: -122.153013)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_street_address">Street Address:</label>
		</td>
		<td>
			<input type="text" id="og_street_address" name="og_street_address" value="<?php echo $og->street_address; ?>" size="50" /> <small>(Example: 1601 S California Ave)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_locality">Locality:</label>
		</td>
		<td>
			<input type="text" id="og_locality" name="og_locality" value="<?php echo $og->locality; ?>" size="50" /> <small>(Example: Palo Alto)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_region">Region:</label>
		</td>
		<td>
			<input type="text" id="og_region" name="og_region" value="<?php echo $og->region; ?>" size="50" /> <small>(Example: CA)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_postal_code">Postal Code:</label>
		</td>
		<td>
			<input type="text" id="og_postal_code" name="og_postal_code" value="<?php echo $og->postal_code; ?>" size="50" /> <small>(Example: 94304 or T5N-3R6)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_country_name">Country Name:</label>
		</td>
		<td>
			<input type="text" id="og_country_name" name="og_country_name" value="<?php echo $og->country_name; ?>" size="50" /> <small>(Example: USA)</small>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p style="font-weight:bold;font-size:14px;">Personal/Business Settings</p>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_email">Email:</label>
		</td>
		<td>
			<input type="text" id="og_email" name="og_email" value="<?php echo $og->email; ?>" size="50" /> <small>(Example: me@example.com)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_phone_number">Phone Number:</label>
		</td>
		<td>
			<input type="text" id="og_phone_number" name="og_phone_number" value="<?php echo $og->phone_number; ?>" size="50" /> <small>(Example: 650-123-4567)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_fax_number">Fax Number:</label>
		</td>
		<td>
			<input type="text" id="og_fax_number" name="og_fax_number" value="<?php echo $og->fax_number; ?>" size="50" /> <small>(Example: +1-415-123-4567)</small>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p style="font-weight:bold;font-size:14px;">Video Settings</p>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_video">Video:</label>
		</td>
		<td>
			<input type="text" id="og_video" name="og_video" value="<?php echo $og->video['og:video']; ?>" size="50" /> <small>(Example: http://example.com/awesome.swf)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_video_height">Video Height:</label>
		</td>
		<td>
			<input type="text" id="og_video_height" name="og_video_height" value="<?php echo $og->video['og:video:height']; ?>" size="50" /> <small>(Example: 640)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_video_width">Video Width:</label>
		</td>
		<td>
			<input type="text" id="og_video_width" name="og_video_width" value="<?php echo $og->video['og:video:width']; ?>" size="50" /> <small>(Example: 385)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_video_type">Video Type:</label>
		</td>
		<td>
			<input type="text" id="og_video_type" name="og_video_type" value="<?php echo $og->video['og:video:type']; ?>" size="50" /> <small>(Example: application/x-shockwave-flash)</small>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p style="font-weight:bold;font-size:14px;">Audio Settings</p>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_audio">Audio:</label>
		</td>
		<td>
			<input type="text" id="og_audio" name="og_audio" value="<?php echo $og->audio['og:audio']; ?>" size="50" /> <small>(Example: http://example.com/amazing.mp3)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_audio_title">Audio Title:</label>
		</td>
		<td>
			<input type="text" id="og_audio_title" name="og_audio_title" value="<?php echo $og->audio['og:audio:title']; ?>" size="50" /> <small>(Example: Amazing Soft Rock Ballad)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_audio_artist">Audio Artist:</label>
		</td>
		<td>
			<input type="text" id="og_audio_artist" name="og_audio_artist" value="<?php echo $og->audio['og:audio:artist']; ?>" size="50" /> <small>(Example: Amazing Band)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_audio_album">Audio Album:</label>
		</td>
		<td>
			<input type="text" id="og_audio_album" name="og_audio_album" value="<?php echo $og->audio['og:audio:album']; ?>" size="50" /> <small>(Example: Amazing Album)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_audio_type">Audio Type:</label>
		</td>
		<td>
			<input type="text" id="og_audio_type" name="og_audio_type" value="<?php echo $og->audio['og:audio:type']; ?>" size="50" /> <small>(Example: application/mp3)</small>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p style="font-weight:bold;font-size:14px;">Product Settings</p>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_upc">UPC:</label>
		</td>
		<td>
			<input type="text" id="og_upc" name="og_upc" value="<?php echo $og->upc; ?>" size="50" /> <small>(Example: 123456789999)</small>
		</td>
	</tr>
	<tr>
		<td>
			<label for="og_isbn">ISBN:</label>
		</td>
		<td>
			<input type="text" id="og_isbn" name="og_isbn" value="<?php echo $og->isbn; ?>" size="50" /> <small>(Example: 99921-58-10-7)</small>
		</td>
	</tr>
</table>