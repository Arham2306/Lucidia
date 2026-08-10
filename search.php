<?php
/**
 * The template for displaying search results pages
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

global $wp_query;
$total_results = $wp_query->found_posts;
?>

<div class="site-content-container container">
    <?php custom_theme_breadcrumbs(); ?>
    
    <!-- Search Results Header -->
    <header class="archive-header search-results-header page-header">
        <div class="archive-header-inner">
            <div class="archive-header-meta">
                <span class="archive-badge search-badge">
                    <?php echo custom_theme_svg_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <span><?php esc_html_e( 'Search Results', 'custom-theme' ); ?></span>
                </span>
                <span class="archive-count-badge">
                    <?php
                    /* translators: %d: number of search results */
                    printf( esc_html( _n( '%d Result Found', '%d Results Found', $total_results, 'custom-theme' ) ), (int) $total_results );
                    ?>
                </span>
            </div>

            <h1 class="page-title archive-title search-title">
                <?php
                /* translators: %s: search query */
                printf( esc_html__( 'Results for: &ldquo;%s&rdquo;', 'custom-theme' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
                ?>
            </h1>

            <div class="search-refine-form">
                <?php get_search_form(); ?>
            </div>
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
