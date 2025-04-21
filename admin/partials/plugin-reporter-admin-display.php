<?php
/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/notalentgeek/wp-plugin-reporter
 * @since      1.0.0
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Get sort order
$sort_by = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : 'default';
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <p><?php esc_html_e( 'This tool provides comprehensive information about all installed plugins, including update status and disk usage.', 'plugin-reporter' ); ?></p>

    <!-- Export options -->
    <div class="plugin-reporter-export">
        <h2><?php esc_html_e( 'Export Options', 'plugin-reporter' ); ?></h2>
        <p><?php esc_html_e( 'Export plugin information in CSV or JSON format for documentation or analysis.', 'plugin-reporter' ); ?></p>

        <!-- Single checkbox for file size data -->
        <div class="export-option-checkbox">
            <label>
                <input type="checkbox" id="include_size_toggle" value="1">
                <?php esc_html_e( 'Include file size data', 'plugin-reporter' ); ?>
            </label>
        </div>

        <div class="export-options">
            <!-- CSV Export -->
            <form method="post" action="">
                <?php wp_nonce_field( 'plugin_reporter_export_csv', 'plugin_reporter_nonce' ); ?>
                <input type="hidden" name="action" value="export_csv">
                <input type="hidden" name="include_size" id="include_size_csv" value="0">
                <button type="submit" class="button button-primary">
                    <span class="dashicons dashicons-media-spreadsheet"></span>
                    <?php esc_html_e( 'Export as CSV', 'plugin-reporter' ); ?>
                </button>
            </form>

            <!-- JSON Export -->
            <form method="post" action="">
                <?php wp_nonce_field( 'plugin_reporter_export_json', 'plugin_reporter_json_nonce' ); ?>
                <input type="hidden" name="action" value="export_json">
                <input type="hidden" name="include_size" id="include_size_json" value="0">
                <button type="submit" class="button button-primary">
                    <span class="dashicons dashicons-media-code"></span>
                    <?php esc_html_e( 'Export as JSON', 'plugin-reporter' ); ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Summary section -->
    <div class="plugin-reporter-summary">
        <h2><?php esc_html_e( 'Summary', 'plugin-reporter' ); ?></h2>
        <div class="summary-cards">

            <!-- Total plugins card -->
            <div class="summary-card">
                <h3><?php esc_html_e( 'Total Plugins', 'plugin-reporter' ); ?></h3>
                <div class="plugin-count"><?php echo esc_html( count( $plugins_data ) ); ?></div>
                <div class="plugin-status-counts">
                    <span class="plugin-card active"><?php echo esc_html( $active_count ); ?> <?php esc_html_e( 'Active', 'plugin-reporter' ); ?></span>
                    <span class="plugin-card inactive"><?php echo esc_html( $inactive_count ); ?> <?php esc_html_e( 'Inactive', 'plugin-reporter' ); ?></span>
                </div>
            </div>

            <!-- Updates card -->
            <div class="summary-card">
                <h3><?php esc_html_e( 'Updates Available', 'plugin-reporter' ); ?></h3>
                <div class="update-count <?php echo esc_attr( $update_count > 0 ? 'update-available' : 'up-to-date' ); ?>">
                    <?php echo esc_html( $update_count ); ?>
                </div>
                <div class="update-status">
                    <?php if ( $update_count > 0 ) : ?>
                        <p><?php esc_html_e( 'Some plugins need updates. See details below.', 'plugin-reporter' ); ?></p>
                    <?php else : ?>
                        <p><?php esc_html_e( 'All plugins are up to date.', 'plugin-reporter' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Add size card -->
            <div class="summary-card">
                <h3><?php esc_html_e( 'Total Disk Usage', 'plugin-reporter' ); ?></h3>
                <div class="plugin-size"><?php echo esc_html( $total_size_formatted ); ?></div>
                <div class="plugin-size-details">
                    <p><?php esc_html_e( 'Combined size of all plugins', 'plugin-reporter' ); ?></p>
                </div>
            </div>

        </div>
    </div>

    <!-- Plugin details with sorting options -->
    <div class="plugin-reporter-content">
        <h2 class="plugin-section-header">
            <?php esc_html_e( 'Plugin Details', 'plugin-reporter' ); ?>
            <div class="sort-controls">
                <label for="sort-plugins"><?php esc_html_e( 'Sort by:', 'plugin-reporter' ); ?></label>
                <select id="sort-plugins" class="sort-select">
                    <option value="default" <?php selected( $sort_by, 'default' ); ?>><?php esc_html_e( 'Update Status', 'plugin-reporter' ); ?></option>
                    <option value="size" <?php selected( $sort_by, 'size' ); ?>><?php esc_html_e( 'Size (largest first)', 'plugin-reporter' ); ?></option>
                    <option value="name" <?php selected( $sort_by, 'name' ); ?>><?php esc_html_e( 'Name (A-Z)', 'plugin-reporter' ); ?></option>
                    <option value="status" <?php selected( $sort_by, 'status' ); ?>><?php esc_html_e( 'Status', 'plugin-reporter' ); ?></option>
                </select>
            </div>
        </h2>

        <!-- Plugin table -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 25%;"><?php esc_html_e( 'Plugin', 'plugin-reporter' ); ?></th>
                    <th style="width: 80px;"><?php esc_html_e( 'Status', 'plugin-reporter' ); ?></th>
                    <th style="width: 12%;"><?php esc_html_e( 'Current Version', 'plugin-reporter' ); ?></th>
                    <th style="width: 12%;"><?php esc_html_e( 'Latest Version', 'plugin-reporter' ); ?></th>
                    <th style="width: 12%;"><?php esc_html_e( 'Update Available', 'plugin-reporter' ); ?></th>
                    <th style="width: 12%;"><?php esc_html_e( 'Size', 'plugin-reporter' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Create a copy of plugins data for sorting
                $sorted_plugins = $plugins_data;

                // Sort plugins based on the selected criteria
                switch ( $sort_by ) {
                    case 'size':
                        // Sort by size (largest first)
                        usort( $sorted_plugins, function( $a, $b ) {
                            $size_a = isset( $a['size_bytes'] ) ? $a['size_bytes'] : 0;
                            $size_b = isset( $b['size_bytes'] ) ? $b['size_bytes'] : 0;
                            return $size_b - $size_a;
                        });
                        break;

                    case 'name':
                        // Sort by name (A-Z)
                        usort( $sorted_plugins, function( $a, $b ) {
                            return strcasecmp( $a['name'], $b['name'] );
                        });
                        break;

                    case 'status':
                        // Sort by status (active first)
                        usort( $sorted_plugins, function( $a, $b ) {
                            if ( $a['is_active'] && ! $b['is_active'] ) return -1;
                            if ( ! $a['is_active'] && $b['is_active'] ) return 1;
                            return strcasecmp( $a['name'], $b['name'] );
                        });
                        break;

                    default:
                        // Default sort: updates first, then active, then alphabetical
                        usort( $sorted_plugins, function( $a, $b ) {
                            // First sort by update availability
                            if ( $a['update_available'] && ! $b['update_available'] ) return -1;
                            if ( ! $a['update_available'] && $b['update_available'] ) return 1;

                            // Then by active status
                            if ( $a['is_active'] && ! $b['is_active'] ) return -1;
                            if ( ! $a['is_active'] && $b['is_active'] ) return 1;

                            // Finally by name
                            return strcasecmp( $a['name'], $b['name'] );
                        });
                        break;
                }

                foreach ( $sorted_plugins as $plugin ) :
                ?>
                    <tr>
                        <td>
                            <strong><?php echo esc_html( $plugin['name'] ); ?></strong>
                            <div class="row-actions">
                                <span class="description"><?php echo esc_html( wp_trim_words( $plugin['description'], 20 ) ); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="plugin-status-badge <?php echo esc_attr( $plugin['is_active'] ? 'active' : 'inactive' ); ?>">
                                <?php echo esc_html( $plugin['status'] ); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html( $plugin['version'] ); ?></td>
                        <td class="<?php echo esc_attr( $plugin['update_available'] ? 'update-available' : '' ); ?>">
                            <?php echo esc_html( $plugin['latest_version'] ); ?>
                        </td>
                        <td>
                            <?php if ( $plugin['update_available'] ) : ?>
                                <span class="update-available"><?php esc_html_e( 'Yes', 'plugin-reporter' ); ?></span>
                            <?php else : ?>
                                <span class="up-to-date"><?php esc_html_e( 'No', 'plugin-reporter' ); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo isset( $plugin['size_human'] ) ? esc_html( $plugin['size_human'] ) : esc_html__( 'Unknown', 'plugin-reporter' ); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer with links -->
    <div class="plugin-reporter-footer">
        <p>
            <?php
            printf(
                // translators: %s: URL to WordPress Updates page
                esc_html__( 'Go to %s to perform updates.', 'plugin-reporter' ),
                '<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">' . esc_html__( 'WordPress Updates', 'plugin-reporter' ) . '</a>'
            );
            ?>
        </p>
    </div>
</div>
