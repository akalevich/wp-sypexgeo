<?php

    add_action( 'init', posts_button);
    // Add the script and style files
    add_action('admin_enqueue_scripts', 		'load_styles');

	/**
	 * Load CSS styles
	 */
	function load_styles() {
		// Check if they are in admin
        // Set the common style
        wp_register_style( 'wpgeocode_admin_styles', GEOTARGETING_PLUGIN_CSS_URL .'admin/styles.css', false, '1', 'screen' );
        wp_enqueue_style( 'wpgeocode_admin_styles' );
	}

    function register_button( $buttons ) {
       array_push( $buttons, "|", "wpgeocode", "wpgeotext" );
       return $buttons;
    }

	function add_plugin( $plugin_array ) {
	   $plugin_array['wpgeocode'] = GEOTARGETING_PLUGIN_JS_URL . 'admin/wpgeocode.js';
	   $plugin_array['wpgeotext'] = GEOTARGETING_PLUGIN_JS_URL . 'admin/wpgeotext.js';
	   return $plugin_array;
	}

	function posts_button() {
       if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
          return;
       }

       if ( get_user_option('rich_editing') == 'true' ) {
          add_filter( 'mce_external_plugins', 'add_plugin');
          add_filter( 'mce_buttons', 'register_button' );
       }

    }

?>
