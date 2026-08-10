<?php
/**
 * Template part for displaying posts in a classic format
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card-classic' ); ?>>
    
    <div class="card-classic-media">
        <?php custom_theme_post_thumbnail( 'custom-theme-featured', true ); ?>
    </div>

    <div class="card-classic-content">
        
        <div class="card-classic-category">
            <?php custom_theme_category_badge(); ?>
        </div>

        <h2 class="card-classic-title">
            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h2>

        <div class="card-classic-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <footer class="card-classic-footer entry-meta">
            <div class="card-meta-left">
                <?php custom_theme_posted_by( true ); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_posted_on(); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_reading_time_badge(); ?>
            </div>
            <?php custom_theme_bookmark_button( get_the_ID(), 'card-bookmark-btn' ); ?>
        </footer>

    </div><!-- .card-classic-content -->

</article><!-- #post-<?php the_ID(); ?> -->
