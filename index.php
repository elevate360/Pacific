<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Retail_Motion
 */

 
$el_pacific_theme = el_pacific_theme::getInstance();
get_header(); 

//get a listing of our categories as links
$categories = $el_pacific_theme::get_post_term_links();
echo $categories;
?>
<div class="el-row inner">
	<div id="primary" class="content-area el-col-small-9">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :
			//Listing of masonry blogs
			echo '<div class="el-row animation-container inner blog-listing masonry-elements small-margin-top-bottom-medium">';
			while ( have_posts() ) : the_post();

				$html = '';
				
				$html .= $el_pacific_theme::get_post_card_html($post->ID);
				
				echo $html;

			endwhile;
			echo '</div>';

			echo '<div class="el-row inner small-align-center small-margin-bottom-small">';
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
	<?php get_sidebar() ?>
</div>
<?php
get_footer();
