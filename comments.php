<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <div class="comments-header section-header">
            <h2 class="comments-title section-title">
                <?php echo custom_theme_svg_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <span>
                    <?php
                    $custom_theme_comment_count = get_comments_number();
                    if ( '1' === $custom_theme_comment_count ) {
                        printf(
                            /* translators: 1: title. */
                            esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'custom-theme' ),
                            '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                        );
                    } else {
                        printf(
                            /* translators: 1: comment count number, 2: title. */
                            esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $custom_theme_comment_count, 'comments title', 'custom-theme' ) ),
                            number_format_i18n( $custom_theme_comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                        );
                    }
                    ?>
                </span>
            </h2>
        </div>

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 48,
                    'format'      => 'html5',
                )
            );
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note.
        if ( ! comments_open() ) :
            ?>
            <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'custom-theme' ); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    comment_form(
        array(
            'class_form'           => 'editorial-comment-form',
            'class_submit'         => 'btn btn-primary comment-submit-btn',
            'title_reply'          => esc_html__( 'Leave a Reply', 'custom-theme' ),
            'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'custom-theme' ),
            'cancel_reply_link'    => esc_html__( 'Cancel reply', 'custom-theme' ),
            'label_submit'         => esc_html__( 'Post Comment', 'custom-theme' ),
            'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
            'title_reply_after'    => '</h3>',
            'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'custom-theme' ) . '</span></p>',
        )
    );
    ?>

</div><!-- #comments -->
