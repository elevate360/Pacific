<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Retail_Motion
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class('debugg'); ?>>
<?php
global $template;
//echo $template;

//output our sticky side menu element
do_action('el_display_sticky_side_menu');
?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'retail-motion' ); ?></a>


	<!--Header, information pulled in on a page-by-page basis -->
	<header id="masthead" class="site-header el-row" role="banner">
		
		<?php
		$header_background_image = get_post_meta($post->ID, 'header_background_image', true);
		$header_background_color = get_post_meta($post->ID, 'header_background_color', true);
		$header_overlay_color = get_post_meta($post->ID, 'header_overlay_color', true);
		$header_overlay_opacity = get_post_meta($post->ID,'header_overlay_opacity', true);
		$header_text_color = get_post_meta($post->ID, 'header_text_color', true);
		$header_logo = get_post_meta($post->ID, 'header_logo', true);
		$header_title = get_post_meta($post->ID, 'header_title', true);
		$header_subtitle = get_post_meta($post->ID, 'header_subtitle', true);
		$header_video_url = get_post_meta($post->ID, 'header_video_url', true);
		?>
		
		<?php 
		//if we have a background-color
		if(!empty($header_background_color)){?>
		<div class="header-background-color" style="background-color: <?php echo $header_background_color; ?>;"></div>
		<?php } ?>
		
		<?php 
		//if we have a background-image set
		if(!empty($header_background_image)){
			$header_background_image_url = wp_get_attachment_image_src($header_background_image, 'large', false)[0];
			?>
			
			<div class="header-background-image background-image" style="background-image: url(<?php echo $header_background_image_url; ?>);"></div>
			
		<?php }?>
		
		<?php
		//if we have an overlay color
		if(!empty($header_overlay_color)){
			
			//display based on opacity
			if($header_overlay_opacity){?>
				<div class="header-overlay-color" style="background-color: <?php echo $header_overlay_color; ?>; opacity: <?php echo $header_overlay_opacity; ?>;"></div>
			<?php }else{?>
				<div class="header-overlay-color" style="background-color: <?php echo $header_overlay_color; ?>"></div>
			<?php } ?>
			
		<?php } ?>
		
		
		<?php
		$style = '';
		if(!empty($header_text_color)){
			$style = 'color: ' . $header_text_color . ';';
		}
		?>
		<div class="header-inner el-row inner small-padding-top-bottom-small medium-padding-top-bottom-medium medium-padding-bottom-large " style="<?php echo $style; ?>">
			<div class="logo-wrap el-col-small-6">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img class="logo" src="<?php echo get_stylesheet_directory_uri() . '/img/retail_motion_logo_white.png'; ?>" alt="Retail Motion"/>
				</a>
			</div>
			<!--menu + search-->
			<div class="action-wrap el-col-small-6 small-align-right">
				<div class="menu-toggle inline-block small-margin-right-small" data-menu-id="primary-menu">
					<i class="toggle-main-menu icon fa fa-bars fa-2x" aria-hidden="true"></i>
				</div>
				<div class="search-toggle inline-block">
					<i class="toggle-search icon fa fa-search fa-2x" aria-hidden="false"></i>
				</div>
			</div>
			<!--Main content block-->
			<div class="content clear el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center medium-margin-top-large medium-margin-bottom-large">
				<?php if(!empty($header_title)){?>
					<h1 class="title uppercase big fat "><?php echo $header_title; ?></h1>
				<?php } ?>
				<?php if(!empty($header_logo)){
					$post_attachment = get_post($header_logo);
					$image_url = '';
					if($post_attachment->post_mime_type == 'image/svg+xml'){
						$image_url = $post_attachment->guid;
					}else{
						$image_url = wp_get_attachment_image_src($header_logo, 'medium', false)[0];
					}
					?>
					<img class="header-logo small-margin-top-botom-large" src="<?php echo $image_url; ?>"/>
					
				<?php } ?>
				
				<?php
				//Video functionality
				if(!empty($header_video_url)){?>
					<div class="video-button"><i class="icon fa fa-play-circle" aria-hidden="true"></i></div>
				<?php } ?>
				
				
				<?php if(!empty($header_subtitle)){?>
					<h2 class="subtitle small-margin-top-bottom-large small-margin-top-x-large fat"><?php echo $header_subtitle; ?></h2>
				<?php } ?>
				
				
				
			</div>
			<div class="fold-arrow align-center">
				<i class="fold-icon fa fa-angle-down" aria-hidden="true"></i>
			</div>
		</div>
		
		<!--Nav + Social media-->
		<nav id="site-navigation" class="vertical-nav nav-menu" role="navigation">
			<div class="background-overlay"></div>
			<div class="el-row relative inner small-padding-top-bottom-large ">
				<div class="toggle-main-menu el-col-small-12 small-align-right small-margin-bottom-small"><i class="fa fa-times" aria-hidden="true"></i></div>
				<div class="menu-inner el-col-small-12 medium-margin-top-large">
					<?php
					$menu_args = array(
						'theme_location' 	=> 'primary', 
						'menu_id' 			=> 'primary-menu', 
						'container' 		=> false, 
						'link_before'			=> '<div class="link-text">',
						'link_after' 		=> '</div><div class="submenu-toggle"><i class="fa fa-angle-down" aria-hidden="true"></i></div>'
					);
					
					?>
					<?php wp_nav_menu( $menu_args ); ?>
				</div>
				<!--social media-->
				<?php
				$el_universal = el_universal::getInstance();
				$social_media =  $el_universal::el_display_social_media_icons();
				
				echo $social_media;
				?>
				
			</div>
		</nav><!-- #site-navigation -->
		<!--Search Popup -->
		<div class="site-search">
			<div class="background-overlay"></div>
			<div class="el-row inner small-margin small-padding-top-bottom-large">
				
				<div class="toggle-search el-col-small-12 small-align-right small-margin-bottom-small"><i class="fa fa-times" aria-hidden="true"></i></div>
				<div class="el-col-small-12">
					<?php get_search_form(); ?>
				</div>
				
			</div>
			
		</div>
		<!-- Video Popup-->
		<?php if(!empty($header_video_url)){ ?>
		<div class="video-popup">
			<div class="background-overlay"></div>
			
			<div class="el-row inner small-margin small-padding-top-bottom-large">
				<div class="popup-inner">
					<div class="toggle-video-popup el-col-small-12 small-align-right small-margin-bottom-small">
						<i class="icon fa fa-times" aria-hidden="true"></i>
					</div>
					<div class="video-container el-col-small-12">
						<div>
							<iframe src="<?php echo $header_video_url; ?>"></iframe>
						</div>
						
					</div>	
				</div>			
			</div>
		</div>
		<?php } ?>
			
	</header><!-- #masthead -->

	<div id="content" class="site-content">