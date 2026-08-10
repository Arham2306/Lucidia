<?php
/**
 * The template for displaying all single posts
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<?php
$sidebar_override = get_post_meta( get_the_ID(), '_custom_theme_sidebar_override', true );
if ( 'show' === $sidebar_override ) {
    $show_sidebar = true;
} elseif ( 'hide' === $sidebar_override ) {
    $show_sidebar = false;
} else {
    $show_sidebar = get_theme_mod( 'custom_theme_single_show_sidebar', true );
}

$single_template = get_post_meta( get_the_ID(), '_custom_theme_single_template', true );
if ( empty( $single_template ) || 'inherit' === $single_template ) {
    $single_template = get_theme_mod( 'custom_theme_default_single_template', 'classic' );
}
?>

<div class="site-content-container container">
    <?php custom_theme_breadcrumbs(); ?>
    <div class="editorial-layout <?php echo ! $show_sidebar ? 'editorial-layout-no-sidebar' : ''; ?>">
        <main id="primary" class="site-main content-area single-post-main">

            <?php
            while ( have_posts() ) :
                the_post();

                if ( 'magazine' === $single_template ) {
                    get_template_part( 'template-parts/content-single-magazine' );
                } elseif ( 'minimal' === $single_template ) {
                    get_template_part( 'template-parts/content-single-minimal' );
                } else {
                    get_template_part( 'template-parts/content-single' );
                }

                // If comments are open and enabled in settings, load up the comment template.
                if ( get_theme_mod( 'custom_theme_single_show_comments', true ) && ( comments_open() || get_comments_number() ) ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>

        </main><!-- #primary -->

        <?php
        if ( $show_sidebar ) :
            get_sidebar();
        endif;
        ?>

    </div><!-- .editorial-layout -->
</div><!-- .container -->

<?php
get_footer();
