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

        // Handle checkbox toggle for both export forms
        $('#include_size_toggle').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('#include_size_csv, #include_size_json').val(isChecked ? '1' : '0');
        });

        // Handle sort select change
        $('#sort-plugins').on('change', function() {
            var sortValue = $(this).val();
            var currentUrl = window.location.href;

            // Remove existing sort parameter if it exists
            currentUrl = currentUrl.replace(/&sort=[^&]*/, '');
            currentUrl = currentUrl.replace(/\?sort=[^&]*&/, '?');

            // Add the new sort parameter
            var separator = currentUrl.indexOf('?') !== -1 ? '&' : '?';
            window.location.href = currentUrl + separator + 'sort=' + sortValue;
        });
    });

})( jQuery );
