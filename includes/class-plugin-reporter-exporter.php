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
     *
     * @since    1.0.0
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
     * @param    bool    $include_size_info      Whether to include size information.
     * @return   array   The array of plugin data.
     */
    public function get_plugin_data( $include_update_info = true, $include_size_info = false ) {
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
                'name'        => $plugin['Name'],
                'version'     => $plugin['Version'],
                'status'      => $status,
                'description' => $plugin['Description'],
                'author'      => $plugin['Author'],
                'plugin_uri'  => isset( $plugin['PluginURI'] ) ? $plugin['PluginURI'] : '',
                'plugin_path' => $plugin_path,
            );

            // Add update information if requested and available
            if ( $include_update_info && isset( $this->update_info ) ) {
                $update_info = $this->update_info->get_plugin_update_info( $plugin_path );
                $plugin_info['update_available'] = $update_info['update_available'] ? 'Yes' : 'No';
                $plugin_info['latest_version'] = $update_info['latest_version'];
            }

            // Add size information if requested
            if ( $include_size_info ) {
                $size_data = $this->calculate_plugin_size( $plugin_path );
                $plugin_info['size_bytes'] = $size_data['total_size'];
                $plugin_info['size_human'] = $size_data['human_readable_size'];
                $plugin_info['subdirectory_sizes'] = $size_data['subdirectories'];
            }

            $plugin_data[] = $plugin_info;
        }

        return $plugin_data;
    }

    /**
     * Get cached plugin size or calculate if not cached
     *
     * @since    1.0.0
     * @param    string    $plugin_path    The plugin main file path
     * @return   array     Size information array
     */
    public function get_plugin_size( $plugin_path ) {
        // Create a cache key specific to this plugin
        $cache_key = 'plugin_reporter_size_' . sanitize_key( $plugin_path );

        // Try to get from cache first
        $cached_size = get_transient( $cache_key );
        if ( false !== $cached_size ) {
            return $cached_size;
        }

        // If not in cache, calculate size
        $size_data = $this->calculate_plugin_size( $plugin_path );

        // Store in cache for 24 hours (86400 seconds)
        // You can adjust this time as needed
        set_transient( $cache_key, $size_data, 86400 );

        return $size_data;
    }

    /**
     * Clear plugin size cache
     *
     * @since    1.0.0
     * @param    string    $plugin_path    Optional. Clear specific plugin cache, or all if null.
     * @return   void
     */
    public function clear_plugin_size_cache( $plugin_path = null ) {
        if ( null === $plugin_path ) {
            // Clear all plugin size caches
            global $wpdb;
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM $wpdb->options WHERE option_name LIKE %s",
                    $wpdb->esc_like( '_transient_plugin_reporter_size_' ) . '%'
                )
            );

            // Also clear expired transients
            delete_expired_transients();
        } else {
            // Clear specific plugin cache
            $cache_key = 'plugin_reporter_size_' . sanitize_key( $plugin_path );
            delete_transient( $cache_key );
        }
    }

    /**
     * Export plugin data as CSV.
     *
     * @since    1.0.0
     * @param    bool    $include_size_info    Whether to include size information.
     * @return   string   The CSV data.
     */
    public function export_as_csv( $include_size_info = false ) {
        $plugin_data = $this->get_plugin_data( true, $include_size_info );
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

        // Add size info headers if available
        if ( $include_size_info ) {
            $headers[] = 'Size';
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

            // Add size info if available
            if ( isset( $plugin['size_human'] ) ) {
                $row[] = $plugin['size_human'];
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
     * @param    bool    $include_size_info    Whether to include size information.
     * @return   string    The JSON data.
     */
    public function export_as_json( $include_size_info = false ) {
        $plugin_data = $this->get_plugin_data( true, $include_size_info );
        return wp_json_encode( $plugin_data, JSON_PRETTY_PRINT );
    }

    /**
     * Calculate the size of a plugin directory
     *
     * @since    1.0.0
     * @param    string    $plugin_path    The plugin main file path (relative to plugins directory)
     * @return   array     Array containing total size and subdirectory breakdown
     */
    public function calculate_plugin_size( $plugin_path ) {
        // Convert plugin file path to directory path
        $directory = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( $plugin_path ) );

        // Initialize return array
        $size_data = array(
            'total_size' => 0,
            'subdirectories' => array(),
            'human_readable_size' => '',
        );

        // Check if directory exists
        if ( ! is_dir( $directory ) ) {
            return $size_data;
        }

        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator( $directory, RecursiveDirectoryIterator::SKIP_DOTS )
            );

            // Track subdirectory sizes
            $subdirectories = array();

            // Calculate total size
            foreach ( $iterator as $file ) {
                if ( $file->isFile() ) {
                    $size = $file->getSize();
                    $size_data['total_size'] += $size;

                    // Track subdirectory sizes
                    $subdir = str_replace( $directory, '', $file->getPath() );
                    $subdir = trim( $subdir, '/' );

                    if ( empty( $subdir ) ) {
                        $subdir = '/'; // Root directory
                    }

                    if ( ! isset( $subdirectories[ $subdir ] ) ) {
                        $subdirectories[ $subdir ] = 0;
                    }

                    $subdirectories[ $subdir ] += $size;
                }
            }

            // Sort subdirectories by size (largest first)
            arsort( $subdirectories );

            // Add to return data
            $size_data['subdirectories'] = $subdirectories;
            $size_data['human_readable_size'] = $this->format_file_size( $size_data['total_size'] );

        } catch ( Exception $e ) {
            // Handle any errors
            error_log( 'Plugin Reporter error calculating plugin size: ' . $e->getMessage() );
        }

        return $size_data;
    }

    /**
     * Format file size into human readable format
     *
     * @since    1.0.0
     * @param    int       $bytes    File size in bytes
     * @return   string    Formatted file size (e.g., "1.5 MB")
     */
    public function format_file_size( $bytes ) {
        if ( $bytes >= 1073741824 ) {
            $bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
        } elseif ( $bytes >= 1048576 ) {
            $bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
        } elseif ( $bytes >= 1024 ) {
            $bytes = number_format( $bytes / 1024, 2 ) . ' KB';
        } elseif ( $bytes > 1 ) {
            $bytes = $bytes . ' bytes';
        } elseif ( $bytes == 1 ) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
