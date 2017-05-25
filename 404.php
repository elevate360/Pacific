<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Pacific
 */

get_header(); ?>
<div class="el-row inner">
	<div id="primary" class="content-area">
		<main id="main" class="site-main animation-container small-padding-top-bottom-medium" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'We found nothing, we\'re sorry', 'pacific' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'You\'ve reached this page and there\'s nothing here. We might have eaten your pages or maybe someone ran away with it.', 'pacific' ); ?></p>
					<p><?php esc_html_e( 'Here are a few options for you:', 'pacific' ); ?></p>
					<ul>
						<li><?php esc_html_e( 'see if you can find your content via our search form below', 'pacific' ); ?></li>
						<li><?php esc_html_e( 'Use the main navigation menu at the top to find what you\'re looking for', 'pacific' ); ?></li>
					</ul>

					<?php
						get_search_form();
					?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
