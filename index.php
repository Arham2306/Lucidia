<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Custom_Theme
 */

get_header();
?>

<div class="site-content-container container">
    <div class="editorial-layout">
        <main id="primary" class="site-main content-area">

            <?php if ( is_home() && ! is_front_page() ) : ?>
                <header class="page-header section-header">
                    <h1 class="page-title section-title"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>

            <?php
            if ( have_posts() ) :
                echo '<div class="article-grid">';

                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'template-parts/content', get_post_type() );

                endwhile;

                echo '</div>';

                // Custom pagination
                custom_theme_pagination();

            else :

                get_template_part( 'template-parts/content', 'none' );

            endif;
            ?>

        </main><!-- #primary -->

        <?php get_sidebar(); ?>

    </div><!-- .editorial-layout -->
</div><!-- .container -->

<?php
get_footer();
