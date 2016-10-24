<?php
/**
 * The main template file.
 *
 * This is the most generic template file used by WP when nothing else matches (also used as a blog index)

 */

get_header(); ?>
?>
<div class="el-row inner">
	<div id="primary" class="content-area el-col-small-9">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :
			//Listing of masonry blogs
			echo '<div class="el-row animation-container inner blog-listing masonry-elements small-margin-top-bottom-medium">';
			
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content');

				//displays the comments template
				do_action('el_display_comment_template');

			endwhile;
			
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
