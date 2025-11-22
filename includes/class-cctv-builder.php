<?php
/**
 * Main CCTV Builder class
 */

class CCTV_Builder {

    const OPTION_SEO_SETTINGS = 'cctv_builder_seo_settings';

    /**
     * Initialize the plugin
     */
    public function init() {
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

        // Register settings
        add_action( 'admin_init', array( $this, 'register_settings' ) );

        // Add AJAX handlers
        add_action( 'wp_ajax_get_components', array( $this, 'ajax_get_components' ) );
        add_action( 'wp_ajax_nopriv_get_components', array( $this, 'ajax_get_components' ) );

        // Frontend SEO enhancements
        add_action( 'wp_head', array( $this, 'render_seo_meta' ) );
        add_filter( 'pre_get_document_title', array( $this, 'filter_document_title' ) );
    }

    /**
     * Register settings for SEO and slug management
     */
    public function register_settings() {
        register_setting(
            'cctv_builder_settings',
            self::OPTION_SEO_SETTINGS,
            array(
                'type'              => 'array',
                'sanitize_callback' => array( $this, 'sanitize_seo_settings' ),
                'default'           => self::get_default_seo_settings(),
            )
        );
    }

    /**
     * Get default SEO settings
     *
     * @return array
     */
    public static function get_default_seo_settings() {
        return array(
            'meta_title'       => '',
            'meta_description' => '',
            'canonical_url'    => '',
            'robots_noindex'   => 0,
            'quote_slug'       => 'cctv-quote',
            'head_verification'=> '',
            'og_image'         => '',
        );
    }

    /**
     * Retrieve SEO settings with defaults applied
     *
     * @return array
     */
    public static function get_seo_settings() {
        $defaults = self::get_default_seo_settings();
        $settings = get_option( self::OPTION_SEO_SETTINGS, array() );

        return wp_parse_args( $settings, $defaults );
    }

    /**
     * Sanitize SEO settings input
     *
     * @param array $input Raw settings from the form.
     * @return array
     */
    public function sanitize_seo_settings( $input ) {
        $defaults = self::get_default_seo_settings();
        $existing = self::get_seo_settings();

        $sanitized = array(
            'meta_title'       => isset( $input['meta_title'] ) ? sanitize_text_field( $input['meta_title'] ) : '',
            'meta_description' => isset( $input['meta_description'] ) ? sanitize_textarea_field( $input['meta_description'] ) : '',
            'canonical_url'    => isset( $input['canonical_url'] ) ? esc_url_raw( $input['canonical_url'] ) : '',
            'robots_noindex'   => empty( $input['robots_noindex'] ) ? 0 : 1,
            'quote_slug'       => isset( $input['quote_slug'] ) ? sanitize_title( $input['quote_slug'] ) : $defaults['quote_slug'],
            'head_verification'=> isset( $input['head_verification'] ) ? wp_kses_post( $input['head_verification'] ) : '',
            'og_image'         => isset( $input['og_image'] ) ? esc_url_raw( $input['og_image'] ) : '',
        );

        if ( empty( $sanitized['quote_slug'] ) ) {
            $sanitized['quote_slug'] = $defaults['quote_slug'];
        }

        if ( $existing['quote_slug'] !== $sanitized['quote_slug'] ) {
            flush_rewrite_rules();
        }

        return wp_parse_args( $sanitized, $defaults );
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
        $seo_settings = self::get_seo_settings();
        ?>
        <div class="wrap">
            <h1>CCTV Builder Settings</h1>
            <p>Welcome to the CCTV Builder plugin. Use the shortcode <code>[cctv_builder]</code> to display the builder on your website.</p>

            <form method="post" action="options.php">
                <?php settings_fields( 'cctv_builder_settings' ); ?>

                <h2 class="title">SEO &amp; URL Controls</h2>
                <p>Optimize the builder page for search engines and customize the URL slug for saved quotes.</p>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="cctv_meta_title">Meta Title</label>
                        </th>
                        <td>
                            <input
                                name="<?php echo esc_attr( self::OPTION_SEO_SETTINGS ); ?>[meta_title]"
                                type="text"
                                id="cctv_meta_title"
                                class="regular-text"
                                value="<?php echo esc_attr( $seo_settings['meta_title'] ); ?>"
                            />
                            <p class="description">Optional page title override when the CCTV Builder shortcode is present.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cctv_meta_description">Meta Description</label>
                        </th>
                        <td>
                            <textarea
                                name="<?php echo esc_attr( self::OPTION_SEO_SETTINGS ); ?>[meta_description]"
                                id="cctv_meta_description"
                                class="large-text"
                                rows="3"
                            ><?php echo esc_textarea( $seo_settings['meta_description'] ); ?></textarea>
                            <p class="description">Short summary shown in search results for builder pages.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cctv_canonical_url">Canonical URL</label>
                        </th>
                        <td>
                            <input
                                name="<?php echo esc_attr( self::OPTION_SEO_SETTINGS ); ?>[canonical_url]"
                                type="url"
                                id="cctv_canonical_url"
                                class="regular-text"
                                value="<?php echo esc_attr( $seo_settings['canonical_url'] ); ?>"
                            />
                            <p class="description">Specify a canonical link to avoid duplicate content issues.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Robots</th>
                        <td>
                            <label for="cctv_robots_noindex">
                                <input
                                    name="<?php echo esc_attr( self::OPTION_SEO_SETTINGS ); ?>[robots_noindex]"
                                    type="checkbox"
                                    id="cctv_robots_noindex"
                                    value="1"
                                    <?php checked( $seo_settings['robots_noindex'], 1 ); ?>
                                />
                                Prevent indexing (noindex, nofollow)
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cctv_quote_slug">Quote URL Slug</label>
                        </th>
                        <td>
                            <input
                                name="<?php echo esc_attr( self::OPTION_SEO_SETTINGS ); ?>[quote_slug]"
                                type="text"
                                id="cctv_quote_slug"
                                class="regular-text"
                                value="<?php echo esc_attr( $seo_settings['quote_slug'] ); ?>"
                            />
                            <p class="description">Customize the URL structure for saved CCTV quotes. Updating this will flush rewrite rules.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cctv_head_verification">Verification &amp; SEO Snippets</label>
                        </th>
                        <td>
                            <textarea
                                name="<?php echo esc_attr( self::OPTION_SEO_SETTINGS ); ?>[head_verification]"
                                id="cctv_head_verification"
                                class="large-text code"
                                rows="4"
                            ><?php echo esc_textarea( $seo_settings['head_verification'] ); ?></textarea>
                            <p class="description">Paste meta tags or scripts from SEO tools (e.g., Search Console, analytics) to load in the page head when the builder is present.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cctv_og_image">Open Graph Image</label>
                        </th>
                        <td>
                            <input
                                name="<?php echo esc_attr( self::OPTION_SEO_SETTINGS ); ?>[og_image]"
                                type="url"
                                id="cctv_og_image"
                                class="regular-text"
                                value="<?php echo esc_attr( $seo_settings['og_image'] ); ?>"
                            />
                            <p class="description">Optional social sharing image for builder pages (Open Graph <code>og:image</code>).</p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
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

    /**
     * Determine if the current request is for a page with the CCTV Builder shortcode
     *
     * @return bool
     */
    private function is_builder_page() {
        if ( is_admin() || ! is_singular() ) {
            return false;
        }

        global $post;

        return $post instanceof WP_Post && has_shortcode( $post->post_content, 'cctv_builder' );
    }

    /**
     * Override the document title for builder pages
     *
     * @param string $title Default title.
     * @return string
     */
    public function filter_document_title( $title ) {
        if ( ! $this->is_builder_page() ) {
            return $title;
        }

        $settings = self::get_seo_settings();

        return ! empty( $settings['meta_title'] ) ? $settings['meta_title'] : $title;
    }

    /**
     * Render SEO meta tags for builder pages
     */
    public function render_seo_meta() {
        if ( ! $this->is_builder_page() ) {
            return;
        }

        $settings = self::get_seo_settings();

        if ( ! empty( $settings['meta_description'] ) ) {
            echo '<meta name="description" content="' . esc_attr( $settings['meta_description'] ) . '" />';
        }

        if ( ! empty( $settings['canonical_url'] ) ) {
            echo '<link rel="canonical" href="' . esc_url( $settings['canonical_url'] ) . '" />';
        }

        if ( ! empty( $settings['robots_noindex'] ) ) {
            echo '<meta name="robots" content="noindex,nofollow" />';
        }

        if ( ! empty( $settings['meta_title'] ) ) {
            echo '<meta property="og:title" content="' . esc_attr( $settings['meta_title'] ) . '" />';
        }

        if ( ! empty( $settings['meta_description'] ) ) {
            echo '<meta property="og:description" content="' . esc_attr( $settings['meta_description'] ) . '" />';
        }

        if ( ! empty( $settings['og_image'] ) ) {
            echo '<meta property="og:image" content="' . esc_url( $settings['og_image'] ) . '" />';
        }

        if ( ! empty( $settings['head_verification'] ) ) {
            echo wp_kses_post( $settings['head_verification'] );
        }
    }
}
