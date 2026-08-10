/**
 * Theme Customization Admin JavaScript
 *
 * Handles tab switching, localStorage tab persistence, and wpColorPicker initialization.
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        // 1. Initialize Color Pickers
        $('.color-picker-field').wpColorPicker();

        // 2. Tab Navigation
        const tabButtons = $('.tab-nav-btn');
        const tabPanels = $('.tab-panel');
        const activeTabInput = $('#custom_theme_active_tab');

        tabButtons.on('click', function (e) {
            e.preventDefault();

            const targetTab = $(this).data('tab');

            // Update buttons
            tabButtons.removeClass('is-active');
            $(this).addClass('is-active');

            // Update panels
            tabPanels.removeClass('is-active');
            $('#tab-' + targetTab).addClass('is-active');

            // Update hidden input
            activeTabInput.val(targetTab);

            // Update URL hash for sharing / bookmarks
            if (history.replaceState) {
                const newUrl = window.location.protocol + '//' + window.location.host + window.location.pathname + '?page=custom-theme-options&tab=' + targetTab;
                window.history.replaceState({ path: newUrl }, '', newUrl);
            }
        });

        // 3. Dismiss Notification Alert
        $('.theme-save-alert-close').on('click', function () {
            $(this).closest('.theme-save-alert').fadeOut(200, function () {
                $(this).remove();
            });
        });
    });

})(jQuery);
