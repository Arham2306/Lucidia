<?php
/**
 * Template part for displaying related posts below single articles
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$related_query = custom_theme_get_related_posts( get_the_ID(), 3 );

if ( $related_query->have_posts() ) :
    ?>
    <section class="related-posts-section" aria-label="<?php esc_attr_e( 'Related Articles', 'custom-theme' ); ?>">
        <div class="section-header">
            <h2 class="section-title">
                <?php echo custom_theme_svg_icon( 'article' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <span><?php esc_html_e( 'Related Stories', 'custom-theme' ); ?></span>
            </h2>
        </div>

        <div class="article-grid article-grid-3">
            <?php
            while ( $related_query->have_posts() ) :
                $related_query->the_post();
                get_template_part( 'template-parts/content-card' );
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </section>
    <?php
endif;
