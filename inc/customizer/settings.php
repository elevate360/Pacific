<?php
/**
 * Setting general
 *
 * @package Pacific
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function pacific_customize_register( $wp_customize ) {

	$setting = pacific_setting_default();

	// Arctic Theme Setting Panel
	$wp_customize->add_panel( 'theme_settings', array(
		'title' 		=> __( 'Theme Settings', 'pacific' ),
		'priority' 		=> 199,
	) );

	$wp_customize->get_setting( 'blogname' )->transport         		= 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  		= 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport 		= 'postMessage';
	$wp_customize->get_setting( 'header_image' )->transport 			= 'postMessage';
	$wp_customize->get_setting( 'header_image_data'  )->transport 		= 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport 		= 'postMessage';
	$wp_customize->get_setting( 'background_image' )->transport 		= 'postMessage';
	$wp_customize->get_setting( 'background_repeat' )->transport 		= 'postMessage';
	$wp_customize->get_setting( 'background_position_x' )->transport 	= 'postMessage';
	$wp_customize->get_setting( 'background_attachment' )->transport 	= 'postMessage';

	/** WP */
	$wp_customize->get_section( 'header_image' )->panel 				= 'theme_settings';
	$wp_customize->get_section( 'background_image' )->panel 			= 'theme_settings';
	$wp_customize->get_section( 'colors' )->panel 						= 'theme_settings';

	/** Theme Colors */
	$wp_customize->add_setting(
		'primary_color',
		array(
			'default'           => $setting['primary_color'],
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
		'primary_color',
		array(
			'label'       	=> __( 'Link Color', 'pacific' ),
			'description'	=> __( 'Used for link, button, selection.', 'pacific' ),
			'section'     	=> 'colors',
			'setting'		=> 'primary_color',
			'priority'		=> 99
	) ) );

	$wp_customize->add_setting(
		'secondary_color',
		array(
			'default'           => $setting['secondary_color'],
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
		'secondary_color',
		array(
			'label'       	=> __( 'Hover Color', 'pacific' ),
			'description'	=> __( 'Used for link:hover, button:hover.', 'pacific' ),
			'section'     	=> 'colors',
			'setting'		=> 'secondary_color',
			'priority'		=> 99
	) ) );

	// Archive Setting
	$wp_customize->add_section(
		'blog_section' ,
		array(
			'title' 			=> __( 'Blog Setting', 'pacific' ),
			'priority' 			=> 200,
			'panel'				=> 'theme_settings'
	) );

	$wp_customize->add_setting(
		'post_date' ,
		 array(
		    'default' 			=> $setting['post_date'],
		    'transport'			=> 'postMessage',
		    'sanitize_callback' => 'pacific_sanitize_checkbox',
	) );

	$wp_customize->add_control(
		'post_date',
		array(
			'label'    => __( 'Display Post Date', 'pacific' ),
			'section'  => 'blog_section',
			'settings' => 'post_date',
			'type'     => 'checkbox'
		)
	);

	$wp_customize->add_setting(
		'post_author' ,
		 array(
		    'default' 			=> $setting['post_author'],
		    'transport'			=> 'postMessage',
		    'sanitize_callback' => 'pacific_sanitize_checkbox',
	) );

	$wp_customize->add_control(
		'post_author',
		array(
			'label'    => __( 'Display Post Author', 'pacific' ),
			'section'  => 'blog_section',
			'settings' => 'post_author',
			'type'     => 'checkbox'
		)
	);

	$wp_customize->add_setting(
		'post_cat' ,
		 array(
		    'default' 			=> $setting['post_cat'],
		    'transport'			=> 'postMessage',
		    'sanitize_callback' => 'pacific_sanitize_checkbox',
	) );

	$wp_customize->add_control(
		'post_cat',
		array(
			'label'    => __( 'Display Post Category', 'pacific' ),
			'section'  => 'blog_section',
			'settings' => 'post_cat',
			'type'     => 'checkbox'
		)
	);

	$wp_customize->add_setting(
		'post_tag' ,
		 array(
		    'default' 			=> $setting['post_tag'],
		    'transport'			=> 'postMessage',
		    'sanitize_callback' => 'pacific_sanitize_checkbox',
	) );

	$wp_customize->add_control(
		'post_tag',
		array(
			'label'    => __( 'Display Post Tag', 'pacific' ),
			'section'  => 'blog_section',
			'settings' => 'post_tag',
			'type'     => 'checkbox'
		)
	);

	$wp_customize->add_setting(
		'post_comments' ,
		 array(
		    'default' 			=> $setting['post_comments'],
		    'transport'			=> 'postMessage',
		    'sanitize_callback' => 'pacific_sanitize_checkbox',
	) );

	$wp_customize->add_control(
		'post_comments',
		array(
			'label'    => __( 'Display comments count', 'pacific' ),
			'section'  => 'blog_section',
			'settings' => 'post_comments',
			'type'     => 'checkbox'
		)
	);

	$wp_customize->add_setting(
		'author_display' ,
		 array(
		    'default' 			=> $setting['author_display'],
		    'transport'			=> 'postMessage',
		    'sanitize_callback' => 'pacific_sanitize_checkbox',
	) );

	$wp_customize->add_control(
		'author_display',
		array(
			'label'    => __( 'Display Author biography at single post', 'pacific' ),
			'section'  => 'blog_section',
			'settings' => 'author_display',
			'type'     => 'checkbox'
		)
	);

	$wp_customize->add_setting(
		'excerpt_length' ,
		 array(
		    'default' 			=> $setting['excerpt_length'],
		    'sanitize_callback' => 'pacific_sanitize_number_absint',
	) );

	$wp_customize->add_control(
		'excerpt_length',
		array(
			'label'    => __( 'Excerpt length', 'pacific' ),
			'section'  => 'blog_section',
			'settings' => 'excerpt_length',
			'type'     => 'number',
		    'input_attrs' => array(
		        'min'   => 1,
		        'max'   => 9999,
		    )
		)
	);

	$wp_customize->add_setting(
		'posts_navigation',
		array(
			'default'           => $setting['posts_navigation'],
			'sanitize_callback' => 'pacific_sanitize_select',
			'transport'         => 'postMessage',
	) );

	$wp_customize->add_control(
		'posts_navigation',
		array(
			'label'    => __( 'Posts Navigation', 'pacific' ),
			'section'  => 'blog_section',
			'setting'  => 'posts_navigation',
			'type'     => 'select',
			'choices'  => array(
				'posts_navigation' 	=> esc_attr__( 'Prev / Next', 'pacific' ),
				'posts_pagination' 	=> esc_attr__( 'Numeric', 'pacific' ),
			),
	) );

	// Footer Widgets
	$wp_customize->add_section(
		'footer_area' ,
		array(
			'title' 			=> __( 'Footer', 'pacific' ),
			'priority' 			=> 200,
			'panel'				=> 'theme_settings'
	) );

	$wp_customize->add_setting(
		'footer_copyright' ,
		 array(
		    'default' 			=> '',
		    'transport'			=> 'postMessage',
		    'sanitize_callback' => 'wp_kses_post',
	) );

	$wp_customize->add_control(
		'footer_copyright',
		array(
			'label'    		=> __( 'Footer copyright', 'pacific' ),
			'description'	=> __( 'Use [YEAR] for dynamic current year. Use [SITE] to render site link.', 'pacific' ),
			'section'  		=> 'footer_area',
			'settings' 		=> 'footer_copyright',
			'type'     		=> 'textarea'
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {

		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'pacific_customize_partial_blogname',
		) );

		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'pacific_customize_partial_blogdescription',
		) );

		$wp_customize->selective_refresh->add_partial(
			'posts_navigation',
			array(
				'selector' 				=> array( '.navigation.posts-navigation', '.navigation.pagination' ),
				'settings' 				=> array( 'posts_navigation' ),
				'render_callback' 		=> 'pacific_posts_navigation',
				'container_inclusive'	=> true,
		) );

		$wp_customize->selective_refresh->add_partial(
			'footer_copyright',
			array(
				'selector' 				=> '.site-info',
				'settings' 				=> array( 'footer_copyright' ),
				'render_callback' 		=> 'pacific_get_footer_copyright',
				'container_inclusive'	=> false,
		) );

	}

}
add_action( 'customize_register', 'pacific_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function pacific_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function pacific_customize_partial_blogdescription() {
	bloginfo( 'description' );
}
