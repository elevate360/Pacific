<?php
/*
 * Page Content Template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('el-row'); ?>>

	<div class="entry-content el-col-small-12 el-col-medium-8 el-col-medium-offset-2">
		<?php
			the_content();
			
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'pacific' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-margin-bottom-small">
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'pacific' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>',
					'',
					'button black small'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->
