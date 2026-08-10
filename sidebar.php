<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<aside id="secondary" class="widget-area sidebar-area" role="complementary" aria-label="<?php esc_attr_e( 'Main Sidebar', 'custom-theme' ); ?>">
    
    <?php
    if ( is_active_sidebar( 'sidebar-1' ) ) :
        dynamic_sidebar( 'sidebar-1' );
    else :
        // Default editorial widgets fallback
        ?>
        
        <!-- Widget: Search -->
        <div class="widget widget_search">
            <h3 class="widget-title"><?php esc_html_e( 'Search', 'custom-theme' ); ?></h3>
            <?php get_search_form(); ?>
        </div>

        <!-- Widget: Recent Articles with Thumbnails (Latest Stories) -->
        <div class="widget widget_recent_entries">
            <h3 class="widget-title"><?php esc_html_e( 'Latest Stories', 'custom-theme' ); ?></h3>
            <div class="widget-recent-posts">
                <?php
                $recent_query = new WP_Query(
                    array(
                        'posts_per_page'      => 4,
                        'post_status'         => 'publish',
                        'ignore_sticky_posts' => 1,
                        'no_found_rows'       => true,
                    )
                );

                if ( $recent_query->have_posts() ) :
                    while ( $recent_query->have_posts() ) :
                        $recent_query->the_post();
                        ?>
                        <article class="sidebar-post-item">
                            <a href="<?php the_permalink(); ?>" class="sidebar-post-thumb" tabindex="-1" aria-hidden="true">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'custom-theme-thumbnail' ); ?>
                                <?php else : ?>
                                    <div class="thumb-mini-placeholder"></div>
                                <?php endif; ?>
                            </a>
                            <div class="sidebar-post-details">
                                <h4 class="sidebar-post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <div class="sidebar-post-meta">
                                    <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                                </div>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>

        <!-- Widget: Categories -->
        <div class="widget widget_categories">
            <h3 class="widget-title"><?php esc_html_e( 'Categories', 'custom-theme' ); ?></h3>
            <ul class="widget-category-list">
                <?php
                $categories = get_transient('custom_theme_nav_categories');
                if (false === $categories) {
                    $categories = get_categories(array('orderby' => 'count', 'order' => 'DESC', 'number' => 6));
                    set_transient('custom_theme_nav_categories', $categories, HOUR_IN_SECONDS);
                }
                foreach ( $categories as $category ) :
                    ?>
                    <li>
                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
                            <span class="cat-name"><?php echo esc_html( $category->name ); ?></span>
                            <span class="cat-count"><?php echo esc_html( $category->count ); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    <?php endif; ?>

</aside><!-- #secondary -->
