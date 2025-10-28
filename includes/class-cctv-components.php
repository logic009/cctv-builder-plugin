<?php
/**
 * CCTV Components class for managing component data
 */

class CCTV_Components {

    const OPTION_KEY = 'cctv_builder_components';

    /**
     * Initialize default components
     */
    public static function init_default_components() {
        $existing = get_option( self::OPTION_KEY );

        if ( ! $existing ) {
            $default_components = array(
                'dvr_nvr' => array(
                    'name' => 'Digital Video Recorder (DVR) / Network Video Recorder (NVR)',
                    'items' => array(
                        array(
                            'id' => 'dvr_4ch',
                            'name' => '4-Channel DVR',
                            'price' => 150.00,
                            'description' => 'Basic 4-channel DVR for small installations',
                        ),
                        array(
                            'id' => 'dvr_8ch',
                            'name' => '8-Channel DVR',
                            'price' => 250.00,
                            'description' => 'Mid-range 8-channel DVR',
                        ),
                        array(
                            'id' => 'dvr_16ch',
                            'name' => '16-Channel DVR',
                            'price' => 400.00,
                            'description' => 'Professional 16-channel DVR',
                        ),
                        array(
                            'id' => 'nvr_4ch',
                            'name' => '4-Channel NVR',
                            'price' => 200.00,
                            'description' => 'Network-based 4-channel NVR',
                        ),
                        array(
                            'id' => 'nvr_8ch',
                            'name' => '8-Channel NVR',
                            'price' => 350.00,
                            'description' => 'Network-based 8-channel NVR',
                        ),
                    ),
                ),
                'cameras' => array(
                    'name' => 'Cameras',
                    'items' => array(
                        array(
                            'id' => 'dome_2mp',
                            'name' => 'Dome Camera (2MP)',
                            'price' => 60.00,
                            'description' => 'Indoor dome camera with 2MP resolution',
                        ),
                        array(
                            'id' => 'dome_4mp',
                            'name' => 'Dome Camera (4MP)',
                            'price' => 100.00,
                            'description' => 'Indoor dome camera with 4MP resolution',
                        ),
                        array(
                            'id' => 'bullet_2mp',
                            'name' => 'Bullet Camera (2MP)',
                            'price' => 70.00,
                            'description' => 'Outdoor bullet camera with 2MP resolution',
                        ),
                        array(
                            'id' => 'bullet_4mp',
                            'name' => 'Bullet Camera (4MP)',
                            'price' => 110.00,
                            'description' => 'Outdoor bullet camera with 4MP resolution',
                        ),
                        array(
                            'id' => 'ptz_4mp',
                            'name' => 'PTZ Camera (4MP)',
                            'price' => 250.00,
                            'description' => 'Pan-Tilt-Zoom camera with 4MP resolution',
                        ),
                    ),
                ),
                'storage' => array(
                    'name' => 'Hard Disk Drive (Storage)',
                    'items' => array(
                        array(
                            'id' => 'hdd_1tb',
                            'name' => '1TB HDD',
                            'price' => 50.00,
                            'description' => 'Surveillance-grade 1TB hard drive',
                        ),
                        array(
                            'id' => 'hdd_2tb',
                            'name' => '2TB HDD',
                            'price' => 80.00,
                            'description' => 'Surveillance-grade 2TB hard drive',
                        ),
                        array(
                            'id' => 'hdd_4tb',
                            'name' => '4TB HDD',
                            'price' => 120.00,
                            'description' => 'Surveillance-grade 4TB hard drive',
                        ),
                        array(
                            'id' => 'hdd_6tb',
                            'name' => '6TB HDD',
                            'price' => 150.00,
                            'description' => 'Surveillance-grade 6TB hard drive',
                        ),
                    ),
                ),
                'cables' => array(
                    'name' => 'Cables and Connectors',
                    'items' => array(
                        array(
                            'id' => 'bnc_cable_50m',
                            'name' => 'BNC Cable (50m)',
                            'price' => 30.00,
                            'description' => 'Coaxial BNC cable 50 meters',
                        ),
                        array(
                            'id' => 'bnc_cable_100m',
                            'name' => 'BNC Cable (100m)',
                            'price' => 50.00,
                            'description' => 'Coaxial BNC cable 100 meters',
                        ),
                        array(
                            'id' => 'power_cable_pack',
                            'name' => 'Power Cable Pack (10)',
                            'price' => 25.00,
                            'description' => 'Pack of 10 power cables for cameras',
                        ),
                        array(
                            'id' => 'connector_pack',
                            'name' => 'Connector Pack (20)',
                            'price' => 15.00,
                            'description' => 'Pack of 20 BNC connectors',
                        ),
                    ),
                ),
                'power_supply' => array(
                    'name' => 'Power Supply',
                    'items' => array(
                        array(
                            'id' => 'psu_5a',
                            'name' => 'Power Supply (5A)',
                            'price' => 40.00,
                            'description' => 'Centralized power supply 5A',
                        ),
                        array(
                            'id' => 'psu_10a',
                            'name' => 'Power Supply (10A)',
                            'price' => 70.00,
                            'description' => 'Centralized power supply 10A',
                        ),
                        array(
                            'id' => 'psu_20a',
                            'name' => 'Power Supply (20A)',
                            'price' => 120.00,
                            'description' => 'Centralized power supply 20A',
                        ),
                    ),
                ),
                'monitor' => array(
                    'name' => 'Monitor (Optional)',
                    'items' => array(
                        array(
                            'id' => 'monitor_19',
                            'name' => '19-inch Monitor',
                            'price' => 80.00,
                            'description' => 'LCD monitor 19 inches',
                        ),
                        array(
                            'id' => 'monitor_22',
                            'name' => '22-inch Monitor',
                            'price' => 120.00,
                            'description' => 'LCD monitor 22 inches',
                        ),
                        array(
                            'id' => 'monitor_24',
                            'name' => '24-inch Monitor',
                            'price' => 150.00,
                            'description' => 'LCD monitor 24 inches',
                        ),
                    ),
                ),
            );

            update_option( self::OPTION_KEY, $default_components );
        }
    }

    /**
     * Get all components
     */
    public static function get_all_components() {
        return get_option( self::OPTION_KEY, array() );
    }

    /**
     * Get component by category
     */
    public static function get_component_by_category( $category ) {
        $components = self::get_all_components();
        return isset( $components[ $category ] ) ? $components[ $category ] : array();
    }

    /**
     * Get item by ID
     */
    public static function get_item_by_id( $item_id ) {
        $components = self::get_all_components();

        foreach ( $components as $category ) {
            if ( isset( $category['items'] ) ) {
                foreach ( $category['items'] as $item ) {
                    if ( $item['id'] === $item_id ) {
                        return $item;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Update components
     */
    public static function update_components( $components ) {
        return update_option( self::OPTION_KEY, $components );
    }
}
