<?php
/**
 * Helper functions for Custom Editorial Theme
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Calculate estimated reading time for a post.
 *
 * @param int|WP_Post|null $post_id Optional. Post ID or post object.
 * @return string Formatted reading time string.
 */
function custom_theme_reading_time( $post_id = null ) {
    $post = get_post( $post_id );
    if ( ! $post ) {
        return '';
    }

    $content = strip_shortcodes( $post->post_content );
    $content = wp_strip_all_tags( $content );
    $word_count = count( preg_split( '/\s+/', trim( $content ) ) );

    $words_per_minute = 200;
    $minutes = (int) ceil( $word_count / $words_per_minute );

    if ( $minutes < 1 ) {
        $minutes = 1;
    }

    /* translators: %d: estimated reading time in minutes */
    return sprintf( _n( '%d min read', '%d min read', $minutes, 'custom-theme' ), $minutes );
}

/**
 * Query related posts based on current post categories and tags.
 *
 * @param int|null $post_id Optional. Post ID.
 * @param int      $count   Number of related posts to retrieve.
 * @return WP_Query
 */
function custom_theme_get_related_posts( $post_id = null, $count = 3 ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $categories = wp_get_post_categories( $post_id );
    $tags       = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );

    $tax_query = array();

    if ( ! empty( $categories ) ) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $categories,
        );
    } elseif ( ! empty( $tags ) ) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field'    => 'term_id',
            'terms'    => $tags,
        );
    }

    $args = array(
        'post_type'           => 'post',
        'posts_per_page'      => $count,
        'post__not_in'        => array( $post_id ),
        'ignore_sticky_posts' => 1,
        'no_found_rows'       => true,
        'update_post_meta_cache' => false,
    );

    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    return new WP_Query( $args );
}

/**
 * Generate share links for social platforms.
 *
 * @param int|null $post_id Optional. Post ID.
 * @return array Array of social share details (url, label, icon).
 */
function custom_theme_get_share_links( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $url   = rawurlencode( get_permalink( $post_id ) );
    $title = rawurlencode( html_entity_decode( get_the_title( $post_id ), ENT_QUOTES, 'UTF-8' ) );

    return array(
        'x' => array(
            'label' => esc_html__( 'Share on X', 'custom-theme' ),
            'url'   => "https://twitter.com/intent/tweet?text={$title}&url={$url}",
            'icon'  => 'x-twitter',
        ),
        'facebook' => array(
            'label' => esc_html__( 'Share on Facebook', 'custom-theme' ),
            'url'   => "https://www.facebook.com/sharer/sharer.php?u={$url}",
            'icon'  => 'facebook',
        ),
        'linkedin' => array(
            'label' => esc_html__( 'Share on LinkedIn', 'custom-theme' ),
            'url'   => "https://www.linkedin.com/sharing/share-offsite/?url={$url}",
            'icon'  => 'linkedin',
        ),
        'email' => array(
            'label' => esc_html__( 'Share via Email', 'custom-theme' ),
            'url'   => "mailto:?subject={$title}&body={$url}",
            'icon'  => 'mail',
        ),
        'copy' => array(
            'label' => esc_html__( 'Copy Link', 'custom-theme' ),
            'url'   => esc_url( get_permalink( $post_id ) ),
            'icon'  => 'link',
        ),
    );
}

/**
 * Parse post content for h2 and h3 headings and build Table of Contents.
 *
 * @param string $content HTML content.
 * @return array Array containing 'content' (with IDs injected) and 'toc' (list of headings).
 */
function custom_theme_parse_toc( $content ) {
    $toc = array();

    if ( ! is_singular( 'post' ) || empty( $content ) ) {
        return array(
            'content' => $content,
            'toc'     => $toc,
        );
    }

    $cache_key = 'custom_theme_toc_v2_' . get_the_ID();
    $cache = get_transient($cache_key);
    if ($cache !== false && $cache['modified'] === get_the_modified_date('U')) {
        return $cache['data'];
    }

    // Match all h2 and h3 headings
    $pattern = '/<h([2-3])([^>]*)>(.*?)<\/h\1>/i';
    $used_ids = array();

    $modified_content = preg_replace_callback(
        $pattern,
        function( $matches ) use ( &$toc, &$used_ids ) {
            $level = (int) $matches[1];
            $attrs = $matches[2];
            $title = $matches[3];

            $clean_title = wp_strip_all_tags( $title );

            // Check if id attribute already exists
            $has_existing_id = false;
            if ( preg_match( '/id=["\']([^"\']+)["\']/i', $attrs, $id_match ) ) {
                $has_existing_id = true;
                $base_slug = trim( $id_match[1] );
            } else {
                $base_slug = sanitize_title( $clean_title );
            }

            if ( empty( $base_slug ) ) {
                $base_slug = 'heading-' . ( count( $toc ) + 1 );
            }

            $slug = $base_slug;
            $suffix = 2;
            while ( isset( $used_ids[ $slug ] ) ) {
                $slug = $base_slug . '-' . $suffix;
                $suffix++;
            }
            $used_ids[ $slug ] = true;

            if ( $has_existing_id ) {
                $attrs = preg_replace( '/\s+id=["\'][^"\']*["\']/i', ' id="' . esc_attr( $slug ) . '"', $attrs, 1 );
            } else {
                $attrs .= ' id="' . esc_attr( $slug ) . '"';
            }

            $toc[] = array(
                'id'    => $slug,
                'title' => $clean_title,
                'level' => $level,
            );

            return sprintf( '<h%d%s>%s</h%d>', $level, $attrs, $title, $level );
        },
        $content
    );

    $result = array(
        'content' => $modified_content,
        'toc'     => $toc,
    );

    set_transient($cache_key, array('modified' => get_the_modified_date('U'), 'data' => $result), DAY_IN_SECONDS);

    return $result;
}

/**
 * Clear TOC transient on post save.
 */
function custom_theme_clear_toc_transient( $post_id ) {
    delete_transient( 'custom_theme_toc_' . $post_id );
    delete_transient( 'custom_theme_toc_v2_' . $post_id );
}
add_action( 'save_post', 'custom_theme_clear_toc_transient' );

/**
 * Register Live Search REST API route.
 */
function custom_theme_register_search_route() {
    register_rest_route(
        'custom-theme/v1',
        '/search',
        array(
            'methods'             => 'GET',
            'callback'            => 'custom_theme_rest_live_search',
            'permission_callback' => '__return_true',
            'args'                => array(
                'q' => array(
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        )
    );
}
add_action( 'rest_api_init', 'custom_theme_register_search_route' );

/**
 * Handle Live Search REST API request.
 */
function custom_theme_rest_live_search( $request ) {
    $query_term = trim( $request->get_param( 'q' ) );

    if ( empty( $query_term ) || strlen( $query_term ) < 2 ) {
        return rest_ensure_response(
            array(
                'results'     => array(),
                'total_count' => 0,
            )
        );
    }

    $search_query = new WP_Query(
        array(
            's'                   => $query_term,
            'posts_per_page'      => 5,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'no_found_rows'       => false,
        )
    );

    $results = array();

    if ( $search_query->have_posts() ) {
        while ( $search_query->have_posts() ) {
            $search_query->the_post();
            $post_id = get_the_ID();

            // Category
            $category_name = '';
            $category_url  = '';
            $cats          = get_the_category( $post_id );
            if ( ! empty( $cats ) ) {
                $category_name = $cats[0]->name;
                $category_url  = get_category_link( $cats[0]->term_id );
            }

            // Thumbnail
            $thumb_url = '';
            if ( has_post_thumbnail( $post_id ) ) {
                $thumb_url = get_the_post_thumbnail_url( $post_id, 'custom-theme-thumbnail' );
            }

            $results[] = array(
                'id'           => $post_id,
                'title'        => html_entity_decode( get_the_title(), ENT_QUOTES, 'UTF-8' ),
                'url'          => get_permalink( $post_id ),
                'thumbnail'    => $thumb_url,
                'date'         => get_the_date( '', $post_id ),
                'category'     => $category_name,
                'category_url' => $category_url,
                'reading_time' => custom_theme_reading_time( $post_id ),
            );
        }
        wp_reset_postdata();
    }

    return rest_ensure_response(
        array(
            'results'     => $results,
            'total_count' => (int) $search_query->found_posts,
            'query'       => $query_term,
            'view_all'    => add_query_arg( 's', rawurlencode( $query_term ), home_url( '/' ) ),
        )
    );
}

/**
 * Display Breadcrumbs
 */
function custom_theme_breadcrumbs() {
    if ( ! get_theme_mod( 'custom_theme_show_breadcrumbs', true ) ) {
        return;
    }

    if ( is_front_page() ) {
        return;
    }

    $items = array();

    // Home
    $items[] = array(
        'name' => esc_html__( 'Home', 'custom-theme' ),
        'url'  => home_url( '/' )
    );

    if ( is_singular() ) {
        if ( is_page() && ! is_front_page() ) {
            global $post;
            if ( $post->post_parent ) {
                $ancestors = get_post_ancestors( $post->ID );
                $ancestors = array_reverse( $ancestors );
                foreach ( $ancestors as $ancestor ) {
                    $items[] = array(
                        'name' => get_the_title( $ancestor ),
                        'url'  => get_permalink( $ancestor )
                    );
                }
            }
            $items[] = array(
                'name' => get_the_title(),
                'url'  => ''
            );
        } elseif ( is_single() ) {
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                $items[] = array(
                    'name' => $categories[0]->name,
                    'url'  => get_category_link( $categories[0]->term_id )
                );
            }
            $items[] = array(
                'name' => get_the_title(),
                'url'  => ''
            );
        }
    } elseif ( is_category() ) {
        $items[] = array(
            'name' => single_cat_title( '', false ),
            'url'  => ''
        );
    } elseif ( is_tag() ) {
        $items[] = array(
            'name' => esc_html__( 'Tags', 'custom-theme' ),
            'url'  => ''
        );
        $items[] = array(
            'name' => single_tag_title( '', false ),
            'url'  => ''
        );
    } elseif ( is_author() ) {
        $items[] = array(
            'name' => esc_html__( 'Authors', 'custom-theme' ),
            'url'  => ''
        );
        $items[] = array(
            'name' => get_the_author(),
            'url'  => ''
        );
    } elseif ( is_search() ) {
        $items[] = array(
            'name' => sprintf( esc_html__( 'Search Results for "%s"', 'custom-theme' ), get_search_query() ),
            'url'  => ''
        );
    } elseif ( is_date() ) {
        $items[] = array(
            'name' => get_the_date( _x( 'Y', 'yearly archives date format', 'custom-theme' ) ),
            'url'  => is_year() ? '' : get_year_link( get_query_var( 'year' ) )
        );
        if ( is_month() || is_day() ) {
            $items[] = array(
                'name' => get_the_date( _x( 'F', 'monthly archives date format', 'custom-theme' ) ),
                'url'  => is_month() ? '' : get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) )
            );
        }
        if ( is_day() ) {
            $items[] = array(
                'name' => get_the_date(),
                'url'  => ''
            );
        }
    } elseif ( is_404() ) {
        $items[] = array(
            'name' => esc_html__( 'Page Not Found', 'custom-theme' ),
            'url'  => ''
        );
    } elseif ( is_archive() ) {
        $items[] = array(
            'name' => get_the_archive_title(),
            'url'  => ''
        );
    } else {
        $items[] = array(
            'name' => get_the_title(),
            'url'  => ''
        );
    }

    // JSON-LD structured data
    $json_ld = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => array()
    );

    $position = 1;
    foreach ( $items as $item ) {
        $json_ld_item = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $item['name']
        );
        if ( ! empty( $item['url'] ) ) {
            $json_ld_item['item'] = $item['url'];
        }
        $json_ld['itemListElement'][] = $json_ld_item;
        $position++;
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $json_ld ) . '</script>' . "\n";

    // Visible Breadcrumbs
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'custom-theme' ) . '">';
    echo '<ol class="breadcrumb-list">';

    $count = count( $items );
    foreach ( $items as $index => $item ) {
        echo '<li class="breadcrumb-item">';
        
        $is_last = ( $index === $count - 1 );
        
        if ( $is_last || empty( $item['url'] ) ) {
            echo '<span aria-current="page">' . esc_html( $item['name'] ) . '</span>';
        } else {
            echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['name'] ) . '</a>';
            if ( function_exists( 'custom_theme_svg_icon' ) ) {
                echo custom_theme_svg_icon( 'chevron-right' );
            }
        }
        
        echo '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}
