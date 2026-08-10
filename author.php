<?php
/**
 * The template for displaying author archive pages
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

$author = get_queried_object();
$author_id = $author ? $author->ID : get_the_author_meta( 'ID' );
$author_posts_count = count_user_posts( $author_id, 'post' );
?>

<div class="site-content-container container">
    <?php custom_theme_breadcrumbs(); ?>
    
    <!-- Author Profile Hero Header -->
    <header class="author-hero-header page-header">
        <div class="author-hero-box">
            <div class="author-hero-avatar">
                <?php echo get_avatar( $author_id, 100, '', get_the_author_meta( 'display_name', $author_id ) ); ?>
            </div>

            <div class="author-hero-info">
                <div class="author-hero-badge">
                    <span class="archive-badge"><?php esc_html_e( 'Author Archive', 'custom-theme' ); ?></span>
                    <span class="archive-count-badge">
                        <?php
                        /* translators: %d: number of articles by this author */
                        printf( esc_html( _n( '%d Article', '%d Articles', $author_posts_count, 'custom-theme' ) ), (int) $author_posts_count );
                        ?>
                    </span>
                </div>

                <h1 class="page-title author-hero-name">
                    <?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?>
                </h1>

                <?php if ( get_the_author_meta( 'description', $author_id ) ) : ?>
                    <p class="author-hero-bio lead">
                        <?php echo esc_html( get_the_author_meta( 'description', $author_id ) ); ?>
                    </p>
                <?php endif; ?>

                <?php if ( get_the_author_meta( 'user_url', $author_id ) ) : ?>
                    <div class="author-hero-website">
                        <a href="<?php echo esc_url( get_the_author_meta( 'user_url', $author_id ) ); ?>" target="_blank" rel="noopener noreferrer" class="author-website-link">
                            <?php echo custom_theme_svg_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <span><?php echo esc_html( preg_replace( '#^https?://#i', '', get_the_author_meta( 'user_url', $author_id ) ) ); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <?php
    $show_sidebar = get_theme_mod( 'custom_theme_archive_show_sidebar', true );
    ?>

    <div class="editorial-layout <?php echo ! $show_sidebar ? 'editorial-layout-no-sidebar' : ''; ?>">
        <main id="primary" class="site-main content-area">

            <div class="section-header">
                <h2 class="section-title">
                    <?php
                    /* translators: %s: author name */
                    printf( esc_html__( 'Articles by %s', 'custom-theme' ), esc_html( get_the_author_meta( 'display_name', $author_id ) ) );
                    ?>
                </h2>
            </div>

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
