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

        // 4. Logo Media Uploader
        let logoMediaFrame;
        const uploadLogoBtn = $('#upload-logo-btn');
        const removeLogoBtn = $('#remove-logo-btn');
        const logoUrlInput = $('#custom_theme_logo_url');
        const logoIdInput = $('#custom_theme_logo_id');
        const logoPreviewBox = $('#logo-preview-box');
        const logoPreviewImg = $('#logo-preview-img');

        uploadLogoBtn.on('click', function (e) {
            e.preventDefault();

            if (logoMediaFrame) {
                logoMediaFrame.open();
                return;
            }

            logoMediaFrame = wp.media({
                title: 'Select or Upload Site Logo',
                button: { text: 'Use this Logo' },
                multiple: false,
                library: { type: 'image' }
            });

            logoMediaFrame.on('select', function () {
                const attachment = logoMediaFrame.state().get('selection').first().toJSON();
                
                logoUrlInput.val(attachment.url);
                logoIdInput.val(attachment.id);
                
                logoPreviewImg.attr('src', attachment.url);
                logoPreviewBox.show();
                removeLogoBtn.show();
                uploadLogoBtn.find('.upload-btn-text').text('Change Logo');
            });

            logoMediaFrame.open();
        });

        removeLogoBtn.on('click', function (e) {
            e.preventDefault();
            logoUrlInput.val('');
            logoIdInput.val('0');
            logoPreviewBox.hide();
            logoPreviewImg.attr('src', '');
            removeLogoBtn.hide();
            uploadLogoBtn.find('.upload-btn-text').text('Upload / Select Logo');
        });
    });

})(jQuery);
