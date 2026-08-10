<?php
/**
 * Custom template tags for this theme
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function custom_theme_posted_on() {
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated screen-reader-text" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    echo '<span class="posted-on meta-item">' . custom_theme_svg_icon( 'calendar' ) . '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author.
 *
 * @param bool $show_avatar Whether to include author avatar.
 */
function custom_theme_posted_by( $show_avatar = true ) {
    $author_id   = get_the_author_meta( 'ID' );
    $author_name = get_the_author();
    $author_url  = get_author_posts_url( $author_id );

    echo '<span class="byline meta-item" itemprop="author" itemscope itemtype="https://schema.org/Person">';
    
    if ( $show_avatar ) {
        echo '<span class="author-avatar">' . get_avatar( $author_id, 32, '', esc_attr( $author_name ) ) . '</span>';
    } else {
        echo custom_theme_svg_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    echo '<a class="url fn n author-link" href="' . esc_url( $author_url ) . '" itemprop="url"><span itemprop="name">' . esc_html( $author_name ) . '</span></a>';
    echo '</span>';
}

/**
 * Prints or returns the primary category badge.
 *
 * @param int|null $post_id Optional. Post ID.
 * @param bool     $echo    Whether to echo or return the badge markup.
 * @return string|void
 */
function custom_theme_category_badge( $post_id = null, $echo = true ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $categories = get_the_category( $post_id );
    if ( empty( $categories ) ) {
        return '';
    }

    $primary_category = $categories[0];
    $category_link     = get_category_link( $primary_category->term_id );

    $output = sprintf(
        '<a href="%1$s" class="category-badge category-%2$s" rel="category tag">%3$s</a>',
        esc_url( $category_link ),
        esc_attr( $primary_category->slug ),
        esc_html( $primary_category->name )
    );

    if ( $echo ) {
        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    } else {
        return $output;
    }
}

/**
 * Prints the estimated reading time badge.
 *
 * @param int|null $post_id Optional. Post ID.
 */
function custom_theme_reading_time_badge( $post_id = null ) {
    $reading_time = custom_theme_reading_time( $post_id );
    if ( empty( $reading_time ) ) {
        return;
    }

    echo '<span class="reading-time meta-item">' . custom_theme_svg_icon( 'clock' ) . '<span>' . esc_html( $reading_time ) . '</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays an optional post thumbnail or a refined placeholder.
 *
 * @param string $size    Image size name.
 * @param bool   $link    Whether to wrap in a permalink tag.
 * @param string $classes Extra CSS classes.
 */
function custom_theme_post_thumbnail( $size = 'custom-theme-card', $link = true, $classes = '', $lazy = true ) {
    if ( post_password_required() || is_attachment() ) {
        return;
    }

    $has_thumb = has_post_thumbnail();
    $class_attr = 'entry-thumbnail ' . esc_attr( $classes );

    if ( $link ) {
        echo '<a class="' . esc_attr( $class_attr ) . '" href="' . esc_url( get_permalink() ) . '" aria-hidden="true" tabindex="-1">';
    } else {
        echo '<div class="' . esc_attr( $class_attr ) . '">';
    }

    if ( $has_thumb ) {
        $attrs = array(
            'alt'   => the_title_attribute( array( 'echo' => false ) ),
            'class' => 'thumbnail-img',
            'loading' => $lazy ? 'lazy' : 'eager',
        );
        if ( ! $lazy ) {
            $attrs['fetchpriority'] = 'high';
        }
        the_post_thumbnail( $size, $attrs );
    } else {
        // Sophisticated editorial fallback placeholder
        echo '<div class="thumbnail-placeholder" aria-hidden="true">';
        echo '<div class="placeholder-pattern"></div>';
        echo '<span class="placeholder-icon">' . custom_theme_svg_icon( 'article' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';
    }

    if ( $link ) {
        echo '</a>';
    } else {
        echo '</div>';
    }
}

/**
 * Display pagination with custom accessible markup (supports standard numbered, AJAX Load More, and Infinite Scroll).
 */
function custom_theme_pagination() {
    global $wp_query;
    $pagination_type = get_theme_mod( 'custom_theme_pagination_type', 'numbered' );
    $max_pages       = $wp_query->max_num_pages;
    $current_page    = max( 1, get_query_var( 'paged' ) );

    if ( $max_pages <= 1 ) {
        return;
    }

    if ( in_array( $pagination_type, array( 'load_more', 'infinite' ), true ) ) {
        $next_page_url = next_posts( $max_pages, false );
        if ( ! empty( $next_page_url ) ) {
            ?>
            <div class="ajax-pagination-container" 
                 data-pagination-type="<?php echo esc_attr( $pagination_type ); ?>" 
                 data-max-pages="<?php echo esc_attr( $max_pages ); ?>" 
                 data-current-page="<?php echo esc_attr( $current_page ); ?>"
                 data-next-url="<?php echo esc_url( $next_page_url ); ?>">
                
                <button type="button" class="btn btn-primary btn-load-more" id="btn-load-more" aria-label="<?php esc_attr_e( 'Load more articles', 'custom-theme' ); ?>">
                    <span class="btn-text"><?php esc_html_e( 'Load More Stories', 'custom-theme' ); ?></span>
                    <span class="btn-spinner" aria-hidden="true"><?php echo custom_theme_svg_icon( 'spinner' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                </button>

                <?php if ( 'infinite' === $pagination_type ) : ?>
                    <div class="infinite-scroll-trigger" aria-hidden="true"></div>
                <?php endif; ?>

                <div class="no-more-posts-msg" style="display: none;">
                    <span><?php esc_html_e( 'You have reached the end of the stories.', 'custom-theme' ); ?></span>
                </div>
            </div>

            <!-- Fallback standard pagination for noscript / SEO crawlers -->
            <noscript>
            <?php
            the_posts_pagination(
                array(
                    'mid_size'           => 2,
                    'prev_text'          => custom_theme_svg_icon( 'chevron-left' ) . '<span>' . esc_html__( 'Previous', 'custom-theme' ) . '</span>',
                    'next_text'          => '<span>' . esc_html__( 'Next', 'custom-theme' ) . '</span>' . custom_theme_svg_icon( 'chevron-right' ),
                    'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'custom-theme' ) . ' </span>',
                    'class'              => 'editorial-pagination noscript-pagination',
                )
            );
            ?>
            </noscript>
            <?php
            return;
        }
    }

    the_posts_pagination(
        array(
            'mid_size'           => 2,
            'prev_text'          => custom_theme_svg_icon( 'chevron-left' ) . '<span>' . esc_html__( 'Previous', 'custom-theme' ) . '</span>',
            'next_text'          => '<span>' . esc_html__( 'Next', 'custom-theme' ) . '</span>' . custom_theme_svg_icon( 'chevron-right' ),
            'before_page_number' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'custom-theme' ) . ' </span>',
            'class'              => 'editorial-pagination',
        )
    );
}

/**
 * Prints HTML with tag links for the current post.
 */
function custom_theme_entry_tags() {
    $tags_list = get_the_tag_list( '', ' ' );
    if ( $tags_list ) {
        echo '<div class="entry-tags"><span class="tags-label">' . custom_theme_svg_icon( 'tag' ) . ' ' . esc_html__( 'Tags:', 'custom-theme' ) . '</span> <div class="tags-pills">' . $tags_list . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

/**
 * Renders an accessible interactive bookmark / save for later button.
 *
 * @param int|null $post_id Post ID.
 * @param string   $class   Extra CSS class.
 */
function custom_theme_bookmark_button( $post_id = null, $class = '' ) {
    if ( ! get_theme_mod( 'custom_theme_enable_bookmarks', true ) ) {
        return;
    }

    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $title    = html_entity_decode( get_the_title( $post_id ), ENT_QUOTES, 'UTF-8' );
    $url      = get_permalink( $post_id );
    $thumb    = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'custom-theme-compact' ) : '';
    $cats     = get_the_category( $post_id );
    $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
    $reading  = custom_theme_reading_time( $post_id );

    ?>
    <button type="button" 
            class="btn-bookmark <?php echo esc_attr( $class ); ?>" 
            data-post-id="<?php echo esc_attr( $post_id ); ?>"
            data-title="<?php echo esc_attr( $title ); ?>"
            data-url="<?php echo esc_url( $url ); ?>"
            data-thumb="<?php echo esc_url( $thumb ); ?>"
            data-category="<?php echo esc_attr( $cat_name ); ?>"
            data-reading-time="<?php echo esc_attr( $reading ); ?>"
            aria-label="<?php esc_attr_e( 'Save story for later', 'custom-theme' ); ?>"
            title="<?php esc_attr_e( 'Save story for later', 'custom-theme' ); ?>">
        <span class="bookmark-icon bookmark-icon-outline"><?php echo custom_theme_svg_icon( 'bookmark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
        <span class="bookmark-icon bookmark-icon-filled"><?php echo custom_theme_svg_icon( 'bookmark-filled' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
    </button>
    <?php
}

