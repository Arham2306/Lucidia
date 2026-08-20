<?php
/**
 * Template part for displaying posts in a list format
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card-list' ); ?>>
    
    <div class="card-list-media">
        <?php custom_theme_post_thumbnail( 'custom-theme-compact', true ); ?>
    </div>

    <div class="card-list-content">
        
        <div class="card-list-category">
            <?php custom_theme_category_badge(); ?>
        </div>

        <h3 class="card-list-title">
            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="card-list-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <footer class="card-list-footer entry-meta">
            <div class="card-meta-left">
                <?php custom_theme_posted_by( true ); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_posted_on(); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_reading_time_badge(); ?>
            </div>
        </footer>

    </div><!-- .card-list-content -->

</article><!-- #post-<?php the_ID(); ?> -->
