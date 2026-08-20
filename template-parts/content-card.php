<?php
/**
 * Template part for displaying posts in a card grid
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card' ); ?>>
    
    <!-- Thumbnail & Category Overlay -->
    <div class="card-media">
        <?php custom_theme_post_thumbnail( 'custom-theme-card', true ); ?>
        
        <div class="card-category-overlay">
            <?php custom_theme_category_badge(); ?>
        </div>
    </div>

    <!-- Content Body -->
    <div class="card-content">
        
        <h3 class="card-title">
            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="card-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <footer class="card-footer entry-meta">
            <div class="card-meta-left">
                <?php custom_theme_posted_by( true ); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_posted_on(); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_reading_time_badge(); ?>
            </div>
        </footer>

    </div><!-- .card-content -->

</article><!-- #post-<?php the_ID(); ?> -->
