<?php
$options = get_option( 'cnr_performance' );

// removes wlwmanifest (Windows Live Writer) link
if( isset($options['disable_wlwmanifest']) == 1 ) {
	remove_action('wp_head', 'wlwmanifest_link');
}


// Remove auto generated feed links
function cnr_remove_rss_feeds() {
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );
}

// remove rss xml support
if( 1 == isset($options['remove_rss_link'] )) {
	add_action( 'after_setup_theme', 'cnr_remove_rss_feeds' );
}

// disable emoji
if( 1 == isset($options['disable_emoji']) ) {
	function cnr_disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );	
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	}
	add_action( 'init', 'cnr_disable_emojis' );
	
	function cnr_disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
}

// remove wp generator meta tag

if( isset($options['remove_wp_generator']) == 1 ) {
	remove_action('wp_head', 'wp_generator');
}

// remove vc generator meta tag

if( isset($options['remove_vc_generator']) == 1 ) {
	remove_action('wp_head', array(visual_composer(), 'addMetaData'));
}

// remove wp shortlink

if( isset($options['remove_wp_shortlink']) == 1 ) {
	remove_action('wp_head', 'wp_shortlink_wp_head');
}

// remove feed links

if( isset($options['remove_feed_links']) == 1 ) {
	remove_action( 'wp_head', 'feed_links', 2 );
}

// remove comments feed links

if( isset($options['remove_comments_feed_links']) == 1 ) {
	remove_action( 'wp_head', 'feed_links_extra', 3 );
}

// disable oembed

if( isset($options['disable_wp_oembed']) == 1 ) {
	wp_deregister_script('wp-embed');
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	add_filter( 'embed_oembed_discover', '__return_false' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}

// disable xml rpc

if( isset($options['disable_xmlrpc']) == 1 ) {
	add_filter( 'xmlrpc_enabled', '__return_false' );
}
// disable rest api

if( isset($options['disable_restapi']) == 1 ) {
	add_filter('json_enabled', '__return_false');
	add_filter('json_jsonp_enabled', '__return_false');
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}

// disable pingback

if( isset($options['disable_pingback']) == 1 ) {
	add_filter('wp_headers', function($headers, $wp_query){
		if (array_key_exists('X-Pingback', $headers)) {
			unset($headers['X-Pingback']);
		}
		return $headers;
	}, 11, 2);
	add_filter('bloginfo_url', function($output, $property){
		error_log("====property=" . $property);
		return ($property == 'pingback_url') ? null : $output;
	}, 11, 2);
}

// remove the support of RSD link

if( isset($options['remove_rsd_link']) == 1 ) {
	remove_action('wp_head', 'rsd_link');
}

// remove version parameters from scripts & styles

if( isset($options['remove_scripts_ver']) == 1 ) {
	function cnr_remove_script_version( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}
	add_filter( 'script_loader_src', 'cnr_remove_script_version', 15, 1 );
	add_filter( 'style_loader_src', 'cnr_remove_script_version', 15, 1 );
}

// disable heartbeat
function cnr_stop_heartbeat() {
        wp_deregister_script('heartbeat');
}
if( isset($options['disable_heartbeat']) == 1 ) {
	add_action( 'init', 'cnr_stop_heartbeat', 1 );
}

// reduce heartbeat
if( isset($options['reduce_heartbeat']) ) {
	$options['reduce_heartbeat'];
}
function cnr_reduce_heartbeat( $settings ) {
	$val = 15;
	if( isset($options['reduce_heartbeat']) ) {
		$val = $options['reduce_heartbeat'];
	}
	$settings['interval'] = $val;
	
	return $settings;
}
if( isset($options['reduce_heartbeat']) ) {
	add_filter( 'heartbeat_settings', 'cnr_reduce_heartbeat' );
}

// disable wp toolbar

if( isset($options['disable_wp_toolbar']) == 1 ) {
	show_admin_bar(false);
}

// cache

if( isset($options['preserve_cache']) == 1 ) {
	function cnr_cache( $oldname, $oldtheme = false ) {
		
	  	require_once( ABSPATH . '/wp-admin/includes/misc.php' );
	  	
		$rules = array();
		
		$rules[] = '<IfModule mod_expires.c>';
		$rules[] = 'ExpiresActive On';
		$rules[] = 'ExpiresByType image/jpg "access 1 second"';
		$rules[] = 'ExpiresByType image/jpeg "access 1 second"';
		$rules[] = 'ExpiresByType image/gif "access 1 second"';
		$rules[] = 'ExpiresByType image/png "access 1 second"';
		$rules[] = 'ExpiresByType text/css "access 1 second"';
		$rules[] = 'ExpiresByType application/pdf "access 1 second"';
		$rules[] = 'ExpiresByType text/x-javascript "access 1 second"';
		$rules[] = 'ExpiresByType application/x-shockwave-flash "access 1 second"';
		$rules[] = 'ExpiresByType image/x-icon "access 1 second"';
		$rules[] = 'ExpiresDefault "access 1 second"';
		$rules[] = '</IfModule>';
		
		$htaccess_file = ABSPATH.'.htaccess';
		insert_with_markers($htaccess_file, 'CNR Performance', (array) $rules);
	}
	
	add_action("init", "cnr_cache", 10 ,  2);
}
else {
	function cnr_remove_cache( $oldname, $oldtheme = false ) {
		require_once( ABSPATH . '/wp-admin/includes/misc.php' );
		
		$rules = array();
		
		$htaccess_file = ABSPATH.'.htaccess';
		insert_with_markers($htaccess_file, 'CNR Performance', (array) $rules);
	}
	add_action("init", "cnr_remove_cache", 10 ,  2);
}
