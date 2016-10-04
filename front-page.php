<?php
/*
 * Front page
 * Displayed on the front page (homepage)
 */

get_header(); ?>
<div class="el-row inner">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
		<?php
		if ( have_posts() ) :
			
			//display main content
			get_template_part( 'template-parts/content', 'home');
			
			$args = array(
				'title'		=> 'Key Areas',
				'subtitle'	=> 'What we do at RetailMotion'
			);
			do_action('el_display_service_categories_and_services', $args);
			
			//display all related call to action elements
			do_action('el_display_post_call_to_action', $post);
				
			//display a listing a grid of client cards
			do_action('el_display_client_card_listing');
				
			//display the top 6 upcoming portfolios
			$portfolio_args = array(
				'display_type' 	=> 'card',
				'title'			=> 'Our Work',
				'subtitle'		=> 'Our latest and greatest projects'
			);
			
			do_action('el_display_portfolio_tiles', $portfolio_args, 10, 1);
				
			//display testimonial slider
			do_action('el_display_recent_testimonial_slider');
			
			//display featured posts
			do_action('el_display_featured_posts');


		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	
</div>
<?php

get_footer();
