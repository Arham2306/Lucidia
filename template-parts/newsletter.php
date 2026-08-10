<?php
/**
 * Template part for displaying the Newsletter Signup Callout
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$newsletter_title  = get_theme_mod( 'custom_theme_newsletter_title', esc_html__( 'Get thoughtful stories delivered directly to your inbox.', 'custom-theme' ) );
$newsletter_desc   = get_theme_mod( 'custom_theme_newsletter_desc', esc_html__( 'Join our weekly digest featuring deep dives, editorial commentary, and design inspiration. No spam, ever.', 'custom-theme' ) );
$newsletter_action = get_theme_mod( 'custom_theme_newsletter_action', '' );
?>

<section class="newsletter-section" aria-label="<?php esc_attr_e( 'Newsletter Signup', 'custom-theme' ); ?>">
    <div class="newsletter-inner">
        <div class="newsletter-content">
            <span class="newsletter-badge"><?php esc_html_e( 'Stay Informed', 'custom-theme' ); ?></span>
            <h3 class="newsletter-title"><?php echo esc_html( $newsletter_title ); ?></h3>
            <p class="newsletter-description"><?php echo esc_html( $newsletter_desc ); ?></p>
        </div>

        <div class="newsletter-form-wrapper">
            <form action="<?php echo esc_url( $newsletter_action ? $newsletter_action : '#' ); ?>" method="post" class="newsletter-form" <?php echo empty( $newsletter_action ) ? 'onsubmit="event.preventDefault(); alert(\'' . esc_js( __( 'Thank you for subscribing!', 'custom-theme' ) ) . '\');"' : ''; ?>>
                <div class="newsletter-input-group">
                    <input type="email" name="newsletter_email" class="newsletter-input" placeholder="<?php echo esc_attr_x( 'Enter your email address&hellip;', 'placeholder', 'custom-theme' ); ?>" required>
                    <button type="submit" class="btn btn-primary newsletter-submit-btn">
                        <span><?php esc_html_e( 'Subscribe', 'custom-theme' ); ?></span>
                        <?php echo custom_theme_svg_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </button>
                </div>
                <p class="newsletter-privacy"><?php esc_html_e( 'We respect your privacy. Unsubscribe at any time.', 'custom-theme' ); ?></p>
            </form>
        </div>
    </div>
</section>
