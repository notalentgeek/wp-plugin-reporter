<?php
/**
 * Plugin Reporter
 *
 * @package           Plugin_Reporter
 * @author            New Naratif Development Team
 * @copyright         2025 New Naratif
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Reporter
 * Plugin URI:        https://github.com/notalentgeek/wp-plugin-reporter
 * Description:       A tool to extract information about installed plugins and export to CSV/JSON formats.
 * Version:           1.0.0
 * Author:            New Naratif Development Team
 * Author URI:        https://newnaratif.com
 * Text Domain:       plugin-reporter
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 */
define( 'PLUGIN_REPORTER_VERSION', '1.0.0' );

/**
 * The core plugin path and URL.
 */
define( 'PLUGIN_REPORTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_REPORTER_URL', plugin_dir_url( __FILE__ ) );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_plugin_reporter() {
    require_once PLUGIN_REPORTER_PATH . 'includes/class-plugin-reporter.php';
    $plugin = new Plugin_Reporter();
    $plugin->run();
}

// Let's go!
run_plugin_reporter();
