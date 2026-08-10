<?php
/**
 * Template Name: Page Builder (Blank Canvas)
 * Template Post Type: page, post
 *
 * A blank canvas template with no header or footer,
 * ideal for landing pages, coming soon pages, and full custom page builder takeovers.
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?><!doctype html>
<html <?php language_attributes(); ?> data-theme="<?php echo esc_attr( get_theme_mod( 'custom_theme_dark_mode_default', 'light' ) === 'dark' ? 'dark' : 'light' ); ?>">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'page-builder-canvas-body' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site page-builder-canvas-wrapper">
    <main id="primary" class="site-main content-area page-builder-canvas-main">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
    </main><!-- #primary -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
