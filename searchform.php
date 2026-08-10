<?php
/**
 * The template for displaying search forms
 *
 * @package Custom_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$custom_theme_search_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="search-field-wrapper">
        <label for="<?php echo esc_attr( $custom_theme_search_id ); ?>" class="screen-reader-text">
            <?php esc_html_e( 'Search for:', 'custom-theme' ); ?>
        </label>
        <input 
            type="search" 
            id="<?php echo esc_attr( $custom_theme_search_id ); ?>" 
            class="search-field" 
            placeholder="<?php echo esc_attr_x( 'Search articles&hellip;', 'placeholder', 'custom-theme' ); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
            autocomplete="off"
            required
        />
        <button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'Search', 'custom-theme' ); ?>" title="<?php esc_attr_e( 'Search', 'custom-theme' ); ?>">
            <?php echo custom_theme_svg_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </button>
    </div>
</form>
