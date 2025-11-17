<?php
/**
 * Plugin Name: CCTV System Builder
 * Plugin URI: https://example.com/cctv-builder
 * Description: A WordPress plugin that allows users to build custom CCTV systems with dynamic price calculation.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cctv-builder
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'CCTV_BUILDER_VERSION', '1.0.0' );
define( 'CCTV_BUILDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCTV_BUILDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CCTV_BUILDER_PAGE_OPTION', 'cctv_builder_page_id' );

// Include necessary files
require_once CCTV_BUILDER_PLUGIN_DIR . 'includes/class-cctv-builder.php';
require_once CCTV_BUILDER_PLUGIN_DIR . 'includes/class-cctv-components.php';
require_once CCTV_BUILDER_PLUGIN_DIR . 'includes/shortcodes.php';
require_once CCTV_BUILDER_PLUGIN_DIR . 'includes/api.php';

// Initialize the plugin
function cctv_builder_init() {
    $cctv_builder = new CCTV_Builder();
    $cctv_builder->init();
}
add_action( 'plugins_loaded', 'cctv_builder_init' );

// Activation hook
register_activation_hook( __FILE__, 'cctv_builder_activate' );
function cctv_builder_activate() {
    // Set up default components
    CCTV_Components::init_default_components();

    // Ensure quote post type rewrite rules are registered immediately
    cctv_register_quote_post_type();
    flush_rewrite_rules();

    // Create a published builder page for live use if it doesn't exist
    cctv_builder_maybe_create_builder_page();
}

/**
 * Create a published builder page with the shortcode if one doesn't already exist.
 */
function cctv_builder_maybe_create_builder_page() {
    $existing_page_id = get_option( CCTV_BUILDER_PAGE_OPTION );

    if ( $existing_page_id && get_post_status( $existing_page_id ) ) {
        return;
    }

    $existing_page = get_page_by_path( 'cctv-builder' );

    if ( $existing_page ) {
        update_option( CCTV_BUILDER_PAGE_OPTION, $existing_page->ID );
        return;
    }

    $page_id = wp_insert_post( array(
        'post_title'   => 'CCTV Builder',
        'post_name'    => 'cctv-builder',
        'post_content' => '[cctv_builder]',
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ) );

    if ( ! is_wp_error( $page_id ) ) {
        update_option( CCTV_BUILDER_PAGE_OPTION, $page_id );
    }
}

// Deactivation hook
register_deactivation_hook( __FILE__, 'cctv_builder_deactivate' );
function cctv_builder_deactivate() {
    // Clean up if needed
}
