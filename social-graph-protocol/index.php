<?php

	/*

		Plugin Name: Social Graph Protocol

		Plugin URI: http://www.codehooks.com/social-graph-protocol-plugin-for-wordpress/

		Description: Automatically adds Open Graph meta tags to your Wordpress website that enables your content to be shown on a users news feed when someone likes or shares your content. Also enables you to view social statistics on your content via Facebook Insights.

		Author: Adam Losier

		Version: 2.0.6

		Author URI: http://www.codehooks.com/

		License: <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" target="_blank">GPL2</a>

	 	Tags: Facebook, Open Graph, Social Graph, Open Graph Protocol, Social Graph Protocol, CodeHooks, Code Hooks, Social, Social Plugins, Social Media

	*/

	class SocialGraphProtocol {

		/*

		 * Called during the Wordpress initalization and is used to setup any globals and/or actions that might be used throughout the plugin

		 * and merged the load_textdomain function into this one

		 *

		 * Version 2.0.0 Note:

		 * 		- Added this function and moved some actions, filters and globals inside of it

		 * 		- Removed add_namespaces function, no longer need to include the og and fb namespaces in HTML5 markup

		 * 		- Added a version option to be used to make changes based upon this version vs previous versions

		 */

		function __construct() {

			add_filter( 'language_attributes', array( &$this, 'add_namespaces' ) ); // Correctly adds namespaces to the html tag

			add_action( 'admin_init', array( &$this, 'register_settings' ) ); // Registers form settings using the Settings API

			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) ); // Loads some JavaScript that gets used in the plugin menu

			add_action ( 'admin_menu', array( &$this, 'create_menu' ) ); // Creates top level plugin menu in the Wordpress administration panel

			add_action( 'wp_head', array( &$this, 'add_meta_html' ), 1 ); // Adds open graph data to the themes header when wp_head() is called

			add_action( 'add_meta_boxes', array( &$this, 'custom_meta_boxes' ) ); // Creates meta boxes on custom post types, page/post edit screens

			add_action( 'save_post', array( &$this, 'save_meta_box_values' ) ); // Saves the data when a post/page is updated or saved

			register_deactivation_hook( __FILE__, array( &$this, 'plugin_deactivated' ) ); // Removes garbage, keeps your Wordpress install clean



			$this->textdomain = 'social-graph-protocol'; // Global variable to store name of my text domain

			load_plugin_textdomain( $this->textdomain, false, 'social-graph-protocol/i18n' ); // Used to load the textdomain in order to internationalize the plugin



			// Compare this version with a previous version

			$this->version = '2.0.0';

			$this->compare_versions();



			// This next function call creates a translation array that is used to localize one of our Javascript file

			$this->create_translation_array();

			$this->facebook_locales = array( 'af_ZA', 'ar_AR', 'az_AZ', 'be_BY', 'bg_BG', 'bn_IN', 'bs_BA', 'ca_ES', 'cs_CZ', 'cy_GB', 'da_DK', 'de_DE', 'el_GR', 'en_GB', 'en_PI', 'en_UD',
				'en_US', 'eo_EO', 'es_ES', 'es_LA', 'et_EE', 'eu_ES', 'fa_IR', 'fb_LT', 'fi_FI', 'fo_FO', 'fr_CA', 'fr_FR', 'fy_NL', 'ga_IE', 'gl_ES', 'he_IL', 'hi_IN', 'hr_HR', 'hu_HU',
				'hy_AM', 'id_ID', 'is_IS', 'it_IT', 'ja_JP', 'ka_GE', 'km_KH', 'ko_KR', 'ku_TR', 'la_VA', 'lt_LT', 'lv_LV', 'mk_MK', 'ml_IN', 'ms_MY', 'nb_NO', 'ne_NP', 'nl_NL', 'nn_NO',
				'pa_IN', 'pl_PL', 'ps_AF', 'pt_BR', 'pt_PT', 'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sq_AL', 'sr_RS', 'sv_SE', 'sw_KE', 'ta_IN', 'te_IN', 'th_TH', 'tl_PH', 'tr_TR', 'uk_UA',
				'vi_VN', 'zh_CN', 'zh_HK', 'zh_TW' );
		}



		/*

		 * This function is used to compare between the current plugin version vs a possible updated plugin version and its used to perform any update

		 * functions that may be required, things like removing old options or updating old meta data

		 *

		 * Version 2.0.0 Notes:

		 * 		- This check was first introduced in version 2.0.0

		 */

		function compare_versions() {

			$previous_version = get_option( 'sgp_ver' );



			/*

			 * If the previous version doesn't exist or is lower than the current version than this is either the first time they've used the

			 * plugin or the first time they've updated there plugin to/past version the current version

			 */

			if( version_compare( $this->version, $previous_version, '>' ) ) {

				//update_option( 'sgp_ver', $this->version ); // Updates the version option

				delete_option( 'disable_namespaces' ); // This option is no longer needed in this version

				delete_option( 'og:type' ); // This option is no longer needed in this version



				// Return's all post_ids where the post has meta-data added by our plugin

				$post_ids = get_posts( array(

					'posts_per_page' => '-1',

					'meta_key' => 'og:type',

					'post_type' => 'any',

					'post_status' => 'any',

					'fields' => 'ids'

				));



				// Create an empty object and array

				$og = new stdClass();

				$og->arr = array();



				// Loop through all the posts found

				foreach( (array)$post_ids as $post_id ) {

					$post_meta = get_post_meta( $post_id ); // Obtain all meta data related to this post

					$og->arr[$post_id] = array(); // Create an array to store the meta data our plugin has added to this post



					$existingMetaObj = new stdClass();

					$existingMetaObj->type = $post_meta['og:type'][0];

					$existingMetaObj->attributes = array();



					// Loop through all the meta data found for this post

					foreach( (array)$post_meta as $key => $meta ) {

						// If the meta data starts with og: then our plugin has added it

						if( substr( $key, 0, 3 ) == 'og:' ) {

							$og->arr[$post_id][$key] = $meta[0]; // Save this meta data into the array

							// oGraphProtocolMeta



							switch( $key ) {

								case 'og:title':

								case 'og:description':

								case 'og:audio':

									$existingMetaObj->attributes[$key] = $meta[0];

									delete_post_meta( $post_id, $key );

									break;

								case 'og:video':

									$existingMetaObj->attributes[$key] = array( array( 'src' => $meta[0], 'width' => $post_meta['og:video:width'][0], 'height' => $post_meta['og:video:height'][0] ) );

									delete_post_meta( $post_id, $key );

									delete_post_meta( $post_id, 'og:video:height' );

									delete_post_meta( $post_id, 'og:video:width' );

									break;

								case 'og:image':

									$existingMetaObj->attributes[$key] = array( array( 'src' => $meta[0], 'width' => '', 'height' => '' ) );

									delete_post_meta( $post_id, $key );

									break;

								case 'og:upc':

								case 'og:video:type':

								case 'og:audio:title':

								case 'og:audio:artist':

								case 'og:audio:album':

								case 'og:audio:type':

									delete_post_meta( $post_id, $key );

									break;

							}

						}

					}



					// Loop through all current open graph types and remove any tags no longer associated with this type of object

					$this->remove_invalid_tags( $og, $post_id );



					// We need to change some of the og:types to other object types

					switch( $og->arr[$post_id]['og:type'] ) {

						case 'activity':

						case 'band':

						case 'cause':

						case 'country':

						case 'sport':

						case 'sports_league':

						case 'sports_team':

						case 'government':

						case 'state_province':

						case 'drink':

						case 'food':

						case 'game':

						case 'product':

							$existingMetaObj->type = 'article';

							break;

						case 'athlete':

						case 'director':

						case 'musician':

						case 'politician':

						case 'public_figure':

							$existingMetaObj->type = 'profile';

							break;

						case 'bar':

						case 'company':

						case 'cafe':

						case 'hotel':

						case 'restaurant':

						case 'non_profit':

						case 'school':

						case 'university':

							$existingMetaObj->type = 'business.business';



							// If the user has specified an email address property it is no longer allowed to be here so remove it

							if( !empty( $og->arr[$post_id]['og:email'] ) ) {

								delete_post_meta( $post_id, 'og:email' );

							}



							if( !empty( $og->arr[$post_id]['og:street-address'] ) ) {

								$existingMetaObj->attributes['business:contact_info:street_address'] = $og->arr[$post_id]['og:street-address'];

								delete_post_meta( $post_id, 'og:street-address' );

							}



							if( !empty( $og->arr[$post_id]['og:locality'] ) ) {

								$existingMetaObj->attributes['business:contact_info:locality'] = $og->arr[$post_id]['og:locality'];

								delete_post_meta( $post_id, 'og:locality' );

							}



							if( !empty( $og->arr[$post_id]['og:region'] ) ) {

								$existingMetaObj->attributes['business:contact_info:region'] = $og->arr[$post_id]['og:region'];

								delete_post_meta( $post_id, 'og:region' );

							}



							if( !empty( $og->arr[$post_id]['og:postal-code'] ) ) {

								$existingMetaObj->attributes['business:contact_info:postal_code'] = $og->arr[$post_id]['og:postal-code'];

								delete_post_meta( $post_id, 'og:postal-code' );

							}



							if( !empty( $og->arr[$post_id]['og:country-name'] ) ) {

								$existingMetaObj->attributes['business:contact_info:country_name'] = $og->arr[$post_id]['og:country-name'];

								delete_post_meta( $post_id, 'og:country-name' );

							}



							if( !empty( $og->arr[$post_id]['og:phone_number'] ) ) {

								$existingMetaObj->attributes['business:contact_info:phone_number'] = $og->arr[$post_id]['og:phone_number'];

								delete_post_meta( $post_id, 'og:phone_number' );

							}



							if( !empty( $og->arr[$post_id]['og:fax_number'] ) ) {

								$existingMetaObj->attributes['business:contact_info:fax_number'] = $og->arr[$post_id]['og:fax_number'];

								delete_post_meta( $post_id, 'og:fax_number' );

							}



							if( !empty( $og->arr[$post_id]['og:latitude'] ) ) {

								$existingMetaObj->attributes['place:location:latitude'] = $og->arr[$post_id]['og:latitude'];

							}



							if( !empty( $og->arr[$post_id]['og:longitude'] ) ) {

								$existingMetaObj->attributes['place:location:longitude'] = $og->arr[$post_id]['og:longitude'];

							}



							break;

						case 'city':

						case 'landmark':

							$existingMetaObj->type = 'place';



							break;

						case 'book':

							if( !empty( $og->arr[$post_id]['og:isbn'] ) ) {

								$existingMetaObj->attributes['book:isbn'] = $og->arr[$post_id]['og:isbn'];

								delete_post_meta( $post_id, 'og:isbn' );

							}



							break;

					}



					update_post_meta( $post_id, 'oGraphProtocolMeta', $existingMetaObj ); // Create our new post object

					delete_post_meta( $post_id, 'og:type' );

					delete_post_meta( $post_id, 'og:latitude' );

					delete_post_meta( $post_id, 'og:longitude' );

				}

			}

		}



		/*

		 * This function will loop through all posts where our plugin has added meta data too, if it finds meta tags that are no longer allowed

		 * to be associated with a particular type it will remove them

		 *

		 * Version 2.0.0 Notes:

		 * 		- Added this as a clean-up and fix function

		 */

		function remove_invalid_tags( $og, $post_id ) {

			$remove_tags = array(); // Used to avoid



			// Determine the og:type and set the meta tags no longer allowed to be associated with this type

			switch( $og->arr[$post_id]['og:type'] ) {

				case 'article':

				case 'album':

				case 'author':

				case 'movie':

				case 'song':

				case 'tv_show':

				case 'actor':

				case 'activity':

				case 'athlete':

				case 'band':

				case 'cause':

				case 'country':

				case 'sport':

				case 'sports_league':

				case 'sports_team':

				case 'government':

				case 'director':

				case 'musician':

				case 'politician':

				case 'public_figure':

				case 'state_province':

				case 'drink':

				case 'food':

				case 'game':

				case 'product':

					$remove_tags = array( 'og:latitude', 'og:longitude',  'og:street-address', 'og:postal-code', 'og:email', 'og:phone_number', 'og:fax_number', 'og:isbn', 'og:upc',

						'og:locality', 'og:region', 'og:country-name' );

					break;

				case 'book':

					$remove_tags = array( 'og:latitude', 'og:longitude',  'og:street-address', 'og:postal-code', 'og:email', 'og:phone_number', 'og:fax_number', 'og:upc',

						'og:locality', 'og:region', 'og:country-name' );

					break;

				case 'company':

				case 'bar':

				case 'cafe':

				case 'hotel':

				case 'restaurant':

				case 'non_profit':

				case 'school':

				case 'university':

					$remove_tags = array( 'og:isbn', 'og:upc' );

					break;

				case 'city':

				case 'landmark':

					$remove_tags = array( 'og:street-address', 'og:postal-code', 'og:email', 'og:phone_number', 'og:fax_number', 'og:upc', 'og:locality', 'og:region', 'og:country-name' );

					break;

			}



			// Loop through the options that are not allowed to be associated with this type

			foreach( $remove_tags as $remove_tag ) {

				// If we find meta data that is not allowed to be associated with this type, delete it

				if( array_key_exists( $remove_tag, $og->arr[$post_id] ) ) {

					delete_post_meta( $post_id, $remove_tag ); // This will delete the meta data no longer allowed to be associated with this type of object

				}

			}

		}



		/*

		 * Used to register new form settings for the plugins options

		 */

		function register_settings() {

		 	add_settings_section( 'sgp_setting_section', 'Settings For The Social Graph Protocol Plugin', '', 'SocialGraphProtocol' );

			register_setting( 'sgp_group', 'sgp_settings' );

		}

		/*

		 * Adds stylesheets and javascript to pages that utalize this plugin

		 *

		 * Version 2.0.0 Notes:

		 * 		- Added Google fonts (Open Sans and Open Sans Condensed) to plugins admin header

		 * 		- Added a stylesheet for the plugins admin panel

		 * 		- Added thickbox support for uploading site/post image(s)

		 */

		function admin_enqueue_scripts( $hook ) {

			if( 'post.php' == $hook || 'post-new.php' == $hook ) {

				wp_enqueue_script( 'open_graph_collapse', plugins_url( '/static/js/admin.js', __FILE__ ) );

				wp_enqueue_style( 'social_graph_css', plugins_url( '/static/css/main.css', __FILE__ ) );

				wp_localize_script( 'open_graph_collapse', 'labels', $this->translation_array );

			}

			elseif( count( (array)$this->open_graph_hook ) > 0 && in_array( $hook, (array)$this->open_graph_hook ) ) {

				wp_enqueue_style( 'google_fonts', 'http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700,300|Open+Sans:600' );

				wp_enqueue_style( 'social_graph_css', plugins_url( '/static/css/main.css', __FILE__ ) );

				wp_enqueue_style( 'thickbox' ); // Ensure the thickbox styles is included on our plugin page

				wp_enqueue_script( 'thickbox', '', 'jquery' ); // Ensure the thickbox javascript is included on our plugin page as well as jQuery

				wp_enqueue_script( 'open_graph_collapse', plugins_url( '/static/js/thickbox-handler.js', __FILE__ ) ); // Interacts with thickbox to auto-fill image fields

			}

		}



		/*

		 * This function is used for the sole purpose of localizing one of our main javascript files

		 *

		 * Version 2.0.0 Notes:

		 * 		- Added in this version to localize Javascript files

		 */

		function create_translation_array() {

			$this->translation_array = array(

				'article' => __( 'Article', $this->textdomain ),

				'book' => __( 'Book', $this->textdomain ),

				'author' => __( 'Author', $this->textdomain ),

				'release_date' => __( 'Release Date', $this->textdomain ),

				'album' => __( 'Album', $this->textdomain ),

				'tags' => __( 'Tags', $this->textdomain ),

				'song' => __( 'Song', $this->textdomain ),

				'disc' => __( 'Disc', $this->textdomain ),

				'track' => __( 'Track', $this->textdomain ),

				'musician' => __( 'Musician', $this->textdomain ),

				'business' => __( 'Business', $this->textdomain ),

				'street_address' => __( 'Street Address', $this->textdomain ),

				'locality' => __( 'Locality', $this->textdomain ),

				'region' => __( 'Region', $this->textdomain ),

				'postal_code' => __( 'Postal Code', $this->textdomain ),

				'country_name' => __( 'Country Name', $this->textdomain ),

				'phone_number' => __( 'Phone Number', $this->textdomain ),

				'fax_number' => __( 'Fax Number', $this->textdomain ),

				'website' => __( 'Website', $this->textdomain ),

				'latitude' => __( 'Latitude', $this->textdomain ),

				'longitude' => __( 'Longitude', $this->textdomain ),

				'altitude' => __( 'Altitude', $this->textdomain ),

				'movie' => __( 'Movie', $this->textdomain ),

				'actor' => __( 'Actor', $this->textdomain ),

				'director' => __( 'Director', $this->textdomain ),

				'writer' => __( 'Writer', $this->textdomain ),

				'duration' => __( 'Duration', $this->textdomain ),

				'music_playlist' => __( 'Music Playlist', $this->textdomain ),

				'creator' => __( 'Creator', $this->textdomain ),

				'place' => __( 'Place', $this->textdomain ),

				'profile' => __( 'Profile', $this->textdomain ),

				'first_name' => __( 'First Name', $this->textdomain ),

				'last_name' => __( 'Last Name', $this->textdomain ),

				'username' => __( 'Username', $this->textdomain ),

				'gender' => __( 'Gender', $this->textdomain ),

				'series' => __( 'Series', $this->textdomain ),

				'tv_show' => __( 'Tv Show', $this->textdomain ),

				'tv_episode' => __( 'Tv Episode', $this->textdomain ),

				'video' => __( 'Video', $this->textdomain ),

				'title' => __( 'Title', $this->textdomain ),

				'image' => __( 'Image', $this->textdomain ),

				'audio' => __( 'Audio', $this->textdomain ),

				'description' => __( 'Description', $this->textdomain ),

				'determiner' => __( 'Determiner', $this->textdomain ),

				'related_post' => __( 'Related Post', $this->textdomain ),

				'ageRestriction' => __( 'Age Restriction', $this->textdomain ),

				'countriesAllowed' => __( 'Countries Allowed', $this->textdomain ),

				'countriesDisallowed' => __( 'Countries Disallowed', $this->textdomain ),

				'width' => __( 'Width', $this->textdomain ),

				'height' => __( 'Height', $this->textdomain ),

				'og_title' => __( 'The title of your post as it should appear within the graph, e.g., "The Rock". Defaults to your post title.', $this->textdomain ),

				'og_image' => __( 'An image URL which should represent your post within the graph - <a href="http://www.codehooks.com/social-graph-protocol-version-2-0-0-documentation/#imageres" target="_blank">See Image Restrictions</a>', $this->textdomain ),

				'og_video' => __( 'A URL to a video file that complements this post.', $this->textdomain ),

				'og_audio' => __( 'A URL to an audio file to accompany this post.', $this->textdomain ),

				'og_description' => __( 'A one to two sentence description of your post.', $this->textdomain ),

				'og_determiner' => __( 'The word that appears before the post title in a sentence. e.g. "A" Block Of Wood, "An" Animal, "The" Rocks Of Earth', $this->textdomain ),

				'og_see_also' => __( 'Used to supply an additional link that shows related content to the post.', $this->textdomain ),

				'fb_profile_id' => __( 'This ties the user profile on your site to the user profile on Facebook. This can either be the user’s Facebook ID, a third party id, or a Facebook Profile URL.', $this->textdomain ),

				'og_restrictions_age' => __( 'The minimum age a person should be in order to see this post.', $this->textdomain ),

				'og_restrictions_country_allowed' => __( 'A comma seperated list of two character country codes as specified by the ISO 3166 standard. If specified only these countries will be able to share this post', $this->textdomain ),

				'og_restrictions_country_disallowed' => __( 'A comma seperated list of two character country codes as specified by the ISO 3166 standard. If specified these countries will NOT be able to share this post', $this->textdomain ),

				'business_contact_info_street_address' => __( 'A physical real world street address e.g. 1601 Willow Road', $this->textdomain ),

				'business_contact_info_locality' => __( 'A city or municipality name.', $this->textdomain ),

				'business_contact_info_region' => __( 'A state or province.', $this->textdomain ),

				'business_contact_info_postal_code' => __( 'A Zip code, Post code or other postal identifier.', $this->textdomain ),

				'business_contact_info_country_name' => __( 'A valid country or nation.', $this->textdomain ),

				'business_contact_info_phone_number' => __( 'A valid phone number with area code.', $this->textdomain ),

				'business_contact_info_fax_number' => __( 'A valid fax number with area code.', $this->textdomain ),

				'business_contact_info_website' => __( 'A URL of the website.', $this->textdomain ),

				'place_location_latitude' => __( 'Latitude as represented in decimal degrees format.', $this->textdomain ),

				'place_location_longitude' => __( 'Longitude as represented in decimal degrees format.', $this->textdomain ),

				'place_location_altitude' => __( 'Altitude of location in feet.', $this->textdomain ),

				'article_author' => __( 'Link to the authors profile page.', $this->textdomain ),

				'music_song' => __( 'A song on this album. This is a URL of a page with og type music.song. Multiple music:song tags can be specified.', $this->textdomain ),

				'music_song_disc' => __( 'The disc number this song is on within this album [defaults to 1]', $this->textdomain ),

				'music_track' => __( 'The track number of this song on this album [relative to the disc number]', $this->textdomain ),

				'music_musician' => __( 'The artist of this album. This is a URL of a page with og type profile. Multiple music:musician tags can be specified.', $this->textdomain ),

				'music_release_date' => __( 'The date this album was first released, expressed in ISO 8061 format.', $this->textdomain ),

				'book_author' => __( 'An array of references to the objects representing the authors of the book', $this->textdomain ),

				'book_isbn' => __( 'The International Standard Book Number (ISBN) for the book', $this->textdomain ),

				'book_release_date' => __( 'A time representing when the book was released', $this->textdomain ),

				'book_tag' => __( 'Tag words associated with this book.', $this->textdomain ),

				'video_actor_id' => __( 'An array of the Facebook IDs (or references to the profiles) of the actors in the movie', $this->textdomain ),

				'video_director' => __( 'An array of the Facebook IDs (or references to the profiles) of the directors of the movie', $this->textdomain ),

				'video_writer' => __( 'An array of the Facebook IDs (or references to the profiles) of the writers of the movie', $this->textdomain ),

				'video_duration' => __( 'An integer representing the length of the movie in seconds', $this->textdomain ),

				'video_release_date' => __( 'A time representing when the movie was released', $this->textdomain ),

				'video_tag' => __( 'An array of keywords relevant to the movie', $this->textdomain ),

				'music_song_track' => __( 'The track number of this song on this playlist.', $this->textdomain ),

				'music_creator' => __( 'The creator of this playlist. This is the canonical URL of a page with og type profile. Multiple music:creator tags can be specified.', $this->textdomain ),

				'profile_first_name' => __( 'The first name of the person that this profile represents', $this->textdomain ),

				'profile_last_name' => __( 'The last name of the person that this profile represents', $this->textdomain ),

				'profile_username' => __( 'A username for the person that this profile represents', $this->textdomain ),

				'profile_gender' => __( 'The gender (female or male) of the person that this profile represents', $this->textdomain ),

				'music_duration' => __( 'The play time of the song in seconds.', $this->textdomain ),

				'music_album' => __( 'The album which contains this song. This is the URL of a page with og:type music.album. Multiple music:album tags can be specified.', $this->textdomain ),

				'video_series' => __( 'A reference to the object representing the TV show this episode is part of', $this->textdomain ),

			);

		}



		/*

		 * Used to generate a custom admin menu that the user can use to set up some default values

		 *

		 * Version 2.0.0 Notes:

		 * 		- Removed contextual help documentation, more fluid better documentation was written on www.CodeHooks.com

		 */

		function create_menu() {

			// Creates the main administration menu for this plugin

			$this->open_graph_hook[] = add_menu_page( __( 'Social Graph', $this->textdomain ), __ ( 'Social Graph', $this->textdomain ), 'manage_options', 'SocialGraphProtocol',

				array( &$this, 'open_graph_menu' ) );



			$this->open_graph_hook[] = add_submenu_page( 'SocialGraphProtocol', __( 'Settings', $this->textdomain ), __( 'Settings', $this->textdomain ), 'manage_options', 'SocialGraphProtocol',

				array( &$this, 'open_graph_menu' ) );

		}



		/*

		 * Used to display the settings menu for this plugin

		 */

		function open_graph_menu() { include_once( 'includes/general-settings.php' ); }

		/*

		 * This function will save the data specified by the user in a custom meta box that appear on the post/page edit screens

		 */

		function save_meta_box_values( $post_id ) {

			// If this is an auto save routine then the form has not been submitted, so we dont want to do anything

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;



			// Verify this came from the correct screen and with proper authorization

			if ( !wp_verify_nonce( $_POST['sgp_nonce'], plugin_basename( __FILE__ ) ) ) return;

			// Check permissions

			if ( 'page' == $_POST['post_type'] ) {

				if ( !current_user_can( 'edit_page', $post_id ) ) {

			    	return;

				}

			}

			else {

				if ( !current_user_can( 'edit_post', $post_id ) ) {

			    	return;

				}

			}



			// Update the meta data for this post/page

			$openGraphObj = new stdClass();

			$openGraphObj->type = $_POST['og:type'];



			switch( $openGraphObj->type ) {

				case 'article':

					if( is_array( $_POST['article:author'] ) && !empty( $_POST['article:author'] ) ) {

						$openGraphObj->attributes['article:author'] = array();

						foreach( (array)$_POST['article:author'] as $author ) {

							if( empty( $author ) ) continue;

							array_push( $openGraphObj->attributes['article:author'], trim( $author ) );

						}

					}



					break;

				case 'music.album':

					if( is_array( $_POST['music:song'] ) && !empty( $_POST['music:song'] ) ) {

						$openGraphObj->attributes['music:song'] = array();

						foreach( (array)$_POST['music:song'] as $key => $song ) {

							if( empty( $song['url'] ) ) continue;



							$track = ( $_POST['music:song:track'][$key] ) ? $_POST['music:song:track'][$key] : '';

							$disk = ( $_POST['music:song:disc'][$key] ) ? $_POST['music:song:disc'][$key] : '';

							array_push( $openGraphObj->attributes['music:song'], array( 'url' => trim( $song['url'] ), 'track' => trim( $track ), 'disc' => trim( $disk ) ) );

						}

					}



					if( is_array( $_POST['music:musician'] ) && !empty( $_POST['music:musician'] ) ) {

						$openGraphObj->attributes['music:musician'] = array();

						foreach( (array)$_POST['music:musician'] as $musician ) {

							if( empty( $musician ) ) continue;

							array_push( $openGraphObj->attributes['music:musician'], trim( $musician ) );

						}

					}



					if( !empty( $_POST['music:release_date'] ) ) $openGraphObj->attributes['music:release_date'] = trim( $_POST['music:release_date'] );



					break;

				case 'book':

					if( is_array( $_POST['book:author'] ) && !empty( $_POST['book:author'] ) ) {

						$openGraphObj->attributes['book:author'] = array();

						foreach( (array)$_POST['book:author'] as $book_author ) {

							if( empty( $book_author ) ) continue;

							array_push( $openGraphObj->attributes['book:author'], trim( $book_author ) );

						}

					}



					if( !empty( $_POST['book:isbn'] ) ) $openGraphObj->attributes['book:isbn'] = trim( $_POST['book:isbn'] );

					if( !empty( $_POST['book:release_date'] ) ) $openGraphObj->attributes['book:release_date'] = trim( $_POST['book:release_date'] );

					if( !empty( $_POST['book:tag'] ) ) $openGraphObj->attributes['book:tag'] = trim( $_POST['book:tag'] );



					break;

				case 'business.business':

					if( !empty( $_POST['business:contact_info:street_address'] ) ) $openGraphObj->attributes['business:contact_info:street_address'] = trim( $_POST['business:contact_info:street_address'] );

					if( !empty( $_POST['business:contact_info:locality'] ) ) $openGraphObj->attributes['business:contact_info:locality'] = trim( $_POST['business:contact_info:locality'] );

					if( !empty( $_POST['business:contact_info:region'] ) ) $openGraphObj->attributes['business:contact_info:region'] = trim( $_POST['business:contact_info:region'] );

					if( !empty( $_POST['business:contact_info:postal_code'] ) ) $openGraphObj->attributes['business:contact_info:postal_code'] = trim( $_POST['business:contact_info:postal_code'] );

					if( !empty( $_POST['business:contact_info:country_name'] ) ) $openGraphObj->attributes['business:contact_info:country_name'] = trim( $_POST['business:contact_info:country_name'] );

					if( !empty( $_POST['business:contact_info:phone_number'] ) ) $openGraphObj->attributes['business:contact_info:phone_number'] = trim( $_POST['business:contact_info:phone_number'] );

					if( !empty( $_POST['business:contact_info:fax_number'] ) ) $openGraphObj->attributes['business:contact_info:fax_number'] = trim( $_POST['business:contact_info:fax_number'] );

					if( !empty( $_POST['business:contact_info:website'] ) ) $openGraphObj->attributes['business:contact_info:website'] = trim( $_POST['business:contact_info:website'] );

					if( !empty( $_POST['place:location:latitude'] ) ) $openGraphObj->attributes['place:location:latitude'] = trim( $_POST['place:location:latitude'] );

					if( !empty( $_POST['place:location:longitude'] ) ) $openGraphObj->attributes['place:location:longitude'] = trim( $_POST['place:location:longitude'] );

					if( !empty( $_POST['place:location:altitude'] ) ) $openGraphObj->attributes['place:location:altitude'] = trim( $_POST['place:location:altitude'] );



					break;

				case 'video.movie':

					if( is_array( $_POST['video:actor:id'] ) && !empty( $_POST['video:actor:id'] ) ) {

						$openGraphObj->attributes['video:actor:id'] = array();

						foreach( (array)$_POST['video:actor:id'] as $actor ) {

							if( empty( $actor ) ) continue;

							array_push( $openGraphObj->attributes['video:actor:id'], trim( $actor ) );

						}

					}



					if( is_array( $_POST['video:director'] ) && !empty( $_POST['video:director'] ) ) {

						$openGraphObj->attributes['video:director'] = array();

						foreach( (array)$_POST['video:director'] as $director ) {

							if( empty( $director ) ) continue;

							array_push( $openGraphObj->attributes['video:director'], trim( $director ) );

						}

					}



					if( is_array( $_POST['video:writer'] ) && !empty( $_POST['video:writer'] ) ) {

						$openGraphObj->attributes['video:writer'] = array();

						foreach( (array)$_POST['video:writer'] as $writer ) {

							if( empty( $writer ) ) continue;

							array_push( $openGraphObj->attributes['video:writer'], trim( $writer ) );

						}

					}



					if( !empty( $_POST['video:duration'] ) ) $openGraphObj->attributes['video:duration'] = trim( $_POST['video:duration'] );

					if( !empty( $_POST['video:release_date'] ) ) $openGraphObj->attributes['video:release_date'] = trim( $_POST['video:release_date'] );



					$tags = explode( ',', trim( $_POST['video:tag'] ) ); // Comma seperated list, split into array manually

					if( is_array( $tags ) && !empty( $tags ) ) {

						$openGraphObj->attributes['video:tag'] = array();

						foreach( (array)$tags as $tag ) {

							if( empty( $tag ) ) continue;

							array_push( $openGraphObj->attributes['video:tag'], trim( $tag ) );

						}

					}

					break;

				case 'music.playlist':

					if( is_array( $_POST['music:song'] ) && !empty( $_POST['music:song'] ) ) {

						$openGraphObj->attributes['music:song'] = array();

						foreach( (array)$_POST['music:song'] as $key => $song ) {

							if( empty( $song['url'] ) ) continue;

							$track = ( $_POST['music:song:track'][$key] ) ? $_POST['music:song:track'][$key] : '';



							array_push( $openGraphObj->attributes['music:song'], array( 'url' => $song['url'], 'track' => trim( $track ) ) );

						}

					}



					if( !empty( $_POST['music:creator'] ) ) $openGraphObj->attributes['music:creator'] = trim( $_POST['music:creator'] );



					break;

				case 'profile':

					if( !empty( $_POST['profile:first_name'] ) ) $openGraphObj->attributes['profile:first_name'] = trim( $_POST['profile:first_name'] );

					if( !empty( $_POST['profile:last_name'] ) ) $openGraphObj->attributes['profile:last_name'] = trim( $_POST['profile:last_name'] );

					if( !empty( $_POST['profile:username'] ) ) $openGraphObj->attributes['profile:username'] = trim( $_POST['profile:username'] );

					if( !empty( $_POST['profile:gender'] ) ) $openGraphObj->attributes['profile:gender'] = trim( $_POST['profile:gender'] );



					break;

				case 'place':

					if( !empty( $_POST['place:location:latitude'] ) ) $openGraphObj->attributes['place:location:latitude'] = trim( $_POST['place:location:latitude'] );

					if( !empty( $_POST['place:location:longitude'] ) ) $openGraphObj->attributes['place:location:longitude'] = trim( $_POST['place:location:longitude'] );

					if( !empty( $_POST['place:location:altitude'] ) ) $openGraphObj->attributes['place:location:altitude'] = trim( $_POST['place:location:altitude'] );



					break;

				case 'music.song':

					if( !empty( $_POST['music:duration'] ) ) $openGraphObj->attributes['music:duration'] = trim( $_POST['music:duration'] );

					if( !empty( $_POST['music:release_date'] ) ) $openGraphObj->attributes['music:release_date'] = trim( $_POST['music:release_date'] );



					if( is_array( $_POST['music:album'] ) && !empty( $_POST['music:album'] ) ) {

						$openGraphObj->attributes['music:album'] = array();

						foreach( (array)$_POST['music:album'] as $album ) {

							if( empty( $album ) ) continue;

							array_push( $openGraphObj->attributes['music:album'], trim( $album ) );

						}

					}



					if( is_array( $_POST['music:musician'] ) && !empty( $_POST['music:musician'] ) ) {

						$openGraphObj->attributes['music:musician'] = array();

						foreach( (array)$_POST['music:musician'] as $musician ) {

							if( empty( $musician ) ) continue;

							array_push( $openGraphObj->attributes['music:musician'], trim( $musician ) );

						}

					}



					break;

				case 'video.tv_show':

					if( !empty( $_POST['video:release_date'] ) ) $openGraphObj->attributes['video:release_date'] = trim( $_POST['video:release_date'] );

					if( !empty( $_POST['video:duration'] ) ) $openGraphObj->attributes['video:duration'] = trim( $_POST['video:duration'] );



					if( is_array( $_POST['video:actor:id'] ) && !empty( $_POST['video:actor:id'] ) ) {

						$openGraphObj->attributes['video:actor:id'] = array();

						foreach( (array)$_POST['video:actor:id'] as $actor ) {

							if( empty( $actor ) ) continue;

							array_push( $openGraphObj->attributes['video:actor:id'], trim( $actor ) );

						}

					}



					if( is_array( $_POST['video:director'] ) && !empty( $_POST['video:director'] ) ) {

						$openGraphObj->attributes['video:director'] = array();

						foreach( (array)$_POST['video:director'] as $director ) {

							if( empty( $director ) ) continue;

							array_push( $openGraphObj->attributes['video:director'], trim( $director ) );

						}

					}



					if( is_array( $_POST['video:writer'] ) && !empty( $_POST['video:writer'] ) ) {

						$openGraphObj->attributes['video:writer'] = array();

						foreach( (array)$_POST['video:writer'] as $writer ) {

							if( empty( $writer ) ) continue;

							array_push( $openGraphObj->attributes['video:writer'], trim( $writer ) );

						}

					}



					if( !empty( $_POST['video:tag'] ) ) $openGraphObj->attributes['video:tag'] = trim( $_POST['video:tag'] );



					break;

				case 'video.episode':

					if( !empty( $_POST['video:release_date'] ) ) $openGraphObj->attributes['video:release_date'] = trim( $_POST['video:release_date'] );

					if( !empty( $_POST['video:duration'] ) ) $openGraphObj->attributes['video:duration'] = trim( $_POST['video:duration'] );

					if( !empty( $_POST['video:series'] ) ) $openGraphObj->attributes['video:series'] = trim( $_POST['video:series'] );



					if( is_array( $_POST['video:actor:id'] ) && !empty( $_POST['video:actor:id'] ) ) {

						$openGraphObj->attributes['video:actor:id'] = array();

						foreach( (array)$_POST['video:actor:id'] as $actor ) {

							if( empty( $actor ) ) continue;

							array_push( $openGraphObj->attributes['video:actor:id'], trim( $actor ) );

						}

					}



					if( is_array( $_POST['video:director'] ) && !empty( $_POST['video:director'] ) ) {

						$openGraphObj->attributes['video:director'] = array();

						foreach( (array)$_POST['video:director'] as $director ) {

							if( empty( $director ) ) continue;

							array_push( $openGraphObj->attributes['video:director'], trim( $director ) );

						}

					}



					if( is_array( $_POST['video:writer'] ) && !empty( $_POST['video:writer'] ) ) {

						$openGraphObj->attributes['video:writer'] = array();

						foreach( (array)$_POST['video:writer'] as $writer ) {

							if( empty( $writer ) ) continue;

							array_push( $openGraphObj->attributes['video:writer'], trim( $writer ) );

						}

					}



					if( !empty( $_POST['video:tag'] ) ) $openGraphObj->attributes['video:tag'] = trim( $_POST['video:tag'] );



					break;

				case 'video.other':

					if( !empty( $_POST['video:release_date'] ) ) $openGraphObj->attributes['video:release_date'] = trim( $_POST['video:release_date'] );

					if( !empty( $_POST['video:duration'] ) ) $openGraphObj->attributes['video:duration'] = trim( $_POST['video:duration'] );



					if( is_array( $_POST['video:actor:id'] ) && !empty( $_POST['video:actor:id'] ) ) {

						$openGraphObj->attributes['video:actor:id'] = array();

						foreach( (array)$_POST['video:actor:id'] as $actor ) {

							if( empty( $actor ) ) continue;

							array_push( $openGraphObj->attributes['video:actor:id'], trim( $actor ) );

						}

					}



					if( is_array( $_POST['video:director'] ) && !empty( $_POST['video:director'] ) ) {

						$openGraphObj->attributes['video:director'] = array();

						foreach( (array)$_POST['video:director'] as $director ) {

							if( empty( $director ) ) continue;

							array_push( $openGraphObj->attributes['video:director'], trim( $director ) );

						}

					}



					if( is_array( $_POST['video:writer'] ) && !empty( $_POST['video:writer'] ) ) {

						$openGraphObj->attributes['video:writer'] = array();

						foreach( (array)$_POST['video:writer'] as $writer ) {

							if( empty( $writer ) ) continue;

							array_push( $openGraphObj->attributes['video:writer'], trim( $writer ) );

						}

					}



					if( !empty( $_POST['video:tag'] ) ) $openGraphObj->attributes['video:tag'] = trim( $_POST['video:tag'] );



					break;

			}



			if( !empty( $_POST['og:title'] ) ) {

				$openGraphObj->attributes['og:title'] = trim( $_POST['og:title'] );

			}



			if( is_array( $_POST['og:image'] ) && !empty( $_POST['og:image'] ) ) {

				$openGraphObj->attributes['og:image'] = array();

				foreach( (array)$_POST['og:image'] as $key => $image ) {

					if( empty( $image['src'] ) ) continue;



					$width = ( $_POST['og:image:width'][$key] ) ? $_POST['og:image:width'][$key] : '';

					$height = ( $_POST['og:image:height'][$key] ) ? $_POST['og:image:height'][$key] : '';



					array_push( $openGraphObj->attributes['og:image'], array( 'src' => $image['src'], 'width' => $width, 'height' => $height ) );

				}



				if( empty( $openGraphObj->attributes['og:image'] ) ) unset( $openGraphObj->attributes['og:image'] );

			}



			if( is_array( $_POST['og:video'] ) && !empty( $_POST['og:video'] ) ) {

				$openGraphObj->attributes['og:video'] = array();

				foreach( (array)$_POST['og:video'] as $key => $video ) {

					if( empty( $video['src'] ) ) continue;



					$width = ( $_POST['og:video:width'][$key] ) ? $_POST['og:video:width'][$key] : '';

					$height = ( $_POST['og:video:height'][$key] ) ? $_POST['og:video:height'][$key] : '';



					array_push( $openGraphObj->attributes['og:video'], array( 'src' => $video['src'], 'width' => $width, 'height' => $height ) );

				}



				if( empty( $openGraphObj->attributes['og:video'] ) ) unset( $openGraphObj->attributes['og:video'] );

			}



			if( is_array( $_POST['og:audio'] ) && !empty( $_POST['og:audio'] ) ) {

				$openGraphObj->attributes['og:audio'] = array();

				foreach( (array)$_POST['og:audio'] as $audio ) {

					if( empty( $audio ) ) continue;

					array_push( $openGraphObj->attributes['og:audio'], trim( $audio ) );

				}

			}



			if( is_array( $_POST['og:see_also'] ) && !empty( $_POST['og:see_also'] ) ) {

				$openGraphObj->attributes['og:see_also'] = array();

				foreach( (array)$_POST['og:see_also'] as $related_post ) {

					if( empty( $related_post ) ) continue;

					array_push( $openGraphObj->attributes['og:see_also'], trim( $related_post ) );

				}

			}



			if( !empty( $_POST['og:description'] ) ) {

			 	$openGraphObj->attributes['og:description'] = trim( $_POST['og:description'] );

			}



			if( !empty( $_POST['og:determiner'] ) ) $openGraphObj->attributes['og:determiner'] = trim( $_POST['og:determiner'] );

			if( !empty( $_POST['fb:profile_id'] ) ) $openGraphObj->attributes['fb:profile_id'] = trim( $_POST['fb:profile_id'] );

			if( !empty( $_POST['og:restrictions:age'] ) ) $openGraphObj->attributes['og:restrictions:age'] = trim( $_POST['og:restrictions:age'] );



			if( !empty( $_POST['og:restrictions:country:allowed'] ) ) {

				$countries_allowed = explode( ',', trim( $_POST['og:restrictions:country:allowed'] ) ); // Comma seperated list, split into array manually

				if( is_array( $countries_allowed ) && !empty( $countries_allowed ) ) {

					$openGraphObj->attributes['og:restrictions:country:allowed'] = array();

					foreach( (array)$countries_allowed as $allow_country ) {

						if( empty( $allow_country ) ) continue;

						array_push( $openGraphObj->attributes['og:restrictions:country:allowed'], trim( $allow_country ) );

					}

				}

			}



			if( !empty( $_POST['og:restrictions:country:disallowed'] ) ) {

				$countries_disallowed = explode( ',', trim( $_POST['og:restrictions:country:disallowed'] ) ); // Comma seperated list, split into array manually

				if( is_array( $countries_disallowed ) && !empty( $countries_disallowed ) ) {

					$openGraphObj->attributes['og:restrictions:country:disallowed'] = array();

					foreach( (array)$countries_disallowed as $disallow_country ) {

						if( empty( $disallow_country ) ) continue;

						array_push( $openGraphObj->attributes['og:restrictions:country:disallowed'], trim( $disallow_country ) );

					}

				}

			}



			// Update the post with the new meta data

			update_post_meta( $post_id, 'oGraphProtocolMeta', $openGraphObj );



			return;

		}

		/*

		 * This function will add custom meta boxes to the post and page edit screens that will

		 * enable the user to add more customized meta tags to their specific page

		 */

		function custom_meta_boxes() {

			$custom_post_types = get_post_types( array( '_builtin' => false ) ); // An array of custom post types

			add_meta_box(

		        'OpenGraphPlugin',

		        __( 'Social Graph Protocol Options', $this->textdomain ),

		        array( &$this, 'render_meta_box_content' ),

		        'post',

		        'normal',

		        'high'

		    );

		    add_meta_box(

		        'OpenGraphPlugin',

		        __( 'Social Graph Protocol Options', $this->textdomain ),

		        array( &$this, 'render_meta_box_content' ),

		        'page',

		        'normal',

		        'high'

		    );



			// Loop through the custom post types giving each of them a meta box for our plugin

			if( is_array( $custom_post_types ) && !empty( $custom_post_types ) ) {

				foreach( $custom_post_types as $post_type ) {

					$post_type = trim( $post_type );

					add_meta_box(

				        'OpenGraphPlugin',

				        __( 'Social Graph Protocol Options', $this->textdomain ),

				        array( &$this, 'render_meta_box_content' ),

				        $post_type,

				        'normal',

				        'high'

				    );

				}

			}

		}

		function render_meta_box_content( $post ) {

			// Use nonce for verification

			wp_nonce_field( plugin_basename( __FILE__ ), 'sgp_nonce' );

			include_once( ABSPATH . 'wp-content/plugins/social-graph-protocol/includes/meta-box.php' );

			return;

		}

		/*

		 * Adds certain open graph meta data when the theme calls the wp_head function.

		 * Determines which properties should be displayed on this page and ignores invalid and/or empty entries

		 */

		function add_meta_html( $html ) {

			global $post;



			// Removes duplicate og tags added by the WP Facebook Plugin

			if( class_exists( 'Facebook_Open_Graph_Protocol' ) ) {

				remove_action( 'wp_head', array( 'Facebook_Open_Graph_Protocol', 'add_og_protocol' ) );

			}



			// Get general settings

			$general_settings = get_option( 'sgp_settings' ); // Get plugin options

			// Get open graph tags set by the user
			$openGraphTags = get_post_meta( $post->ID, 'oGraphProtocolMeta', true );

			/*
			 *  Make sure that the Wordpress locale is in the list of locale's supported by the open graph
			 *
			 *  List of supported Open Graph locale's can be found at https://www.facebook.com/translations/FacebookLocales.xml
			 */
			$locale = get_locale();
			if( in_array( $locale, $this->facebook_locales ) ) {
				$openGraphTags->attributes['og:locale'] = get_locale();
			}

			$openGraphTags->attributes['og:type'] = $openGraphTags->type;

			// If this is a single or post page

			if( is_single() || is_page() ) {

				// If the user has not added a description generate one based upon the post content

				if( empty( $openGraphTags->attributes['og:description'] ) ) {

					$description = trim( substr( strip_shortcodes( strip_tags( str_ireplace( "\n", '', $post->post_content ) ) ), 0, 150 ) ); // Strip out any shortcodes, html elements and trim the content

					$openGraphTags->attributes['og:description'] = htmlspecialchars( trim( substr( $description, 0, strripos( $description, ' ' ) ) ) ); // Convert any html special characters just in case

				}



				if( !empty( $openGraphTags->attributes['og:restrictions:age'] ) ) {

					$openGraphTags->attributes['og:restrictions:age'] = $openGraphTags->attributes['og:restrictions:age'] . '+';

				}



				// If the user has not added a title use the post title

				if( empty( $openGraphTags->attributes['og:title'] ) ) {

					$openGraphTags->attributes['og:title'] = htmlspecialchars( $post->post_title ); // Set to the title of this post

				}



				// Use default post tags if the user did not specify any

				switch( $openGraphTags->attributes['og:type'] ) {

					case 'book':

					case 'video.episode':

					case 'video.movie':

					case 'video.other':

					case 'video.tv_show':

						$tag_type = ( $openGraphTags->attributes['og:type'] == 'book' ) ? 'book' : 'video';

						if( empty( $openGraphTags->attributes[$tag_type.':tag'] ) ) {

							$tags = get_the_tags( $post->ID );

							$openGraphTags->attributes[$tag_type.':tag'] = array();

							if( !empty( $tags ) && is_array( $tags ) ) {

								foreach( (array)$tags as $tag ) {

									array_push( $openGraphTags->attributes[$tag_type.':tag'], $tag->name );

								}

							}

						}

						else {

							$openGraphTags->attributes[$tag_type.':tag'] = explode( ',', $openGraphTags->attributes[$tag_type.':tag'] );

						}



						break;

					case 'article':

						$tags = get_the_tags( $post->ID );

						$openGraphTags->attributes['article:tag'] = array();

						if( !empty( $tags ) && is_array( $tags ) ) {

							foreach( (array)$tags as $tag ) {

								array_push( $openGraphTags->attributes['article:tag'], $tag->name );

							}

						}



						break;

				}



				$openGraphTags->attributes['og:url'] = get_permalink(); // Set the og:url meta tag to this posts permalink

				// Make sure the og:type attribute has been set if not set it to article

				if( empty( $openGraphTags->attributes['og:type'] ) ) {

					$openGraphTags->attributes['og:type'] = 'article';

				}



				// Make sure the og:image attribute has been set if not set it to the featured image for this post

				if( $this->array_empty( $openGraphTags->attributes['og:image']  ) ) {

					if( function_exists( 'get_post_thumbnail_id' ) ) {

						$img = wp_get_attachment_image_src( get_post_thumbnail_id() );

						$openGraphTags->attributes['og:image'] = array( array( 'src' => $img[0], 'width' => '', 'height' => '' ) ); // Attempt to set the og:image tag to the post thumbnail link

					}

				}



				/*

				 * If we still do not have an og:image tag attempt to use the default value the user can set in the options panel of this plugin

				 *

				 * Version 1.1.4 Note

				 * 		- Consider removing this check in a later version

				 *

				 * Version 1.2.1 Note

				 * 		- Consider adding the option to give multiple og:image elements

				 */

				if( $this->array_empty( $openGraphTags->attributes['og:image'] ) ) {

					$openGraphTags->attributes['og:image'] = array( array( 'src' => $general_settings['image'], 'width' => '', 'height' => '' ) );

				}



				// Set the site name and facebook admins

				$openGraphTags->attributes['og:site_name'] = htmlspecialchars( get_bloginfo( 'name' ) ); // Set the default site name to the wordpress site name

				$openGraphTags->attributes['fb:admins'] = $general_settings['fbuids']; // Get all the admins based upon the users entry

			}

			elseif( is_home() ) {

				// See if the user has created a custom description for there homepage

				$openGraphTags->attributes['og:description'] = $general_settings['description'];

				// If the user has not created a custom description attempt to create one by using the blog description element

				if( empty( $openGraphTags->attributes['og:description'] ) ) {

					$openGraphTags->attributes['og:description'] = htmlspecialchars( get_bloginfo( 'description' ) );

				}

				$openGraphTags->attributes['og:title'] = htmlspecialchars( get_bloginfo( 'name' ) ); // Set the og:title tag to the name of this blog

				$openGraphTags->attributes['og:url'] = get_bloginfo( 'wpurl' ); // Set the og:url tag to the default site url

				// Use the default or user selected type for the og:type tag since this is the root domain, value should be website or blog

				$openGraphTags->attributes['og:type'] = 'website';



				// Attempt to set the og:image tag to the users potential default value

				$openGraphTags->attributes['og:image'] = $general_settings['image'];

				$openGraphTags->attributes['og:site_name'] = htmlspecialchars( get_bloginfo( 'name' ) ); // Set the default site name to the wordpress site name

				$openGraphTags->attributes['fb:admins'] = $general_settings['fbuids']; // Get all the admins based upon the users entry

			}

			// Begin to generate the header output

			$html = "\n<!-- " . __( 'Start Of Social Graph Protocol Meta Data', $this->textdomain ) . " -->\n";

			// Loop through all of the items in the $og object

			$loop = 0;

			foreach( (array)$openGraphTags->attributes as $key => $value ) {

				if( empty( $value ) && !is_array( $value ) ) continue; // If the input value is empty and is not an array



				if( $key == 'fb:admins' ) {

					$value = explode( ',', trim( $value ) );

				}



				// If the meta tag value is not empty but is instead an array then...

				if( is_array( $value ) ) {

					// Loop through each value in the array

					foreach( (array)$value as $property => $content ) {

						if( empty( $content ) && !is_array( $content ) ) continue; // If the $content var is empty and is not an array continue



						// For the value video and audio, this check removes a possible duplicate entry of these items

						if( $key == 'og:image' || $key == 'og:video' ) {

							$src = $content['src'];

							$width = $content['width'];

							$height = $content['height'];



							if( !empty( $src ) ) $html .= "<meta property=\"$key\" content=\"$src\" />\n";

							if( !empty( $width ) ) $html .= "<meta property=\"$key:width\" content=\"$width\" />\n";

							if( !empty( $height ) ) $html .= "<meta property=\"$key:height\" content=\"$height\" />\n";

						}

						elseif( $key == 'music:song' ) {

							$song = $content['url'];

							$track = $content['track'];

							$disc = $content['disc'];



							if( !empty( $song ) ) $html .= "<meta property=\"$key\" content=\"$song\" />\n";

							if( !empty( $disc ) ) $html .= "<meta property=\"$key:disc\" content=\"$disc\" />\n";

							if( !empty( $track ) ) $html .= "<meta property=\"$key:track\" content=\"$track\" />\n";

						}

						elseif( $key == 'og:audio' ) {

							$html .= "<meta property=\"$key\" content=\"$content\" />\n";

							$html .= "<meta property=\"og:audio:type\" content=\"audio/vnd.facebook.bridge\" />\n";

						}

						elseif( $key == 'fb:admins' ) {

							foreach( (array)$content as $admin ) {

								$admin = trim( $admin );

								$html .= "<meta property=\"$key\" content=\"$admin\" />\n";

							}

						}

						else {

							// Generate the HTML meta tag for the open graph

							$html .= "<meta property=\"$key\" content=\"$content\" />\n";

						}

					}



					continue;

				}

				// Generate the HTML meta tag for the open graph

				$html .= "<meta property=\"$key\" content=\"$value\" />\n";

				$loop++;

			}

			$html .= '<!-- ' . __( 'End Of Social Graph Protocol Meta Data', $this->textdomain ) . " -->\n"; // End the HTML output with this little hidden tag

			if( $loop <= 0 ) $html = ''; // If nothing is to be displayed in the HTML don't display the comments

			echo $html; // Add the content to the header of this webpage

		}



		/*

		 * This function is used to add the proper html prefixes (namespaces) to the <head> tag

		 * $data = Any language attributes already created by wordpress or other plugins

		 *

		 * Version 2.0.0 Notes:

		 * 		- Automatically determines if a prefix element exists and adds required namespaces if they are not found

		 * 		// prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#" --- ADD THOSE

		 */

		function add_namespaces( $data ) {

			global $post;



			$openGraphTags = get_post_meta( $post->ID, 'oGraphProtocolMeta', true );



			// Check to see if the xmlns:og namespace has already been added, if not add it

			if( !stristr( $data, 'prefix' ) ) {

				$data .= ' prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#';



				if( is_single() || is_page() ) {

					switch( $openGraphTags->type ) {

						case 'music.song':

						case 'music.album':

						case 'music.playlist':

						case 'music.radio_station':

							$data .= ' music: http://ogp.me/ns/music#';

							break;

						case 'video.movie':

						case 'video.episode':

						case 'video.tv_show':

						case 'video.other':

							$data .= ' video: http://ogp.me/ns/video#';

							break;

						case 'article':

							$data .= ' article: http://ogp.me/ns/article#';

							break;

						case 'book':

							$data .= ' book: http://ogp.me/ns/book#';

							break;

						case 'profile':

							$data .= ' profile: http://ogp.me/ns/profile#';

							break;

						case 'business.business':

							$data .= ' business: http://ogp.me/ns/business# place: http://ogp.me/ns/place#';

							break;

						case 'place':

							$data .= ' place: http://ogp.me/ns/place#';

							break;

					}

				}



				$data .= '"';

			}



			// Return the $data string for further processing or output

			return $data;

		}

		/*

		 * This function gets fired when the user deactivates the plugin, we use this to clean up some options

		 * this plugin has inserted into the options table (basically used for cleanup)

		 *

		 * Version 2.0.0 Notes:

		 * 		- Removed delete_option( 'disable_namespaces' ) option no longer exists

		 * 		- Removed delete_option( 'og:type' ) option no longer exists

		 */

		function plugin_deactivated() {

			delete_option( 'og:image' );

	        delete_option( 'og:description' );

	        delete_option( 'fb:admins' );

		}



		function array_empty( $mixed ) {

		    if( is_array( $mixed ) ) {

		        foreach( (array)$mixed as $value ) {

		            if( !$this->array_empty( $value ) ) {

		                return false;

		            }

		        }

		    }

		    elseif( !empty( $mixed ) ) {

		        return false;

		    }



		    return true;

		}

	}



	/*

	 * Initializing the class like this gives me access to the $this variable and enables me to handle the Social Graphs Object

	 * in a cleaner and more fluid way

	 *

	 * Version 2.0.0 Note:

	 * 		- Added this function to the plugin

	 */

	add_action( 'init', 'init_social_graph_protocol' );

	function init_social_graph_protocol() {

		global $SocialGraphProtocol;



		$SocialGraphProtocol = new SocialGraphProtocol();

	}

?>