<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Retail_Motion
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('el-row nested'); ?>>
	<header class="entry-header el-col-medium-8 el-col-medium-offset-2 small-align-cente">
		<?php
		//Entry meta if post
		if ( get_post_type() == 'post' ){
			
			echo '<div class="entry-meta">';
				pacific_posted_on();
				pacific_categories_and_tags();
			echo '</div>';
			
		}?>
	</header><!-- .entry-header -->

	<div class="entry-content el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-margin-top-medium medium-margin-top-bottom-large">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'retail-motion' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'retail-motion' ),
				'after'  => '</div>',
			) );
		?>
		<hr class="medium"/>
	</div><!-- .entry-content -->
	

</article><!-- #post-## -->
