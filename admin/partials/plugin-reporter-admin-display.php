<?php
/**
 * Provide a admin area view for the plugin
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
    <p><?php _e( 'This tool provides comprehensive information about all installed plugins, including update status.', 'plugin-reporter' ); ?></p>

    <!-- Export options -->
    <div class="plugin-reporter-export" style="margin-bottom: 20px; background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
        <h2 style="margin-top: 0;"><?php _e( 'Export Options', 'plugin-reporter' ); ?></h2>
        <p><?php _e( 'Export plugin information in CSV or JSON format for documentation or analysis.', 'plugin-reporter' ); ?></p>

        <div style="display: flex; gap: 15px; margin-top: 15px;">
            <!-- CSV Export -->
            <form method="post" action="">
                <?php wp_nonce_field( 'plugin_reporter_export_csv', 'plugin_reporter_nonce' ); ?>
                <input type="hidden" name="action" value="export_csv">
                <button type="submit" class="button button-primary" style="padding: 0 15px;">
                    <span class="dashicons dashicons-media-spreadsheet" style="vertical-align: text-bottom; margin-right: 5px;"></span>
                    <?php _e( 'Export as CSV', 'plugin-reporter' ); ?>
                </button>
            </form>

            <!-- JSON Export -->
            <form method="post" action="">
                <?php wp_nonce_field( 'plugin_reporter_export_json', 'plugin_reporter_json_nonce' ); ?>
                <input type="hidden" name="action" value="export_json">
                <button type="submit" class="button" style="padding: 0 15px;">
                    <span class="dashicons dashicons-media-code" style="vertical-align: text-bottom; margin-right: 5px;"></span>
                    <?php _e( 'Export as JSON', 'plugin-reporter' ); ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Summary section -->
    <div class="plugin-reporter-summary">
        <h2><?php _e( 'Summary', 'plugin-reporter' ); ?></h2>
        <div class="summary-cards" style="display: flex; gap: 20px;">

            <!-- Total plugins card -->
            <div class="summary-card" style="flex: 1; background: #f9f9f9; padding: 15px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin-top: 0;"><?php _e( 'Total Plugins', 'plugin-reporter' ); ?></h3>
                <div style="font-size: 24px; font-weight: bold;"><?php echo count( $plugins_data ); ?></div>
                <div style="margin-top: 10px;">
                    <span class="plugin-card active"><?php echo $active_count; ?> <?php _e( 'Active', 'plugin-reporter' ); ?></span>
                    <span class="plugin-card inactive"><?php echo $inactive_count; ?> <?php _e( 'Inactive', 'plugin-reporter' ); ?></span>
                </div>
            </div>

            <!-- Updates card -->
            <div class="summary-card" style="flex: 1; background: #f9f9f9; padding: 15px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin-top: 0;"><?php _e( 'Updates Available', 'plugin-reporter' ); ?></h3>
                <div style="font-size: 24px; font-weight: bold; <?php echo ( $update_count > 0 ? 'color: #d54e21;' : 'color: #46b450;' ); ?>">
                    <?php echo $update_count; ?>
                </div>
                <div style="margin-top: 10px;">
                    <?php if ( $update_count > 0 ) : ?>
                        <p><?php _e( 'Some plugins need updates. See details below.', 'plugin-reporter' ); ?></p>
                    <?php else : ?>
                        <p><?php _e( 'All plugins are up to date.', 'plugin-reporter' ); ?></p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Plugin details with tabbed interface -->
    <div class="plugin-reporter-tabs" style="margin-top: 20px;">
        <h2 style="margin-bottom: 10px;"><?php _e( 'Plugin Details', 'plugin-reporter' ); ?></h2>

        <nav class="nav-tab-wrapper wp-clearfix">
            <?php if ( $update_count > 0 ) : ?>
                <a href="#needs-update" class="nav-tab nav-tab-active" id="tab-needs-update"><?php _e( 'Needs Update', 'plugin-reporter' ); ?> <span class="update-count"><?php echo $update_count; ?></span></a>
            <?php endif; ?>
        </nav>

        <!-- Plugin listing (previously in All plugins tab but now shown directly) -->
        <div id="plugin-listing" class="plugin-content" style="display: block;">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 30%;"><?php _e( 'Plugin', 'plugin-reporter' ); ?></th>
                        <th style="width: 100px;"><?php _e( 'Status', 'plugin-reporter' ); ?></th>
                        <th style="width: 15%;"><?php _e( 'Current Version', 'plugin-reporter' ); ?></th>
                        <th style="width: 15%;"><?php _e( 'Latest Version', 'plugin-reporter' ); ?></th>
                        <th style="width: 15%;"><?php _e( 'Update Available', 'plugin-reporter' ); ?></th>
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
                                <span class="plugin-status-badge" style="background-color: <?php echo $plugin['is_active'] ? '#e7f7e5' : '#f7f7f7'; ?>; border: 1px solid <?php echo $plugin['is_active'] ? '#8bcb81' : '#e0e0e0'; ?>;">
                                    <?php echo esc_html( $plugin['status'] ); ?>
                                </span>
                            </td>
                            <td><?php echo esc_html( $plugin['version'] ); ?></td>
                            <td style="<?php echo $plugin['update_available'] ? 'color: #d54e21; font-weight: bold;' : ''; ?>">
                                <?php echo esc_html( $plugin['latest_version'] ); ?>
                            </td>
                            <td>
                                <?php if ( $plugin['update_available'] ) : ?>
                                    <span class="update-available"><?php _e( 'Yes', 'plugin-reporter' ); ?></span>
                                <?php else : ?>
                                    <span class="up-to-date"><?php _e( 'No', 'plugin-reporter' ); ?></span>
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
                            <th style="width: 30%;"><?php _e( 'Plugin', 'plugin-reporter' ); ?></th>
                            <th style="width: 100px;"><?php _e( 'Status', 'plugin-reporter' ); ?></th>
                            <th style="width: 15%;"><?php _e( 'Current Version', 'plugin-reporter' ); ?></th>
                            <th style="width: 15%;"><?php _e( 'Latest Version', 'plugin-reporter' ); ?></th>
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
                                    <span class="plugin-status-badge" style="background-color: <?php echo $plugin['is_active'] ? '#e7f7e5' : '#f7f7f7'; ?>; border: 1px solid <?php echo $plugin['is_active'] ? '#8bcb81' : '#e0e0e0'; ?>;">
                                        <?php echo esc_html( $plugin['status'] ); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html( $plugin['version'] ); ?></td>
                                <td style="color: #d54e21; font-weight: bold;">
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
    <div style="margin-top: 20px; font-size: 13px; color: #666;">
        <p>
            <?php
            printf(
                __( 'Go to <a href="%s">WordPress Updates</a> to perform updates.', 'plugin-reporter' ),
                admin_url( 'update-core.php' )
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
            $('.tab-content, .plugin-content').hide();

            // Show the selected tab
            $($(this).attr('href')).show();

            // Update active tab class
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
        });

        // If no active tab, show the plugin listing by default
        if ($('.nav-tab-active').length === 0) {
            $('#plugin-listing').show();
        }
    });
</script>
