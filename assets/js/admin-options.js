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

        // 5. Media Thumbnail Batch Regenerator
        const startRegenBtn = $('#btn-start-regenerate-thumbs');
        const pauseRegenBtn = $('#btn-pause-regenerate-thumbs');
        const progressBox = $('#regenerate-progress-box');
        const barFill = $('#regenerate-bar-fill');
        const statsText = $('#regenerate-stats-text');
        const statusTitle = $('#regenerate-status-title');
        const currentItemText = $('#regenerate-current-item');
        const logList = $('#regenerate-log-list');
        const clearLogBtn = $('#btn-clear-regenerate-log');

        let isRunning = false;
        let isPaused = false;
        let queue = [];
        let totalCount = 0;
        let processedCount = 0;
        let successCount = 0;
        let errorCount = 0;

        function addLog(type, message) {
            const time = new Date().toLocaleTimeString();
            const icon = type === 'success' ? 'dashicons-yes' : (type === 'error' ? 'dashicons-warning' : 'dashicons-info');
            const item = $(`
                <li class="log-item log-${type}">
                    <span class="dashicons ${icon}"></span>
                    <span class="log-time">[${time}]</span>
                    <span class="log-msg">${message}</span>
                </li>
            `);
            logList.prepend(item);
        }

        clearLogBtn.on('click', function (e) {
            e.preventDefault();
            logList.empty();
        });

        startRegenBtn.on('click', function (e) {
            e.preventDefault();

            if (isRunning && isPaused) {
                // Resume
                isPaused = false;
                pauseRegenBtn.show().find('span:last').text('Pause');
                startRegenBtn.hide();
                statusTitle.text('Regenerating thumbnails...');
                addLog('info', 'Process resumed.');
                processNext();
                return;
            }

            if (typeof customThemeAdminData === 'undefined') {
                alert('Admin data configuration missing.');
                return;
            }

            if (!confirm(customThemeAdminData.strings.confirmRegenerate)) {
                return;
            }

            // Start Fresh
            isRunning = true;
            isPaused = false;
            processedCount = 0;
            successCount = 0;
            errorCount = 0;

            startRegenBtn.prop('disabled', true).addClass('is-busy');
            statusTitle.text('Fetching media library items...');
            currentItemText.text('Querying attachment IDs...');
            progressBox.slideDown(250);
            barFill.css('width', '0%').removeClass('is-complete');
            logList.empty();
            addLog('info', 'Querying Media Library for image attachments...');

            $.post(customThemeAdminData.ajaxUrl, {
                action: 'custom_theme_get_attachment_ids',
                nonce: customThemeAdminData.nonce
            })
            .done(function (res) {
                if (!res.success || !res.data.ids || res.data.ids.length === 0) {
                    isRunning = false;
                    startRegenBtn.prop('disabled', false).removeClass('is-busy');
                    statusTitle.text(customThemeAdminData.strings.noImages);
                    currentItemText.text(customThemeAdminData.strings.noImages);
                    addLog('info', customThemeAdminData.strings.noImages);
                    return;
                }

                queue = res.data.ids;
                totalCount = queue.length;
                statsText.text(`0 / ${totalCount} (0%)`);
                addLog('info', `Found ${totalCount} image attachments. Starting thumbnail generation...`);

                startRegenBtn.hide().removeClass('is-busy').prop('disabled', false);
                pauseRegenBtn.show();
                statusTitle.text('Regenerating thumbnails...');

                processNext();
            })
            .fail(function (xhr, status, err) {
                isRunning = false;
                startRegenBtn.prop('disabled', false).removeClass('is-busy');
                statusTitle.text('Failed to query Media Library.');
                currentItemText.text('Error: ' + err);
                addLog('error', 'AJAX request failed: ' + err);
            });
        });

        pauseRegenBtn.on('click', function (e) {
            e.preventDefault();
            isPaused = true;
            pauseRegenBtn.hide();
            startRegenBtn.show().find('.btn-text').text('Resume Regeneration');
            statusTitle.text('Regeneration paused.');
            currentItemText.text(`Paused at ${processedCount} of ${totalCount} images.`);
            addLog('info', `Paused at ${processedCount} / ${totalCount}.`);
        });

        function processNext() {
            if (isPaused) {
                return;
            }

            if (queue.length === 0) {
                // Done!
                isRunning = false;
                barFill.css('width', '100%').addClass('is-complete');
                statsText.text(`${totalCount} / ${totalCount} (100%)`);
                statusTitle.text(customThemeAdminData.strings.finished);
                currentItemText.text(`Completed: ${successCount} successful, ${errorCount} errors.`);
                addLog('success', `Finished processing ${totalCount} images (${successCount} regenerated, ${errorCount} errors).`);
                pauseRegenBtn.hide();
                startRegenBtn.show().find('.btn-text').text('Regenerate Again');
                return;
            }

            const currentId = queue.shift();
            processedCount++;

            const percent = Math.round((processedCount / totalCount) * 100);
            barFill.css('width', percent + '%');
            statsText.text(`${processedCount} / ${totalCount} (${percent}%)`);
            currentItemText.text(`Processing Image #${currentId}...`);

            $.post(customThemeAdminData.ajaxUrl, {
                action: 'custom_theme_regenerate_single_thumbnail',
                nonce: customThemeAdminData.nonce,
                attachment_id: currentId
            })
            .done(function (res) {
                if (res.success) {
                    successCount++;
                    const sizeCount = res.data.sizes ? res.data.sizes.length : 0;
                    addLog('success', `[#${res.data.id}] ${res.data.title} &mdash; (${sizeCount} sizes generated)`);
                } else {
                    errorCount++;
                    const msg = (res.data && res.data.message) ? res.data.message : 'Unknown error';
                    addLog('error', `[#${currentId}] Failed: ${msg}`);
                }
            })
            .fail(function (xhr, status, err) {
                errorCount++;
                addLog('error', `[#${currentId}] Network Error: ${err}`);
            })
            .always(function () {
                if (!isPaused) {
                    processNext();
                }
            });
        }
    });

})(jQuery);
