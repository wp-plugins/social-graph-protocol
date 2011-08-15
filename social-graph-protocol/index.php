<?php
	/*  
		Plugin Name: Social Graph Protocol
		Plugin URI: http://www.bizzylabs.com/facebook-open-graph-plugin/
		Description: The Facebook Open Graph Protocol plugin automatically adds Open Graph meta data to your Wordpress site to enable Facebook page insights as well as to enable your site to be searched inside of Facebook as well as gives you the ability to provide a more interactive and applealing view to the users who like your content.
		Author: bizzyLabs
		Version: 1.0.2
		Author URI: http://www.bizzylabs.com/
		License: <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" target="_blank">GPL2</a>
	*/
	add_action( 'wp_head', array( 'fbOpenGraphByBizzyLabs', 'add_meta_html' ) );
	add_action( 'add_meta_boxes', array( 'fbOpenGraphByBizzyLabs', 'custom_meta_boxes' ) );
	add_action( 'save_post', array( 'fbOpenGraphByBizzyLabs', 'save_meta_box_values' ) );
	add_action ( 'admin_menu', array( 'fbOpenGraphByBizzyLabs', 'create_menu' ) );
	add_action( 'admin_enqueue_scripts', array( 'fbOpenGraphByBizzyLabs', 'add_stylesheet' ) );
	add_filter( 'contextual_help', array( 'fbOpenGraphByBizzyLabs', 'add_help_documentation' ), 10, 3 );
	add_filter( 'language_attributes', array( 'fbOpenGraphByBizzyLabs', 'add_namespaces' ) );
	register_deactivation_hook( __FILE__, array( 'fbOpenGraphByBizzyLabs', 'plugin_deactivated' ) );
	
	class fbOpenGraphByBizzyLabs {
		/*
		 * This function adds information to the contextual help dropdown inside of wordpress, this makes sure we only add our
		 * specific help documentation to our plugin page and not the page for other plugins or built in features.
		 */
		function add_help_documentation( $contextual_help, $screen_id, $screen ) {
			global $open_graph_hook;
			
			if( $screen_id == $open_graph_hook ) {
				$contextual_help = <<<EOD
					<p style="line-height:20px;font-size:13px;">The Facebook Open Graph plugin contains four settings that should be set in order to utilize the full potential of the plugin. We 
					ask you to set these values because they are the values that are going to be used for your homepage, these values will <span style="border-bottom: 3px double;">NOT</span> 
					carry over to individual Wordpress posts or pages, they are strictly for the homepage of your website, you'll be able to set the values of each post or page in the 
					create/edit post/page screens.</p>
					
					<p style="line-height:20px;font-size:13px;">The first option to set is pretty straight forward, its a one to two sentence description of your overall website and its limited 
					to 255 characters. You can enter anything you want, however we highly suggest you write something meaningful about your website. Additionally if you leave this value blank, 
					it will attempt to default to your websites Tagline specified under the Wordpress Settings menu. If the Tagline value is not set and you do not provide a description, then 
					this meta tag will not be generated in your html.</p>
					
					<p style="line-height:20px;font-size:13px;">The second option asks you to provide a URL to a screenshot of your website, as an example if I wanted to use the bizzyLabs logo 
					as the screenshot I would insert the text http://www.bizzylabs.com/wp-content/uploads/2011/08/logo.png. One thing to note is that the image must be at least 50px by 50px 
					and have a maximum aspect ratio of 3:1. Facebook supports PNG, JPEG and GIF formats only.</p>
					
					<p style="line-height:20px;font-size:13px;">The third option asks you to enter your Facebook user id or a comma-separated list of either user IDs and/or Facebook platform 
					application IDs that will be used to administer your page. It is valid to include both id types. Basically only list the IDs of people that you want to give admin rights 
					to, if this is only you then only enter in your Facebook UID and no one elses. Now if you have absolutely no clue how to obtain your Facebook UID, you can svisit the 
					<a href="http://www.facebook.com/insights/" title="Facebook Insights" target="_blank">Facebook Insights</a> page, click the green Insights for your website button near the 
					upper right, select your name from the "Link With" drop down and just below that you'll notice a meta tag simply copy that meta tag it will look something like 
					&lt;meta property="fb:admins" content="<span style="background-color:#fffc00;">655660025</span>" /&gt; where the highlighted part is going to be your Facebook UID that 
					you include on the settings page.</p>
					
					<p>The next option is where you enter in a comma seperated list of custom post type slugs, for instnace lets say you create three custom post types called Movies, Restaurants,
					and Podcasts. In this option you would list the slugs of these which should be movies, restaurants, podcasts. In the future as more features are released we intend to make
					the integration with custom post types a little easier.</p>
					
					<p style="line-height:20px;font-size:13px;">Finally the very last option is a simple drop down menu with two options, one option is called Blog and the second option is called
					website. If you think your website is more of a blog then choose blog, but if you think your website is more of a traditional website then select website. The difference is
					minor and a large majority of Wordpress users will select blog. The difference is that a Website is considered to be more static content as where a blog your continually
					writing new content making it more dynamic. Overall its a very stupid feature in the Facebook Open Graph and I'm sure they'll end up changing it or clarifying the difference
					a bit more.</p> 
EOD;
			}

			return $contextual_help;
		}
		
		// Simply adds a custom Facebook Open Graph stylesheet to the admin header of our plugin	
		function add_stylesheet( $hook ) {
			// If this is not our plugin page there is no need to include the css file
			if( 'toplevel_page_social-graph-protocol/index' != $hook ) return;
			
		    echo "<link rel='stylesheet' href='" . WP_PLUGIN_URL . "/social-graph-protocol/css/open-graph-menu.css' type='text/css' media='all' />";
		}
		
		/*
		 * This function is used to generate a custom menu that the user can use to set up some default
		 * values
		 */
		function create_menu() {
			global $open_graph_hook;
			
			// Adds the main menu for this plugin to the Wordpress Admin Panel
			$open_graph_hook = add_menu_page ( __ ( 'Social Graph', 'Facebook Open Graph Protocol' ), __ ( 'Social Graph', 'Facebook Open Graph Protocol' ), 8, 
			__FILE__, array( 'fbOpenGraphByBizzyLabs', 'open_graph_menu' ) );
			
			// Add a sub-menu called Add Videos
			add_submenu_page( __FILE__, 'Settings', 'Settings', 8, __FILE__, array( 'fbOpenGraphByBizzyLabs', 'open_graph_menu' ) );
		}
		
		/*
		 * This function is used to display the settings menu for this plugin, it's also used to catch and 
		 * deal with saving the form data on that page
		 */
		function open_graph_menu() {
			// If the user has clicked the save button, update the information in the options	
			if( isset( $_POST['save_fb_open_graph_settings'] ) ) {
				if( !empty( $_POST['og_description'] ) ) update_option( 'og:description', trim( $_POST['og_description'] ) ); else delete_option( 'og:description' );
				if( !empty( $_POST['og_image'] ) ) update_option( 'og:image', trim( $_POST['og_image'] ) ); else delete_option( 'og:image' );
				if( !empty( $_POST['fb_admins'] ) ) update_option( 'fb:admins', trim( $_POST['fb_admins'] ) ); else delete_option( 'fb:admins' );
				if( !empty( $_POST['og_type'] ) ) update_option( 'og:type', trim( $_POST['og_type'] ) ); else delete_option( 'og:type' );
				if( !empty( $_POST['disable_namespaces'] ) ) update_option( 'disable_namespaces', trim( $_POST['disable_namespaces'] ) ); else delete_option( 'disable_namespaces' );
				
				$message = "Your Settings Have Been Successfully Saved.";
			}
			
			include_once( ABSPATH . 'wp-content/plugins/social-graph-protocol/includes/open-graph-menu.php' );
		
			return;
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
			if ( !wp_verify_nonce( $_POST['bizzyLabs_open_graph_nonce'], plugin_basename( __FILE__ ) ) ) {
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
			$custom_post_types = get_post_types( array( '_builtin' => false ) ); // An array of custom post types
			
			add_meta_box( 
		        'bizzyLabsOpenGraphPlugin',
		        'Open Graph Protocol Options',
		        array( 'fbOpenGraphByBizzyLabs', 'render_meta_box_content' ),
		        'post',
		        'normal',
		        'high'
		    );
		    add_meta_box(
		        'bizzyLabsOpenGraphPlugin',
		        'Open Graph Protocol Options',
		        array( 'fbOpenGraphByBizzyLabs', 'render_meta_box_content' ),
		        'page',
		        'normal',
		        'high'
		    );
			
			// Loop through the custom post types giving each of them a meta box for our plugin
			if( is_array( $custom_post_types ) && !empty( $custom_post_types ) ) {
				foreach( $custom_post_types as $post_type ) {
					$post_type = trim( $post_type );
					add_meta_box(
				        'bizzyLabsOpenGraphPlugin',
				        'Open Graph Protocol Options',
				        array( 'fbOpenGraphByBizzyLabs', 'render_meta_box_content' ),
				        $post_type,
				        'normal',
				        'high'
				    );
				}
			}
		}
		
		function render_meta_box_content( $post ) {
			// Use nonce for verification
			wp_nonce_field( plugin_basename( __FILE__ ), 'bizzyLabs_open_graph_nonce' );
			
			include_once( ABSPATH . 'wp-content/plugins/social-graph-protocol/includes/meta-box.php' );
		
			return;
		}
		
		/*
		 * This function is used to add the proper xmlns:og and xmlns:fb namespaces to the <head> tag
		 * $data = Any language attributes already created by wordpress or other plugins
		 */
		function add_namespaces( $data ) {
			// If the user has choosen to disable namespaces then don't include them
			$disable_namespaces = get_option( 'disable_namespaces' );
			if( $disable_namespaces == 1 ) return $data; // Do not include namespaces
			
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
		 * Adds certain open graph meta data when the theme calls the wp_head function.
		 * Determines which properties should be displayed on this page and ignores invalid and/or empty entries
		 */
		function add_meta_html() {
			global $post;
			
			// Create an empty $og object to store meta tags with
			$og = new stdClass();
			
			// If this is not the homepage of this website
			if( is_single() || is_page() ) {
				// See if the user has created a custom description element
				$og->description = get_post_meta( $post->ID, 'og:description', true );
				
				// If there is no current description element, attempt to create one based upon the post content
				if( empty( $og->description ) ) {
					$description = trim( substr( strip_shortcodes( strip_tags( str_ireplace("\n", '', $post->post_content ) ) ), 0, 255 ) ); // Strip out any shortcodes, html elements and trim the content
					$og->description = htmlspecialchars( trim( substr( $description, 0, strripos( $description, ' ' ) ) ) ); // Convert any html special characters just in case
				}
				
				$og->title = htmlspecialchars( $post->post_title ); // Set the og:title to the title of this post
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
					$og->image = get_the_post_thumbnail(); // Attempt to set the og:image tag to the post thumbnail link
				}
				
				// If we still do not have a $og:image value attempt to use the default value the user can set in the options panel of this plugin
				if( empty( $og->image ) ) {
					$og->image = get_option( 'og:image' ); // Attempt to set the og:image tag to the post thumbnail link
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
				
				$og->site_name = htmlspecialchars( get_option( 'blogname' ) ); // Set the default site name to the wordpress site name
				$og->admins = get_option( 'fb:admins' ); // Get all the admins based upon the users entry
			}
			elseif( is_home() ) {
				// See if the user has created a custom description for there homepage
				$og->description = get_option( 'og:description' );
				
				// If the user has not created a custom description attempt to create one by using the blog description element
				if( empty( $og->description ) ) {
					$og->description = htmlspecialchars( get_option( 'blogdescription' ) );
				}
				
				$og->title = htmlspecialchars( get_option( 'blogname' ) ); // Set the og:title tag to the name of this blog
				$og->url = get_option( 'siteurl' ); // Set the og:url tag to the default site url
				
				// Use the default or user selected type for the og:type tag since this is the root domain, value should be website or blog
				$og->type = htmlspecialchars( get_option( 'og:type' ) );
				
				// Attempt to set the og:image tag to the users potential default value
				$og->image = get_option( 'og:image' );
				
				$og->site_name = htmlspecialchars( get_option( 'blogname' ) ); // Set the default site name to the wordpress site name
				$og->admins = get_option( 'fb:admins' ); // Get all the admins based upon the users entry
			}

			// Begin to generate the header output
			$html = "\n<!-- Start Of Facebook Open Graph Meta Data -->\n";
			
			// Loop through all of the items in the $og object
			$loop = 0;
			foreach( $og as $key => $value ) {
				if( empty( $value ) ) continue; // If the input value is empty continue
				
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

			$html .= "<!-- End Of Facebook Open Graph Meta Data -->\n"; // End the HTML output with this little hidden tag
			if( $loop <= 0 ) $html = ''; // If nothing is to be displayed in the HTML don't display the comments
			
			echo $html; // Add the content to the header of this webpage
		}
	}
?>