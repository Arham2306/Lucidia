<?php
/**
 * Template part for displaying posts in a compact horizontal layout
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card-compact' ); ?>>
    
    <!-- Thumbnail -->
    <div class="card-compact-media">
        <?php custom_theme_post_thumbnail( 'custom-theme-compact', true ); ?>
    </div>

    <!-- Content -->
    <div class="card-compact-content">
        <div class="card-compact-category">
            <?php custom_theme_category_badge(); ?>
        </div>

        <h4 class="card-compact-title">
            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h4>

        <div class="card-compact-meta entry-meta">
            <div class="compact-meta-left">
                <?php custom_theme_posted_on(); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_reading_time_badge(); ?>
            </div>
            <?php custom_theme_bookmark_button( get_the_ID(), 'card-bookmark-btn' ); ?>
        </div>
    </div>

</article><!-- #post-<?php the_ID(); ?> -->
