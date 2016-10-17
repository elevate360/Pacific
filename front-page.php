<?php
/*
 * Front page
 * Displayed on the front page (homepage)
 */

get_header(); ?>
<div class="el-row inner">
	<div id="primary" class="content-area">
		<main id="main" class="site-main small-padding-top-bottom-medium" role="main">
		<?php
		if ( have_posts() ) : the_post();
			
			//display main content
			get_template_part( 'template-parts/content', 'page');
			
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	
</div>
<?php

get_footer();
