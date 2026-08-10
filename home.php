<?php
/**
 * The blog posts index template
 *
 * Used when a static front page is assigned and a separate "Posts page" is selected,
 * or as the default posts feed.
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<div class="site-content-container container">
    
    <!-- Archive / Blog Header -->
    <header class="archive-header page-header">
        <div class="archive-header-inner">
            <span class="archive-badge"><?php esc_html_e( 'Journal & Articles', 'custom-theme' ); ?></span>
            <h1 class="page-title archive-title">
                <?php
                if ( is_home() && ! is_front_page() ) {
                    single_post_title();
                } else {
                    esc_html_e( 'Latest Articles', 'custom-theme' );
                }
                ?>
            </h1>
            <p class="archive-description lead">
                <?php esc_html_e( 'Explore all stories, in-depth perspectives, analysis, and guides.', 'custom-theme' ); ?>
            </p>
        </div>
    </header>

    <div class="editorial-layout">
        <main id="primary" class="site-main content-area">

            <?php
            if ( have_posts() ) :
                $archive_layout = get_theme_mod( 'custom_theme_archive_layout', 'grid' );
                $grid_class = 'article-grid';
                $card_template = 'content-card';
                
                if ( 'list' === $archive_layout ) {
                    $grid_class = 'article-list';
                    $card_template = 'content-card-list';
                } elseif ( 'classic' === $archive_layout ) {
                    $grid_class = 'article-classic';
                    $card_template = 'content-card-classic';
                } else {
                    $grid_class .= ' article-grid-2';
                }

                echo '<div class="' . esc_attr( $grid_class ) . '">';

                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/' . $card_template );
                endwhile;

                echo '</div>';

                // Pagination
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
