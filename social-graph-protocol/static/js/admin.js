jQuery(document).ready(function() {
	var current_attributes = new Object();

	jQuery( '#social_graph_table tbody tr:gt(3)').each(function(index){
		var attr_name = jQuery(this).find('td label').html().split(':').join('');
		var attr_value = jQuery(this).find('td label').attr('for');

		// If this attribute has not yet been set
		if( !current_attributes[jQuery(this).find('td label').attr('for').split(':').join('_')] ) {
			current_attributes[jQuery(this).find('td label').attr('for').split(':').join('_')] = new Array(); // Create an array of this attribute
			current_attributes[jQuery(this).find('td label').attr('for').split(':').join('_')].push( {name: attr_name, value:attr_value} ); // add attributes to it
		}
		else {
			current_attributes[jQuery(this).find('td label').attr('for').split(':').join('_')].push( {name: attr_name, value:attr_value} ); // add attributes to it
		}
	});

	// When a user changes the Object Type (Article, Album, ect...)
	jQuery( '#object_type' ).change( function( event, paramOne ) {
		var objectType = jQuery( this ).val(); // Obtain the value that was selected

		var OpenGraphTypes = {
			article: {
				label: labels.article,
				article_author: labels.article + ' ' + labels.author
			},
			music_album: {
				label: labels.album,
				music_song: labels.song,
				music_song_disc: labels.disc,
				music_track: labels.track,
				music_musician: labels.musician,
				music_release_date: labels.release_date
			},
			book: {
				label: labels.book,
				book_author: labels.author,
				book_isbn: 'ISBN',
				book_release_date: labels.release_date,
				book_tag: labels.tags
			},
			business_business: {
				label: labels.business,
				business_contact_info_street_address: labels.street_address,
				business_contact_info_locality: labels.locality,
				business_contact_info_region: labels.region,
				business_contact_info_postal_code: labels.postal_code,
				business_contact_info_country_name: labels.country_name,
				business_contact_info_phone_number: labels.phone_number,
				business_contact_info_fax_number: labels.fax_number,
				business_contact_info_website: labels.website,
				place_location_latitude: labels.latitude,
				place_location_longitude: labels.longitude,
				place_location_altitude: labels.altitude
			},
			video_movie: {
				label: labels.movie,
				video_actor_id: labels.actor,
				video_director: labels.director,
				video_writer: labels.writer,
				video_duration: labels.duration,
				video_release_date: labels.release_date,
				video_tag: labels.tags
			},
			music_playlist: {
				label: labels.music_playlist,
				music_song: labels.song,
				music_song_track: labels.track,
				music_creator: labels.creator
			},
			profile: {
				label: labels.profile,
				profile_first_name: labels.first_name,
				profile_last_name: labels.last_name,
				profile_username: labels.username,
				profile_gender: labels.gender
			},
			place: {
				label: labels.place,
				place_location_latitude: labels.latitude,
				place_location_longitude: labels.longitude,
				place_location_altitude: labels.altitude
			},
			music_song: {
				label: labels.song,
				music_duration: labels.duration,
				music_album: labels.album,
				music_musician: labels.musician,
				music_release_date: labels.release_date
			},
			video_tv_show: {
				label: labels.tv_show,
				video_actor_id: labels.actor,
				video_director: labels.director,
				video_writer: labels.writer,
				video_duration: labels.duration,
				video_release_date: labels.release_date,
				video_tag: labels.tags
			},
			video_episode: {
				label: labels.tv_episode,
				video_actor_id: labels.actor,
				video_director: labels.director,
				video_writer: labels.writer,
				video_duration: labels.duration,
				video_series: labels.series,
				video_release_date: labels.release_date,
				video_tag: labels.tags
			},
			video_other: {
				label: labels.video,
				video_actor_id: labels.actor,
				video_director: labels.director,
				video_writer: labels.writer,
				video_duration: labels.duration,
				video_release_date: labels.release_date,
				video_tag: labels.tags
			},
			object: {
				og_title: labels.title,
				og_image: labels.image,
				og_video: labels.video,
				og_audio: labels.audio,
				og_description: labels.description,
				og_determiner: labels.determiner,
				og_see_also: labels.related_post,
				fb_profile_id: labels.profile,
				og_restrictions_age: labels.ageRestriction,
				og_restrictions_country_allowed: labels.countriesAllowed,
				og_restrictions_country_disallowed: labels.countriesDisallowed
			}
		};

		if( jQuery( '#social_graph_table tbody tr:first td' ).attr('class') == 'fberrorbox' ) {
			jQuery( '#social_graph_table tbody tr:first' ).remove();
		}

		if( paramOne == undefined ) {
			var notAllowedLabel = ''; // Used to hold the labels of elements not allowed to be associated with this object type
			for( var i in current_attributes ) { // for each attribute currently added
				// If this attribute is not allowd to be associated with this new object type
				if( OpenGraphTypes.object[current_attributes[i][0].value.split( ':' ).join( '_' )] == undefined ) {
					notAllowedLabel += current_attributes[i][0].name + ', ';
					jQuery( '#social_graph_table .attributes_set label[for="' + current_attributes[i][0].value + '"]' ).parent().parent().remove();
					delete current_attributes[i];
				}
			}

			if( notAllowedLabel.length > 0 ) {
				var objectLabel = jQuery( '#object_type option[value="' + objectType + '"]' ).html();
				jQuery( '#social_graph_table tbody' ).prepend(
					'<tr><td class="fberrorbox" colspan="3">The attributes "' + notAllowedLabel.slice( 0, -2 ) + '" have been removed because they are not allowed to be associated with the  "' + objectLabel + '" type.<p><a id="dismiss_message" href="javascript:void();">Dismiss Message</a></p></tr></td>' )
			}
		}

		// If an error message is displayed a dismiss message link will be included, delete it once this link is clicked
		jQuery( '#dismiss_message' ).on( 'click', function() {
			jQuery( '#social_graph_table tbody tr:first' ).remove();
		});

		// Loop through the OpenGraphTypes.object array and generate a default attribute list
		var defaultAttributes = '';
		for( var i in OpenGraphTypes.object ) {
			var attributeName = i.split( '_' ).join( ':' );
			if( attributeName == 'og:see:also' ) attributeName = 'og:see_also';
			if( attributeName == 'fb:profile:id' ) attributeName = 'fb:profile_id';

			var multiples_allowed = ['og:image','og:video','og:audio','og:see_also','article:author','book:author','music:song','music:musician','music:album','video:actor','video:director','video:writer'];

			if( !current_attributes[i] || multiples_allowed.indexOf( attributeName ) > -1 ) {
				if( attributeName == 'og:title' ) {
					defaultAttributes += '<option value="' + attributeName + '" selected="true">' + OpenGraphTypes.object[i] + '</option>';
					continue;
				}

				if( attributeName == 'og:restrictions:country:disallowed' && !jQuery.isEmptyObject( current_attributes.og_restrictions_country_allowed ) ) {
					continue;
				}

				if( attributeName == 'og:restrictions:country:allowed' && !jQuery.isEmptyObject( current_attributes.og_restrictions_country_disallowed ) ) {
					continue;
				}

				defaultAttributes += '<option value="' + attributeName + '">' + OpenGraphTypes.object[i] + '</option>';
			}
		}

		// Reset the attributes drop down to a defaulted state
		jQuery( '#attributes' ).html( defaultAttributes );

		// Reset the input display to a defaulted state
		jQuery( '#display_attribute' ).html( '<input type="text" size="50" value="" name="og:title" />' );
		jQuery( '.description' ).html( labels['og_title'] );

		// Determine what object type was selected and add the appropriate attributes to the display
		for( var i in OpenGraphTypes[objectType.split( '.' ).join( '_' )] ) {
			if( i == 'label' ) continue; // Skip over the label attribute

			// Some attribute names were corrupted when ported over to Javascript, fix those issues now
			var attributeName = i.split( '_' ).join( ':' );
			if( attributeName == 'music:song:track' || attributeName == 'music:track' || attributeName == 'music:song:disc' ) continue;
			attributeName = attributeName.replace( 'street:address', 'street_address' );
			attributeName = attributeName.replace( 'contact:info', 'contact_info' );
			attributeName = attributeName.replace( 'release:date', 'release_date' );
			attributeName = attributeName.replace( 'postal:code', 'postal_code' );
			attributeName = attributeName.replace( 'country:name', 'country_name' );
			attributeName = attributeName.replace( 'phone:number', 'phone_number' );
			attributeName = attributeName.replace( 'fax:number', 'fax_number' );
			attributeName = attributeName.replace( 'first:name', 'first_name' );
			attributeName = attributeName.replace( 'last:name', 'last_name' );
			attributeName = attributeName.replace( 'see:also', 'see_also' );
			attributeName = attributeName.replace( 'profile:id', 'profile_id' );

			// Append these additional attributes to the drop down list
			jQuery( '#attributes' ).append( '<option value="' + attributeName + '">' + OpenGraphTypes[objectType.split( '.' ).join( '_' )][i] + '</option>' );
		}

		// If we have no attributes set make sure the currently set attributes label is removed
		if( jQuery.isEmptyObject( current_attributes ) ) {
			jQuery( '#current_attributes_label' ).remove();
		}

		jQuery( '#attributes' ).trigger( 'change' ); // Triggers a change to the attributes list to update the description and to change the input fields if required
	});

	// When the user selects a new attribute determine what type of form (input, select, textfield, ect...) and description to show
	jQuery( '#attributes' ).on( 'change', function() {
		// Reset the form fields to a defaulted state
		var attribute_value = jQuery( this ).val();

		// Reset the form and description for this new attribute
		jQuery( '.description' ).html( labels[attribute_value.split( ':' ).join( '_' )] );

		// If the image selected was an image, video, determiner, or gender change the input box to the correct html elements
		switch( attribute_value ) {
			case 'og:image':
				jQuery( '#display_attribute' ).html( '<input type="text" size="50" value="" name="' + attribute_value + '[][src]" />' );
				jQuery( '#display_attribute' ).append('<input type="text" size="5" placeholder="' + labels.width + '" value="" name="og:image:width[]" /> x <input type="text" size="5" placeholder="' + labels.height + '" value="" name="og:image:height[]" />');
				break;
			case 'og:video':
				jQuery( '#display_attribute' ).html( '<input type="text" size="50" value="" name="' + attribute_value + '[][src]" />' );
				jQuery( '#display_attribute' ).append('<input type="text" size="5" placeholder="' + labels.width + '" value="" name="og:video:width[]" /> x <input type="text" size="5" placeholder="' + labels.height + '" value="" name="og:video:height[]" />');
				break;
			case 'music:song':
				jQuery( '#display_attribute' ).html( '<input type="text" size="50" value="" name="' + attribute_value + '[][url]" />' );
				if( jQuery( '#object_type').val() != 'music.playlist' ) {
					jQuery( '#display_attribute' ).append('<input type="text" size="5" placeholder="' + labels.disc + ' #" value="" name="music:song:disc[]" />');
				}
				jQuery( '#display_attribute' ).append('<input type="text" size="5" placeholder="' + labels.track + ' #" value="" name="music:song:track[]" />');
				break;
			case 'og:audio':
			case 'og:see_also':
			case 'article:author':
			case 'book:author':
			case 'music:musician':
			case 'music:album':
			case 'video:actor:id':
			case 'video:director':
			case 'video:writer':
				jQuery( '#display_attribute' ).html( '<input type="text" size="50" value="" name="' + attribute_value + '[]" />' );
				break;
			case 'og:determiner':
				jQuery( '#display_attribute' ).html( '<select name="' + attribute_value + '"><option value="a">a</option><option value="an">an</option><option value="the">the</option></select>' );
				break;
			case 'profile:gender':
				jQuery( '#display_attribute' ).html( '<select name="' + attribute_value + '"><option value="female">Female</option><option value="male">Male</option></select>' );
				break;
			default:
				jQuery( '#display_attribute' ).html( '<input type="text" size="50" value="" name="' + attribute_value + '" />' );
				break;
		}

		// If the attribute is in the form of a select box and they change that value, make sure it's saved for future processing
		jQuery( '#display_attribute select' ).on( 'change', function() {
			jQuery( '#display_attribute select option:selected' ).attr( 'selected', 'true' );
		});
	});

	// When the user clicks the add attribute button
	jQuery( '#add_attribute' ).live( 'click', function() {
		var attribute_value = jQuery( '#attributes' ).val(); // Get the value of this attribute
		var attribute_name = jQuery( '#attributes option:selected' ).html(); // Get the name (label) of this attribute

		// If this attribute has not yet been set
		if( !current_attributes[attribute_value.split(':').join('_')] ) {
			current_attributes[attribute_value.split(':').join('_')] = new Array(); // Create an array of this attribute
			current_attributes[attribute_value.split(':').join('_')].push( {name: attribute_name, value:attribute_value} ); // add attributes to it
		}
		else {
			current_attributes[attribute_value.split(':').join('_')].push( {name: attribute_name, value:attribute_value} ); // add attributes to it
		}

		// If the "Current Attributes" row on the table does not exist create it
		if( jQuery( "#current_attributes_label" ).length == 0 ) {
			jQuery( '#social_graph_table tbody' ).append( '<tr id="current_attributes_label"><td><strong>Current Attributes</strong></td></tr>' );
		}

		// For each input element make sure we update the value field so we can process it using JavaScript
		jQuery( '#display_attribute input' ).each( function( index ) {
			jQuery( this ).attr( 'value', jQuery( this ).val() );
		});

		// Select the entire HTML element for this attribute and appened it onto the table as a new row
		var htm = jQuery( '#display_attribute' ).html();
		jQuery( '#social_graph_table tbody' ).append( '<tr class="attributes_set"><td><label for="' + attribute_value + '">' + attribute_name + ':</label></td><td colspan="2">' + htm + ' <a id="delete_attribute" href="javascript:void();">Delete Attribute</a></td></tr>' );

		// The following attributes are allowed to have multiple values
		var multiples_allowed = ['og:image','og:video','og:audio','og:see_also','article:author','book:author','music:song','music:musician','music:album','video:actor:id','video:director','video:writer'];

		// If this attribute is not allowed to have multiple values remove it from the list
		if( multiples_allowed.indexOf( attribute_value ) == -1 ) {
			jQuery( '#attributes option[value="' + attribute_value + '"]' ).remove();
		}

		// If this attribute is the countries allowed attribute remove the countries not allowed attribute
		if( attribute_value == 'og:restrictions:country:allowed' ) {
			jQuery( '#attributes option[value="og:restrictions:country:disallowed"]' ).remove();
		}

		// If this attribute is the countries not allowed attribute remove the countries allowed attribute
		if( attribute_value == 'og:restrictions:country:disallowed' ) {
			jQuery( '#attributes option[value="og:restrictions:country:allowed"]' ).remove();
		}

		jQuery( '#attributes' ).trigger( 'change' ); // Triggers a change to the attributes list to update the description and to change the input fields if required
	});

	jQuery( '#delete_attribute' ).live( 'click', function() {
		var attr = jQuery( this ).parent().parent().find('td:first label').attr('for').split(':').join('_');

		if( current_attributes[attr].length > 1 ) {
			current_attributes[attr].splice(0,1);
		}
		else {
			delete current_attributes[attr];
		}

		if( jQuery.isEmptyObject( current_attributes ) ) {
			jQuery( '#current_attributes_label' ).remove();
		}

		jQuery( this ).parent().parent().remove();
		jQuery( '#object_type' ).trigger( 'change', ['deleted_attr'] ); // Triggers a change to the attributes list to update the description and to change the input fields if required
	});
});