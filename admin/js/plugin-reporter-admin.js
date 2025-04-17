/**
 * Plugin Reporter Admin JavaScript
 *
 * All of the JavaScript for your admin-specific functionality.
 *
 * @package    Plugin_Reporter
 * @subpackage Plugin_Reporter/admin/js
 */

(function( $ ) {
    'use strict';

    /**
     * Initialize the admin functionality when the DOM is ready.
     */
    $(document).ready(function() {
        // Tab functionality
        $('.plugin-reporter-tabs .nav-tab').on('click', function(e) {
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

})( jQuery );
