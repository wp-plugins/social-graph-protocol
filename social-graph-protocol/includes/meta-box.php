<?php
	// An empty class to store various values
	$og = new stdClass();
	// Associative Array to hold og:types and there attributes
	$og->types = array( 'article' => array(
							'label' => __( 'Article', $this->textdomain ),
							'article:author' => __( 'Author Profile', $this->textdomain )
						),
						'music.album' => array(
							'label' => __( 'Album', $this->textdomain ),
							'music:song' => __( 'Song', $this->textdomain ),
							'music:song:disc' => __( 'Disc', $this->textdomain ),
							'music:track' => __( 'Track', $this->textdomain ),
							'music:musician' => __( 'Musician', $this->textdomain ),
							'music:release_date' => __( 'Release Date', $this->textdomain )
						),
						'book' => array(
							'label' => __( 'Book', $this->textdomain ),
							'book:author' => __( 'Author', $this->textdomain ),
							'book:isbn' => __( 'ISBN', $this->textdomain ),
							'book:release_date' => __( 'Release Date', $this->textdomain ),
							'book:tag' => __( 'Tags', $this->textdomain )
						),
						'business.business' => array(
							'label' => __( 'Business', $this->textdomain ),
							'business:contact_info:street_address' => __( 'Street Address', $this->textdomain ),
							'business:contact_info:locality' => __( 'Locality', $this->textdomain ),
							'business:contact_info:region' => __( 'Region', $this->textdomain ),
							'business:contact_info:postal_code' => __( 'Postal Code', $this->textdomain ),
							'business:contact_info:country_name' => __( 'Country Name', $this->textdomain ),
							'business:contact_info:phone_number' => __( 'Phone Number', $this->textdomain ),
							'business:contact_info:fax_number' => __( 'Fax Number', $this->textdomain ),
							'business:contact_info:website' => __( 'Website', $this->textdomain ),
							'place:location:latitude' => __( 'Latitude', $this->textdomain ),
							'place:location:longitude' => __( 'Longitude', $this->textdomain ),
							'place:location:altitude' => __( 'Altitude', $this->textdomain )
						),
						'video.movie' => array(
							'label' => __( 'Movie', $this->textdomain ),
							'video:actor:id' => __( 'Actor', $this->textdomain ),
							'video:director' => __( 'Director', $this->textdomain ),
							'video:writer' => __( 'Writer', $this->textdomain ),
							'video:duration' => __( 'Duration', $this->textdomain ),
							'video:release_date' => __( 'Release Date', $this->textdomain ),
							'video:tag' => __( 'Tags', $this->textdomain )
						),
						'music.playlist' => array(
							'label' => __( 'Music Playlist', $this->textdomain ),
							'music:song' => __( 'Song', $this->textdomain ),
							'music:song:track' => __( 'Track', $this->textdomain ),
							'music:creator' => __( 'Creator', $this->textdomain )
						),
						'profile' => array(
							'label' => __( 'Profile', $this->textdomain ),
							'profile:first_name' => __( 'First Name', $this->textdomain ),
							'profile:last_name' => __( 'Last Name', $this->textdomain ),
							'profile:username' => __( 'Username', $this->textdomain ),
							'profile:gender' => __( 'Gender', $this->textdomain )
						),
						'place' => array(
							'label' => __( 'Place', $this->textdomain ),
							'place:location:latitude' => __( 'Latitude', $this->textdomain ),
							'place:location:longitude' => __( 'Longitude', $this->textdomain ),
							'place:location:altitude' => __( 'Altitude', $this->textdomain )
						),
						'music.song' => array(
							'label' => __( 'Song', $this->textdomain ),
							'music:duration' => __( 'Duration', $this->textdomain ),
							'music:album' => __( 'Album', $this->textdomain ),
							'music:musician' => __( 'Musician', $this->textdomain ),
							'music:release_date' => __( 'Release Date', $this->textdomain )
						),
						'video.tv_show' => array(
							'label' => __( 'Tv Show', $this->textdomain ),
							'video:actor:id' => __( 'Actor', $this->textdomain ),
							'video:director' => __( 'Director', $this->textdomain ),
							'video:writer' => __( 'Writer', $this->textdomain ),
							'video:duration' => __( 'Duration', $this->textdomain ),
							'video:release_date' => __( 'Release Date', $this->textdomain ),
							'video:tag' => __( 'Tags', $this->textdomain )
						),
						'video.episode' => array(
							'label' => __( 'Tv Episode', $this->textdomain ),
							'video:actor:id' => __( 'Actor', $this->textdomain ),
							'video:director' => __( 'Director', $this->textdomain ),
							'video:writer' => __( 'Writer', $this->textdomain ),
							'video:duration' => __( 'Duration', $this->textdomain ),
							'video:series' => __( 'Series', $this->textdomain ),
							'video:release_date' => __( 'Release Date', $this->textdomain ),
							'video:tag' => __( 'Tags', $this->textdomain )
						),
						'video.other' => array(
							'label' => __( 'Video', $this->textdomain ),
							'video:actor:id' => __( 'Actor', $this->textdomain ),
							'video:director' => __( 'Director', $this->textdomain ),
							'video:writer' => __( 'Writer', $this->textdomain ),
							'video:duration' => __( 'Duration', $this->textdomain ),
							'video:release_date' => __( 'Release Date', $this->textdomain ),
							'video:tag' => __( 'Tags', $this->textdomain )
						),
						'object' => array(
							'og:title' => __( 'Title', $this->textdomain ),
							'og:image' => __( 'Image', $this->textdomain ),
							'og:video' => __( 'Video', $this->textdomain ),
							'og:audio' => __( 'Audio', $this->textdomain ),
							'og:description' => __( 'Description', $this->textdomain ),
							'og:determiner' => __( 'Determiner', $this->textdomain ),
							'og:see_also' => __( 'Related Post', $this->textdomain ),
							'fb:profile_id' => __( 'Profile', $this->textdomain ),
							'og:restrictions:age' => __( 'Age Restriction', $this->textdomain ),
							'og:restrictions:country:allowed' => __( 'Countries Allowed', $this->textdomain ),
							'og:restrictions:country:disallowed' => __( 'Countries Disallowed', $this->textdomain )
						)
				 );
	ksort( $og->types ); // Sort the array for output purposes
	$post_id = $_GET['post']; // Attempt to get the post/page ID from the URL
	// If there is no post/page id, this is a new post/page don't look for any values because they are not there
	if( !empty( $post_id ) ) {
		// This is the edit post/page screen, attempt to get any meta data previously added by the user
		$openGraphObj = get_post_meta( $post_id, 'oGraphProtocolMeta', true );
	}
	if( empty( $openGraphObj->type ) ) {
		$openGraphObj = new stdClass();
		$openGraphObj->type = 'article';
	}

	$multiples = array(
		'og:image',
		'og:video',
		'og:audio',
		'og:see_also',
		'article:author',
		'book:author',
		'music:song',
		'music:musician',
		'music:album',
		'video:actor:id',
		'video:director',
		'video:writer'
	);
?>

<table id="social_graph_table" cellpadding="4" cellspacing="8">
	<tbody>
		<tr>
			<td>
				<label for="object_type"><?php _e( 'Type', $this->textdomain ) ?>:</label>
			</td>

			<td>
				<select id="object_type" name="og:type">
					<?php
						foreach( $og->types as $key => $attributes ) {
							if( $key == 'object' ) continue;

							if( $key == $openGraphObj->type ) {
								echo "<option value='$key' selected='true'>" . $attributes['label'] . "</option>";
								continue;
							}

							echo "<option value='$key'>" . $attributes['label'] . "</option>";
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="attributes"><?php _e( 'New Attribute', $this->textdomain ) ?>:</label></td>
			<td>
				<select id="attributes" name="attributes">
					<?php
						// Display all the defaulted attributes
						foreach( (array)$og->types['object'] as $key => $label ) {
							if( array_key_exists( $key, (array)$openGraphObj->attributes ) && !in_array( $key, $multiples ) ) continue;
							echo '<option value="' . $key . '">' . $label . '</option>';
						}

						// If a type was selected show it now
						foreach( (array)$og->types[$openGraphObj->type] as $key => $label ) {
							if( $key == 'label' ) continue;
							if( array_key_exists( $key, (array)$openGraphObj->attributes ) && !in_array( $key, $multiples ) ) continue;
							if( $key == 'music:song:track' && $openGraphObj->type = 'music.playlist' ) continue;
							if( $key == 'music:track' && $openGraphObj->type = 'music.album' ) continue;

							echo '<option value="' . $key . '">' . $label . '</option>';
						}
					?>
				</select>

				<span id="display_attribute">
					<input type="text" size="50" value="" name="og:title" />
				</span>

				<a id="add_attribute" href="javascript:void();"><?php _e( 'Add Attribute', $this->textdomain ) ?></a>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p class="description"><?php _e( 'The title of your post as it should appear within the graph, e.g., "The Rock". Defaults to your post title.', $this->textdomain ) ?></p>
			</td>
		</tr>
		<?php if( !empty( $openGraphObj->attributes ) ) : ?>
			<tr id="current_attributes_label">
				<td><strong>Current Attributes</strong></td>
			</tr>
			<?php foreach( (array)$openGraphObj->attributes as $attr_name => $value ) : ?>
				<?php if( is_array( $value ) ) : ?>
					<?php foreach( (array)$value as $array_value ) : ?>
						<tr class="attributes_set">
							<?php if( empty( $og->types[$openGraphObj->type][$attr_name] ) ) : ?>
								<td><label for="<?php echo $attr_name; ?>"><?php echo $og->types['object'][$attr_name]; ?>:</label></td>
							<?php else: ?>
								<td><label for="<?php echo $attr_name; ?>"><?php echo $og->types[$openGraphObj->type][$attr_name]; ?>:</label></td>
							<?php endif; ?>
							<td>
								<?php if( $attr_name == 'og:image' ) : ?>
									<input type="text" size="50" value="<?php echo $array_value['src']; ?>" name="og:image[][src]" />
									<input type="text" size="5" value="<?php echo $array_value['width']; ?>" placeholder="<?php _e( 'Width', $this->textdomain ); ?>" name="og:image:width[]" />
									<input type="text" size="5" value="<?php echo $array_value['height']; ?>" placeholder="<?php _e( 'Height', $this->textdomain ); ?>" name="og:image:height[]" />
								<?php elseif( $attr_name == 'og:video' ) : ?>
									<input type="text" size="50" value="<?php echo $array_value['src']; ?>" name="og:video[][src]" />
									<input type="text" size="5" value="<?php echo $array_value['width']; ?>" placeholder="<?php _e( 'Width', $this->textdomain ); ?>" name="og:video:width[]" />
									<input type="text" size="5" value="<?php echo $array_value['height']; ?>" placeholder="<?php _e( 'Height', $this->textdomain ); ?>" name="og:video:height[]" />
								<?php elseif( $attr_name == 'music:song' ) : ?>
									<input type="text" size="50" value="<?php echo $array_value['url']; ?>" name="music:song[][url]" />
									<?php if( $openGraphObj->type != 'music.playlist' ) : ?>
										<input type="text" size="5" value="<?php echo $array_value['disc']; ?>" placeholder="<?php _e( 'Disc #', $this->textdomain ); ?>" name="music:song:disc[]" />
									<?php endif; ?>
									<input type="text" size="5" value="<?php echo $array_value['track']; ?>" placeholder="<?php _e( 'Track #', $this->textdomain ); ?>" name="music:song:track[]" />
								<?php else: ?>
									<?php if( in_array( $attr_name, $multiples ) ) : ?>
										<input type="text" size="50" value="<?php echo $array_value; ?>" name="<?php echo $attr_name; ?>[]" />
									<?php else: ?>
										<input type="text" size="50" value="<?php echo $array_value; ?>" name="<?php echo $attr_name; ?>" />
									<?php endif; ?>
								<?php endif; ?>
								 <a id="delete_attribute" href="javascript:void();">Delete Attribute</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr class="attributes_set">
						<?php if( empty( $og->types[$openGraphObj->type][$attr_name] ) ) : ?>
							<td><label for="<?php echo $attr_name; ?>"><?php echo $og->types['object'][$attr_name]; ?>:</label></td>
						<?php else: ?>
							<td><label for="<?php echo $attr_name; ?>"><?php echo $og->types[$openGraphObj->type][$attr_name]; ?>:</label></td>
						<?php endif; ?>

						<td>
							<?php if( $attr_name == 'og:determiner' ) : ?>
								<select name="og:determiner">
									<option<?php if( $value == 'a' ) {echo ' selected="true"';} ?> value="a">a</option>
									<option<?php if( $value == 'an' ) {echo ' selected="true"';} ?> value="an">an</option>
									<option<?php if( $value == 'the' ) {echo ' selected="true"';} ?> value="the">the</option>
								</select>
							<?php elseif( $attr_name == 'profile:gender' ) : ?>
								<select name="profile:gender">
									<option<?php if( $value == 'female' ) {echo ' selected="true"';} ?> value="female">Female</option>
									<option<?php if( $value == 'male' ) {echo ' selected="true"';} ?> value="male">Male</option>
								</select>
							<?php else: ?>
									<input type="text" size="50" value="<?php echo $value; ?>" name="<?php echo $attr_name; ?>" />
							<?php endif; ?>
							 <a id="delete_attribute" href="javascript:void();">Delete Attribute</a>
						</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>