<?php

/*
 * Category Term Archive
 */


$pacific_init = Pacific_Init::getInstance();
get_header();
//get a listing of our categories as links
$categories = $pacific_init::get_post_term_links('category');
echo $categories;
?>
<div class="el-row inner">
	<div id="primary" class="content-area el-col-medium-9">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :

			/* Start the Loop */
			echo '<div class="el-row inner blog-listing masonry-elements small-margin-top-bottom-medium">';
			while ( have_posts() ) : the_post();

				$html = '';

				$html .= $pacific_init::get_post_card_html($post->ID);

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
	<?php get_sidebar(); ?>
</div>
<?php
get_footer();
