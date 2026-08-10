<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'custom-theme' ); ?></h1>
    </header>

    <div class="page-content">
        <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

            <p>
                <?php
                printf(
                    wp_kses(
                        /* translators: 1: link to WP admin new post page. */
                        __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'custom-theme' ),
                        array(
                            'a' => array(
                                'href' => array(),
                            ),
                        )
                    ),
                    esc_url( admin_url( 'post-new.php' ) )
                );
                ?>
            </p>

        <?php elseif ( is_search() ) : ?>

            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'custom-theme' ); ?></p>
            <div class="not-found-search">
                <?php get_search_form(); ?>
            </div>

        <?php else : ?>

            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'custom-theme' ); ?></p>
            <div class="not-found-search">
                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->
