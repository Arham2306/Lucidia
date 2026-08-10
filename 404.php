<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<div class="site-content-container container">
    <?php custom_theme_breadcrumbs(); ?>
    <main id="primary" class="site-main content-area error-404 not-found">
        
        <!-- 404 Hero Banner -->
        <div class="error-404-hero">
            <span class="error-code-badge">404</span>
            <h1 class="page-title error-title"><?php esc_html_e( 'Story Not Found', 'custom-theme' ); ?></h1>
            <p class="error-description lead">
                <?php esc_html_e( 'The page or article you are looking for may have been moved, renamed, or is temporarily unavailable. Try searching below or explore our latest stories.', 'custom-theme' ); ?>
            </p>

            <div class="error-search-box container-narrow">
                <?php get_search_form(); ?>
            </div>

            <div class="error-home-cta">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                    <?php echo custom_theme_svg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <span><?php esc_html_e( 'Back to Homepage', 'custom-theme' ); ?></span>
                </a>
            </div>
        </div>

        <!-- Recommended Recent Stories -->
        <section class="error-recommended-section">
            <div class="section-header">
                <h2 class="section-title">
                    <?php echo custom_theme_svg_icon( 'article' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <span><?php esc_html_e( 'Popular & Recent Stories', 'custom-theme' ); ?></span>
                </h2>
            </div>

            <?php
            $recommended_query = new WP_Query(
                array(
                    'posts_per_page'      => 3,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => 1,
                    'no_found_rows'       => true,
                )
            );

            if ( $recommended_query->have_posts() ) :
                echo '<div class="article-grid article-grid-3">';
                while ( $recommended_query->have_posts() ) :
                    $recommended_query->the_post();
                    get_template_part( 'template-parts/content-card' );
                endwhile;
                echo '</div>';
                wp_reset_postdata();
            endif;
            ?>
        </section>

    </main><!-- #primary -->
</div><!-- .container -->

<?php
get_footer();
