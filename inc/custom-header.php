<?php
//Check for custom header element

if ( has_header_image() ) {?>
	<a href="<?php echo esc_url( esc_url(home_url()) ); ?>" rel="home">
		<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="">
	</a>
<?php } ?>