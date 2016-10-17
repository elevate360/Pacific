<?php

/*
 * Category Term Archive
 */

 
$el_pacific_theme = $el_pacific_theme::getInstance();
get_header(); ?>
<div class="el-row">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :

			//term header

			//get a listing of our categories as links
			$categories = $el_pacific_theme::get_post_term_links('category');
			echo $categories;

			/* Start the Loop */
			echo '<div class="el-row inner blog-listing masonry-elements">';
			while ( have_posts() ) : the_post();

				$html = '';
				
				$html .= $el_pacific_theme::get_post_card_html($post->ID);
				
				echo $html;

			endwhile;
			echo '</div>';

			echo '<div class="el-row inner small-align-center">';
				//the_posts_navigation();
				$post_pagination_args = array(
					'mid_size'		=> 6,
					'prev_text'		=> '',
					'next_text'		=> '',
					'screen_reader_text' => 'Post Navigation'
				);
				the_posts_pagination($post_pagination_args);
			echo '</div>';
				
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
