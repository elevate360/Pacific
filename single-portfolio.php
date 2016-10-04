<?php
/**
 * Template for displaying a single portfolio item. Based on a multi layout design
 */

get_header(); ?>
<?php



?>
<div class="el-row inner">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			echo '<div class="el-col-small-12 small-align-center">';
				echo '<h1 class="entry-title fat big">' . get_the_title() . '</h1>';
			echo '</div>';

			do_action('el_display_portfolio_brief', $post);

			do_action('el_display_portfolio_insight', $post);
			
			do_action('el_display_portfolio_solution', $post);
		
			do_action('el_display_post_call_to_action', $post);
			
			do_action('el_display_portfolio_gallery_slider', $post);
		
			do_action('el_display_portfolio_testimonial_slider', $post);
			
			do_action('el_display_portfolio_tiles');

			//display post navigation 
			do_action('el_display_post_navigation');
			

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
