<?php
/**
 * Pacific Theme Customizer
 *
 * @package Pacific
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function pacific_customize_preview_js() {
	wp_enqueue_script( 'pacific_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview', 'customize-selective-refresh' ), '20151215', true );
}
add_action( 'customize_preview_init', 'pacific_customize_preview_js' );

/**
 * [pacific_setting_default description]
 * @return [type] [description]
 */
function pacific_setting_default(){
	$settings = array(
		'primary_color'		=> '#ff9800',
		'secondary_color'	=> '#ef6c00',
		'post_date'			=> true,
		'post_author'		=> true,
		'post_cat'			=> true,
		'post_tag'			=> true,
		'post_comments'		=> true,
		'author_display'	=> true,
		'excerpt_length'	=> 20,
		'posts_navigation'	=> 'posts_navigation',
	);

	return apply_filters( 'pacific_setting_default', $settings );
}

/**
 * Load Customizer Setting.
 */
require get_template_directory() . '/inc/customizer/sanitization-callbacks.php';
require get_template_directory() . '/inc/customizer/settings.php';
require get_template_directory() . '/inc/customizer/output.php';
