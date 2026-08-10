<?php
/**
 * Template part for displaying the author bio box below single articles
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$author_id = get_the_author_meta( 'ID' );
$author_description = get_the_author_meta( 'description', $author_id );
?>

<div class="author-box" itemprop="author" itemscope itemtype="https://schema.org/Person">
    <div class="author-box-avatar">
        <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" rel="author" tabindex="-1" aria-hidden="true">
            <?php echo get_avatar( $author_id, 80, '', get_the_author() ); ?>
        </a>
    </div>

    <div class="author-box-content">
        <span class="author-box-label"><?php esc_html_e( 'Written by', 'custom-theme' ); ?></span>
        <h3 class="author-box-name" itemprop="name">
            <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" itemprop="url" rel="author">
                <?php the_author(); ?>
            </a>
        </h3>

        <?php if ( $author_description ) : ?>
            <p class="author-box-bio" itemprop="description">
                <?php echo esc_html( $author_description ); ?>
            </p>
        <?php else : ?>
            <p class="author-box-bio">
                <?php esc_html_e( 'Writer and contributor exploring modern ideas, long-form stories, and technology.', 'custom-theme' ); ?>
            </p>
        <?php endif; ?>

        <div class="author-box-links">
            <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="author-box-more-link">
                <span><?php esc_html_e( 'View all articles by this author', 'custom-theme' ); ?></span>
                <?php echo custom_theme_svg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </a>
        </div>
    </div>
</div>
