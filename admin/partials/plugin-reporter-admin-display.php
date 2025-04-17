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
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <p><?php esc_html_e( 'This tool provides comprehensive information about all installed plugins, including update status.', 'plugin-reporter' ); ?></p>

    <!-- Export options -->
    <div class="plugin-reporter-export">
        <h2><?php esc_html_e( 'Export Options', 'plugin-reporter' ); ?></h2>
        <p><?php esc_html_e( 'Export plugin information in CSV or JSON format for documentation or analysis.', 'plugin-reporter' ); ?></p>

        <div class="export-options">
            <!-- CSV Export -->
            <form method="post" action="">
                <?php wp_nonce_field( 'plugin_reporter_export_csv', 'plugin_reporter_nonce' ); ?>
                <input type="hidden" name="action" value="export_csv">
                <button type="submit" class="button button-primary">
                    <span class="dashicons dashicons-media-spreadsheet"></span>
                    <?php esc_html_e( 'Export as CSV', 'plugin-reporter' ); ?>
                </button>
            </form>

            <!-- JSON Export -->
            <form method="post" action="">
                <?php wp_nonce_field( 'plugin_reporter_export_json', 'plugin_reporter_json_nonce' ); ?>
                <input type="hidden" name="action" value="export_json">
                <button type="submit" class="button">
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

        </div>
    </div>

    <!-- Plugin details with tabbed interface -->
    <div class="plugin-reporter-tabs">
        <h2><?php esc_html_e( 'Plugin Details', 'plugin-reporter' ); ?></h2>

        <nav class="nav-tab-wrapper wp-clearfix">
            <a href="#all-plugins" class="nav-tab nav-tab-active" id="tab-all-plugins"><?php esc_html_e( 'All Plugins', 'plugin-reporter' ); ?></a>
            <?php if ( $update_count > 0 ) : ?>
                <a href="#needs-update" class="nav-tab" id="tab-needs-update"><?php esc_html_e( 'Needs Update', 'plugin-reporter' ); ?> <span class="update-count"><?php echo esc_html( $update_count ); ?></span></a>
            <?php endif; ?>
        </nav>

        <!-- All plugins tab -->
        <div id="all-plugins" class="tab-content">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 30%;"><?php esc_html_e( 'Plugin', 'plugin-reporter' ); ?></th>
                        <th style="width: 100px;"><?php esc_html_e( 'Status', 'plugin-reporter' ); ?></th>
                        <th style="width: 15%;"><?php esc_html_e( 'Current Version', 'plugin-reporter' ); ?></th>
                        <th style="width: 15%;"><?php esc_html_e( 'Latest Version', 'plugin-reporter' ); ?></th>
                        <th style="width: 15%;"><?php esc_html_e( 'Update Available', 'plugin-reporter' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Sort plugins - updates first, then active, then alphabetical
                    usort( $plugins_data, function( $a, $b ) {
                        // First sort by update availability
                        if ( $a['update_available'] && ! $b['update_available'] ) return -1;
                        if ( ! $a['update_available'] && $b['update_available'] ) return 1;

                        // Then by active status
                        if ( $a['is_active'] && ! $b['is_active'] ) return -1;
                        if ( ! $a['is_active'] && $b['is_active'] ) return 1;

                        // Finally by name
                        return strcasecmp( $a['name'], $b['name'] );
                    });

                    foreach ( $plugins_data as $plugin ) :
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Needs update tab -->
        <?php if ( $update_count > 0 ) : ?>
            <div id="needs-update" class="tab-content" style="display: none;">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 30%;"><?php esc_html_e( 'Plugin', 'plugin-reporter' ); ?></th>
                            <th style="width: 100px;"><?php esc_html_e( 'Status', 'plugin-reporter' ); ?></th>
                            <th style="width: 15%;"><?php esc_html_e( 'Current Version', 'plugin-reporter' ); ?></th>
                            <th style="width: 15%;"><?php esc_html_e( 'Latest Version', 'plugin-reporter' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Filter plugins needing updates
                        $needs_update = array_filter( $plugins_data, function( $plugin ) {
                            return $plugin['update_available'];
                        });

                        // Sort by active first, then alphabetical
                        usort( $needs_update, function( $a, $b ) {
                            // First by active status
                            if ( $a['is_active'] && ! $b['is_active'] ) return -1;
                            if ( ! $a['is_active'] && $b['is_active'] ) return 1;

                            // Then by name
                            return strcasecmp( $a['name'], $b['name'] );
                        });

                        foreach ( $needs_update as $plugin ) :
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
                                <td class="update-available">
                                    <?php echo esc_html( $plugin['latest_version'] ); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
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

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Tab functionality
        $('.nav-tab').click(function(e) {
            e.preventDefault();

            // Hide all tabs
            $('.tab-content').hide();

            // Show the selected tab
            $($(this).attr('href')).show();

            // Update active tab class
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
        });

        // Show the first tab by default
        $('#all-plugins').show();
        $('#needs-update').hide();
    });
</script>
