<?php
/**
 * Template Name: Page Builder (Full Width)
 * Template Post Type: page, post
 *
 * A full-width template without sidebar or container constraints,
 * perfect for Elementor, Beaver Builder, Divi, WPBakery, Brizy, and Gutenberg full-width layouts.
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<main id="primary" class="site-main content-area page-builder-main page-builder-full-width">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'page-builder-article' ); ?>>
            <div class="entry-content page-builder-content">
                <?php
                the_content();

                wp_link_pages(
                    array(
                        'before' => '<nav class="page-links container" aria-label="' . esc_attr__( 'Page', 'custom-theme' ) . '"><span class="page-links-title">' . esc_html__( 'Pages:', 'custom-theme' ) . '</span>',
                        'after'  => '</nav>',
                    )
                );
                ?>
            </div><!-- .entry-content -->
        </article><!-- #post-<?php the_ID(); ?> -->
        <?php
    endwhile;
    ?>
</main><!-- #primary -->

<?php
get_footer();
