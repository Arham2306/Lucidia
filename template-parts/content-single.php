<?php
/**
 * Template part for displaying single post content
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Parse Table of Contents and inject anchor IDs into headings
$raw_content   = get_the_content();
$parsed_data   = custom_theme_parse_toc( $raw_content );
$content_with_ids = $parsed_data['content'];
$toc_items     = $parsed_data['toc'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>
    
    <!-- Article Header -->
    <header class="single-article-header">
        
        <!-- Category Badge & Reading Time -->
        <div class="single-header-top">
            <div class="single-category">
                <?php custom_theme_category_badge(); ?>
            </div>
            <div class="single-reading-time">
                <?php custom_theme_reading_time_badge(); ?>
            </div>
        </div>

        <!-- Article Title -->
        <h1 class="single-post-title page-title">
            <?php the_title(); ?>
        </h1>

        <!-- Article Subtitle / Excerpt -->
        <?php if ( has_excerpt() ) : ?>
            <div class="single-post-subtitle lead">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>

        <!-- Author & Metadata Row -->
        <div class="single-post-byline entry-meta">
            <div class="byline-left">
                <?php custom_theme_posted_by( true ); ?>
                <span class="meta-divider">&bull;</span>
                <?php custom_theme_posted_on(); ?>
            </div>
            <div class="byline-right">
                <?php if ( get_theme_mod( 'custom_theme_enable_reading_mode', true ) ) : ?>
                    <button type="button" class="btn-reading-mode-toggle" id="reading-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle Distraction-Free Reading Mode', 'custom-theme' ); ?>" title="<?php esc_attr_e( 'Reading Mode', 'custom-theme' ); ?>">
                        <?php echo custom_theme_svg_icon( 'book-open' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <span><?php esc_html_e( 'Reader View', 'custom-theme' ); ?></span>
                    </button>
                <?php endif; ?>

                <?php custom_theme_bookmark_button( get_the_ID(), 'single-bookmark-btn' ); ?>

                <?php if ( get_theme_mod( 'custom_theme_single_show_social_share', true ) ) : ?>
                    <?php get_template_part( 'template-parts/social-share' ); ?>
                <?php endif; ?>
            </div>
        </div>

    </header><!-- .single-article-header -->

    <!-- Featured Image Media -->
    <?php if ( get_theme_mod( 'custom_theme_single_show_featured_image', true ) && has_post_thumbnail() ) : ?>
        <figure class="single-featured-media">
            <?php the_post_thumbnail( 'full', array( 'class' => 'single-featured-img', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
            <?php
            $caption = get_the_post_thumbnail_caption();
            if ( $caption ) :
                ?>
                <figcaption class="single-featured-caption wp-caption-text"><?php echo esc_html( $caption ); ?></figcaption>
            <?php endif; ?>
        </figure>
    <?php endif; ?>

    <!-- In-Article Table of Contents (if 2 or more headings exist) -->
    <?php if ( get_theme_mod( 'custom_theme_single_show_toc', true ) && ! empty( $toc_items ) && count( $toc_items ) >= 2 ) : ?>
        <div class="article-toc-box <?php echo get_theme_mod( 'custom_theme_toc_sticky', true ) ? 'is-sticky-toc' : ''; ?>" aria-label="<?php esc_attr_e( 'Table of Contents', 'custom-theme' ); ?>">
            <div class="toc-header" role="button" tabindex="0" aria-expanded="true" aria-controls="toc-nav-list">
                <div class="toc-header-title">
                    <span class="toc-icon"><?php echo custom_theme_svg_icon( 'article' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <h3 class="toc-title"><?php esc_html_e( 'Table of Contents', 'custom-theme' ); ?></h3>
                </div>
                <button type="button" class="toc-toggle-btn" aria-label="<?php esc_attr_e( 'Toggle Table of Contents', 'custom-theme' ); ?>">
                    <?php echo custom_theme_svg_icon( 'chevron-down' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </button>
            </div>
            <nav class="toc-nav" id="toc-nav-list">
                <ul class="toc-list">
                    <?php foreach ( $toc_items as $item ) : ?>
                        <li class="toc-item toc-level-<?php echo esc_attr( $item['level'] ); ?>" data-target="<?php echo esc_attr( $item['id'] ); ?>">
                            <a href="#<?php echo esc_attr( $item['id'] ); ?>" class="toc-link">
                                <span class="toc-link-bullet" aria-hidden="true"></span>
                                <span class="toc-link-text"><?php echo esc_html( $item['title'] ); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>

    <!-- Main Prose Body -->
    <div class="single-prose-body">
        <div class="entry-content prose-content">
            <?php
            // Output the content with injected heading IDs and apply filters
            echo apply_filters( 'the_content', $content_with_ids ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

            wp_link_pages(
                array(
                    'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'custom-theme' ) . '"><span class="page-links-title">' . esc_html__( 'Pages:', 'custom-theme' ) . '</span>',
                    'after'    => '</nav>',
                    'pagelink' => '<span class="page-number">%</span>',
                )
            );
            ?>
        </div><!-- .entry-content -->

        <!-- Article Tags -->
        <footer class="single-article-footer">
            <?php custom_theme_entry_tags(); ?>
            <?php if ( get_theme_mod( 'custom_theme_single_show_social_share', true ) ) : ?>
                <div class="single-end-share">
                    <?php get_template_part( 'template-parts/social-share' ); ?>
                </div>
            <?php endif; ?>
        </footer>

        <!-- Author Bio Box -->
        <?php
        if ( get_theme_mod( 'custom_theme_single_show_author_box', true ) ) :
            get_template_part( 'template-parts/author-box' );
        endif;
        ?>

        <!-- Previous / Next Post Navigation -->
        <?php if ( get_theme_mod( 'custom_theme_single_show_post_nav', true ) ) : ?>
            <nav class="post-navigation-editorial" aria-label="<?php esc_attr_e( 'Post Navigation', 'custom-theme' ); ?>">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                <div class="post-nav-grid">
                    <?php if ( $prev_post ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="post-nav-card post-nav-prev">
                            <span class="post-nav-label">
                                <?php echo custom_theme_svg_icon( 'chevron-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                <span><?php esc_html_e( 'Previous Story', 'custom-theme' ); ?></span>
                            </span>
                            <h4 class="post-nav-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></h4>
                        </a>
                    <?php else : ?>
                        <div class="post-nav-empty"></div>
                    <?php endif; ?>

                    <?php if ( $next_post ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="post-nav-card post-nav-next">
                            <span class="post-nav-label">
                                <span><?php esc_html_e( 'Next Story', 'custom-theme' ); ?></span>
                                <?php echo custom_theme_svg_icon( 'chevron-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </span>
                            <h4 class="post-nav-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></h4>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        <?php endif; ?>

        <!-- Related Posts -->
        <?php
        if ( get_theme_mod( 'custom_theme_single_show_related_posts', true ) ) :
            get_template_part( 'template-parts/related-posts' );
        endif;
        ?>

    </div><!-- .single-prose-body -->

</article><!-- #post-<?php the_ID(); ?> -->
