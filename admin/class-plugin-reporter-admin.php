<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/notalentgeek/wp-plugin-reporter
 * @since      1.0.0
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for the admin area.
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/admin
 * @author     New Naratif Development Team <tech@newnaratif.com>
 */
class Plugin_Reporter_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The exporter object.
     *
     * @since    1.0.0
     * @access   private
     * @var      Plugin_Reporter_Exporter    $exporter    The exporter instance.
     */
    private $exporter;

    /**
     * The update info object.
     *
     * @since    1.0.0
     * @access   private
     * @var      Plugin_Reporter_Update_Info    $update_info    The update info instance.
     */
    private $update_info;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version    The version of this plugin.
     * @param    Plugin_Reporter_Exporter    $exporter    The exporter instance.
     * @param    Plugin_Reporter_Update_Info    $update_info    The update info instance.
     */
    public function __construct( $plugin_name, $version, $exporter, $update_info ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->exporter = $exporter;
        $this->update_info = $update_info;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        $screen = get_current_screen();

        // Only load on our plugin's page
        if ( $screen && strpos( $screen->id, 'plugin-reporter' ) !== false ) {
            wp_enqueue_style(
                $this->plugin_name,
                PLUGIN_REPORTER_URL . 'admin/css/plugin-reporter-admin.css',
                array(),
                $this->version,
                'all'
            );

            // Add inline styles for update display
            $this->add_admin_styles();
        }
    }

    /**
     * Add admin styles for the page
     *
     * @since    1.0.0
     */
    private function add_admin_styles() {
        $styles = '
            .plugin-reporter-summary {
                margin-top: 20px;
                background: #fff;
                padding: 15px;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            .update-available {
                color: #d54e21;
                font-weight: bold;
            }
            .up-to-date {
                color: #46b450;
            }
            .row-actions {
                color: #777;
                font-size: 12px;
                margin-top: 4px;
            }
            .plugin-card {
                display: inline-block;
                margin-right: 10px;
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 11px;
            }
            .plugin-card.active {
                background: #e7f7e5;
                border: 1px solid #8bcb81;
            }
            .plugin-card.inactive {
                background: #f7f7f7;
                border: 1px solid #e0e0e0;
            }
            .plugin-status-badge {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 3px;
                font-size: 12px;
                font-weight: 500;
            }
        ';

        wp_add_inline_style( $this->plugin_name, $styles );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        $screen = get_current_screen();

        // Only load on our plugin's page
        if ( $screen && strpos( $screen->id, 'plugin-reporter' ) !== false ) {
            wp_enqueue_script(
                $this->plugin_name,
                PLUGIN_REPORTER_URL . 'admin/js/plugin-reporter-admin.js',
                array( 'jquery' ),
                $this->version,
                false
            );
        }
    }

    /**
     * Add menu item for the plugin reporter.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        add_management_page(
            __( 'Plugin Reporter', 'plugin-reporter' ), // Page title
            __( 'Plugin Reporter', 'plugin-reporter' ), // Menu title
            'manage_options', // Capability
            $this->plugin_name, // Menu slug
            array( $this, 'display_plugin_admin_page' ) // Callback
        );
    }

    /**
     * Render the admin page for the plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        // Get all plugins data
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();
        $active_plugins = get_option( 'active_plugins', array() );

        // Initialize counters
        $active_count = 0;
        $inactive_count = 0;
        $update_count = 0;

        // Get plugin data with update info
        $plugins_data = array();
        foreach ( $all_plugins as $plugin_file => $plugin ) {
            $is_active = in_array( $plugin_file, $active_plugins, true );
            $update_info = $this->update_info->get_plugin_update_info( $plugin_file );

            // Count status
            if ( $is_active ) {
                $active_count++;
            } else {
                $inactive_count++;
            }

            // Count updates
            if ( $update_info['update_available'] ) {
                $update_count++;
            }

            $plugins_data[] = array(
                'file' => $plugin_file,
                'name' => $plugin['Name'],
                'description' => $plugin['Description'],
                'author' => $plugin['Author'],
                'plugin_uri' => isset( $plugin['PluginURI'] ) ? $plugin['PluginURI'] : '',
                'is_active' => $is_active,
                'status' => $is_active ? 'Active' : 'Inactive',
                'version' => $plugin['Version'],
                'current_version' => $update_info['current_version'],
                'latest_version' => $update_info['latest_version'],
                'update_available' => $update_info['update_available']
            );
        }

        // Include the admin display
        include_once PLUGIN_REPORTER_PATH . 'admin/partials/plugin-reporter-admin-display.php';
    }

    /**
     * Process export requests.
     *
     * @since    1.0.0
     */
    public function process_export() {
        // CSV Export
        if ( isset( $_POST['action'] ) && 'export_csv' === $_POST['action'] ) {
            // Verify nonce
            if ( ! isset( $_POST['plugin_reporter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['plugin_reporter_nonce'] ) ), 'plugin_reporter_export_csv' ) ) {
                wp_die( esc_html__( 'Security check failed. Please try again.', 'plugin-reporter' ) );
            }

            // Check capabilities
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( esc_html__( 'You do not have permission to export plugin data.', 'plugin-reporter' ) );
            }

            // Turn off error reporting for this process
            $original_error_reporting = error_reporting();
            $original_display_errors = ini_get('display_errors');
            error_reporting(0);
            ini_set('display_errors', 0);

            // Set headers for CSV download
            header( 'Content-Type: text/csv; charset=utf-8' );
            header( 'Content-Disposition: attachment; filename=plugin-report-' . date( 'Y-m-d' ) . '.csv' );
            header( 'Pragma: no-cache' );
            header( 'Expires: 0' );

            // Output CSV data
            echo $this->exporter->export_as_csv();

            // Restore original error reporting settings
            error_reporting($original_error_reporting);
            ini_set('display_errors', $original_display_errors);

            exit;
        }

        // JSON Export
        if ( isset( $_POST['action'] ) && 'export_json' === $_POST['action'] ) {
            // Verify nonce
            if ( ! isset( $_POST['plugin_reporter_json_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['plugin_reporter_json_nonce'] ) ), 'plugin_reporter_export_json' ) ) {
                wp_die( esc_html__( 'Security check failed. Please try again.', 'plugin-reporter' ) );
            }

            // Check capabilities
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( esc_html__( 'You do not have permission to export plugin data.', 'plugin-reporter' ) );
            }

            // Turn off error reporting for this process
            $original_error_reporting = error_reporting();
            $original_display_errors = ini_get('display_errors');
            error_reporting(0);
            ini_set('display_errors', 0);

            // Set headers for JSON download
            header( 'Content-Type: application/json; charset=utf-8' );
            header( 'Content-Disposition: attachment; filename=plugin-report-' . date( 'Y-m-d' ) . '.json' );
            header( 'Pragma: no-cache' );
            header( 'Expires: 0' );

            // Output JSON data
            echo $this->exporter->export_as_json();

            // Restore original error reporting settings
            error_reporting($original_error_reporting);
            ini_set('display_errors', $original_display_errors);

            exit;
        }
    }
}
