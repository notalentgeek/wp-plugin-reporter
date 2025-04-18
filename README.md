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

## Roadmap

Our development roadmap outlines new features and enhancements planned for future releases:

### 1. Enhanced Plugin Information Collection

- **File Size Analysis**: Calculate and display the disk space used by each plugin
- **Plugin Dependency Detection**: Identify which plugins are dependent on others
- **Update History Logging**: Track when plugins were last updated
- **Performance Impact Assessment**: Add metrics to show potential performance impact of plugins
- **License Information Display**: Add license type, expiration dates, and compliance status

### 2. Security Features

- **Vulnerability Scanning**: Connect to public vulnerability databases to check for known issues
- **Abandoned Plugin Detection**: Flag plugins that haven't been updated in X months
- **Code Quality Indicators**: Basic static analysis to identify potential security risks
- **Plugin Origin Verification**: Check if plugins come from WordPress.org or external sources

### 3. Advanced Reporting & Visualization

- **Interactive Dashboard**: Visual representation of plugin data with charts and graphs
- **Scheduled Reports**: Automated email reports at configurable intervals
- **Custom Report Builder**: Allow users to select which metrics to include in exports
- **PDF Export**: Add ability to create professional PDF reports
- **Comparison Tools**: Compare plugin configurations between sites or over time

### 4. Maintenance & Management Tools

- **Bulk Actions**: Perform actions on multiple plugins (deactivate, update, etc.)
- **Update Scheduler**: Schedule plugin updates for low-traffic periods
- **Pre-update Testing**: Automated checks before updating (backups, compatibility)
- **Update Rollback**: Ability to revert to previous versions if issues occur
- **Plugin Health Score**: Generate an overall health score based on multiple metrics

### 5. Integration Features

- **Multi-site Support**: Enhanced functionality for WordPress multi-site installations
- **REST API Endpoints**: Add API access to plugin data for external tools
- **Export to Popular Systems**: Direct export to documentation platforms or project management tools
- **Slack/Teams Notifications**: Send alerts about critical plugin updates

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
- Local WordPress and MySQL setup or Docker/Podman for containerized development

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

The plugin includes unit tests built on the WordPress testing framework. You'll need a WordPress environment with MySQL set up to run the tests.

#### Using Docker (Recommended)

If you're using a Docker-based WordPress environment, you can run the tests with:

```bash
docker exec -w /var/www/html/wp-content/plugins/wp-plugin-reporter wordpress ./vendor/bin/phpunit
```

Sample output:
```
Installing...
Running as single site... To run multisite, use -c tests/phpunit/multisite.xml
Not running ajax tests. To execute these, use --group ajax.
Not running ms-files tests. To execute these, use --group ms-files.
Not running external-http tests. To execute these, use --group external-http.
PHPUnit 9.6.22 by Sebastian Bergmann and contributors.

.......                                                             7 / 7 (100%)

Time: 00:00.053, Memory: 42.50 MB

OK (7 tests, 29 assertions)
```

#### Local Environment

For a local WordPress installation:

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
