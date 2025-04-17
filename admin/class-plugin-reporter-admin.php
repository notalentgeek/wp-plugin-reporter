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
     * @param    string                       $plugin_name       The name of this plugin.
     * @param    string                       $version           The version of this plugin.
     * @param    Plugin_Reporter_Exporter     $exporter          The exporter instance.
     * @param    Plugin_Reporter_Update_Info  $update_info       The update info instance.
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
        }
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
        $total_size_bytes = 0;

        // Get plugin data with update info
        $plugins_data = array();
        foreach ( $all_plugins as $plugin_file => $plugin ) {
            $is_active = in_array( $plugin_file, $active_plugins, true );
            $update_info = $this->update_info->get_plugin_update_info( $plugin_file );

            // Get plugin size information
            $size_data = $this->exporter->get_plugin_size( $plugin_file );
            $size_bytes = $size_data['total_size'];
            $size_human = $size_data['human_readable_size'];

            // Add to total size
            $total_size_bytes += $size_bytes;

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
                'file'             => $plugin_file,
                'name'             => $plugin['Name'],
                'description'      => $plugin['Description'],
                'author'           => $plugin['Author'],
                'plugin_uri'       => isset( $plugin['PluginURI'] ) ? $plugin['PluginURI'] : '',
                'is_active'        => $is_active,
                'status'           => $is_active ? 'Active' : 'Inactive',
                'version'          => $plugin['Version'],
                'current_version'  => $update_info['current_version'],
                'latest_version'   => $update_info['latest_version'],
                'update_available' => $update_info['update_available'],
                'size_bytes'       => $size_bytes,
                'size_human'       => $size_human
            );
        }

        // Format total size
        $total_size_formatted = $this->exporter->format_file_size( $total_size_bytes );

        // Include the admin display
        include_once PLUGIN_REPORTER_PATH . 'admin/partials/plugin-reporter-admin-display.php';
    }

    /**
     * Process export request
     *
     * @since    1.0.0
     */
    public function process_export() {
        // Check if this is an export action
        if ( isset( $_POST['action'] ) ) {
            $action = sanitize_text_field( wp_unslash( $_POST['action'] ) );

            if ( 'export_csv' === $action ) {
                // Verify CSV export nonce
                if ( ! isset( $_POST['plugin_reporter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['plugin_reporter_nonce'] ) ), 'plugin_reporter_export_csv' ) ) {
                    wp_die( 'Security check failed' );
                }

                $include_size = isset( $_POST['include_size'] ) ? (bool) $_POST['include_size'] : false;
                $data = $this->exporter->export_as_csv( $include_size );
                $filename = 'plugin-report-' . date( 'Y-m-d' ) . '.csv';
                $content_type = 'text/csv';

                // Output headers and data
                header( 'Content-Type: ' . $content_type );
                header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
                header( 'Cache-Control: no-cache, no-store, must-revalidate' );
                header( 'Pragma: no-cache' );
                header( 'Expires: 0' );
                echo $data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                exit;
            }

            if ( 'export_json' === $action ) {
                // Verify JSON export nonce
                if ( ! isset( $_POST['plugin_reporter_json_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['plugin_reporter_json_nonce'] ) ), 'plugin_reporter_export_json' ) ) {
                    wp_die( 'Security check failed' );
                }

                $include_size = isset( $_POST['include_size'] ) ? (bool) $_POST['include_size'] : false;
                $data = $this->exporter->export_as_json( $include_size );
                $filename = 'plugin-report-' . date( 'Y-m-d' ) . '.json';
                $content_type = 'application/json';

                // Output headers and data
                header( 'Content-Type: ' . $content_type );
                header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
                header( 'Cache-Control: no-cache, no-store, must-revalidate' );
                header( 'Pragma: no-cache' );
                header( 'Expires: 0' );
                echo $data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                exit;
            }
        }
    }

    /**
     * Render the admin page
     *
     * @since    1.0.0
     */
    public function render_admin_page() {
        // Get plugin data with size information
        $exporter = new Plugin_Reporter_Exporter();
        $plugins = $exporter->get_plugin_data( true, true );

        // Calculate summary statistics including file size
        $total_plugins = count( $plugins );
        $active_plugins = count( array_filter( $plugins, function( $plugin ) {
            return 'active' === $plugin['status'];
        }));
        $inactive_plugins = $total_plugins - $active_plugins;

        // Calculate total size
        $total_size_bytes = array_reduce( $plugins, function( $carry, $plugin ) {
            return $carry + (isset($plugin['size_bytes']) ? $plugin['size_bytes'] : 0);
        }, 0);
        $total_size_formatted = $exporter->format_file_size( $total_size_bytes );

        // Sort plugins by size if requested
        if ( isset( $_GET['orderby'] ) && 'size' === $_GET['orderby'] ) {
            usort( $plugins, function( $a, $b ) {
                $size_a = isset( $a['size_bytes'] ) ? $a['size_bytes'] : 0;
                $size_b = isset( $b['size_bytes'] ) ? $b['size_bytes'] : 0;

                if ( isset( $_GET['order'] ) && 'asc' === $_GET['order'] ) {
                    return $size_a - $size_b;
                }

                return $size_b - $size_a; // Default to descending
            });
        }

        // Include the view file
        include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/plugin-reporter-admin-display.php';
    }
}
