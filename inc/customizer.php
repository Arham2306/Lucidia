<?php
/**
 * Custom Editorial Theme Customizer settings and live CSS output
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function custom_theme_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => function () {
                    bloginfo( 'name' );
                },
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => function () {
                    bloginfo( 'description' );
                },
            )
        );
    }

    /* ==========================================================================
       1. Theme Colors
       ========================================================================== */
    $wp_customize->add_setting(
        'custom_theme_accent_color',
        array(
            'default'           => '#184a7e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_accent_color',
            array(
                'label'       => esc_html__( 'Accent Color', 'custom-theme' ),
                'description' => esc_html__( 'Primary accent color for buttons, badges, links, and highlights.', 'custom-theme' ),
                'section'     => 'colors',
            )
        )
    );

    $wp_customize->add_setting(
        'custom_theme_accent_hover_color',
        array(
            'default'           => '#11365d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_accent_hover_color',
            array(
                'label'       => esc_html__( 'Accent Hover Color', 'custom-theme' ),
                'description' => esc_html__( 'Darker hover variant of the accent color.', 'custom-theme' ),
                'section'     => 'colors',
            )
        )
    );

    /* ==========================================================================
       2. Header & Navigation Panel (Complete Customization Suite)
       ========================================================================== */
    $wp_customize->add_panel(
        'custom_theme_header_panel',
        array(
            'title'       => esc_html__( 'Header & Navigation', 'custom-theme' ),
            'description' => esc_html__( 'Customize header layouts, top bar, navigation styles, call-to-action buttons, and sticky behavior.', 'custom-theme' ),
            'priority'    => 28,
        )
    );

    // Section 2A: Header Layout & Sizing
    $wp_customize->add_section(
        'custom_theme_header_layout_section',
        array(
            'title'    => esc_html__( 'Layout & Container', 'custom-theme' ),
            'panel'    => 'custom_theme_header_panel',
            'priority' => 10,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_header_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'custom_theme_sanitize_header_layout',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_header_layout',
        array(
            'label'       => esc_html__( 'Header Layout Style', 'custom-theme' ),
            'description' => esc_html__( 'Select the structural arrangement of your logo and navigation.', 'custom-theme' ),
            'section'     => 'custom_theme_header_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'default'  => esc_html__( 'Inline (Logo Left, Nav Center/Right)', 'custom-theme' ),
                'centered' => esc_html__( 'Centered (Stacked Logo & Navigation)', 'custom-theme' ),
                'split'    => esc_html__( 'Split Navigation (Logo Centered)', 'custom-theme' ),
                'minimal'  => esc_html__( 'Minimal (Logo + Hamburger Drawer)', 'custom-theme' ),
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_display_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_display_header',
        array(
            'label'       => esc_html__( 'Display Site Header', 'custom-theme' ),
            'description' => esc_html__( 'Display theme header on site. Turn off if using a page builder custom header template.', 'custom-theme' ),
            'section'     => 'custom_theme_header_layout_section',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_header_width',
        array(
            'default'           => 'contained',
            'sanitize_callback' => 'custom_theme_sanitize_header_width',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_header_width',
        array(
            'label'   => esc_html__( 'Header Width', 'custom-theme' ),
            'section' => 'custom_theme_header_layout_section',
            'type'    => 'select',
            'choices' => array(
                'contained'  => esc_html__( 'Contained (Max Width 1240px)', 'custom-theme' ),
                'full-width' => esc_html__( 'Full Width (Fluid Edge-to-Edge)', 'custom-theme' ),
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_header_height',
        array(
            'default'           => 72,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_header_height',
        array(
            'label'       => esc_html__( 'Header Height (px)', 'custom-theme' ),
            'description' => esc_html__( 'Height in pixels for the main header (default: 72px).', 'custom-theme' ),
            'section'     => 'custom_theme_header_layout_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 54,
                'max'  => 130,
                'step' => 2,
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_header_bg_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_header_bg_color',
            array(
                'label'       => esc_html__( 'Custom Header Background', 'custom-theme' ),
                'description' => esc_html__( 'Leave empty to inherit surface color with dark mode adaptation.', 'custom-theme' ),
                'section'     => 'custom_theme_header_layout_section',
            )
        )
    );

    $wp_customize->add_setting(
        'custom_theme_header_border_bottom',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_header_border_bottom',
        array(
            'label'   => esc_html__( 'Show Bottom Border Line', 'custom-theme' ),
            'section' => 'custom_theme_header_layout_section',
            'type'    => 'checkbox',
        )
    );

    // Section 2B: Top Bar Announcement
    $wp_customize->add_section(
        'custom_theme_topbar_section',
        array(
            'title'    => esc_html__( 'Top Bar / Announcement', 'custom-theme' ),
            'panel'    => 'custom_theme_header_panel',
            'priority' => 15,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_enable_topbar',
        array(
            'default'           => false,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_enable_topbar',
        array(
            'label'       => esc_html__( 'Enable Top Bar', 'custom-theme' ),
            'description' => esc_html__( 'Display a secondary bar above the header for announcements, date, and social links.', 'custom-theme' ),
            'section'     => 'custom_theme_topbar_section',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_topbar_text',
        array(
            'default'           => esc_html__( 'Welcome to our publication — Exploring ideas, research & thoughtful stories.', 'custom-theme' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_topbar_text',
        array(
            'label'   => esc_html__( 'Announcement Message', 'custom-theme' ),
            'section' => 'custom_theme_topbar_section',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_topbar_show_date',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_topbar_show_date',
        array(
            'label'   => esc_html__( 'Display Today&rsquo;s Date', 'custom-theme' ),
            'section' => 'custom_theme_topbar_section',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_topbar_show_social',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_topbar_show_social',
        array(
            'label'   => esc_html__( 'Display Social Links in Top Bar', 'custom-theme' ),
            'section' => 'custom_theme_topbar_section',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_topbar_bg_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_topbar_bg_color',
            array(
                'label'       => esc_html__( 'Top Bar Background Color', 'custom-theme' ),
                'description' => esc_html__( 'Leave empty for default dark contrast background.', 'custom-theme' ),
                'section'     => 'custom_theme_topbar_section',
            )
        )
    );

    $wp_customize->add_setting(
        'custom_theme_topbar_text_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_topbar_text_color',
            array(
                'label'   => esc_html__( 'Top Bar Text Color', 'custom-theme' ),
                'section' => 'custom_theme_topbar_section',
            )
        )
    );

    // Section 2C: Branding & Logo Sizing
    $wp_customize->add_section(
        'custom_theme_header_branding_section',
        array(
            'title'    => esc_html__( 'Logo & Site Title', 'custom-theme' ),
            'panel'    => 'custom_theme_header_panel',
            'priority' => 20,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_logo_max_height',
        array(
            'default'           => 48,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_logo_max_height',
        array(
            'label'       => esc_html__( 'Desktop Logo Max Height (px)', 'custom-theme' ),
            'section'     => 'custom_theme_header_branding_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 24,
                'max'  => 120,
                'step' => 2,
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_logo_mobile_max_height',
        array(
            'default'           => 36,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_logo_mobile_max_height',
        array(
            'label'       => esc_html__( 'Mobile Logo Max Height (px)', 'custom-theme' ),
            'section'     => 'custom_theme_header_branding_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 18,
                'max'  => 80,
                'step' => 2,
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_show_tagline',
        array(
            'default'           => false,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_show_tagline',
        array(
            'label'   => esc_html__( 'Display Tagline under Site Title', 'custom-theme' ),
            'section' => 'custom_theme_header_branding_section',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_site_title_font_size',
        array(
            'default'           => 24,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_site_title_font_size',
        array(
            'label'       => esc_html__( 'Site Title Font Size (px)', 'custom-theme' ),
            'description' => esc_html__( 'Applies when a text site title is displayed.', 'custom-theme' ),
            'section'     => 'custom_theme_header_branding_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 16,
                'max'  => 48,
                'step' => 1,
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_site_title_uppercase',
        array(
            'default'           => false,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_site_title_uppercase',
        array(
            'label'   => esc_html__( 'Transform Site Title to Uppercase', 'custom-theme' ),
            'section' => 'custom_theme_header_branding_section',
            'type'    => 'checkbox',
        )
    );

    // Section 2D: Primary Navigation & Dropdowns
    $wp_customize->add_section(
        'custom_theme_header_nav_section',
        array(
            'title'    => esc_html__( 'Navigation & Dropdowns', 'custom-theme' ),
            'panel'    => 'custom_theme_header_panel',
            'priority' => 30,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_nav_alignment',
        array(
            'default'           => 'center',
            'sanitize_callback' => 'custom_theme_sanitize_nav_align',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_nav_alignment',
        array(
            'label'   => esc_html__( 'Menu Item Alignment', 'custom-theme' ),
            'section' => 'custom_theme_header_nav_section',
            'type'    => 'select',
            'choices' => array(
                'left'   => esc_html__( 'Left', 'custom-theme' ),
                'center' => esc_html__( 'Center', 'custom-theme' ),
                'right'  => esc_html__( 'Right', 'custom-theme' ),
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_nav_indicator_style',
        array(
            'default'           => 'underline',
            'sanitize_callback' => 'custom_theme_sanitize_nav_indicator',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_nav_indicator_style',
        array(
            'label'       => esc_html__( 'Active & Hover Indicator Style', 'custom-theme' ),
            'description' => esc_html__( 'Visual feedback style when hovering or active on navigation links.', 'custom-theme' ),
            'section'     => 'custom_theme_header_nav_section',
            'type'        => 'select',
            'choices'     => array(
                'underline' => esc_html__( 'Animated Bottom Underline', 'custom-theme' ),
                'pill'      => esc_html__( 'Subtle Pill Background Badge', 'custom-theme' ),
                'dot'       => esc_html__( 'Accent Dot Under Link', 'custom-theme' ),
                'none'      => esc_html__( 'Text Color Only (No Indicator)', 'custom-theme' ),
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_nav_font_size',
        array(
            'default'           => 'regular',
            'sanitize_callback' => 'custom_theme_sanitize_nav_size',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_nav_font_size',
        array(
            'label'   => esc_html__( 'Navigation Font Size', 'custom-theme' ),
            'section' => 'custom_theme_header_nav_section',
            'type'    => 'select',
            'choices' => array(
                'small'   => esc_html__( 'Small (13px)', 'custom-theme' ),
                'regular' => esc_html__( 'Regular (14px)', 'custom-theme' ),
                'medium'  => esc_html__( 'Medium (15px)', 'custom-theme' ),
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_nav_uppercase',
        array(
            'default'           => false,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_nav_uppercase',
        array(
            'label'   => esc_html__( 'Uppercase Menu Items', 'custom-theme' ),
            'section' => 'custom_theme_header_nav_section',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_nav_link_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_nav_link_color',
            array(
                'label'   => esc_html__( 'Custom Navigation Link Color', 'custom-theme' ),
                'section' => 'custom_theme_header_nav_section',
            )
        )
    );

    $wp_customize->add_setting(
        'custom_theme_nav_hover_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_nav_hover_color',
            array(
                'label'   => esc_html__( 'Custom Navigation Hover Color', 'custom-theme' ),
                'section' => 'custom_theme_header_nav_section',
            )
        )
    );

    // Section 2E: Call-to-Action & Header Actions
    $wp_customize->add_section(
        'custom_theme_header_actions_section',
        array(
            'title'    => esc_html__( 'Call-to-Action & Buttons', 'custom-theme' ),
            'panel'    => 'custom_theme_header_panel',
            'priority' => 40,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_show_header_search',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_show_header_search',
        array(
            'label'   => esc_html__( 'Show Search Button in Header', 'custom-theme' ),
            'section' => 'custom_theme_header_actions_section',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_enable_header_cta',
        array(
            'default'           => false,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_enable_header_cta',
        array(
            'label'       => esc_html__( 'Enable Header CTA Button', 'custom-theme' ),
            'description' => esc_html__( 'Add an eye-catching call-to-action button (e.g. Subscribe, Sign Up, Contact) in the header.', 'custom-theme' ),
            'section'     => 'custom_theme_header_actions_section',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_header_cta_text',
        array(
            'default'           => esc_html__( 'Subscribe', 'custom-theme' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_header_cta_text',
        array(
            'label'   => esc_html__( 'CTA Button Label', 'custom-theme' ),
            'section' => 'custom_theme_header_actions_section',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_header_cta_url',
        array(
            'default'           => '#newsletter',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_header_cta_url',
        array(
            'label'       => esc_html__( 'CTA Target Link URL', 'custom-theme' ),
            'description' => esc_html__( 'Can be an external URL, internal page, or page anchor like #newsletter.', 'custom-theme' ),
            'section'     => 'custom_theme_header_actions_section',
            'type'        => 'url',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_header_cta_target',
        array(
            'default'           => false,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_header_cta_target',
        array(
            'label'   => esc_html__( 'Open CTA Link in New Tab', 'custom-theme' ),
            'section' => 'custom_theme_header_actions_section',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_header_cta_style',
        array(
            'default'           => 'primary',
            'sanitize_callback' => 'custom_theme_sanitize_cta_style',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_header_cta_style',
        array(
            'label'   => esc_html__( 'CTA Button Style', 'custom-theme' ),
            'section' => 'custom_theme_header_actions_section',
            'type'    => 'select',
            'choices' => array(
                'primary' => esc_html__( 'Filled Accent Button', 'custom-theme' ),
                'outline' => esc_html__( 'Outlined Accent Button', 'custom-theme' ),
                'subtle'  => esc_html__( 'Subtle Pill Button', 'custom-theme' ),
            ),
        )
    );

    // Section 2F: Sticky Header & Scroll Behavior
    $wp_customize->add_section(
        'custom_theme_header_sticky_section',
        array(
            'title'    => esc_html__( 'Sticky Header & Scroll', 'custom-theme' ),
            'panel'    => 'custom_theme_header_panel',
            'priority' => 50,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_sticky_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_sticky_header',
        array(
            'label'   => esc_html__( 'Enable Sticky Header', 'custom-theme' ),
            'section' => 'custom_theme_header_sticky_section',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_sticky_behavior',
        array(
            'default'           => 'smart-hide',
            'sanitize_callback' => 'custom_theme_sanitize_sticky_behavior',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_sticky_behavior',
        array(
            'label'       => esc_html__( 'Sticky Scroll Behavior', 'custom-theme' ),
            'description' => esc_html__( 'Choose whether the header hides when scrolling down and reveals on scroll up, or remains fixed permanently.', 'custom-theme' ),
            'section'     => 'custom_theme_header_sticky_section',
            'type'        => 'select',
            'choices'     => array(
                'smart-hide'   => esc_html__( 'Smart Hide (Hide on Down, Reveal on Up)', 'custom-theme' ),
                'always-fixed' => esc_html__( 'Always Fixed Continuous', 'custom-theme' ),
            ),
        )
    );

    $wp_customize->add_setting(
        'custom_theme_sticky_blur',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_sticky_blur',
        array(
            'label'       => esc_html__( 'Enable Glassmorphism Blur on Sticky', 'custom-theme' ),
            'description' => esc_html__( 'Applies modern backdrop-filter blur when header sticks to top.', 'custom-theme' ),
            'section'     => 'custom_theme_header_sticky_section',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_sticky_shadow',
        array(
            'default'           => 'subtle',
            'sanitize_callback' => 'custom_theme_sanitize_sticky_shadow',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_sticky_shadow',
        array(
            'label'   => esc_html__( 'Sticky Shadow Depth', 'custom-theme' ),
            'section' => 'custom_theme_header_sticky_section',
            'type'    => 'select',
            'choices' => array(
                'subtle' => esc_html__( 'Subtle Depth Shadow', 'custom-theme' ),
                'medium' => esc_html__( 'Elevated Medium Shadow', 'custom-theme' ),
                'none'   => esc_html__( 'No Shadow (Flat Border Only)', 'custom-theme' ),
            ),
        )
    );

    // Section 2G: Mobile Header & Drawer
    $wp_customize->add_section(
        'custom_theme_header_mobile_section',
        array(
            'title'    => esc_html__( 'Mobile Navigation Drawer', 'custom-theme' ),
            'panel'    => 'custom_theme_header_panel',
            'priority' => 60,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_mobile_drawer_bg',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_mobile_drawer_bg',
            array(
                'label'       => esc_html__( 'Mobile Drawer Background Color', 'custom-theme' ),
                'description' => esc_html__( 'Leave empty to inherit surface color.', 'custom-theme' ),
                'section'     => 'custom_theme_header_mobile_section',
            )
        )
    );

    $wp_customize->add_setting(
        'custom_theme_mobile_drawer_show_search',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_mobile_drawer_show_search',
        array(
            'label'   => esc_html__( 'Show Search Form inside Mobile Drawer', 'custom-theme' ),
            'section' => 'custom_theme_header_mobile_section',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_mobile_drawer_show_social',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_mobile_drawer_show_social',
        array(
            'label'   => esc_html__( 'Show Social Links inside Mobile Drawer', 'custom-theme' ),
            'section' => 'custom_theme_header_mobile_section',
            'type'    => 'checkbox',
        )
    );

    /* ==========================================================================
       3. Newsletter Options
       ========================================================================== */
    $wp_customize->add_section(
        'custom_theme_newsletter_section',
        array(
            'title'    => esc_html__( 'Newsletter Settings', 'custom-theme' ),
            'priority' => 40,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_newsletter_title',
        array(
            'default'           => esc_html__( 'Get thoughtful stories delivered directly to your inbox.', 'custom-theme' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'custom_theme_newsletter_title',
        array(
            'label'   => esc_html__( 'Newsletter Headline', 'custom-theme' ),
            'section' => 'custom_theme_newsletter_section',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_newsletter_desc',
        array(
            'default'           => esc_html__( 'Join our weekly digest featuring deep dives, editorial commentary, and design inspiration. No spam, ever.', 'custom-theme' ),
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'custom_theme_newsletter_desc',
        array(
            'label'   => esc_html__( 'Newsletter Subtitle', 'custom-theme' ),
            'section' => 'custom_theme_newsletter_section',
            'type'    => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_newsletter_action',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'custom_theme_newsletter_action',
        array(
            'label'       => esc_html__( 'Form Action URL (Optional)', 'custom-theme' ),
            'description' => esc_html__( 'Paste your Mailchimp, ConvertKit, Brevo, or newsletter form action endpoint.', 'custom-theme' ),
            'section'     => 'custom_theme_newsletter_section',
            'type'        => 'url',
        )
    );

    /* ==========================================================================
       4. Single Post & Blog Layout
       ========================================================================== */
    $wp_customize->add_section(
        'custom_theme_single_post_section',
        array(
            'title'       => esc_html__( 'Single Post & Blog Layout', 'custom-theme' ),
            'description' => esc_html__( 'Configure single article template settings, sidebar display, and reading layouts.', 'custom-theme' ),
            'priority'    => 45,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_single_show_sidebar',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_single_show_sidebar',
        array(
            'label'       => esc_html__( 'Enable Single Post Sidebar', 'custom-theme' ),
            'description' => esc_html__( 'Display sidebar alongside single blog post articles. Turn off for a clean, centered full-width reading layout.', 'custom-theme' ),
            'section'     => 'custom_theme_single_post_section',
            'type'        => 'checkbox',
        )
    );

    /* ==========================================================================
       5. Social Profiles
       ========================================================================== */
    $wp_customize->add_section(
        'custom_theme_social_section',
        array(
            'title'    => esc_html__( 'Social Media Links', 'custom-theme' ),
            'priority' => 50,
        )
    );

    $socials = array(
        'twitter'   => esc_html__( 'X / Twitter URL', 'custom-theme' ),
        'facebook'  => esc_html__( 'Facebook URL', 'custom-theme' ),
        'linkedin'  => esc_html__( 'LinkedIn URL', 'custom-theme' ),
        'instagram' => esc_html__( 'Instagram URL', 'custom-theme' ),
        'github'    => esc_html__( 'GitHub URL', 'custom-theme' ),
        'youtube'   => esc_html__( 'YouTube URL', 'custom-theme' ),
    );

    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting(
            'custom_theme_social_' . $key,
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            'custom_theme_social_' . $key,
            array(
                'label'   => $label,
                'section' => 'custom_theme_social_section',
                'type'    => 'url',
            )
        );
    }

    /* ==========================================================================
       5. Footer Settings
       ========================================================================== */
    $wp_customize->add_section(
        'custom_theme_footer_section',
        array(
            'title'    => esc_html__( 'Footer Settings', 'custom-theme' ),
            'priority' => 60,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_display_footer',
        array(
            'default'           => true,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_display_footer',
        array(
            'label'       => esc_html__( 'Display Site Footer', 'custom-theme' ),
            'description' => esc_html__( 'Display theme footer on site. Turn off if using a page builder custom footer template.', 'custom-theme' ),
            'section'     => 'custom_theme_footer_section',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_footer_bio',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'custom_theme_footer_bio',
        array(
            'label'       => esc_html__( 'Footer Bio / About Text', 'custom-theme' ),
            'description' => esc_html__( 'Leave empty to use the standard site tagline.', 'custom-theme' ),
            'section'     => 'custom_theme_footer_section',
            'type'        => 'textarea',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_footer_copyright',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'custom_theme_footer_copyright',
        array(
            'label'       => esc_html__( 'Custom Copyright Notice', 'custom-theme' ),
            'description' => esc_html__( 'Leave empty for automatic year & site name copyright.', 'custom-theme' ),
            'section'     => 'custom_theme_footer_section',
            'type'        => 'text',
        )
    );

    /* ==========================================================================
       6. Dark Mode Customization Suite
       ========================================================================== */
    $wp_customize->add_section(
        'custom_theme_dark_mode_section',
        array(
            'title'       => esc_html__( 'Dark Mode Settings & Colors', 'custom-theme' ),
            'description' => esc_html__( 'Configure dark mode activation, defaults, and custom color palette for dark theme.', 'custom-theme' ),
            'priority'    => 25,
        )
    );

    $wp_customize->add_setting(
        'custom_theme_enable_dark_mode',
        array(
            'default'           => false,
            'sanitize_callback' => 'custom_theme_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_enable_dark_mode',
        array(
            'label'       => esc_html__( 'Enable Dark Mode Feature', 'custom-theme' ),
            'description' => esc_html__( 'Allow visitors to toggle between light and dark color schemes via header button.', 'custom-theme' ),
            'section'     => 'custom_theme_dark_mode_section',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'custom_theme_dark_mode_default',
        array(
            'default'           => 'light',
            'sanitize_callback' => 'custom_theme_sanitize_dark_mode_default',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'custom_theme_dark_mode_default',
        array(
            'label'       => esc_html__( 'Default Color Scheme', 'custom-theme' ),
            'description' => esc_html__( 'Choose the default color scheme on initial visit.', 'custom-theme' ),
            'section'     => 'custom_theme_dark_mode_section',
            'type'        => 'select',
            'choices'     => array(
                'light'  => esc_html__( 'Light', 'custom-theme' ),
                'dark'   => esc_html__( 'Dark', 'custom-theme' ),
                'auto'   => esc_html__( 'Auto (Follow System / OS)', 'custom-theme' ),
            ),
        )
    );

    // Dark Mode: Background Color
    $wp_customize->add_setting(
        'custom_theme_dark_bg_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_bg_color',
            array(
                'label'       => esc_html__( 'Dark Mode Page Background', 'custom-theme' ),
                'description' => esc_html__( 'Main body background color in dark mode (default: #0f1115).', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: Surface / Card Color
    $wp_customize->add_setting(
        'custom_theme_dark_surface_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_surface_color',
            array(
                'label'       => esc_html__( 'Dark Mode Surface & Card Color', 'custom-theme' ),
                'description' => esc_html__( 'Background for article cards, widgets, and modals in dark mode (default: #181b20).', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: Subtle Surface Color
    $wp_customize->add_setting(
        'custom_theme_dark_surface_subtle',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_surface_subtle',
            array(
                'label'       => esc_html__( 'Dark Mode Subtle Surface Color', 'custom-theme' ),
                'description' => esc_html__( 'Secondary surface for code blocks, badges, and chips (default: #20242b).', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: Main Headings & Text Color
    $wp_customize->add_setting(
        'custom_theme_dark_text_main',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_text_main',
            array(
                'label'       => esc_html__( 'Dark Mode Headings & Main Text', 'custom-theme' ),
                'description' => esc_html__( 'Color for post titles, headings, and primary text (default: #f8fafc).', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: Secondary / Body Text Color
    $wp_customize->add_setting(
        'custom_theme_dark_text_secondary',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_text_secondary',
            array(
                'label'       => esc_html__( 'Dark Mode Body / Secondary Text', 'custom-theme' ),
                'description' => esc_html__( 'Color for article paragraphs, excerpts, and subtitles (default: #cbd5e1).', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: Muted Text Color
    $wp_customize->add_setting(
        'custom_theme_dark_text_muted',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_text_muted',
            array(
                'label'       => esc_html__( 'Dark Mode Muted Text & Meta', 'custom-theme' ),
                'description' => esc_html__( 'Color for dates, reading time, and metadata (default: #94a3b8).', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: Accent / Brand Color
    $wp_customize->add_setting(
        'custom_theme_dark_accent_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_accent_color',
            array(
                'label'       => esc_html__( 'Dark Mode Accent Color', 'custom-theme' ),
                'description' => esc_html__( 'Primary accent color for dark mode buttons and highlights (default: #60a5fa).', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: Accent Hover Color
    $wp_customize->add_setting(
        'custom_theme_dark_accent_hover_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_accent_hover_color',
            array(
                'label'       => esc_html__( 'Dark Mode Accent Hover Color', 'custom-theme' ),
                'description' => esc_html__( 'Hover state for accent elements in dark mode (default: #93c5fd).', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: In-Content Link Color
    $wp_customize->add_setting(
        'custom_theme_dark_link_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_link_color',
            array(
                'label'       => esc_html__( 'Dark Mode Link Color', 'custom-theme' ),
                'description' => esc_html__( 'Color for underlined links inside article content in dark mode.', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: In-Content Link Hover Color
    $wp_customize->add_setting(
        'custom_theme_dark_link_hover_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_link_hover_color',
            array(
                'label'       => esc_html__( 'Dark Mode Link Hover Color', 'custom-theme' ),
                'description' => esc_html__( 'Hover state for in-content links in dark mode.', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: Border Color
    $wp_customize->add_setting(
        'custom_theme_dark_border_color',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_border_color',
            array(
                'label'       => esc_html__( 'Dark Mode Border Color', 'custom-theme' ),
                'description' => esc_html__( 'Color for dividers and card borders in dark mode (default: #2c323c).', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );

    // Dark Mode: Header Background Color
    $wp_customize->add_setting(
        'custom_theme_dark_header_bg',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'custom_theme_dark_header_bg',
            array(
                'label'       => esc_html__( 'Dark Mode Header Background', 'custom-theme' ),
                'description' => esc_html__( 'Leave empty to inherit surface color with transparency.', 'custom-theme' ),
                'section'     => 'custom_theme_dark_mode_section',
            )
        )
    );
}
add_action( 'customize_register', 'custom_theme_customize_register' );

/* ==========================================================================
   Customizer Sanitization Callbacks
   ========================================================================== */

/**
 * Checkbox sanitization callback.
 */
function custom_theme_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === (bool) $checked ) ? true : false );
}

/**
 * Sanitize header layout select.
 */
function custom_theme_sanitize_header_layout( $input ) {
    $valid = array( 'default', 'centered', 'split', 'minimal' );
    return in_array( $input, $valid, true ) ? $input : 'default';
}

/**
 * Sanitize header width select.
 */
function custom_theme_sanitize_header_width( $input ) {
    $valid = array( 'contained', 'full-width' );
    return in_array( $input, $valid, true ) ? $input : 'contained';
}

/**
 * Sanitize navigation alignment.
 */
function custom_theme_sanitize_nav_align( $input ) {
    $valid = array( 'left', 'center', 'right' );
    return in_array( $input, $valid, true ) ? $input : 'center';
}

/**
 * Sanitize navigation indicator style.
 */
function custom_theme_sanitize_nav_indicator( $input ) {
    $valid = array( 'underline', 'pill', 'dot', 'none' );
    return in_array( $input, $valid, true ) ? $input : 'underline';
}

/**
 * Sanitize navigation font size.
 */
function custom_theme_sanitize_nav_size( $input ) {
    $valid = array( 'small', 'regular', 'medium' );
    return in_array( $input, $valid, true ) ? $input : 'regular';
}

/**
 * Sanitize CTA button style.
 */
function custom_theme_sanitize_cta_style( $input ) {
    $valid = array( 'primary', 'outline', 'subtle' );
    return in_array( $input, $valid, true ) ? $input : 'primary';
}

/**
 * Sanitize sticky behavior select.
 */
function custom_theme_sanitize_sticky_behavior( $input ) {
    $valid = array( 'smart-hide', 'always-fixed' );
    return in_array( $input, $valid, true ) ? $input : 'smart-hide';
}

/**
 * Sanitize sticky shadow select.
 */
function custom_theme_sanitize_sticky_shadow( $input ) {
    $valid = array( 'subtle', 'medium', 'none' );
    return in_array( $input, $valid, true ) ? $input : 'subtle';
}

/**
 * Sanitize dark mode default select.
 */
function custom_theme_sanitize_dark_mode_default( $input ) {
    $valid = array( 'light', 'dark', 'auto' );
    return in_array( $input, $valid, true ) ? $input : 'light';
}

/**
 * Output dynamic CSS variables configured in Customizer.
 */
function custom_theme_customizer_css() {
    $accent             = get_theme_mod( 'custom_theme_accent_color', '#184a7e' );
    $accent_hover       = get_theme_mod( 'custom_theme_accent_hover_color', '#11365d' );
    $header_height      = get_theme_mod( 'custom_theme_header_height', 72 );
    $header_bg          = get_theme_mod( 'custom_theme_header_bg_color', '' );
    $header_border      = get_theme_mod( 'custom_theme_header_border_bottom', true );
    $logo_height        = get_theme_mod( 'custom_theme_logo_max_height', 48 );
    $logo_mobile_height = get_theme_mod( 'custom_theme_logo_mobile_max_height', 36 );
    $site_title_size    = get_theme_mod( 'custom_theme_site_title_font_size', 24 );
    $topbar_bg          = get_theme_mod( 'custom_theme_topbar_bg_color', '' );
    $topbar_text        = get_theme_mod( 'custom_theme_topbar_text_color', '' );
    $nav_link_color     = get_theme_mod( 'custom_theme_nav_link_color', '' );
    $nav_hover_color    = get_theme_mod( 'custom_theme_nav_hover_color', '' );
    $drawer_bg          = get_theme_mod( 'custom_theme_mobile_drawer_bg', '' );

    $css = '';

    // Light Theme / Global Variables
    $root_vars = array();

    if ( '#184a7e' !== $accent ) {
        $root_vars[] = '--color-accent: ' . esc_attr( $accent ) . ';';
    }
    if ( '#11365d' !== $accent_hover ) {
        $root_vars[] = '--color-accent-hover: ' . esc_attr( $accent_hover ) . ';';
    }
    if ( 72 !== (int) $header_height ) {
        $root_vars[] = '--header-min-height: ' . (int) $header_height . 'px;';
    }
    if ( 48 !== (int) $logo_height ) {
        $root_vars[] = '--logo-max-height: ' . (int) $logo_height . 'px;';
    }
    if ( 36 !== (int) $logo_mobile_height ) {
        $root_vars[] = '--logo-mobile-max-height: ' . (int) $logo_mobile_height . 'px;';
    }
    if ( 24 !== (int) $site_title_size ) {
        $root_vars[] = '--site-title-size: ' . (int) $site_title_size . 'px;';
    }
    if ( ! empty( $header_bg ) ) {
        $root_vars[] = '--header-custom-bg: ' . esc_attr( $header_bg ) . ';';
    }
    if ( ! empty( $topbar_bg ) ) {
        $root_vars[] = '--topbar-custom-bg: ' . esc_attr( $topbar_bg ) . ';';
    }
    if ( ! empty( $topbar_text ) ) {
        $root_vars[] = '--topbar-custom-text: ' . esc_attr( $topbar_text ) . ';';
    }
    if ( ! empty( $nav_link_color ) ) {
        $root_vars[] = '--nav-custom-link-color: ' . esc_attr( $nav_link_color ) . ';';
    }
    if ( ! empty( $nav_hover_color ) ) {
        $root_vars[] = '--nav-custom-hover-color: ' . esc_attr( $nav_hover_color ) . ';';
    }
    if ( ! empty( $drawer_bg ) ) {
        $root_vars[] = '--drawer-custom-bg: ' . esc_attr( $drawer_bg ) . ';';
    }

    if ( ! empty( $root_vars ) ) {
        $css .= ':root { ' . implode( ' ', $root_vars ) . ' }';
    }

    if ( ! $header_border ) {
        $css .= ' .site-header { border-bottom: none !important; }';
    }

    // Dark Mode Customizer Tokens
    $dark_vars = array();

    $dark_bg           = get_theme_mod( 'custom_theme_dark_bg_color', '' );
    $dark_surface      = get_theme_mod( 'custom_theme_dark_surface_color', '' );
    $dark_subtle       = get_theme_mod( 'custom_theme_dark_surface_subtle', '' );
    $dark_text_main    = get_theme_mod( 'custom_theme_dark_text_main', '' );
    $dark_text_sec     = get_theme_mod( 'custom_theme_dark_text_secondary', '' );
    $dark_text_muted   = get_theme_mod( 'custom_theme_dark_text_muted', '' );
    $dark_accent       = get_theme_mod( 'custom_theme_dark_accent_color', '' );
    $dark_accent_hover = get_theme_mod( 'custom_theme_dark_accent_hover_color', '' );
    $dark_link         = get_theme_mod( 'custom_theme_dark_link_color', '' );
    $dark_link_hover   = get_theme_mod( 'custom_theme_dark_link_hover_color', '' );
    $dark_border       = get_theme_mod( 'custom_theme_dark_border_color', '' );
    $dark_header_bg    = get_theme_mod( 'custom_theme_dark_header_bg', '' );

    if ( ! empty( $dark_bg ) ) {
        $dark_vars[] = '--color-bg: ' . esc_attr( $dark_bg ) . ';';
    }
    if ( ! empty( $dark_surface ) ) {
        $dark_vars[] = '--color-surface: ' . esc_attr( $dark_surface ) . ';';
    }
    if ( ! empty( $dark_subtle ) ) {
        $dark_vars[] = '--color-surface-subtle: ' . esc_attr( $dark_subtle ) . ';';
    }
    if ( ! empty( $dark_text_main ) ) {
        $dark_vars[] = '--color-text-main: ' . esc_attr( $dark_text_main ) . ';';
    }
    if ( ! empty( $dark_text_sec ) ) {
        $dark_vars[] = '--color-text-secondary: ' . esc_attr( $dark_text_sec ) . ';';
    }
    if ( ! empty( $dark_text_muted ) ) {
        $dark_vars[] = '--color-text-muted: ' . esc_attr( $dark_text_muted ) . ';';
    }
    if ( ! empty( $dark_accent ) ) {
        $dark_vars[] = '--color-accent: ' . esc_attr( $dark_accent ) . ';';
        $dark_vars[] = '--color-accent-text: ' . esc_attr( $dark_accent ) . ';';
    }
    if ( ! empty( $dark_accent_hover ) ) {
        $dark_vars[] = '--color-accent-hover: ' . esc_attr( $dark_accent_hover ) . ';';
    }
    if ( ! empty( $dark_link ) ) {
        $dark_vars[] = '--color-link: ' . esc_attr( $dark_link ) . ';';
    }
    if ( ! empty( $dark_link_hover ) ) {
        $dark_vars[] = '--color-link-hover: ' . esc_attr( $dark_link_hover ) . ';';
    }
    if ( ! empty( $dark_border ) ) {
        $dark_vars[] = '--color-border: ' . esc_attr( $dark_border ) . ';';
    }
    if ( ! empty( $dark_header_bg ) ) {
        $dark_vars[] = '--header-custom-bg: ' . esc_attr( $dark_header_bg ) . ';';
    }

    if ( ! empty( $dark_vars ) ) {
        $css .= ' [data-theme="dark"] { ' . implode( ' ', $dark_vars ) . ' }';
    }

    if ( ! empty( $css ) ) {
        echo "\n" . '<style id="custom-theme-customizer-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_head', 'custom_theme_customizer_css', 100 );

