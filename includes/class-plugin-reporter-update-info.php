<?php
/**
 * The file that defines the update information functionality
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/includes
 */

/**
 * The update information functionality class.
 *
 * This is used to collect plugin update information.
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/includes
 */
class Plugin_Reporter_Update_Info {

    /**
     * WordPress update data
     *
     * @var object
     */
    private $update_data;

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize update data
        $this->refresh_update_data();
    }

    /**
     * Simple test method
     *
     * @return string A test message
     */
    public function test_method() {
        return 'Update info class is working';
    }

    /**
     * Refresh the WordPress update data
     *
     * @return void
     */
    public function refresh_update_data() {
        // Force WordPress to check for plugin updates
        wp_update_plugins();

        // Get the update data
        $this->update_data = get_site_transient('update_plugins');
    }

    /**
     * Get update information for a specific plugin
     *
     * @param string $plugin_file The plugin file path relative to the plugins directory
     * @return array Update information for the plugin
     */
    public function get_plugin_update_info($plugin_file) {
        // Default update information
        $update_info = array(
            'update_available' => false,
            'current_version'  => '',
            'latest_version'   => '',
        );

        // Ensure we have access to WordPress plugin functions
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        // Get plugin data
        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_file);
        $update_info['current_version'] = isset($plugin_data['Version']) ? $plugin_data['Version'] : '';

        // Check if there's an update available
        if (isset($this->update_data->response[$plugin_file])) {
            $update = $this->update_data->response[$plugin_file];

            $update_info['update_available'] = true;
            $update_info['latest_version'] = isset($update->new_version) ? $update->new_version : '';
        } else {
            // No update available, so latest version is the current version
            $update_info['latest_version'] = $update_info['current_version'];
        }

        return $update_info;
    }
}
