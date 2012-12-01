<?php
	/*  
		Plugin Name: Social Graph Protocol
		Plugin URI: http://www.codehooks.com/social-graph-protocol-plugin-for-wordpress/
		Description: The Facebook Open Graph Protocol plugin automatically adds Open Graph meta data to your Wordpress site to enable Facebook page insights as well as to enable your site to be searched inside of Facebook as well as gives you the ability to provide a more interactive and applealing view to the users who like your content.
		Author: Adam Losier
		Version: 1.2.2
		Author URI: http://www.codehooks.com/
		License: <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" target="_blank">GPL2</a>
	*/
	
	add_action( 'admin_init', array( 'SocialGraphProtocol', 'register_settings' ) ); // Called during admin initialize
	add_action( 'init', array( 'SocialGraphProtocol', 'load_textdomain' ) );
	add_action( 'wp_head', array( 'SocialGraphProtocol', 'add_meta_html' ), 1 );
	add_action( 'add_meta_boxes', array( 'SocialGraphProtocol', 'custom_meta_boxes' ) );
	add_action( 'save_post', array( 'SocialGraphProtocol', 'save_meta_box_values' ) );
	add_action ( 'admin_menu', array( 'SocialGraphProtocol', 'create_menu' ) );
	add_action( 'admin_enqueue_scripts', array( 'SocialGraphProtocol', 'admin_enqueue_scripts' ) );
	add_filter( 'language_attributes', array( 'SocialGraphProtocol', 'add_namespaces' ) );
	register_deactivation_hook( __FILE__, array( 'SocialGraphProtocol', 'plugin_deactivated' ) );
	
	$textdomain = 'social-graph-protocol'; // Global variable to store name of my text domain

	class SocialGraphProtocol {
		// Used to register new form settings for the general options
		function register_settings() {
		 	add_settings_section( 'sgp_setting_section', 'Settings For The Social Graph Protocol Plugin', '', 'SocialGraphProtocol' );
		 	register_setting( 'sgp_group', 'sgp_settings' );
		} 

		/*
		 * This function is used to load the textdomain in order to internationalize the plugin
		 * Removed Temporarily in 1.1.4
		 */
		function load_textdomain() {
			global $textdomain;
			
			load_plugin_textdomain( $textdomain, false, 'social-graph-protocol/i18n' );
		}
		
		/*
		 * This function adds information to the contextual help dropdown inside of wordpress, this makes sure we only add our
		 * specific help documentation to our plugin page and not the page for other plugins or built in features.
		 */
		function add_help_documentation() {
			global $open_graph_hook, $textdomain;
			
			$screen = get_current_screen();
			if ($screen->id != $open_graph_hook) {
				return;
			}
			
			$screen->add_help_tab( array(
				'id'      => 'site-description',
				'title'   => __('Site Description', $textdomain),
				'content' => __( '<p>Add a short one or two sentence description of your website in general. Keep this short and to the point, I would suggest keeping it under 150 characters, basically no longer than a Tweet. This is an optional setting, it\'s not required because if it\'s missing the plugin will automatically fill it in with your websites Tagline located in the Settings of your WordPress administration panel.</p>', $textdomain),
			));
			
			$screen->add_help_tab( array(
				'id'      => 'site-image',
				'title'   => __('Site Image', $textdomain),
				'content' => __('<p>This is an optional feature that takes the URL to an image of your site, it\'s a good thing to have because it provides people looking at it on Facebook a visual representation of your website, you can create a screenshot of your site using Photoshop or some other editing program and then upload it to WordPress using the Media tab. However the image MUST be at least 50 px by 50 px (actually a minimum of 200 px by 200 px is preferred), have a maximum aspect ratio of 3:1 and can only be a PNG, JPEG or GIF.</p>', $textdomain),
			));
			
			$screen->add_help_tab( array(
				'id'      => 'facebook-uid',
				'title'   => __('Faceboook UID(s)', $textdomain),
				'content' => __('<p>This requires a comma-separated list of either Facebook user ids and/or Facebook platform application ids (Only use the ids of people or applications you trust to administer your websites content). Obtaining your Facebook user id requires you to visit the Facebook Insights page (See Link Below) and to click the green Insights for your Website button that will then generate a popup window containing some html. The number right after the word content is the number you\'ll want to use for this field.</p>', $textdomain) . 
				'<p><a href="http://www.facebook.com/insights/" target="_blank">Facebook Insights</a></p>',
			));
			
			$screen->add_help_tab( array(
				'id'      => 'disable-namespaces',
				'title'   => __('Disabling Namespaces', $textdomain),
				'content' => __('<p>In order for this to work our software needs to add code to your websites html tag, sometimes people will hard code the xmlns:og and xmlns:fb namespaces into your websites theme. To determine if these entries already exist simply visit your website, right click your mouse and select view source. Near the top of this document find the tag that starts with  &lt;html if this tag contains the words xmlns:og and xmlns:fb then you can put a check-mark in this box (P.S. Do this before you activate the plugin). If it does not then you can leave this field unchecked and the software will automatically add the required tags to your theme.</p>', $textdomain),
			));
		}
		
		// Simply adds a custom Facebook Open Graph stylesheet to the admin header of our plugin	
		function admin_enqueue_scripts( $hook ) {
			if( 'post.php' == $hook || 'post-new.php' == $hook ) {
				wp_enqueue_script( 'open_graph_collapse', plugins_url( '/js/collapse.js', __FILE__ ) );
				wp_enqueue_style( 'open_graph_collapse', plugins_url( '/open-graph.css', __FILE__ ) );
			}
		}
		
		// This function is used to generate a custom menu that the user can use to set up some default values
		function create_menu() {
			global $open_graph_hook, $textdomain;
			
			// Adds the main menu for this plugin to the Wordpress Admin Panel
			$open_graph_hook = add_menu_page ( __( 'Social Graph', $textdomain ), __ ( 'Social Graph', $textdomain ), 'manage_options', 'SocialGraphProtocol', 
				array( 'SocialGraphProtocol', 'open_graph_menu' ) );
			
			// Add a sub-menu called Add Videos
			add_submenu_page( __FILE__, __( 'Settings', $textdomain ), __( 'Settings', $textdomain ), 'manage_options', 'SocialGraphProtocol', 
				array( 'SocialGraphProtocol', 'open_graph_menu' ) );
			
			// Add help documentation for this page
			add_action( 'load-' . $open_graph_hook, array( 'SocialGraphProtocol', 'add_help_documentation' ) );
		}
		
		/*
		 * This function is used to display the settings menu for this plugin, it's also used to catch and 
		 * deal with saving the form data on that page
		 */
		function open_graph_menu() {
			global $textdomain;

			include_once( 'includes/open-graph-menu.php' );
		}

		/*
		 * This function will save the data specified by the user in our custom meta boxes
		 * that appear on the post and page edit screens
		 */	
		function save_meta_box_values( $post_id ) {
			// If this is an auto save routine then the form has not been submitted, so we dont want to do anything
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			
			// Verify this came from the correct screen and with proper authorization
			if ( !wp_verify_nonce( $_POST['sgp_nonce'], plugin_basename( __FILE__ ) ) ) {
		    	return;
			}
			
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
			
			// Add the meta data to this post/page
			if( !empty( $_POST['og_type'] ) ) update_post_meta( $post_id, 'og:type', htmlspecialchars( trim( $_POST['og_type'] ) ) ); else delete_post_meta( $post_id, 'og:type' );
			if( !empty( $_POST['og_title'] ) ) update_post_meta( $post_id, 'og:title', htmlspecialchars( trim( $_POST['og_title'] ) ) ); else delete_post_meta( $post_id, 'og:title' );		
			if( !empty( $_POST['og_description'] ) ) update_post_meta( $post_id, 'og:description', htmlspecialchars( trim( $_POST['og_description'] ) ) ); else delete_post_meta( $post_id, 'og:description' );
			if( !empty( $_POST['og_image'] ) ) update_post_meta( $post_id, 'og:image', htmlspecialchars( trim( $_POST['og_image'] ) ) ); else delete_post_meta( $post_id, 'og:image' );
			if( !empty( $_POST['og_latitude'] ) ) update_post_meta( $post_id, 'og:latitude', htmlspecialchars( trim( $_POST['og_latitude'] ) ) ); else delete_post_meta( $post_id, 'og:latitude' );
			if( !empty( $_POST['og_longitude'] ) ) update_post_meta( $post_id, 'og:longitude', htmlspecialchars( trim( $_POST['og_longitude'] ) ) ); else delete_post_meta( $post_id, 'og:longitude' );
			if( !empty( $_POST['og_street_address'] ) ) update_post_meta( $post_id, 'og:street-address', htmlspecialchars( trim( $_POST['og_street_address'] ) ) ); else delete_post_meta( $post_id, 'og:street-address' );
			if( !empty( $_POST['og_locality'] ) ) update_post_meta( $post_id, 'og:locality', htmlspecialchars( trim( $_POST['og_locality'] ) ) ); else delete_post_meta( $post_id, 'og:locality' );
			if( !empty( $_POST['og_region'] ) ) update_post_meta( $post_id, 'og:region', htmlspecialchars( trim( $_POST['og_region'] ) ) ); else delete_post_meta( $post_id, 'og:region' );
			if( !empty( $_POST['og_postal_code'] ) ) update_post_meta( $post_id, 'og:postal-code', htmlspecialchars( trim( $_POST['og_postal_code'] ) ) ); else delete_post_meta( $post_id, 'og:postal-code' );
			if( !empty( $_POST['og_country_name'] ) ) update_post_meta( $post_id, 'og:country-name', htmlspecialchars( trim( $_POST['og_country_name'] ) ) ); else delete_post_meta( $post_id, 'og:country-name' );
			if( !empty( $_POST['og_email'] ) ) update_post_meta( $post_id, 'og:email', htmlspecialchars( trim( $_POST['og_email'] ) ) ); else delete_post_meta( $post_id, 'og:email' );
			if( !empty( $_POST['og_phone_number'] ) ) update_post_meta( $post_id, 'og:phone_number', htmlspecialchars( trim( $_POST['og_phone_number'] ) ) ); else delete_post_meta( $post_id, 'og:phone_number' );
			if( !empty( $_POST['og_fax_number'] ) ) update_post_meta( $post_id, 'og:fax_number', htmlspecialchars( trim( $_POST['og_fax_number'] ) ) ); else delete_post_meta( $post_id, 'og:fax_number' );
			if( !empty( $_POST['og_isbn'] ) ) update_post_meta( $post_id, 'og:isbn', htmlspecialchars( trim( $_POST['og_isbn'] ) ) ); else delete_post_meta( $post_id, 'og:isbn' );
			if( !empty( $_POST['og_upc'] ) ) update_post_meta( $post_id, 'og:upc', htmlspecialchars( trim( $_POST['og_upc'] ) ) ); else delete_post_meta( $post_id, 'og:upc' );
			
			if( !empty( $_POST['og_video'] ) ) {
				update_post_meta( $post_id, 'og:video', htmlspecialchars( trim( $_POST['og_video'] ) ) ); 
				if( !empty( $_POST['og_video_height'] ) ) update_post_meta( $post_id, 'og:video:height', htmlspecialchars( trim( $_POST['og_video_height'] ) ) ); else delete_post_meta( $post_id, 'og:video:height' );
				if( !empty( $_POST['og_video_width'] ) ) update_post_meta( $post_id, 'og:video:width', htmlspecialchars( trim( $_POST['og_video_width'] ) ) ); else delete_post_meta( $post_id, 'og:video:width' );
				if( !empty( $_POST['og_video_type'] ) ) update_post_meta( $post_id, 'og:video:type', htmlspecialchars( trim( $_POST['og_video_type'] ) ) ); else delete_post_meta( $post_id, 'og:video:type' );
			}
			else {
				delete_post_meta( $post_id, 'og:video' );
				delete_post_meta( $post_id, 'og:video:height' );
				delete_post_meta( $post_id, 'og:video:width' );
				delete_post_meta( $post_id, 'og:video:type' );
			}
			
			if( !empty( $_POST['og_audio']) ) {
				update_post_meta( $post_id, 'og:audio', htmlspecialchars( trim( $_POST['og_audio'] ) ) ); 
				if( !empty( $_POST['og_audio_title'] ) ) update_post_meta( $post_id, 'og:audio:title', htmlspecialchars( trim( $_POST['og_audio_title'] ) ) ); else delete_post_meta( $post_id, 'og:audio:title' );
				if( !empty( $_POST['og_audio_artist'] ) ) update_post_meta( $post_id, 'og:audio:artist', htmlspecialchars( trim( $_POST['og_audio_artist'] ) ) ); else delete_post_meta( $post_id, 'og:audio:artist' );
				if( !empty( $_POST['og_audio_album'] ) ) update_post_meta( $post_id, 'og:audio:album', htmlspecialchars( trim( $_POST['og_audio_album'] ) ) ); else delete_post_meta( $post_id, 'og:audio:album' );
				if( !empty( $_POST['og_audio_type'] ) ) update_post_meta( $post_id, 'og:audio:type', htmlspecialchars( trim( $_POST['og_audio_type'] ) ) ); else delete_post_meta( $post_id, 'og:audio:type' );
			}
			else {
				delete_post_meta( $post_id, 'og:audio' );
				delete_post_meta( $post_id, 'og:audio:title' );
				delete_post_meta( $post_id, 'og:audio:artist' );
				delete_post_meta( $post_id, 'og:audio:album' );
				delete_post_meta( $post_id, 'og:audio:type' );
			}

			return;
		}
		
		/*
		 * This function will add custom meta boxes to the post and page edit screens that will
		 * enable the user to add more customized meta tags to their specific page
		 */	
		function custom_meta_boxes() {
			global $textdomain;
			
			$custom_post_types = get_post_types( array( '_builtin' => false ) ); // An array of custom post types
			
			add_meta_box( 
		        'OpenGraphPlugin',
		        __( 'Social Graph Protocol Options', $textdomain ),
		        array( 'SocialGraphProtocol', 'render_meta_box_content' ),
		        'post',
		        'normal',
		        'high'
		    );
		    add_meta_box(
		        'OpenGraphPlugin',
		        __( 'Social Graph Protocol Options', $textdomain ),
		        array( 'SocialGraphProtocol', 'render_meta_box_content' ),
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
				        __( 'Social Graph Protocol Options', $textdomain ),
				        array( 'SocialGraphProtocol', 'render_meta_box_content' ),
				        $post_type,
				        'normal',
				        'high'
				    );
				}
			}
		}
		
		function render_meta_box_content( $post ) {
			global $textdomain;
			
			// Use nonce for verification
			wp_nonce_field( plugin_basename( __FILE__ ), 'sgp_nonce' );
			
			include_once( ABSPATH . 'wp-content/plugins/social-graph-protocol/includes/meta-box.php' );
		
			return;
		}
		
		/*
		 * Adds certain open graph meta data when the theme calls the wp_head function.
		 * Determines which properties should be displayed on this page and ignores invalid and/or empty entries
		 */
		function add_meta_html() {
			global $post, $textdomain;
			
			// Removes duplicate og tags added by the WP Facebook Plugin
			if( class_exists( 'Facebook_Open_Graph_Protocol' ) ) {
				remove_action( 'wp_head', array( 'Facebook_Open_Graph_Protocol', 'add_og_protocol' ) );
			}

			// Get general settings
			$general_settings = get_option( 'sgp_settings' ); // Get plugin options
			
			// Create an empty $og object to store meta tags with
			$og = new stdClass();
			
			// If this is a single or post page
			if( is_single() || is_page() ) {
				// See if the user has created a custom description element
				$og->description = get_post_meta( $post->ID, 'og:description', true );
				
				// If there is no current description element, attempt to create one based upon the post content
				if( empty( $og->description ) ) {
					$description = trim( substr( strip_shortcodes( strip_tags( str_ireplace("\n", '', $post->post_content ) ) ), 0, 150 ) ); // Strip out any shortcodes, html elements and trim the content
					$og->description = htmlspecialchars( trim( substr( $description, 0, strripos( $description, ' ' ) ) ) ); // Convert any html special characters just in case
				}
				
				$og->title = get_post_meta( $post->ID, 'og:title', true );
				if( empty( $og->title ) ) {
					$og->title = htmlspecialchars( $post->post_title ); // Set the og:title to the title of this post
				}
				
				$og->url = get_permalink(); // Set the og:url meta tag to this posts permalink
				
				// See if the user has created a custom type for this specific blog post
				$og->type = get_post_meta( $post->ID, 'og:type', true );
				
				// If the user has not created a custom type for this specific post
				if( empty( $og->type ) ) {
					$og->type = 'article'; // Use the value article for the og:type since that is probably what it will be
				}
				
				// Determine if the user has created some custom thumbnails for the og:image paramater
				$og->image = get_post_meta( $post->ID, 'og:image', true );
				
				// If the user has not added a custom thumbnail for the og:image tag
				if( empty( $og->image ) ) {
					if( function_exists( 'get_post_thumbnail_id' ) ) {
						$img = wp_get_attachment_image_src( get_post_thumbnail_id() );
						$og->image = $img[0]; // Attempt to set the og:image tag to the post thumbnail link
					}
				}
				
				/*
				 * If we still do not have a $og:image value attempt to use the default value the user can set in the options panel of this plugin
				 * 
				 * Version 1.1.4 Note
				 * 		- Consider removing this check in a later version
				 * 
				 * Version 1.2.1 Note
				 * 		- Consider adding the option to give multiple og:image elements
				 */
				if( empty( $og->image ) ) {
					$og->image = $general_settings['image']; // Attempt to set the og:image tag to the post thumbnail link
				}
				
				// Attempt to get these next open graph values from the custom field values created by the user, if they are not set, they don't get added
				$og->email = get_post_meta( $post->ID, 'og:email', true );
				$og->phone_number = get_post_meta( $post->ID, 'og:phone-number', true );
				$og->fax_number = get_post_meta( $post->ID, 'og:fax-number', true );
				$og->video = array( 'video' => get_post_meta( $post->ID, 'og:video', true ), ':width' => get_post_meta( $post->ID, 'og:video:width', true ),
					':height' => get_post_meta( $post->ID, 'og:video:height', true ), ':type' => get_post_meta( $post->ID, 'og:video:type', true ) );
				$og->audio = array( 'audio' => get_post_meta( $post->ID, 'og:audio', true ), ':title' => get_post_meta( $post->ID, 'og:audio:title', true ), 
					':artist' => get_post_meta( $post->ID, 'og:audio:artist', true ), ':album' => get_post_meta( $post->ID, 'og:audio:album', true ), 
					':type' => get_post_meta( $post->ID, 'og:audio:type', true ) );
				$og->latitude = get_post_meta( $post->ID, 'og:latitude', true );
				$og->longitude = get_post_meta( $post->ID, 'og:longitude', true );
				$og->street_address = get_post_meta( $post->ID, 'og:street-address', true );
				$og->locality = get_post_meta( $post->ID, 'og:locality', true );
				$og->region = get_post_meta( $post->ID, 'og:region', true );
				$og->postal_code = get_post_meta( $post->ID, 'og:postal-code', true );
				$og->country_name = get_post_meta( $post->ID, 'og:country-name', true );
				$og->isbn = get_post_meta( $post->ID, 'og:isbn', true );
				$og->upc = get_post_meta( $post->ID, 'og:upc', true );
				
				$og->site_name = htmlspecialchars( get_bloginfo( 'name' ) ); // Set the default site name to the wordpress site name
				$og->admins = $general_settings['fbuids']; // Get all the admins based upon the users entry
			}
			elseif( is_home() ) {
				// See if the user has created a custom description for there homepage
				$og->description = $general_settings['description'];
				
				// If the user has not created a custom description attempt to create one by using the blog description element
				if( empty( $og->description ) ) {
					$og->description = htmlspecialchars( get_bloginfo( 'description' ) );
				}
				
				$og->title = htmlspecialchars( get_bloginfo( 'name' ) ); // Set the og:title tag to the name of this blog
				$og->url = get_bloginfo( 'wpurl' ); // Set the og:url tag to the default site url
				
				// Use the default or user selected type for the og:type tag since this is the root domain, value should be website or blog
				$og->type = 'blog';
				
				// Attempt to set the og:image tag to the users potential default value
				$og->image = $general_settings['image'];
				
				$og->site_name = htmlspecialchars( get_bloginfo( 'name' ) ); // Set the default site name to the wordpress site name
				$og->admins = $general_settings['fbuids']; // Get all the admins based upon the users entry
			}

			// Begin to generate the header output
			$html = "\n<!-- " . __( 'Start Of Facebook Open Graph Meta Data', $textdomain ) . " -->\n";
			
			// Loop through all of the items in the $og object
			$loop = 0;
			foreach( $og as $key => $value ) {
				if( empty( $value ) ) continue; // If the input value is empty continue
				
				if( $key == 'admins' ) {
					$html .= "<meta property=\"fb:$key\" content=\"$value\" />\n";
					continue;
				}
				
				// If the meta tag value is not empty but is instead an array then...
				if( is_array( $value ) ) {
					// Loop through each value in the array
					foreach( $value as $property => $content ) {
						if( empty( $content ) ) continue; // If the value is empty skip it
						
						// For the value video and audio, this check removes a possible duplicate entry of these items
						if( $key != $property ) {
							$property = $key . $property;
						}
						
						// Generate the HTML meta tag for the open graph
						$html .= "<meta property=\"og:$property\" content=\"$content\" />\n";
					}
					continue;
				}
				
				// Generate the HTML meta tag for the open graph
				$html .= "<meta property=\"og:$key\" content=\"$value\" />\n";
				$loop++;
			}

			$html .= '<!-- ' . __( 'End Of Facebook Open Graph Meta Data', $textdomain ) . " -->\n"; // End the HTML output with this little hidden tag
			if( $loop <= 0 ) $html = ''; // If nothing is to be displayed in the HTML don't display the comments
			
			echo $html; // Add the content to the header of this webpage
		}

		/*
		 * This function is used to add the proper xmlns:og and xmlns:fb namespaces to the <head> tag
		 * $data = Any language attributes already created by wordpress or other plugins
		 */
		function add_namespaces( $data ) {
			$options = get_option( 'sgp_settings' ); // Get plugin options
			
			// If the user has choosen to disable namespaces then don't include them
			if( $options['namespaces'] == 1 ) return $data; // Do not include namespaces
			
			// Check to see if the xmlns:og namespace has already been added, if not add it
			if( !stristr( $data, 'xmlns:og' ) ) {
				$data .= ' xmlns:og="http://ogp.me/ns#"';
			}
			
			// Check to see if the xmlns:fb namespace has already been added, if not add it
			if( !stristr( $data, 'xmlns:fb' ) ) {
				$data .= ' xmlns:fb="http://www.facebook.com/2008/fbml"';
			}
			
			// Return the $data string for further processing or output
			return $data;
		}

		/*
		 * This function gets fired when the user deactivates the plugin, we use this to clean up some options
		 * this plugin has inserted into the options table (basically used for cleanup)
		 */	
		function plugin_deactivated() {
			delete_option( 'og:image' );
	        delete_option( 'og:description' );
	        delete_option( 'fb:admins' );
	        delete_option( 'og:type' );
	        delete_option( 'disable_namespaces' );
		}
	}
?>