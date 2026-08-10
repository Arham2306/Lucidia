<?php
/**
 * Template part for displaying the Hero Featured post on the homepage
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'featured-article-hero' ); ?>>
    <div class="featured-hero-inner">
        
        <!-- Featured Image / Media -->
        <div class="featured-hero-media">
            <?php custom_theme_post_thumbnail( 'custom-theme-featured', true, 'featured-hero-thumbnail', false ); ?>
            <div class="featured-hero-badge-overlay">
                <?php custom_theme_category_badge(); ?>
            </div>
        </div>

        <!-- Featured Content Details -->
        <div class="featured-hero-content">
            
            <h2 class="featured-hero-title">
                <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                    <?php the_title(); ?>
                </a>
            </h2>

            <div class="featured-hero-excerpt lead">
                <?php the_excerpt(); ?>
            </div>

            <!-- Author & Metadata Byline -->
            <div class="featured-hero-meta entry-meta">
                <?php custom_theme_posted_by( true ); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_posted_on(); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_reading_time_badge(); ?>
            </div>

            <div class="featured-hero-cta">
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-primary">
                    <span><?php esc_html_e( 'Read Full Article', 'custom-theme' ); ?></span>
                    <?php echo custom_theme_svg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </a>
            </div>

        </div><!-- .featured-hero-content -->

    </div><!-- .featured-hero-inner -->
</article><!-- #post-<?php the_ID(); ?> -->
