<?php
/**
 * The template for displaying Tag archives
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

$current_tag = get_queried_object();
?>

<div class="site-content-container container">
    <?php custom_theme_breadcrumbs(); ?>
    
    <!-- Tag Archive Header -->
    <header class="archive-header tag-header page-header">
        <div class="archive-header-inner">
            <div class="archive-header-meta">
                <span class="archive-badge tag-archive-badge">
                    <?php echo custom_theme_svg_icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <span><?php esc_html_e( 'Tag', 'custom-theme' ); ?></span>
                </span>
                <?php if ( $current_tag && isset( $current_tag->count ) ) : ?>
                    <span class="archive-count-badge">
                        <?php
                        /* translators: %d: number of articles with this tag */
                        printf( esc_html( _n( '%d Article', '%d Articles', $current_tag->count, 'custom-theme' ) ), (int) $current_tag->count );
                        ?>
                    </span>
                <?php endif; ?>
            </div>

            <h1 class="page-title archive-title tag-title">
                <?php single_tag_title(); ?>
            </h1>

            <?php if ( tag_description() ) : ?>
                <div class="archive-description tag-description lead">
                    <?php echo tag_description(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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
