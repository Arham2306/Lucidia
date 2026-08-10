<?php
/**
 * The template for displaying all static pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<div class="site-content-container container">
    <main id="primary" class="site-main content-area static-page-main container-narrow">

        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content-page' );

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>

    </main><!-- #primary -->
</div><!-- .container -->

<?php
get_footer();
