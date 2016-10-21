<?php
/*
 * Header Hero
 * Displays the main header above each page, post, element
 * - Displays selected featured image, falls back to header image if set
 */
 
 $object = get_queried_object();
?>
<header id="masthead" class="site-header el-row" role="banner">

	<?php 
	//Output the header image 
	$header_image = '';
	if(current_theme_supports('custom-header')){
		
		//header background color (can either be set or removed for transparency)
		$header_background_color = get_theme_mod('pacific_header_background_color');
		if($header_background_color){
			echo '<div class="header-background-color" style="background-color:' . $header_background_color . '"></div>';
		}
			
		//custom header image
		if(get_custom_header()){
			$header = get_custom_header();
			if($header->url){
				$header_image = '<div class="header-background-image background-image" style="background-image: url(' . $header->url . ');"></div>';
			}
		}
	}

	//check for featured image (overrides default header)
	if(has_post_thumbnail($object)){
		
		$post_thumbnail_id = get_post_thumbnail_id($object->ID);
		$image_url = wp_get_attachment_image_src($post_thumbnail_id, 'large', false)[0];
		$header_image = '<div class="header-background-image background-image" style="background-image: url(' . $image_url . ');"></div>';
	}
	echo $header_image;
	
	?>

	<div class="header-inner el-row inner small-padding-top-bottom-small medium-padding-top-bottom-medium medium-padding-bottom-large">
		<!-- Logo & Site title-->
		<div class="site-info  el-col-small-6">
			<?php
			//display custom logo if supported & set
			if(function_exists('the_custom_logo')){
				$logo_id = get_theme_mod( 'custom_logo' );
				if($logo_id){
					the_custom_logo();
				}
			}
			//Display sitetitle and description if required
			$show_title_description = get_theme_mod('header_text');
			if($show_title_description){
				$site_name = get_bloginfo('name');
				$site_description = get_bloginfo('description');
				
				if(!empty($site_name) || !empty($site_description)){
					echo '<div class="title-wrap">';
					if(!empty($site_name)){
						echo '<p class="site-title">' . $site_name . '</p>';
					}
					if(!empty($site_description)){
						echo '<p class="site-description">' . $site_description . '</p>';
					}
					echo '</div>';	
				}			
			}?>
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
			
		
			<?php 
			//post, page types
			if(is_a($object, 'WP_Post')){
				$title = apply_filters('the_title', $object->post_title);
				$subtitle = apply_filters('the_excerpt', $object->post_excerpt);
			}
			//terms 
			else if(is_a($object, 'WP_Term')){
				
				$term_prefix = '';
				if($object->taxonomy === 'category'){
					$term_prefix = 'Categorised Under: ';
				}if($object->taxonomy === 'post_tag'){
					$term_prefix = 'Tagged As: ';
				}
				
				$title = $term_prefix . $object->name; 
				$subtitle = $object->description;
			}
			//on date archive e.g month / year
			else if(is_date()){
				$title = single_month_title('', false);
			}
			
			?>
			<?php if(!empty($title) || !empty($subtitle)){
			
				if(!empty($title)){
					?>
					<h1 class="title uppercase big fat"><?php echo $title; ?></h1>
				<?php }
				if(!empty($subtitle)){?>
					<h3 class="subtitle"><?php echo $subtitle; ?></h3>
				<?php } 
			}?>
			
			<?php
			//Display CTA elements if we're on the homepage
			if(is_front_page()){
				$pacific_header_button_primary_text = get_theme_mod('pacific_header_button_primary_text');
				$pacific_header_button_primary_url = get_theme_mod('pacific_header_button_primary_url');
				$pacific_header_button_secondary_text = get_theme_mod('pacific_header_button_secondary_text');
				$pacific_header_button_secondary_url = get_theme_mod('pacific_header_button_secondary_url');
					
				if(!empty($pacific_header_button_primary_text) || !empty($pacific_header_button_secondary_text)){?>
					<div class="cta-buttons">
					<?php if(!empty($pacific_header_button_primary_text)){?>
						<a class="button big white small-margin-left-small small-margin-right-small small-margin-bottom-small" href="<?php echo $pacific_header_button_primary_url; ?>" title="<?php echo $pacific_header_button_primary_text; ?>"><?php echo $pacific_header_button_primary_text; ?></a>
					<?php } ?>
					<?php if(!empty($pacific_header_button_secondary_text)){ ?>
						<a class="button big white small-margin-left-small small-margin-right-small small-margin-bottom-small" href="<?php echo $pacific_header_button_secondary_url; ?>" title="<?php echo $pacific_header_button_secondary_text; ?>"><?php echo $pacific_header_button_secondary_text; ?></a>
					<?php }?>
					</div>
				<?php }	
			}?>
			
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
			//TODO: Removed for now, add future support soon
			//$el_pacific_theme = el_pacific_theme::getInstance();
			////$social_media =  $el_pacific_theme::el_display_social_media_icons();
			
			//echo $social_media;
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
</header><!-- #masthead -->