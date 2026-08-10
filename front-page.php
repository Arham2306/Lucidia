<?php
/**
 * The template for displaying the dynamic editorial homepage
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<div class="site-content-container container">
    <main id="primary" class="site-main content-area homepage-main">

        <?php
        // Array to store displayed post IDs to avoid duplicate rendering
        $do_not_duplicate = array();

        // 1. HERO FEATURED STORY
        $sticky_posts = get_option( 'sticky_posts' );
        $featured_args = array(
            'posts_per_page'      => 1,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 0,
            'no_found_rows'       => true,
        );

        if ( ! empty( $sticky_posts ) ) {
            $featured_args['post__in'] = $sticky_posts;
        }

        $featured_query = new WP_Query( $featured_args );

        if ( $featured_query->have_posts() ) :
            while ( $featured_query->have_posts() ) :
                $featured_query->the_post();
                $do_not_duplicate[] = get_the_ID();
                ?>
                <section class="homepage-section section-featured-hero">
                    <?php get_template_part( 'template-parts/content-featured' ); ?>
                </section>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>

        <!-- 2. LATEST ARTICLES GRID -->
        <section class="homepage-section section-latest-articles">
            <header class="section-header">
                <h2 class="section-title">
                    <?php echo custom_theme_svg_icon( 'article' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <span><?php esc_html_e( 'Latest Stories', 'custom-theme' ); ?></span>
                </h2>
                <?php
                $posts_page_id = get_option( 'page_for_posts' );
                $archive_link  = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/?post_type=post' );
                ?>
                <a href="<?php echo esc_url( $archive_link ); ?>" class="section-link">
                    <span><?php esc_html_e( 'View all articles', 'custom-theme' ); ?></span>
                    <?php echo custom_theme_svg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </a>
            </header>

            <?php
            $latest_args = array(
                'posts_per_page'      => 6,
                'post_status'         => 'publish',
                'post__not_in'        => $do_not_duplicate,
                'ignore_sticky_posts' => 1,
                'no_found_rows'       => true,
            );

            $latest_query = new WP_Query( $latest_args );

            if ( $latest_query->have_posts() ) :
                echo '<div class="article-grid article-grid-3">';
                while ( $latest_query->have_posts() ) :
                    $latest_query->the_post();
                    $do_not_duplicate[] = get_the_ID();
                    get_template_part( 'template-parts/content-card' );
                endwhile;
                echo '</div>';
                wp_reset_postdata();
            else :
                if ( empty( $do_not_duplicate ) ) {
                    get_template_part( 'template-parts/content-none' );
                }
            endif;
            ?>
        </section>

        <!-- 3. NEWSLETTER BANNER -->
        <div class="homepage-section section-newsletter-callout">
            <?php get_template_part( 'template-parts/newsletter' ); ?>
        </div>

        <!-- 4. MAGAZINE SPOTLIGHT & SIDEBAR SPLIT -->
        <section class="homepage-section section-magazine-spotlight">
            <div class="editorial-layout">
                
                <div class="magazine-main-column">
                    <header class="section-header">
                        <h2 class="section-title">
                            <?php echo custom_theme_svg_icon( 'folder' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <span><?php esc_html_e( 'Editor&rsquo;s Picks & Deep Dives', 'custom-theme' ); ?></span>
                        </h2>
                    </header>

                    <?php
                    $spotlight_args = array(
                        'posts_per_page'      => 4,
                        'post_status'         => 'publish',
                        'post__not_in'        => $do_not_duplicate,
                        'ignore_sticky_posts' => 1,
                        'no_found_rows'       => true,
                        'update_post_term_cache' => false,
                    );

                    $spotlight_query = new WP_Query( $spotlight_args );

                    if ( $spotlight_query->have_posts() ) :
                        echo '<div class="compact-cards-list">';
                        while ( $spotlight_query->have_posts() ) :
                            $spotlight_query->the_post();
                            get_template_part( 'template-parts/content-card-compact' );
                        endwhile;
                        echo '</div>';
                        wp_reset_postdata();
                    else :
                        // If all posts were consumed in hero & latest, pull 4 most recent for spotlight
                        $fallback_query = new WP_Query(
                            array(
                                'posts_per_page'      => 4,
                                'post_status'         => 'publish',
                                'ignore_sticky_posts' => 1,
                                'no_found_rows'       => true,
                                'update_post_term_cache' => false,
                            )
                        );
                        if ( $fallback_query->have_posts() ) :
                            echo '<div class="compact-cards-list">';
                            while ( $fallback_query->have_posts() ) :
                                $fallback_query->the_post();
                                get_template_part( 'template-parts/content-card-compact' );
                            endwhile;
                            echo '</div>';
                            wp_reset_postdata();
                        endif;
                    endif;
                    ?>
                </div><!-- .magazine-main-column -->

                <?php get_sidebar(); ?>

            </div><!-- .editorial-layout -->
        </section>

    </main><!-- #primary -->
</div><!-- .container -->

<?php
get_footer();
