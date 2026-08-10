<?php
/**
 * Widget areas and custom widgets registration
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function custom_theme_widgets_init() {
    // Main Editorial Sidebar
    register_sidebar(
        array(
            'name'          => esc_html__( 'Main Sidebar', 'custom-theme' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here to appear in the main editorial sidebar.', 'custom-theme' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    // Footer Column 1
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Column 1 (Brand/About)', 'custom-theme' ),
            'id'            => 'footer-1',
            'description'   => esc_html__( 'Widgets in this column appear under Footer column 1.', 'custom-theme' ),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="footer-heading">',
            'after_title'   => '</h3>',
        )
    );

    // Footer Column 2
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Column 2 (Navigation)', 'custom-theme' ),
            'id'            => 'footer-2',
            'description'   => esc_html__( 'Widgets in this column appear under Footer column 2.', 'custom-theme' ),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="footer-heading">',
            'after_title'   => '</h3>',
        )
    );

    // Footer Column 3
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Column 3 (Topics)', 'custom-theme' ),
            'id'            => 'footer-3',
            'description'   => esc_html__( 'Widgets in this column appear under Footer column 3.', 'custom-theme' ),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="footer-heading">',
            'after_title'   => '</h3>',
        )
    );

    // Footer Column 4
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Column 4 (Recent Stories)', 'custom-theme' ),
            'id'            => 'footer-4',
            'description'   => esc_html__( 'Widgets in this column appear under Footer column 4.', 'custom-theme' ),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="footer-heading">',
            'after_title'   => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'custom_theme_widgets_init' );
