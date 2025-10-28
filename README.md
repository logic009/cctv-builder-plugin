# CCTV System Builder WordPress Plugin

A powerful WordPress plugin that allows users to build custom CCTV systems with dynamic price calculation, similar to the feature found on ryans.com.

## Features

- **Component Selection:** Users can select from various CCTV components including DVR/NVR, cameras, storage, cables, power supplies, and monitors.
- **Dynamic Price Calculation:** Real-time price updates as users make selections.
- **System Summary:** A detailed breakdown of selected items and total pricing.
- **Quote Management:** Save and manage CCTV system quotes.
- **Responsive Design:** Fully responsive interface that works on desktop and mobile devices.
- **Easy Integration:** Simple shortcode to add the builder to any page or post.

## Installation

1. Download the plugin folder to your computer.
2. Upload the `cctv-builder-plugin` folder to the `/wp-content/plugins/` directory on your WordPress site.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Navigate to the CCTV Builder menu in the WordPress admin to configure settings.

## Usage

### Display the Builder

To display the CCTV Builder on any page or post, use the following shortcode:

```
[cctv_builder]
```

### Component Categories

The plugin includes the following component categories:

1. **Digital Video Recorder (DVR) / Network Video Recorder (NVR):** Select the recording device for your system.
2. **Cameras:** Choose from various camera types and resolutions.
3. **Hard Disk Drive (Storage):** Select storage capacity for recorded footage.
4. **Cables and Connectors:** Choose necessary wiring and connectors.
5. **Power Supply:** Select the appropriate power supply for your system.
6. **Monitor (Optional):** Choose a display unit (optional).

### Default Components

The plugin comes with pre-configured components and pricing. You can customize these by editing the component data in the WordPress admin panel.

## Component Structure

Each component has the following properties:

- **ID:** Unique identifier for the component
- **Name:** Display name of the component
- **Price:** Price of the component in USD
- **Description:** Brief description of the component

## AJAX Endpoints

The plugin provides the following AJAX endpoints:

### Get Components
- **Action:** `cctv_get_components`
- **Method:** POST
- **Parameters:** `nonce` (security token)
- **Returns:** JSON array of all components

### Calculate Price
- **Action:** `cctv_calculate_price`
- **Method:** POST
- **Parameters:** `nonce` (security token), `items` (JSON array of selected items)
- **Returns:** JSON object with subtotal, tax, and total price

### Save Quote
- **Action:** `cctv_save_quote`
- **Method:** POST
- **Parameters:** `nonce` (security token), `quote` (JSON object with quote data)
- **Returns:** JSON object with quote ID and success message

## Customization

### Modifying Components

To modify the default components, you can edit the `CCTV_Components::init_default_components()` function in the `includes/class-cctv-components.php` file.

### Styling

The plugin uses CSS for styling. You can customize the appearance by modifying the `assets/css/cctv-builder.css` file.

### Tax Rate

To set a custom tax rate, use the `cctv_builder_tax_rate` filter:

```php
add_filter( 'cctv_builder_tax_rate', function() {
    return 0.10; // 10% tax rate
} );
```

## Hooks and Filters

### Filters

- **cctv_builder_tax_rate:** Modify the tax rate applied to quotes (default: 0)

### Actions

- **cctv_builder_init:** Fired when the plugin is initialized
- **cctv_builder_activate:** Fired when the plugin is activated
- **cctv_builder_deactivate:** Fired when the plugin is deactivated

## Database

The plugin creates a custom post type called `cctv_quote` to store saved quotes. Each quote is stored as a post with the quote data serialized as JSON in the post content.

## Browser Compatibility

The plugin is compatible with all modern browsers including:

- Chrome
- Firefox
- Safari
- Edge

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- jQuery (included with WordPress)

## Support

For support, issues, or feature requests, please contact the plugin author or visit the official website.

## License

This plugin is licensed under the GPL v2 or later. See the LICENSE file for more information.

## Changelog

### Version 1.0.0
- Initial release
- Component selection and pricing calculation
- Quote management
- Responsive design

## Author

Your Name

## Version

1.0.0
