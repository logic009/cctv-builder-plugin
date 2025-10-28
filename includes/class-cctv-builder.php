<?php
/**
 * Main CCTV Builder class
 */

class CCTV_Builder {

    /**
     * Initialize the plugin
     */
    public function init() {
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

        // Add AJAX handlers
        add_action( 'wp_ajax_get_components', array( $this, 'ajax_get_components' ) );
        add_action( 'wp_ajax_nopriv_get_components', array( $this, 'ajax_get_components' ) );
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style( 'cctv-builder-style', CCTV_BUILDER_PLUGIN_URL . 'assets/css/cctv-builder.css', array(), CCTV_BUILDER_VERSION );
        wp_enqueue_script( 'cctv-builder-script', CCTV_BUILDER_PLUGIN_URL . 'assets/js/cctv-builder.js', array( 'jquery' ), CCTV_BUILDER_VERSION, true );

        // Pass AJAX URL and nonce to JavaScript
        wp_localize_script( 'cctv-builder-script', 'cctvBuilderData', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'cctv-builder-nonce' ),
        ) );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {
        wp_enqueue_style( 'cctv-builder-admin-style', CCTV_BUILDER_PLUGIN_URL . 'assets/css/admin.css', array(), CCTV_BUILDER_VERSION );
        wp_enqueue_script( 'cctv-builder-admin-script', CCTV_BUILDER_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), CCTV_BUILDER_VERSION, true );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'CCTV Builder',
            'CCTV Builder',
            'manage_options',
            'cctv-builder',
            array( $this, 'render_admin_page' ),
            'dashicons-camera'
        );

        add_submenu_page(
            'cctv-builder',
            'Components',
            'Components',
            'manage_options',
            'cctv-builder-components',
            array( $this, 'render_components_page' )
        );
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1>CCTV Builder Settings</h1>
            <p>Welcome to the CCTV Builder plugin. Use the shortcode <code>[cctv_builder]</code> to display the builder on your website.</p>
        </div>
        <?php
    }

    /**
     * Render components management page
     */
    public function render_components_page() {
        ?>
        <div class="wrap">
            <h1>Manage CCTV Components</h1>
            <p>Manage the components available in the CCTV Builder.</p>
            <!-- Component management interface will be added here -->
        </div>
        <?php
    }

    /**
     * AJAX handler to get components
     */
    public function ajax_get_components() {
        check_ajax_referer( 'cctv-builder-nonce', 'nonce' );

        $components = CCTV_Components::get_all_components();
        wp_send_json_success( $components );
    }
}
