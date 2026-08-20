<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div class="site-main">
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$categories = get_transient('custom_theme_nav_categories');
if (false === $categories) {
    $categories = get_categories(array('orderby' => 'count', 'order' => 'DESC', 'number' => 6));
    set_transient('custom_theme_nav_categories', $categories, HOUR_IN_SECONDS);
}

// Header Customizer Settings
$header_layout     = get_theme_mod( 'custom_theme_header_layout', 'default' );
$header_width      = get_theme_mod( 'custom_theme_header_width', 'contained' );
$nav_align         = get_theme_mod( 'custom_theme_nav_alignment', 'center' );
$nav_indicator     = get_theme_mod( 'custom_theme_nav_indicator_style', 'underline' );
$nav_size          = get_theme_mod( 'custom_theme_nav_font_size', 'regular' );
$nav_uppercase     = get_theme_mod( 'custom_theme_nav_uppercase', false ) ? 'nav-is-uppercase' : '';
$sticky_enabled    = get_theme_mod( 'custom_theme_sticky_header', true );
$sticky_behavior   = get_theme_mod( 'custom_theme_sticky_behavior', 'smart-hide' );
$sticky_blur       = get_theme_mod( 'custom_theme_sticky_blur', true ) ? 'sticky-blur-enabled' : '';
$sticky_shadow     = get_theme_mod( 'custom_theme_sticky_shadow', 'subtle' );
$container_class   = ( 'full-width' === $header_width ) ? 'header-fluid-container' : 'container';

$header_classes = array(
    'site-header',
    'header-layout-' . sanitize_html_class( $header_layout ),
    'header-width-' . sanitize_html_class( $header_width ),
    'nav-align-' . sanitize_html_class( $nav_align ),
    'nav-indicator-' . sanitize_html_class( $nav_indicator ),
    'nav-size-' . sanitize_html_class( $nav_size ),
    $nav_uppercase,
    $sticky_enabled ? 'sticky-header-enabled' : '',
    'sticky-mode-' . sanitize_html_class( $sticky_behavior ),
    $sticky_blur,
    'sticky-shadow-' . sanitize_html_class( $sticky_shadow ),
);
$header_classes = implode( ' ', array_filter( $header_classes ) );
?><!doctype html>
<html <?php language_attributes(); ?> data-theme="<?php echo esc_attr( get_theme_mod( 'custom_theme_dark_mode_default', 'light' ) === 'dark' ? 'dark' : 'light' ); ?>">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site site-wrapper">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e( 'Skip to content', 'custom-theme' ); ?>
    </a>

    <!-- Single Post Reading Progress Indicator -->
    <?php if ( is_singular( 'post' ) ) : ?>
        <div id="reading-progress-bar" class="reading-progress" aria-hidden="true"></div>
    <?php endif; ?>

    <!-- Header & Navigation Section -->
    <?php
    $show_header = get_theme_mod( 'custom_theme_display_header', true );
    if ( is_singular() ) {
        $header_override = get_post_meta( get_the_ID(), '_custom_theme_header_override', true );
        if ( 'show' === $header_override ) {
            $show_header = true;
        } elseif ( 'hide' === $header_override ) {
            $show_header = false;
        }
    }
    if ( $show_header ) :
    ?>
        <!-- Top Bar Announcement Bar (Customizer Toggleable) -->
        <?php if ( get_theme_mod( 'custom_theme_enable_topbar', false ) ) : ?>
            <aside class="site-topbar" id="site-topbar" aria-label="<?php esc_attr_e( 'Announcement', 'custom-theme' ); ?>">
                <div class="<?php echo esc_attr( $container_class ); ?> topbar-container">
                    <?php if ( get_theme_mod( 'custom_theme_topbar_show_date', true ) ) : ?>
                        <div class="topbar-date">
                            <?php echo custom_theme_svg_icon( 'article' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <span><?php echo esc_html( date_i18n( get_option( 'date_format' ) ) ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php
                    $topbar_text = get_theme_mod( 'custom_theme_topbar_text', '' );
                    if ( ! empty( $topbar_text ) ) :
                        ?>
                        <div class="topbar-announcement">
                            <?php echo wp_kses_post( $topbar_text ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( get_theme_mod( 'custom_theme_topbar_show_social', true ) ) : ?>
                        <div class="topbar-social">
                            <?php
                            $tw = get_theme_mod( 'custom_theme_social_twitter', '' );
                            $fb = get_theme_mod( 'custom_theme_social_facebook', '' );
                            $li = get_theme_mod( 'custom_theme_social_linkedin', '' );
                            $ig = get_theme_mod( 'custom_theme_social_instagram', '' );
                            $gh = get_theme_mod( 'custom_theme_social_github', '' );
                            $yt = get_theme_mod( 'custom_theme_social_youtube', '' );
                            if ( $tw ) echo '<a href="' . esc_url( $tw ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Twitter', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'x-twitter' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            if ( $fb ) echo '<a href="' . esc_url( $fb ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Facebook', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'facebook' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            if ( $li ) echo '<a href="' . esc_url( $li ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'LinkedIn', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'linkedin' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            if ( $ig ) echo '<a href="' . esc_url( $ig ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Instagram', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'share' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            if ( $gh ) echo '<a href="' . esc_url( $gh ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'GitHub', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'folder' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            if ( $yt ) echo '<a href="' . esc_url( $yt ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'YouTube', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'article' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </aside>
        <?php endif; ?>

        <!-- Site Header -->
        <header id="masthead" class="<?php echo esc_attr( $header_classes ); ?>" role="banner">
        <div class="<?php echo esc_attr( $container_class ); ?> header-container">
            
            <!-- Site Branding / Logo -->
            <div class="site-branding <?php echo get_theme_mod( 'custom_theme_site_title_uppercase', false ) ? 'site-title-uppercase' : ''; ?>">
                <?php
                $custom_logo_url = get_theme_mod( 'custom_theme_logo_url', '' );
                if ( empty( $custom_logo_url ) && has_custom_logo() ) {
                    $custom_logo_url = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' );
                }
                $logo_max_h = get_theme_mod( 'custom_theme_logo_max_height', 48 );

                if ( ! empty( $custom_logo_url ) ) :
                    ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
                        <img src="<?php echo esc_url( $custom_logo_url ); ?>" class="custom-logo site-logo-img" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" style="max-height: <?php echo esc_attr( $logo_max_h ); ?>px;">
                    </a>
                    <?php
                elseif ( has_custom_logo() ) :
                    the_custom_logo();
                else :
                    if ( is_front_page() && is_home() ) :
                        ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                        <?php
                    else :
                        ?>
                        <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                        <?php
                    endif;
                endif;

                if ( get_theme_mod( 'custom_theme_show_tagline', false ) ) :
                    $custom_theme_description = get_bloginfo( 'description', 'display' );
                    if ( $custom_theme_description || is_customize_preview() ) :
                        ?>
                        <p class="site-description"><?php echo esc_html( $custom_theme_description ); ?></p>
                        <?php
                    endif;
                endif;
                ?>
            </div><!-- .site-branding -->

            <!-- Desktop Primary Navigation (Suppressed in Minimal Layout) -->
            <?php if ( 'minimal' !== $header_layout ) : ?>
                <nav id="site-navigation" class="main-navigation desktop-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'custom-theme' ); ?>">
                    <?php
                    if ( has_nav_menu( 'primary' ) ) :
                        wp_nav_menu(
                            array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'menu_class'     => 'nav-menu primary-nav-list',
                                'container'      => false,
                                'depth'          => 2,
                                'fallback_cb'    => false,
                            )
                        );
                    else :
                        ?>
                        <ul class="nav-menu primary-nav-list fallback-menu">
                            <li class="menu-item <?php echo is_front_page() ? 'current-menu-item' : ''; ?>">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'custom-theme' ); ?></a>
                            </li>
                            <?php
                            $header_cats = array_slice($categories, 0, 4);
                            foreach ( $header_cats as $cat ) :
                                ?>
                                <li class="menu-item">
                                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </nav><!-- #site-navigation -->
            <?php endif; ?>

            <!-- Header Action Controls -->
            <div class="header-actions">
                <!-- Dark Mode Toggle -->
                <?php if ( get_theme_mod( 'custom_theme_enable_dark_mode', false ) ) : ?>
                    <button type="button" class="header-action-btn dark-mode-toggle" id="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'custom-theme' ); ?>">
                        <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    </button>
                <?php endif; ?>

                <!-- Search Button -->
                <?php if ( get_theme_mod( 'custom_theme_show_header_search', true ) ) : ?>
                    <button type="button" class="header-action-btn search-toggle-btn" id="search-toggle-btn" aria-label="<?php esc_attr_e( 'Open search dialog', 'custom-theme' ); ?>" aria-expanded="false" aria-controls="search-modal">
                        <?php echo custom_theme_svg_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <span class="search-btn-label screen-reader-text"><?php esc_html_e( 'Search', 'custom-theme' ); ?></span>
                    </button>
                <?php endif; ?>

                <!-- Optional Header Call to Action Button -->
                <?php if ( get_theme_mod( 'custom_theme_enable_header_cta', false ) ) : ?>
                    <?php
                    $cta_text   = get_theme_mod( 'custom_theme_header_cta_text', __( 'Subscribe', 'custom-theme' ) );
                    $cta_url    = get_theme_mod( 'custom_theme_header_cta_url', '#newsletter' );
                    $cta_target = get_theme_mod( 'custom_theme_header_cta_target', false ) ? '_blank' : '_self';
                    $cta_style  = get_theme_mod( 'custom_theme_header_cta_style', 'primary' );
                    ?>
                    <a href="<?php echo esc_url( $cta_url ); ?>" target="<?php echo esc_attr( $cta_target ); ?>" class="header-cta-btn header-cta-<?php echo esc_attr( $cta_style ); ?>" <?php echo ( '_blank' === $cta_target ) ? 'rel="noopener noreferrer"' : ''; ?>>
                        <span><?php echo esc_html( $cta_text ); ?></span>
                    </a>
                <?php endif; ?>

                <!-- Mobile Menu Toggle Button (Also shown on desktop in Minimal layout) -->
                <button type="button" class="header-action-btn menu-toggle-btn <?php echo ( 'minimal' === $header_layout ) ? '' : 'mobile-only'; ?>" id="menu-toggle-btn" aria-label="<?php esc_attr_e( 'Open navigation menu', 'custom-theme' ); ?>" aria-expanded="false" aria-controls="mobile-navigation-drawer">
                    <span class="menu-icon-open"><?php echo custom_theme_svg_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <span class="menu-icon-close" style="display: none;"><?php echo custom_theme_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'custom-theme' ); ?></span>
                </button>
            </div><!-- .header-actions -->

        </div><!-- .container -->
    </header><!-- #masthead -->

    <!-- Search Modal Dialog Overlay (Smart Live Search) -->
    <div id="search-modal" class="search-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search the site', 'custom-theme' ); ?>" hidden>
        <div class="search-modal-backdrop" id="search-modal-backdrop"></div>
        <div class="search-modal-box container-narrow">
            <div class="search-modal-header">
                <div class="search-header-title-group">
                    <span class="search-modal-title"><?php esc_html_e( 'Search Articles', 'custom-theme' ); ?></span>
                    <span class="search-modal-subtitle"><?php esc_html_e( 'Discover stories, analysis & ideas', 'custom-theme' ); ?></span>
                </div>
                <button type="button" class="search-modal-close" id="search-modal-close" aria-label="<?php esc_attr_e( 'Close search dialog', 'custom-theme' ); ?>">
                    <?php echo custom_theme_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </button>
            </div>
            
            <div class="search-modal-body">
                <form role="search" method="get" class="search-form-modal" id="search-form-modal" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-input-wrapper">
                        <span class="search-input-icon"><?php echo custom_theme_svg_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <input type="search" id="modal-search-field" class="search-field-modal" placeholder="<?php esc_attr_e( 'Type to search articles instantly&hellip;', 'custom-theme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required>
                        <button type="button" class="search-clear-btn" id="modal-search-clear" aria-label="<?php esc_attr_e( 'Clear search field', 'custom-theme' ); ?>" hidden><?php echo custom_theme_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
                        <div class="search-spinner" id="modal-search-spinner" hidden aria-hidden="true"></div>
                    </div>
                </form>

                <!-- Live Search Results Container -->
                <div class="search-live-results" id="search-live-results" hidden aria-live="polite"></div>

                <!-- Recent Searches Container -->
                <div class="search-recent-searches" id="search-recent-searches" hidden>
                    <div class="recent-searches-header">
                        <span class="recent-searches-title"><?php esc_html_e( 'Recent Searches', 'custom-theme' ); ?></span>
                        <button type="button" class="recent-searches-clear" id="recent-searches-clear"><?php esc_html_e( 'Clear all', 'custom-theme' ); ?></button>
                    </div>
                    <div class="recent-searches-list" id="recent-searches-list"></div>
                </div>

                <!-- Quick Categories Pills -->
                <div class="search-quick-categories" id="search-quick-categories">
                    <span class="quick-cat-label"><?php esc_html_e( 'Popular Topics:', 'custom-theme' ); ?></span>
                    <div class="quick-cat-pills">
                        <?php
                        $filter_cats = array_filter($categories, function($cat) {
                            return strtolower($cat->slug) !== 'uncategorized';
                        });
                        if (empty($filter_cats)) {
                            $filter_cats = $categories;
                        }
                        $quick_cats = array_slice($filter_cats, 0, 5);
                        foreach ( $quick_cats as $cat ) :
                            ?>
                            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="quick-cat-link" data-query="<?php echo esc_attr( $cat->name ); ?>">
                                <?php echo esc_html( $cat->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Modal Keyboard Navigation Footer -->
            <div class="search-modal-footer">
                <div class="search-shortcuts-hint">
                    <span><kbd>&uarr;</kbd><kbd>&darr;</kbd> <?php esc_html_e( 'Navigate', 'custom-theme' ); ?></span>
                    <span><kbd>&crarr;</kbd> <?php esc_html_e( 'Open', 'custom-theme' ); ?></span>
                    <span><kbd>ESC</kbd> <?php esc_html_e( 'Close', 'custom-theme' ); ?></span>
                </div>
            </div>
        </div>
    </div><!-- #search-modal -->

    <!-- Mobile Slide-out Navigation Drawer -->
    <div id="mobile-navigation-drawer" class="mobile-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mobile Menu', 'custom-theme' ); ?>" hidden>
        <div class="mobile-drawer-backdrop" id="mobile-drawer-backdrop"></div>
        <div class="mobile-drawer-panel">
            <div class="mobile-drawer-header">
                <div class="drawer-branding">
                    <span class="drawer-title"><?php bloginfo( 'name' ); ?></span>
                </div>
                <button type="button" class="drawer-close-btn" id="mobile-drawer-close" aria-label="<?php esc_attr_e( 'Close navigation menu', 'custom-theme' ); ?>">
                    <?php echo custom_theme_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </button>
            </div>
            
            <div class="mobile-drawer-body">
                <!-- Mobile Navigation List -->
                <nav class="mobile-navigation" aria-label="<?php esc_attr_e( 'Mobile Navigation List', 'custom-theme' ); ?>">
                    <?php
                    if ( has_nav_menu( 'primary' ) ) :
                        wp_nav_menu(
                            array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'mobile-menu-list',
                                'menu_class'     => 'mobile-nav-list',
                                'container'      => false,
                                'depth'          => 2,
                            )
                        );
                    else :
                        ?>
                        <ul class="mobile-nav-list">
                            <li class="menu-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'custom-theme' ); ?></a></li>
                            <?php foreach ( $categories as $cat ) : ?>
                                <li class="menu-item"><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </nav>

                <!-- Optional Mobile Search Form -->
                <?php if ( get_theme_mod( 'custom_theme_mobile_drawer_show_search', true ) ) : ?>
                    <div class="mobile-drawer-search">
                        <?php get_search_form(); ?>
                    </div>
                <?php endif; ?>

                <!-- Optional Mobile Drawer Social Links -->
                <?php if ( get_theme_mod( 'custom_theme_mobile_drawer_show_social', true ) ) : ?>
                    <div class="mobile-drawer-social">
                        <?php
                        $tw = get_theme_mod( 'custom_theme_social_twitter', '' );
                        $fb = get_theme_mod( 'custom_theme_social_facebook', '' );
                        $li = get_theme_mod( 'custom_theme_social_linkedin', '' );
                        $ig = get_theme_mod( 'custom_theme_social_instagram', '' );
                        if ( $tw ) echo '<a href="' . esc_url( $tw ) . '" target="_blank" rel="noopener noreferrer" class="social-icon-btn" aria-label="' . esc_attr__( 'Twitter', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'x-twitter' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        if ( $fb ) echo '<a href="' . esc_url( $fb ) . '" target="_blank" rel="noopener noreferrer" class="social-icon-btn" aria-label="' . esc_attr__( 'Facebook', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'facebook' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        if ( $li ) echo '<a href="' . esc_url( $li ) . '" target="_blank" rel="noopener noreferrer" class="social-icon-btn" aria-label="' . esc_attr__( 'LinkedIn', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'linkedin' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        if ( $ig ) echo '<a href="' . esc_url( $ig ) . '" target="_blank" rel="noopener noreferrer" class="social-icon-btn" aria-label="' . esc_attr__( 'Instagram', 'custom-theme' ) . '">' . custom_theme_svg_icon( 'share' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div><!-- #mobile-navigation-drawer -->

    <?php if ( is_singular( 'post' ) && get_theme_mod( 'custom_theme_enable_reading_mode', true ) ) : ?>
    <!-- Distraction-Free Reading Mode Floating Control Bar -->
    <div id="reading-mode-bar" class="reading-mode-bar" hidden aria-label="<?php esc_attr_e( 'Reading Mode Controls', 'custom-theme' ); ?>">
        <div class="reader-controls-inner">
            <div class="reader-control-group">
                <span class="reader-control-label"><?php esc_html_e( 'Theme:', 'custom-theme' ); ?></span>
                <button type="button" class="reader-theme-btn is-active" data-reader-theme="light" title="<?php esc_attr_e( 'Light Paper', 'custom-theme' ); ?>">Aa</button>
                <button type="button" class="reader-theme-btn reader-theme-sepia" data-reader-theme="sepia" title="<?php esc_attr_e( 'Sepia Warm', 'custom-theme' ); ?>">Aa</button>
                <button type="button" class="reader-theme-btn reader-theme-dark" data-reader-theme="dark" title="<?php esc_attr_e( 'Dark Night', 'custom-theme' ); ?>">Aa</button>
            </div>
            <div class="reader-control-group">
                <span class="reader-control-label"><?php esc_html_e( 'Font:', 'custom-theme' ); ?></span>
                <button type="button" class="reader-font-btn is-active" data-reader-font="serif" title="<?php esc_attr_e( 'Serif (Lora)', 'custom-theme' ); ?>">Serif</button>
                <button type="button" class="reader-font-btn" data-reader-font="sans" title="<?php esc_attr_e( 'Sans-Serif (Inter)', 'custom-theme' ); ?>">Sans</button>
            </div>
            <div class="reader-control-group">
                <span class="reader-control-label"><?php esc_html_e( 'Size:', 'custom-theme' ); ?></span>
                <button type="button" class="reader-size-btn" id="reader-font-decrease" title="<?php esc_attr_e( 'Decrease text size', 'custom-theme' ); ?>" aria-label="<?php esc_attr_e( 'Decrease text size', 'custom-theme' ); ?>">A-</button>
                <button type="button" class="reader-size-btn" id="reader-font-reset" title="<?php esc_attr_e( 'Reset text size', 'custom-theme' ); ?>" aria-label="<?php esc_attr_e( 'Reset text size', 'custom-theme' ); ?>">100%</button>
                <button type="button" class="reader-size-btn" id="reader-font-increase" title="<?php esc_attr_e( 'Increase text size', 'custom-theme' ); ?>" aria-label="<?php esc_attr_e( 'Increase text size', 'custom-theme' ); ?>">A+</button>
            </div>
            <button type="button" class="reader-exit-btn" id="reader-exit-btn">
                <?php echo custom_theme_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <span><?php esc_html_e( 'Exit Reader', 'custom-theme' ); ?></span>
                <kbd>ESC</kbd>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <?php endif; // End custom_theme_display_header ?>
