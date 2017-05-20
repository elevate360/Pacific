<?php
/**
 * Customizer elements for the theme.
 *
 */

//JS functionality for dynamically changing the live customizer
function pacific_customize_preview_js() {
	wp_enqueue_script( 'pacific_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'pacific_customize_preview_js' );

//add additional theme customizer elements
function pacific_customize_register($wp_customize){

	//update site name, description and show/hide so they are dynamic in the editor
	//$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	//$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	//$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	//if we have support for custom background
	if(current_theme_supports('custom-background')){

		//FONTS
		$google_fonts = array();
		$google_api = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyCxW8RZ-xZVyfbY-nriW_E7VimgydHa_uo';
	    $font_content = wp_remote_get( $google_api, array('sslverify'   => false) );

	    if(!empty($font_content) && !isset($font_content->error)){
			$font_content = json_decode($font_content['body']);

			foreach($font_content->items as $font){
				$google_fonts[$font->family] = $font->family;
			}
		}

		$wp_customize->add_section('pacific_fonts',
			array(
				'title'				=> __( 'Fonts', 'pacific' ),
				'description'		=> __( 'Control how the fonts display across your site', 'pacific' )
			)
		);


		$wp_customize->add_setting('pacific_body_font',
			array(
				'default'			=> 'Montserrat',
				'sanitize_callback'	=> 'esc_html'
			)
		);
		$wp_customize->add_setting('pacific_header_font',
			array(
				'default'			=> 'Montserrat',
				'sanitize_callback'	=> 'esc_html'
			)
		);
		$wp_customize->add_control('pacific_body_font',
			array(
				'label'				=> __( 'Body Font', 'pacific' ),
				'description'		=> __( 'Select the font family to use for your body text', 'pacific' ),
				'section'			=> 'pacific_fonts',
				'type'				=> 'select',
				'choices'			=> $google_fonts
			)
		);
		$wp_customize->add_control('pacific_header_font',
			array(
				'label'				=> __( 'Header Font', 'pacific' ),
				'description'		=> __( 'Select the font family to use for your H1-h6 tags', 'pacific' ),
				'section'			=> 'pacific_fonts',
				'type'				=> 'select',
				'choices'			=> $google_fonts
			)
		);






		//COLOURS
		//body color
		$wp_customize->add_setting('pacific_text_color',
			array(
				'default'			=> '#333333',
				'sanitize_callback'	=> 'sanitize_hex_color'
			)
		);
		$wp_customize->add_control(new WP_Customize_Color_Control(
			$wp_customize,
			'pacific_text_color',
			array(
				'label'				=> __( 'Text Color', 'pacific' ),
				'description'		=> __( 'Color for standard text / body content', 'pacific' ),
				'section'			=> 'colors',
				'type'				=> 'color'
				)
			)
		);

		//link color
		$wp_customize->add_setting('pacific_link_color',
			array(
				'default'			=> '#555555',
				'sanitize_callback'	=> 'sanitize_hex_color'
			)
		);
		$wp_customize->add_control(new WP_Customize_Color_Control(
			$wp_customize,
			'pacific_link_color',
			array(
				'label'				=> __( 'Link Text Color', 'pacific' ),
				'description'		=> __( 'Color used for standard links on the site', 'pacific' ),
				'section'			=> 'colors',
				'type'				=> 'color'
				)
			)
		);

		//header (h1-h6) color
		$wp_customize->add_setting('pacific_header_color',
			array(
				'default'			=> '#333333',
				'sanitize_callback'	=> 'sanitize_hex_color'
			)
		);
		$wp_customize->add_control(new WP_Customize_Color_Control(
			$wp_customize,
			'pacific_header_color',
			array(
				'label'				=> __( 'H1 - H6 Color', 'pacific' ),
				'description'		=> __( 'Color for H1-H6 tags', 'pacific' ),
				'section'			=> 'colors',
				'type'				=> 'color'
				)
			)
		);


		//Footer Color Settings
		//Footer background color
		$wp_customize->add_setting('pacific_footer_background_color',
			array(
				'default'			=> '#333333',
				'sanitize_callback'	=> 'sanitize_hex_color'
			)
		);
		$wp_customize->add_setting('pacific_footer_text_color',
			array(
				'default'			=> '#ffffff',
				'sanitize_callback'	=> 'sanitize_hex_color'
			)
		);

		$wp_customize->add_control(new WP_Customize_Color_Control(
			$wp_customize,
			'pacific_footer_background_color',
			array(
				'label'				=> __( 'Footer Background Color', 'pacific' ),
				'description'		=> __( 'Background color for the footer.', 'pacific' ),
				'section'			=> 'colors',
				'type'				=> 'color'
				)
			)
		);
		$wp_customize->add_control(new WP_Customize_Color_Control(
			$wp_customize,
			'pacific_footer_text_color',
			array(
				'label'				=> __( 'Footer Text Color', 'pacific' ),
				'description'		=> __( 'Colour used for text including headers when displayed in the footer', 'pacific' ),
				'section'			=> 'colors',
				'type'				=> 'color'
				)
			)
		);


		//Misc settings
		$wp_customize->add_setting('pacific_accent_color',
			array(
				'default'			=> '#3487BF',
				'sanitize_callback'	=> 'sanitize_hex_color'
			)
		);
		$wp_customize->add_control(new WP_Customize_Color_Control(
			$wp_customize,
			'pacific_accent_color',
			array(
				'label'				=> __( 'Theme Accent Colour', 'pacific' ),
				'description'		=> __( 'Colour used for various small element such as blockquote text, UL / OL bullets and dividers', 'pacific' ),
				'section'			=> 'colors',
				'type'				=> 'color'
				)
			)
		);

		//CUSTOM HEADER
		//Additional custom-header settings to control output
		$wp_customize->add_setting('pacific_header_background_color',
			array(
				'default'			=> '#efefef',
				'sanitize_callback' => 'sanitize_hex_color'
			)
		);
		$wp_customize->add_setting('pacific_header_text_color',
			array(
				'default'			=> '#333333',
				'sanitize_callback' => 'sanitize_hex_color'
			)
		);
		$wp_customize->add_setting('pacific_header_button_primary_text',
			array(
				'default'			=> '',
				'sanitize_callback'	=> 'esc_html'
			)
		);
		$wp_customize->add_setting('pacific_header_button_primary_url',
			array(
				'default'			=> '',
				'sanitize_callback'	=> 'esc_url'
			)
		);

		$wp_customize->add_setting('pacific_header_button_secondary_text',
			array(
				'default'			=> '',
				'sanitize_callback'	=> 'esc_html'
			)
		);
		$wp_customize->add_setting('pacific_header_button_secondary_url',
			array(
				'default'			=> '',
				'sanitize_callback'	=> 'esc_url'
			)
		);


		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'pacific_header_background_color',
			array(
				'label'				=> __( 'Header Background Color', 'pacific' ),
				'description'		=> __( 'Background color for the header, useful if you don\'t want to upload an image', 'pacific' ),
				'section'			=> 'header_image',
				'type'				=> 'color'
				)
			)
		);
		$wp_customize->add_control(new WP_Customize_Color_Control(
			$wp_customize,
			'pacific_header_text_color',
			array(
				'label'				=> __( 'Header Text Color', 'pacific' ),
				'description'		=> __( 'Colour of the main title and subtitle when displayed in the header. Also determines search / nav icon colour', 'pacific' ),
				'section'			=> 'header_image',
				'type'				=> 'color'
				)
			)
		);
		$wp_customize->add_control('pacific_header_button_primary_text',
			array(
				'label'				=> __( 'Primary CTA Text', 'pacific' ),
				'description'		=> __( 'The main call to action text button displayed in the header', 'pacific' ),
				'section'			=> 'header_image',
				'type'				=> 'text',
				'settings'			=> 'pacific_header_button_primary_text',
			)
		);
		$wp_customize->add_control('pacific_header_button_primary_url',
			array(
				'label'				=> __( 'Primary CTA URL', 'pacific' ),
				'description'		=> __( 'The URL the main call to action button will link to', 'pacific' ),
				'section'			=> 'header_image',
				'type'				=> 'url',
				'settings'			=> 'pacific_header_button_primary_url',
			)
		);
		$wp_customize->add_control('pacific_header_button_secondary_text',
			array(
				'label'				=> __( 'Secondary CTA Text', 'pacific' ),
				'description'		=> __( 'The secondary call to action text button displayed in the header', 'pacific' ),
				'section'			=> 'header_image',
				'type'				=> 'text',
				'settings'			=> 'pacific_header_button_secondary_text',
			)
		);
		$wp_customize->add_control('pacific_header_button_secondary_url',
			array(
				'label'				=> __( 'Secondary CTA URL', 'pacific' ),
				'description'		=> __( 'The URL the secondary call to action button will link to', 'pacific' ),
				'section'			=> 'header_image',
				'type'				=> 'url',
				'settings'			=> 'pacific_header_button_secondary_url',
			)
		);


	}



}

add_action('customize_register','pacific_customize_register');


//Output dynamic styles to the head based on customizer
function pacific_output_dynamic_customizer_styles(){
	//background image options
	$pacific_background_size = get_theme_mod('pacific_background_size');
	$background_color = get_theme_mod('background_color', get_theme_support('custom-background', 'default-color'));
	//universal color options
	$pacific_text_color = get_theme_mod('pacific_text_color');
	$pacific_header_color = get_theme_mod('pacific_header_color');
	$pacific_link_color = get_theme_mod('pacific_link_color', '#555');
	$pacific_accent_color = get_theme_mod('pacific_accent_color', '#3487bf');
	$pacific_body_font = get_theme_mod('pacific_body_font', 'Montserrat');
	$pacific_header_font = get_theme_mod('pacific_header_font', 'Montserrat');
	//footer color options
	$pacific_footer_background_color = get_theme_mod('pacific_footer_background_color', '#333');
	$pacific_footer_text_color = get_theme_mod('pacific_footer_text_color', '#fff');
	//custom header options
	$pacific_header_text_color = get_theme_mod('pacific_header_text_color', '#333');

	?>
	<style type="text/css" id="theme-customizier-styles">
		body{
			background-size: <?php echo $pacific_background_size; ?>;
		}

		<?php if(!empty($background_color)){ ?>
		body{
			background-color: #<?php echo $background_color; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_text_color)){ ?>
		body {
			color: <?php echo $pacific_text_color; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_body_font)){?>
		body{
			font-family: <?php echo $pacific_body_font; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_header_color)){ ?>
		h1,h2,h3,h4,h5,h6{
			color: <?php echo $pacific_header_color; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_header_font)){?>
		h1,h2,h3,h4,h5,h6,.site-title,.site-description{
			font-family: <?php echo $pacific_header_font; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_link_color)){?>
		a,
		a:active,
		a:visited{
			color: <?php echo $pacific_link_color; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_footer_background_color)){ ?>
		.site-footer{
			background-color: <?php echo $pacific_footer_background_color; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_footer_text_color)){ ?>
		.site-footer,
		.site-footer a,
		.site-footer h1,
		.site-footer h2,
		.site-footer h3,
		.site-footer h4,
		.site-footer h5,
		.site-footer h6{
			color: <?php echo $pacific_footer_text_color; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_accent_color)){ ?>
		hr{
			background-color: <?php echo $pacific_accent_color; ?>;
		}
		blockquote, q{
			border-top-color: <?php echo $pacific_accent_color; ?>;
			border-bottom-color: <?php echo $pacific_accent_color; ?>;
			color: <?php echo $pacific_accent_color; ?>;
		}
		.entry-content ul li:before,
		.entry-content ol li:before{
			background-color: <?php echo $pacific_accent_color; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_header_text_color)){?>
		.site-header .header-inner{
			color: <?php echo $pacific_header_text_color; ?>;
		}
		<?php } ?>

		<?php if(!empty($pacific_accent_color)){?>
		.nav-links .page-numbers{
			background-color: <?php echo $pacific_accent_color; ?>;
			color: #fff;
		}
		<?php } ?>


		<?php if(!empty($pacific_accent_color)){?>
		.term-list .button.active,
		.term-list .button:hover,
		.term-list .button:active{
			background-color: <?php echo $pacific_accent_color; ?>;
		}
		<?php } ?>

	</style>
	<?php

}
add_action('wp_head', 'pacific_output_dynamic_customizer_styles', 99, 1);






