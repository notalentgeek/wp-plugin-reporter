# WordPress Plugin Reporter

[![WordPress Compatible](https://img.shields.io/badge/WordPress-5.0%20to%206.5-blue.svg)](https://wordpress.org/)
[![PHP Compatible](https://img.shields.io/badge/PHP-7.2%20to%208.2-purple.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-GPL%20v2%20or%20later-yellow.svg)](http://www.gnu.org/licenses/gpl-2.0.html)

A comprehensive WordPress plugin for extracting detailed information about installed plugins and exporting to CSV and JSON formats.

## Features

- **Complete Plugin Information**: Collects key details about all installed plugins
- **Multiple Export Formats**: Export data as CSV or JSON for different use cases
- **Update Status Tracking**: Identifies plugins that need updates
- **User-Friendly Interface**: Clean, intuitive admin interface with summary statistics
- **Optimized for Analysis**: Export formats designed for easy analysis with spreadsheets or AI tools

## Installation

### Manual Installation

1. Download the latest release ZIP file
2. Log in to your WordPress dashboard
3. Navigate to Plugins → Add New → Upload Plugin
4. Choose the downloaded ZIP file and click "Install Now"
5. Activate the plugin through the WordPress Plugins screen

## Usage

1. After installation, navigate to **Tools → Plugin Reporter** in your WordPress admin
2. The main screen displays an overview of all installed plugins with summary statistics
3. Export options are available at the top of the page:
   - **Export as CSV**: Creates a CSV file suitable for spreadsheet analysis
   - **Export as JSON**: Creates a JSON file for programmatic use or AI analysis
4. Click your preferred export option and the file will download automatically

## Why Use Plugin Reporter?

Plugin Reporter is ideal for:

- **Site Documentation**: Create comprehensive documentation of your WordPress setup
- **Maintenance Planning**: Identify plugins needing updates for maintenance scheduling
- **Site Analysis**: Export data to analyze your plugin usage patterns
- **Handover Documentation**: Create detailed exports when transferring site management
- **AI Tool Integration**: Generate structured data for analysis with AI tools

## Development

### Requirements

- PHP 7.2 or higher
- WordPress 5.0 or higher
- WP-CLI (for running tests)

### Development Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/notalentgeek/wp-plugin-reporter.git
   cd wp-plugin-reporter
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

### Running Tests

The plugin includes unit tests built on the WordPress testing framework:

```bash
# Set up the WordPress test environment (first time only)
bash bin/install-wp-tests.sh wordpress_test root password localhost latest

# Run the tests
./vendor/bin/phpunit
```

## Structure

```
wp-plugin-reporter/
├── admin/                     # Admin-specific functionality
│   ├── css/                   # Admin stylesheets
│   ├── js/                    # Admin JavaScript
│   ├── partials/              # Admin templates
│   └── class-plugin-reporter-admin.php
├── includes/                  # Core plugin functionality
│   ├── class-plugin-reporter.php
│   ├── class-plugin-reporter-exporter.php
│   └── class-plugin-reporter-update-info.php
├── languages/                 # Internationalization
├── tests/                     # Unit tests
├── wp-plugin-reporter.php     # Main plugin file
├── index.php                  # Directory protection
├── README.md                  # This file
└── readme.txt                 # WordPress.org readme
```

## License

This plugin is licensed under the [GPL v2 or later](http://www.gnu.org/licenses/gpl-2.0.html).

## Credits

Developed by the [New Naratif Development Team](https://newnaratif.com/).
