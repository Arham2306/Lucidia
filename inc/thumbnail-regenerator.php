<?php
/**
 * Thumbnail Regenerator & On-Demand Image Size Generator
 *
 * Provides:
 * 1. Just-In-Time (JIT) on-demand thumbnail creation when missing on disk.
 * 2. AJAX batch processing endpoints for bulk library thumbnail regeneration.
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Just-In-Time (JIT) On-Demand Thumbnail Generator
 *
 * Intercepts image_downsize requests. If a registered theme image size is
 * requested on an older attachment and has not yet been generated on disk,
 * this function creates the crop dynamically, updates attachment metadata,
 * and returns the exact resized image URL.
 *
 * @param bool|array   $downsize      Whether to short-circuit the default downsize process.
 * @param int          $attachment_id Attachment ID.
 * @param string|array $size          Requested image size name or dimensions array.
 * @return bool|array
 */
function custom_theme_jit_image_downsize( $downsize, $attachment_id, $size ) {
    // If downsize has already been handled or size is an array, pass through.
    if ( false !== $downsize || ! is_string( $size ) ) {
        return false;
    }

    // Only handle registered theme sizes.
    global $_wp_additional_image_sizes;
    if ( ! isset( $_wp_additional_image_sizes[ $size ] ) ) {
        return false;
    }

    if ( ! wp_attachment_is_image( $attachment_id ) ) {
        return false;
    }

    $meta = wp_get_attachment_metadata( $attachment_id );
    if ( empty( $meta ) || ! is_array( $meta ) ) {
        return false;
    }

    $file_path = get_attached_file( $attachment_id );
    if ( ! $file_path || ! file_exists( $file_path ) ) {
        return false;
    }

    $upload_dir = wp_upload_dir();
    $base_dir   = trailingslashit( $upload_dir['basedir'] );
    $base_url   = trailingslashit( $upload_dir['baseurl'] );

    // If size already exists in metadata, verify file existence.
    if ( ! empty( $meta['sizes'][ $size ]['file'] ) ) {
        $existing_sub_dir = '';
        if ( ! empty( $meta['file'] ) && false !== strpos( $meta['file'], '/' ) ) {
            $existing_sub_dir = dirname( $meta['file'] ) . '/';
        }
        $existing_path = $base_dir . $existing_sub_dir . $meta['sizes'][ $size ]['file'];

        if ( file_exists( $existing_path ) ) {
            // File exists; let WordPress core serve it normally.
            return false;
        }
    }

    // Dimensions and crop settings for requested size.
    $size_data = $_wp_additional_image_sizes[ $size ];
    $width     = absint( $size_data['width'] );
    $height    = absint( $size_data['height'] );
    $crop      = ! empty( $size_data['crop'] );

    if ( $width <= 0 && $height <= 0 ) {
        return false;
    }

    // Attempt resizing via WordPress Image Editor.
    $editor = wp_get_image_editor( $file_path );
    if ( is_wp_error( $editor ) ) {
        return false;
    }

    $resized = $editor->resize( $width, $height, $crop );
    if ( is_wp_error( $resized ) ) {
        return false;
    }

    // Generate destination file name and save.
    $dest_file = $editor->generate_filename( "{$width}x{$height}", null, null );
    $saved     = $editor->save( $dest_file );

    if ( is_wp_error( $saved ) ) {
        return false;
    }

    // Determine URL path.
    $sub_dir = '';
    if ( ! empty( $meta['file'] ) && false !== strpos( $meta['file'], '/' ) ) {
        $sub_dir = dirname( $meta['file'] ) . '/';
    }

    $resized_url = $base_url . $sub_dir . $saved['file'];

    // Update attachment metadata.
    $meta['sizes'][ $size ] = array(
        'file'      => $saved['file'],
        'width'     => $saved['width'],
        'height'    => $saved['height'],
        'mime-type' => $saved['mime-type'],
    );
    wp_update_attachment_metadata( $attachment_id, $meta );

    return array(
        $resized_url,
        $saved['width'],
        $saved['height'],
        true,
    );
}
add_filter( 'image_downsize', 'custom_theme_jit_image_downsize', 10, 3 );

/**
 * AJAX: Retrieve all image attachment IDs for batch regeneration.
 */
function custom_theme_ajax_get_attachment_ids() {
    check_ajax_referer( 'custom_theme_regenerate_nonce', 'nonce' );

    if ( ! current_user_can( 'edit_theme_options' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Permission denied.', 'custom-theme' ) ) );
    }

    $attachments = get_posts(
        array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => array( 'inherit', 'publish', 'private' ),
            'posts_per_page' => -1,
            'fields'         => 'ids',
        )
    );

    if ( empty( $attachments ) ) {
        global $wpdb;
        $attachments = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%' AND post_status != 'trash' ORDER BY ID ASC" );
    }

    $attachment_ids = array_map( 'intval', (array) $attachments );

    wp_send_json_success(
        array(
            'ids'   => $attachment_ids,
            'total' => count( $attachment_ids ),
        )
    );
}
add_action( 'wp_ajax_custom_theme_get_attachment_ids', 'custom_theme_ajax_get_attachment_ids' );

/**
 * AJAX: Regenerate thumbnails for a single attachment ID.
 */
function custom_theme_ajax_regenerate_single_thumbnail() {
    check_ajax_referer( 'custom_theme_regenerate_nonce', 'nonce' );

    if ( ! current_user_can( 'edit_theme_options' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Permission denied.', 'custom-theme' ) ) );
    }

    $attachment_id = isset( $_POST['attachment_id'] ) ? absint( $_POST['attachment_id'] ) : 0;
    if ( ! $attachment_id ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid attachment ID.', 'custom-theme' ) ) );
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';

    $file_path = get_attached_file( $attachment_id );
    if ( ! $file_path || ! file_exists( $file_path ) ) {
        wp_send_json_error(
            array(
                'id'      => $attachment_id,
                'message' => esc_html__( 'Original media file not found on server.', 'custom-theme' ),
            )
        );
    }

    // Generate new full metadata including all registered theme sizes.
    $metadata = wp_generate_attachment_metadata( $attachment_id, $file_path );
    if ( is_wp_error( $metadata ) || empty( $metadata ) ) {
        wp_send_json_error(
            array(
                'id'      => $attachment_id,
                'message' => esc_html__( 'Failed generating image metadata.', 'custom-theme' ),
            )
        );
    }

    wp_update_attachment_metadata( $attachment_id, $metadata );

    $title    = get_the_title( $attachment_id );
    $filename = basename( $file_path );

    wp_send_json_success(
        array(
            'id'       => $attachment_id,
            'title'    => ! empty( $title ) ? $title : $filename,
            'filename' => $filename,
            'sizes'    => ! empty( $metadata['sizes'] ) ? array_keys( $metadata['sizes'] ) : array(),
        )
    );
}
add_action( 'wp_ajax_custom_theme_regenerate_single_thumbnail', 'custom_theme_ajax_regenerate_single_thumbnail' );
