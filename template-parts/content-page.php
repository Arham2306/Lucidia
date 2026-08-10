<?php
/**
 * Template part for displaying page content in page.php
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'static-page-article' ); ?>>
    
    <header class="page-header static-page-header">
        <h1 class="page-title static-page-title"><?php the_title(); ?></h1>
    </header><!-- .page-header -->

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="page-featured-media">
            <?php the_post_thumbnail( 'full', array( 'class' => 'page-featured-img' ) ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content page-content prose-content">
        <?php
        the_content();

        wp_link_pages(
            array(
                'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'custom-theme' ) . '"><span class="page-links-title">' . esc_html__( 'Pages:', 'custom-theme' ) . '</span>',
                'after'  => '</nav>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <?php if ( get_edit_post_link() ) : ?>
        <footer class="page-footer entry-footer">
            <?php
            edit_post_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Edit <span class="screen-reader-text">%s</span>', 'custom-theme' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                ),
                '<span class="edit-link">' . custom_theme_svg_icon( 'article' ) . ' ',
                '</span>'
            );
            ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
