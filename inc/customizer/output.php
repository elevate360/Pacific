<?php
/**
 * Pacific Theme Customizer Output
 *
 * @package Pacific
 */

/**
 * Print inline style
 *
 * @return string
 */
function pacific_add_inline_style(){

	$setting = pacific_setting_default();

	$css= '';

	if ( get_theme_mod( 'post_date', $setting['post_date'] ) == false ) {
		$css .= '.entry-meta .posted-on{ display: none }';
	}

	if ( get_theme_mod( 'post_author', $setting['post_author'] ) == false ) {
		$css .= '.entry-meta .byline{ display: none }';
	}

	if ( get_theme_mod( 'post_cat', $setting['post_cat'] ) == false ) {
		$css .= '.entry-footer .cat-links{ display: none }';
	}

	if ( get_theme_mod( 'post_tag', $setting['post_tag'] ) == false ) {
		$css .= '.entry-footer .tags-links{ display: none }';
	}

	if ( get_theme_mod( 'post_comments', $setting['post_comments'] ) == false ) {
		$css .= '.entry-footer .comments-link{ display: none }';
	}

	if ( ( get_theme_mod( 'post_cat', $setting['post_cat'] ) && get_theme_mod( 'post_tag', $setting['post_tag'] ) && get_theme_mod( 'post_comments', $setting['post_comments'] ) ) == false ) {
		$css .= '.masonry-container .entry-footer { display: none }';
	}

	$primary_color = get_theme_mod( 'primary_color', $setting['primary_color'] );
	$primary_color_background_color = '
		button,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.screen-reader-text:focus,
		.sticky-label,
		.post-edit-link:hover,
		.post-edit-link:focus,
		.masonry-container a.more-link:hover,
		.masonry-container a.more-link:focus,
		.comment-body > .reply a:hover,
		.comment-body > .reply a:focus,
		#cancel-comment-reply-link:hover,
		#cancel-comment-reply-link:focus,
		.posts-navigation .nav-previous a:hover,
		.posts-navigation .nav-previous a:focus,
		.posts-navigation .nav-next a:hover,
		.posts-navigation .nav-next a:focus,
		.post-navigation .nav-previous a:hover,
		.post-navigation .nav-previous a:focus,
		.post-navigation .nav-next a:hover,
		.post-navigation .nav-next a:focus,
		.page-numbers:hover:not(.current),
		.page-numbers:focus:not(.current),
		#secondary .widget_tag_cloud a:hover,
		#secondary .widget_tag_cloud a:focus,
		.return-to-top:hover,
		.return-to-top:focus
	';
	$primary_color_text_color = '
		a,
		.main-navigation ul.menu .sub-menu .menu-item a:hover,
		.main-navigation ul.menu .sub-menu .menu-item a:focus,
		.entry-meta a:hover,
		.entry-meta a:focus,
		.entry-title a:hover,
		.entry-title a:focus,
		.entry-footer a:hover,
		.entry-footer a:focus,
		.author-title a:hover,
		.author-title a:focus,
		.comment-meta a:hover,
		.comment-meta a:focus,
		.footer-widgets a:hover,
		.footer-widgets a:focus,
		.site-footer a:hover,
		.site-footer a:focus
	';

	$primary_color_border_color = '
		.post-edit-link:hover,
		.post-edit-link:focus,
		.masonry-container a.more-link:hover,
		.masonry-container a.more-link:focus,
		.comment-body > .reply a:hover,
		.comment-body > .reply a:focus,
		.page-numbers:hover:not(.current),
		.page-numbers:focus:not(.current),
		#secondary .widget_tag_cloud a:hover,
		#secondary .widget_tag_cloud a:focus,
		.return-to-top:hover,
		.return-to-top:focus
	';

	if ( $primary_color ) {
		$css .= sprintf( '%s{ background-color: %s }', $primary_color_background_color, esc_attr( $primary_color ) );
		$css .= sprintf( '%s{ color: %s }', $primary_color_text_color, esc_attr( $primary_color ) );
		$css .= sprintf( '%s{ border-color: %s }', $primary_color_border_color, esc_attr( $primary_color ) );
		$css .= sprintf( '::selection{background-color:%1$s}::-moz-selection{background-color:%1$s}', esc_attr( $primary_color ) );
	}

	$secondary_color = get_theme_mod( 'secondary_color', $setting['secondary_color'] );
	$secondary_color_background_color = '
		button:hover,
		button:active,
		button:focus,
		input[type="button"]:hover,
		input[type="button"]:active,
		input[type="button"]:focus,
		input[type="reset"]:hover,
		input[type="reset"]:active,
		input[type="reset"]:focus,
		input[type="submit"]:hover,
		input[type="submit"]:active,
		input[type="submit"]:focus
	';
	$secondary_color_text_color = '
		a:hover,
		a:focus
	';

	if ( $secondary_color ) {
		$css .= sprintf( '%s{ background-color: %s }', $secondary_color_background_color, esc_attr( $secondary_color ) );
		$css .= sprintf( '%s{ color: %s }', $secondary_color_text_color, esc_attr( $secondary_color ) );
	}

    $css = str_replace( array( "\n", "\t", "\r" ), '', $css );

	if ( ! empty( $css ) ) {
		wp_add_inline_style( 'pacific-style', apply_filters( 'pacific_inline_style', trim( $css ) ) );
	}

}
add_action( 'wp_enqueue_scripts', 'pacific_add_inline_style' );

/**
 * [pacific_customizer_style_placeholder description]
 * @return [type] [description]
 */
function pacific_customizer_style_placeholder(){
	if ( is_customize_preview() ) {
		echo '<style id="primary-color"></style>';
		echo '<style id="secondary-color"></style>';
	}
}
add_action( 'wp_head', 'pacific_customizer_style_placeholder', 15 );

/**
 * [pacific_editor_style description]
 * @param  [type] $mceInit [description]
 * @return [type]          [description]
 */
function pacific_editor_style( $mceInit ) {

	$primary_color 			= get_theme_mod( 'primary_color', '#ff9800' );
	$secondary_color 		= get_theme_mod( 'secondary_color', '#ef6c00' );

	$styles = '';
	$styles .= '.mce-content-body a{ color: ' . esc_attr( $primary_color ) . '; }';
	$styles .= '.mce-content-body a:hover, .mce-content-body a:focus{ color: ' . esc_attr( $secondary_color ) . '; }';
	$styles .= '.mce-content-body ::selection{ background-color: ' . esc_attr( $secondary_color ) . '; }';
	$styles .= '.mce-content-body ::-mozselection{ background-color: ' . esc_attr( $secondary_color ) . '; }';

	$styles = str_replace( array( "\n", "\t", "\r" ), '', $styles );

	if ( !isset( $mceInit['content_style'] ) ) {
		$mceInit['content_style'] = trim( $styles ) . ' ';
	} else {
		$mceInit['content_style'] .= ' ' . trim( $styles ) . ' ';
	}

	return $mceInit;

}
add_filter( 'tiny_mce_before_init', 'pacific_editor_style' );
