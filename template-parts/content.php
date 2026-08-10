<?php
/**
 * Template part for displaying posts in a standard grid loop
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
        
        <!-- Post Title -->
        <h2 class="card-title">
            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h2>

        <!-- Post Excerpt -->
        <div class="card-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <!-- Footer Meta -->
        <footer class="card-footer entry-meta">
            <?php custom_theme_posted_by( true ); ?>
            <span class="meta-divider">&bull;</span>
            <?php custom_theme_posted_on(); ?>
            <span class="meta-divider">&bull;</span>
            <?php custom_theme_reading_time_badge(); ?>
        </footer>

    </div><!-- .card-content -->

</article><!-- #post-<?php the_ID(); ?> -->
