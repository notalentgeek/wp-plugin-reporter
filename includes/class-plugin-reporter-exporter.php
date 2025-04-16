<?php
/**
 * The file that defines the exporter functionality
 *
 * A class definition that includes methods for exporting plugin data.
 *
 * @link       https://github.com/notalentgeek/wp-plugin-reporter
 * @since      1.0.0
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/includes
 */

/**
 * The exporter functionality class.
 *
 * This is used to collect plugin data and format it for export.
 *
 * @since      1.0.0
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/includes
 * @author     New Naratif Development Team <tech@newnaratif.com>
 */
class Plugin_Reporter_Exporter {

    /**
     * The update info object.
     *
     * @since    1.0.0
     * @access   private
     * @var      Plugin_Reporter_Update_Info    $update_info    The update info instance.
     */
    private $update_info;

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize the update info object if available
        if ( class_exists( 'Plugin_Reporter_Update_Info' ) ) {
            $this->update_info = new Plugin_Reporter_Update_Info();
        }
    }

    /**
     * Get data about all installed plugins.
     *
     * @since    1.0.0
     * @param    bool    $include_update_info    Whether to include update information.
     * @return   array    The array of plugin data.
     */
    public function get_plugin_data( $include_update_info = true ) {
        // Ensure we have access to WordPress plugin functions
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        // Get all plugins data
        $all_plugins = get_plugins();
        $active_plugins = get_option( 'active_plugins', array() );
        $plugin_data = array();

        foreach ( $all_plugins as $plugin_path => $plugin ) {
            // Determine if plugin is active
            $status = in_array( $plugin_path, $active_plugins, true ) ? 'active' : 'inactive';

            // Build plugin data array
            $plugin_info = array(
                'name' => $plugin['Name'],
                'version' => $plugin['Version'],
                'status' => $status,
                'description' => $plugin['Description'],
                'author' => $plugin['Author'],
                'plugin_uri' => isset( $plugin['PluginURI'] ) ? $plugin['PluginURI'] : '',
                'plugin_path' => $plugin_path,
            );

            // Add update information if requested and available
            if ( $include_update_info && isset( $this->update_info ) ) {
                $update_info = $this->update_info->get_plugin_update_info( $plugin_path );
                $plugin_info['update_available'] = $update_info['update_available'] ? 'Yes' : 'No';
                $plugin_info['latest_version'] = $update_info['latest_version'];
            }

            $plugin_data[] = $plugin_info;
        }

        return $plugin_data;
    }

    /**
     * Export plugin data as CSV.
     *
     * @since    1.0.0
     * @return   string    The CSV data.
     */
    public function export_as_csv() {
        $plugin_data = $this->get_plugin_data();
        $output = fopen( 'php://temp', 'r+' );

        // Build headers based on available fields
        $headers = array(
            'Name',
            'Version',
            'Status',
            'Description',
            'Author',
            'Plugin URI',
            'Plugin Path',
        );

        // Add update info headers if available
        if ( isset( $plugin_data[0]['update_available'] ) ) {
            $headers[] = 'Update Available';
            $headers[] = 'Latest Version';
        }

        // Add CSV headers
        fputcsv( $output, $headers, ',', '"', '\\' );

        // Add plugin data rows
        foreach ( $plugin_data as $plugin ) {
            $row = array(
                $plugin['name'],
                $plugin['version'],
                $plugin['status'],
                $plugin['description'],
                $plugin['author'],
                $plugin['plugin_uri'],
                $plugin['plugin_path'],
            );

            // Add update info values if available
            if ( isset( $plugin['update_available'] ) ) {
                $row[] = $plugin['update_available'];
                $row[] = $plugin['latest_version'];
            }

            fputcsv( $output, $row, ',', '"', '\\' );
        }

        rewind( $output );
        $csv = stream_get_contents( $output );
        fclose( $output );

        return $csv;
    }

    /**
     * Export plugin data as JSON.
     *
     * @since    1.0.0
     * @return   string    The JSON data.
     */
    public function export_as_json() {
        $plugin_data = $this->get_plugin_data();
        return wp_json_encode( $plugin_data, JSON_PRETTY_PRINT );
    }
}
