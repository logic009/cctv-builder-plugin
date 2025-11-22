<?php
/**
 * API endpoints for CCTV Builder
 */

// Register AJAX endpoints
add_action( 'wp_ajax_cctv_get_components', 'cctv_ajax_get_components' );
add_action( 'wp_ajax_nopriv_cctv_get_components', 'cctv_ajax_get_components' );

add_action( 'wp_ajax_cctv_calculate_price', 'cctv_ajax_calculate_price' );
add_action( 'wp_ajax_nopriv_cctv_calculate_price', 'cctv_ajax_calculate_price' );

add_action( 'wp_ajax_cctv_save_quote', 'cctv_ajax_save_quote' );
add_action( 'wp_ajax_nopriv_cctv_save_quote', 'cctv_ajax_save_quote' );

/**
 * Get all components via AJAX
 */
function cctv_ajax_get_components() {
    check_ajax_referer( 'cctv-builder-nonce', 'nonce' );

    $components = CCTV_Components::get_all_components();
    wp_send_json_success( $components );
}

/**
 * Calculate total price via AJAX
 */
function cctv_ajax_calculate_price() {
    check_ajax_referer( 'cctv-builder-nonce', 'nonce' );

    $selected_items = isset( $_POST['items'] ) ? sanitize_text_field( wp_unslash( $_POST['items'] ) ) : '';
    $items = json_decode( $selected_items, true );

    if ( ! is_array( $items ) ) {
        wp_send_json_error( array( 'message' => 'Invalid items data' ) );
    }

    $total_price = 0;
    $item_details = array();

    foreach ( $items as $item_id => $quantity ) {
        $item = CCTV_Components::get_item_by_id( $item_id );

        if ( $item ) {
            $item_price = $item['price'] * $quantity;
            $total_price += $item_price;

            $item_details[] = array(
                'id' => $item_id,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $quantity,
                'total' => $item_price,
            );
        }
    }

    $tax_rate = apply_filters( 'cctv_builder_tax_rate', 0 );
    $tax = $total_price * $tax_rate;
    $grand_total = $total_price + $tax;

    wp_send_json_success( array(
        'subtotal' => $total_price,
        'tax' => $tax,
        'total' => $grand_total,
        'items' => $item_details,
    ) );
}

/**
 * Save quote via AJAX
 */
function cctv_ajax_save_quote() {
    check_ajax_referer( 'cctv-builder-nonce', 'nonce' );

    $quote_data = isset( $_POST['quote'] ) ? sanitize_text_field( wp_unslash( $_POST['quote'] ) ) : '';
    $quote = json_decode( $quote_data, true );

    if ( ! is_array( $quote ) ) {
        wp_send_json_error( array( 'message' => 'Invalid quote data' ) );
    }

    // Save quote to database
    $quote_id = wp_insert_post( array(
        'post_type' => 'cctv_quote',
        'post_title' => 'CCTV Quote - ' . current_time( 'mysql' ),
        'post_content' => wp_json_encode( $quote ),
        'post_status' => 'publish',
    ) );

    if ( is_wp_error( $quote_id ) ) {
        wp_send_json_error( array( 'message' => 'Failed to save quote' ) );
    }

    wp_send_json_success( array(
        'quote_id' => $quote_id,
        'message' => 'Quote saved successfully',
    ) );
}

/**
 * Register custom post type for quotes
 */
add_action( 'init', 'cctv_register_quote_post_type' );

function cctv_register_quote_post_type() {
    $seo_settings = CCTV_Builder::get_seo_settings();
    $quote_slug   = isset( $seo_settings['quote_slug'] ) ? $seo_settings['quote_slug'] : 'cctv-quote';

    register_post_type( 'cctv_quote', array(
        'labels' => array(
            'name' => 'CCTV Quotes',
            'singular_name' => 'CCTV Quote',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'cctv-builder',
        'supports' => array( 'title', 'editor' ),
        'rewrite' => array(
            'slug'       => $quote_slug,
            'with_front' => false,
        ),
    ) );
}
