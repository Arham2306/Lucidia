<?php
/**
 * The template for displaying Category archives
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

$current_cat = get_queried_object();
?>

<div class="site-content-container container">
    <?php custom_theme_breadcrumbs(); ?>
    
    <!-- Category Header -->
    <header class="archive-header category-header page-header">
        <div class="archive-header-inner">
            <div class="archive-header-meta">
                <span class="archive-badge category-archive-badge"><?php esc_html_e( 'Category', 'custom-theme' ); ?></span>
                <?php if ( $current_cat && isset( $current_cat->count ) ) : ?>
                    <span class="archive-count-badge">
                        <?php
                        /* translators: %d: number of articles in category */
                        printf( esc_html( _n( '%d Article', '%d Articles', $current_cat->count, 'custom-theme' ) ), (int) $current_cat->count );
                        ?>
                    </span>
                <?php endif; ?>
            </div>

            <h1 class="page-title archive-title category-title">
                <?php single_cat_title(); ?>
            </h1>

            <?php if ( category_description() ) : ?>
                <div class="archive-description category-description lead">
                    <?php echo category_description(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            <?php endif; ?>
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
