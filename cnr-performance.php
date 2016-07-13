<?php
/*
	Plugin Name: CNR Performance
	Plugin URI: https://amitmoreno.com/
	Description: Boost your WordPress performance with additional performance settings.
	Version: 0.0.1
	Author: Amit Moreno
	Author URI: https://amitmoreno.com/
	License: GPLv2 or later
	Text Domain: cnr
*/

// Options panel
include 'lib/panel.php';

// Core settings
include 'lib/core-wp.php';

// Compression settings
include 'lib/compress-html.php';

// Assets
function cnr_performance_assets() {
        wp_register_style( 'admin-style', plugins_url( 'assets/css/admin-style.css', __FILE__ ), false, '1.0.0' );
        wp_enqueue_style( 'admin-style' );
}
add_action( 'admin_enqueue_scripts', 'cnr_performance_assets' );
