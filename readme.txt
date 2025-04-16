=== Plugin Reporter ===
Contributors: newnaratif, notalentgeek
Tags: plugins, report, export, tools, analysis
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A comprehensive tool to extract information about installed plugins and export to CSV/JSON for analysis.

== Description ==

Plugin Reporter is a simple yet powerful tool for WordPress administrators that provides detailed information about all installed plugins on your site and allows exporting this data in CSV or JSON formats for further analysis or documentation.

### Key Features

* **Comprehensive Data Collection**: Gathers detailed information about each plugin including status, author, and more
* **Multiple Export Formats**: Export as CSV for spreadsheet analysis or JSON for programmatic use
* **User-Friendly Interface**: Clean, intuitive admin interface with summary statistics
* **Optimized for LLMs**: Export formats designed for easy analysis with AI tools

### Use Cases

* Create comprehensive documentation of your WordPress setup
* Analyze plugin usage and identify optimization opportunities
* Prepare data for AI analysis of your WordPress installation
* Keep track of plugin versions for update planning

== Installation ==

1. Upload the `wp-plugin-reporter` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Tools > Plugin Reporter' in your WordPress admin

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

= Is this plugin compatible with multisite installations? =

Yes, Plugin Reporter works with multisite installations.

= Does the plugin modify any of my existing plugins? =

No, Plugin Reporter is read-only. It only collects and reports information about your plugins without making any changes to them.

== Screenshots ==

1. The main Plugin Reporter interface with summary statistics
2. Example of exported CSV data
3. Example of exported JSON data

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release of Plugin Reporter.
