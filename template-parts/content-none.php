<?php
/*
 * 404 template
 */
?>

<section class="no-results not-found el-row">
	<header class="page-header  el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'pacific' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content  el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'pacific' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'pacific' ); ?></p>
			<?php
				get_search_form();

		else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'pacific' ); ?></p>
			<?php
				get_search_form();

		endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
