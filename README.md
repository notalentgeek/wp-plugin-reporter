# WordPress Plugin Reporter

A comprehensive WordPress plugin for extracting detailed information about installed plugins and exporting to CSV and JSON formats.

## Features

- **Complete Plugin Information**: Collects key details about all installed plugins
- **Multiple Export Formats**: Export data as CSV or JSON for different use cases
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

## Development

### Requirements

- PHP 7.2 or higher
- WordPress 5.0 or higher
- Docker and Docker Compose (for local development)

### Docker Development Setup

1. Clone the repository and navigate to your Docker WordPress environment:
   ```bash
   git clone https://github.com/notalentgeek/wp-plugin-reporter.git
   cd /path/to/your/docker-wordpress
   ```

2. Make sure the plugin is in your WordPress plugins directory that's mounted to Docker:
   ```bash
   # Example directory structure:
   # /path/to/your/docker-wordpress/plugins/wp-plugin-reporter/
   ```

3. Start your Docker environment:
   ```bash
   docker-compose up -d
   ```

4. Activate the plugin in WordPress:
   ```bash
   docker exec wordpress wp plugin activate wp-plugin-reporter --allow-root
   ```

### Running Tests with Docker

1. Install Composer dependencies (if not already installed):
   ```bash
   docker exec -w /var/www/html/wp-content/plugins/wp-plugin-reporter wordpress composer install
   ```

2. Set up the WordPress test environment:
   ```bash
   docker exec -w /var/www/html/wp-content/plugins/wp-plugin-reporter wordpress ./bin/install-wp-tests.sh wordpress_test root wordpress db latest
   ```

3. Run the tests:
   ```bash
   docker exec -w /var/www/html/wp-content/plugins/wp-plugin-reporter wordpress ./vendor/bin/phpunit
   ```

4. Run tests with coverage reports:
   ```bash
   docker exec -w /var/www/html/wp-content/plugins/wp-plugin-reporter wordpress ./vendor/bin/phpunit --coverage-html ./reports/coverage
   ```

### Local Development Setup (Non-Docker)

```bash
# Clone the repository
git clone https://github.com/notalentgeek/wp-plugin-reporter.git
cd wp-plugin-reporter

# Set up directories if needed
mkdir -p includes admin/css admin/js admin/partials languages
```

### Running Tests (Non-Docker)

The plugin includes unit tests built on the WordPress testing framework:

```bash
# Set up the WordPress test environment
bash bin/install-wp-tests.sh wordpress_test root password localhost latest

# Run the tests
phpunit
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
│   └── class-plugin-reporter-exporter.php
├── languages/                 # Internationalization
├── tests/                     # Unit tests
├── README.md                  # This file
├── readme.txt                 # WordPress.org readme
└── wp-plugin-reporter.php     # Main plugin file
```

## License

This plugin is licensed under the [GPL v2 or later](http://www.gnu.org/licenses/gpl-2.0.html).

## Credits

Developed by the [New Naratif Development Team](https://newnaratif.com).
