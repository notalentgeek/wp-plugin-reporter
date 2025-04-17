=== Plugin Reporter ===
Contributors: newnaratif, notalentgeek
Tags: plugins, report, export, tools, analysis, documentation
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A comprehensive tool to extract information about installed plugins and export to CSV/JSON for analysis and documentation.

== Description ==

Plugin Reporter is a powerful tool for WordPress administrators that provides detailed information about all installed plugins on your site and allows exporting this data in CSV or JSON formats for further analysis or documentation.

### Key Features

* **Comprehensive Data Collection**: Gathers detailed information about each plugin including status, author, version, and more
* **Update Status Tracking**: Identifies which plugins have updates available
* **Multiple Export Formats**: Export as CSV for spreadsheet analysis or JSON for programmatic use
* **User-Friendly Interface**: Clean, intuitive admin interface with summary statistics
* **Optimized for Documentation**: Perfect for creating site documentation or preparing for handovers

### Use Cases

* Create comprehensive documentation of your WordPress setup
* Analyze plugin usage and identify optimization opportunities
* Prepare data for AI analysis of your WordPress installation
* Keep track of plugin versions and update status
* Simplify site handovers with complete plugin documentation

### Coming Soon

We're working on exciting new features for future releases:

* **Performance Impact Assessment**: Measure how plugins affect your site speed
* **File Size Analysis**: See how much disk space each plugin uses
* **Plugin Dependency Detection**: Discover which plugins depend on others
* **Vulnerability Scanning**: Check for known security issues
* **Interactive Dashboard**: Visual representations of your plugin data
* **Scheduled Reports**: Get plugin reports automatically by email
* **PDF Export**: Create professional PDF documentation

### For Developers

Plugin Reporter is built following WordPress coding standards and includes comprehensive unit tests. The exported JSON format is designed to be easily parsed and used in other tools or scripts.

== Installation ==

1. Upload the `wp-plugin-reporter` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Tools > Plugin Reporter' in your WordPress admin
4. Click one of the export buttons to download your plugin information

== Frequently Asked Questions ==

= What information does the plugin collect? =

The plugin collects the following information about each installed plugin:
* Name
* Version
* Status (active/inactive)
* Description
* Author
* Plugin URI
* Plugin Path
* Update status (if available)
* Latest version (if available)

= Is this plugin compatible with multisite installations? =

Yes, Plugin Reporter works with multisite installations.

= Does the plugin modify any of my existing plugins? =

No, Plugin Reporter is read-only. It only collects and reports information about your plugins without making any changes to them.

= How often is update information refreshed? =

The plugin uses WordPress's built-in update checking system, so update information is refreshed whenever WordPress checks for updates.

= Should I run this plugin on my production site? =

Yes, Plugin Reporter is safe to run on production sites as it's a read-only tool that only gathers information. Future features like performance impact assessment would be best run on staging environments first.

= Will this plugin help identify performance issues? =

The current version focuses on plugin information and updates. We're developing a Performance Impact Assessment feature for future releases that will help identify which plugins affect site performance.

== Screenshots ==

1. The main Plugin Reporter interface with summary statistics
2. Example of exported CSV data
3. Example of exported JSON data

== Changelog ==

= 1.0.0 =
* Initial release
* Support for CSV and JSON exports
* Update status tracking
* Complete plugin information reporting

== Upgrade Notice ==

= 1.0.0 =
Initial release of Plugin Reporter.
