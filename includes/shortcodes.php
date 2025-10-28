<?php
/**
 * Shortcodes for CCTV Builder
 */

/**
 * Register shortcode for CCTV Builder
 */
add_shortcode( 'cctv_builder', 'cctv_builder_shortcode' );

function cctv_builder_shortcode() {
    ob_start();
    ?>
    <div id="cctv-builder-container" class="cctv-builder-wrapper">
        <div class="cctv-builder-header">
            <h2>Build Your Custom CCTV System</h2>
            <p>Select components to create your perfect surveillance solution</p>
        </div>

        <div class="cctv-builder-content">
            <div class="cctv-builder-form">
                <!-- DVR/NVR Selection -->
                <div class="cctv-builder-section">
                    <h3>Step 1: Select Recording Device</h3>
                    <div id="dvr-nvr-options" class="component-options">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Cameras Selection -->
                <div class="cctv-builder-section">
                    <h3>Step 2: Select Cameras</h3>
                    <div id="cameras-options" class="component-options">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Storage Selection -->
                <div class="cctv-builder-section">
                    <h3>Step 3: Select Storage</h3>
                    <div id="storage-options" class="component-options">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Cables Selection -->
                <div class="cctv-builder-section">
                    <h3>Step 4: Select Cables and Connectors</h3>
                    <div id="cables-options" class="component-options">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Power Supply Selection -->
                <div class="cctv-builder-section">
                    <h3>Step 5: Select Power Supply</h3>
                    <div id="power-supply-options" class="component-options">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Monitor Selection (Optional) -->
                <div class="cctv-builder-section">
                    <h3>Step 6: Select Monitor (Optional)</h3>
                    <div id="monitor-options" class="component-options">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Summary and Pricing -->
            <div class="cctv-builder-summary">
                <h3>System Summary</h3>
                <div id="selected-items" class="selected-items-list">
                    <p class="no-items-message">No items selected yet</p>
                </div>

                <div class="cctv-builder-pricing">
                    <div class="price-row">
                        <span>Subtotal:</span>
                        <span id="subtotal-price">$0.00</span>
                    </div>
                    <div class="price-row">
                        <span>Tax (0%):</span>
                        <span id="tax-price">$0.00</span>
                    </div>
                    <div class="price-row total">
                        <span>Total Price:</span>
                        <span id="total-price">$0.00</span>
                    </div>
                </div>

                <button id="add-to-quote-btn" class="cctv-builder-btn">Add to Quote</button>
                <button id="reset-builder-btn" class="cctv-builder-btn secondary">Reset</button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
