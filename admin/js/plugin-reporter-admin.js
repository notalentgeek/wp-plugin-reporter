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
