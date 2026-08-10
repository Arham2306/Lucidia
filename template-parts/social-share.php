<?php
/**
 * Template part for displaying social share buttons
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$share_links = custom_theme_get_share_links( get_the_ID() );
?>

<div class="social-share-bar" aria-label="<?php esc_attr_e( 'Share this story', 'custom-theme' ); ?>">
    <span class="share-label"><?php esc_html_e( 'Share:', 'custom-theme' ); ?></span>
    <div class="share-buttons-group">
        <?php foreach ( $share_links as $key => $share ) : ?>
            <?php if ( 'copy' === $key ) : ?>
                <button type="button" class="share-btn share-btn-copy" data-url="<?php echo esc_url( $share['url'] ); ?>" aria-label="<?php echo esc_attr( $share['label'] ); ?>" title="<?php echo esc_attr( $share['label'] ); ?>">
                    <?php echo custom_theme_svg_icon( $share['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </button>
            <?php else : ?>
                <a href="<?php echo esc_url( $share['url'] ); ?>" class="share-btn share-btn-<?php echo esc_attr( $key ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $share['label'] ); ?>" title="<?php echo esc_attr( $share['label'] ); ?>">
                    <?php echo custom_theme_svg_icon( $share['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
