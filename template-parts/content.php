<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Pacific
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('el-row nested'); ?>>
	<div class="entry-content el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-margin-top-medium medium-margin-top-bottom-large">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'pacific' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'pacific' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php
	//display widget zone if required
	if(is_active_sidebar( 'widget-post-bottom-sidebar' )){?>
		<div class="el-col-small-12 el-col-medium-8 el-col-medium-offset-2 widget-area small-margin-top-bottom-medium small-margin-top-small">
			<?php dynamic_sidebar( 'widget-post-bottom-sidebar' ); ?>
		</div>
	<?php }?>

	<footer class="entry-header el-col-medium-8 el-col-medium-offset-2 small-align-center">
		<?php
		//Entry meta if post
		if ( get_post_type() == 'post' ){

			echo '<div class="entry-meta">'; ?>
				<hr class="small"/>
				<?php
				pacific_posted_on();
				pacific_categories_and_tags();?>

				<?php
			echo '</div>';

		}?>
	</footer><!-- .entry-header -->


</article><!-- #post-## -->
