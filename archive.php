<?php
/**
 * The template for displaying general archive pages
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
    
    <!-- Archive Header -->
    <header class="archive-header page-header">
        <div class="archive-header-inner">
            <span class="archive-badge"><?php esc_html_e( 'Archive', 'custom-theme' ); ?></span>
            <?php
            the_archive_title( '<h1 class="page-title archive-title">', '</h1>' );
            the_archive_description( '<div class="archive-description lead">', '</div>' );
            ?>
        </div>
    </header>

    <?php
    $show_sidebar = get_theme_mod( 'custom_theme_archive_show_sidebar', true );
    ?>

    <div class="editorial-layout <?php echo ! $show_sidebar ? 'editorial-layout-no-sidebar' : ''; ?>">
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
                    $grid_class .= $show_sidebar ? ' article-grid-2' : ' article-grid-3';
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

        <?php
        if ( $show_sidebar ) :
            get_sidebar();
        endif;
        ?>

    </div><!-- .editorial-layout -->
</div><!-- .container -->

<?php
get_footer();
