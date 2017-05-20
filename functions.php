<?php
/*
 * Theme
 * Primary starting class, contains functionality central to the core of the theme
 */

 class Pacific_Init{

 	private static $instance = null;

	private $content_width = 720;

 	public function __construct(){

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_and_styles') );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts_and_styles') );
		add_action( 'widgets_init', array( $this, 'register_widget_areas') );
		add_action( 'after_setup_theme', array( $this, 'set_theme_content_width') );
		add_action( 'el_display_featured_posts', array( $this, 'display_featured_posts') );  //display featured blogs
		add_action( 'el_display_blog_listing', array( $this,'display_post_listings') ); //display a listing of all blogs

		add_action( 'after_setup_theme', array( $this, 'load_theme_textdomain') ); //load textdomains
		add_action( 'after_setup_theme', array( $this, 'add_theme_support') ); //register theme support
		add_action( 'after_setup_theme', array( $this, 'register_navigation_menus') ); //register navigation menu locations

		add_action( 'el_display_post_navigation', array( $this, 'display_post_navigation') );
		add_action( 'el_display_comment_template', array( $this, 'el_display_comment_template') );

		//universal shortcodes
		//add_action('init', array($this, 'register_shortcodes'),10, 1);


 	}

	//hook to display featured posts
	public function display_featured_posts(){

		$html = '';

		$instance = self::getInstance();

		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'orderby'			=> 'post_title',
			'meta_query'		=> array(
				array(
					'key'				=> 'post_is_featured',
					'value'				=> 'yes',
					'compare'			=> '='
				)
			)
		);

		$posts = get_posts($args);


		if($posts){
			$html .= '<div class="el-row inner blog-listing equal-height-items featured small-margin-top-bottom-medium">';
				$html .= '<div class="el-col-small-12 small-margin-top-large small-align-center">';
					$html .= '<h1 class="big fat">Featured Posts</h1>';
				$html .= '</div>';
				foreach($posts as $post){
					$html .= $instance::get_post_card_html($post->ID, 'featured');

				}
			$html .= '</div>';
		}

		echo $html;
	}

	//gets post terms (categories or tags) for easy display
	public static function get_post_term_links($term_name = 'category'){

		$instance = self::getInstance();

		$html = '';

		$term_args = array(
			'hide_empty'	=> false,
			'taxonomy'		=> $term_name,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
		);

		$terms = get_terms($term_args);

		if($terms){
			$html .= '<div class="term-list el-row nested">';
				$html .= '<div class="el-row inner">';

					$html .= '<div class="terms el-col-small-12 small-padding-top-small">';
						$queried_object = get_queried_object();

						//display all link (optionally highlighting it)
						$class = '';
						if( is_home()){
							$class = 'active';
						}
						$post_index_page = get_permalink( get_option( 'page_for_posts' ) );
						$html .= '<div class="term inline-block small-margin-bottom-small small-margin-right-small">';
							$html .= '<a class="button no-border ' . $class .'" href="' . $post_index_page . '" title="All">'. __( 'All', 'pacific' ) .'</a>';
						$html .= '</div>';


						foreach($terms as $term){
							$term_id = $term->term_id;
							$term_name = $term->name;
							$term_permalink = get_term_link($term);
							$term_count = $term->count;

							//highlight the current term if possible
							$class = '';
							if( ( is_a($queried_object,'WP_Term')) && ($term_id == $queried_object->term_taxonomy_id) ){
								$class = 'active';
							}

							$html .= '<div class="term inline-block small-margin-bottom-small small-margin-right-small">';
								$html .= '<a class="button no-border ' . $class .'" href="' . $term_permalink . '" title="' . $term_name . '">' . $term_name . '</a>';
							$html .= '</div>';

						}
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}


	//action hook, displays a listing of posts using the blgo formatting (masonry)
	public static function display_post_listings(){

		$instance = self::getInstance();
		$html = '';

		$post_args = array(
			'post_type'		=> 'post',
			'post_status'	=> 'publish',
			'orderby'		=> 'date',
			'order'			=> 'DESC',
			'posts_per_page'=> 10
		);
		$posts = get_posts($post_args);

		if($posts){
			$html .= '<div class="el-row inner blog-listing small-margin-top-bottom-large">';

				//blog listing
				$html .= '<div class="masonry-elements">';
				foreach($posts as $post){
					$html .= $instance::get_post_card_html($post->ID, 'blog');
				}
				$html .= '</div>';
			$html .= '</div>';
		}

		echo $html;
	}

	//gets the HTML for a single post card, using different design layouts
	public static function get_post_card_html($post_id, $style = 'blog'){

		$instance = self::getInstance();

		$html = '';

		$post = get_post($post_id);

		$post_title = apply_filters('post_title', $post->post_title);
		$post_excerpt = $post->post_excerpt;
		$post_permalink = get_permalink($post_id);
		$post_author_id = $post->post_author;
		$post_author = get_user_by('id', $post_author_id);
		$post_author_name = $post_author->display_name;
		$post_author_permalink = get_author_posts_url($post_author_id);
		$post_author_gravatar_url = get_avatar_url($post_author_id, array('default' => 'gravatar_default', 'size' => 48));

		$post_background_image = has_post_thumbnail($post_id) ? wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'medium', false)[0] : '';
		$post_accent_color = get_theme_mod('pacific_accent_color', '#3487bf');

		$post_date_raw = $post->post_date;
		$post_date = new DateTime($post_date_raw);


		//standard blog design (masonry)
		if($style == 'blog'){

			$html .= '<article class="masonry-item el-col-small-12 el-col-medium-6 small-margin-bottom-medium">';
				$html .= '<div class="blog">';

					//display background image / solid color
					if(!empty($post_background_image)){
						$html .= '<div class="background-image" style="background-image: url(' . $post_background_image . ');"></div>';
					}else{
						$html .= '<div class="background-color" style="background-color: ' . $post_accent_color . '" ></div>';
					}

					$html .= '<div class="post-info">';
						$html .= '<h3 class="post-date small">' . $post_date->format("dS M Y") . '</h3>';
						$html .= '<h1 class="title"><a href="' . $post_permalink  . '">' . $post_title . '</a></h1>';
						if(!empty($post_excerpt)){
							$html .= '<div class="excerpt small-margin-bottom-small">' . $post_excerpt . '</div>';
						}
						$html .= '<div class="author-info">';
							$html .= '<img class="author-image" src="' . $post_author_gravatar_url . '" alt="' . $post_author_name . '"/>';
							$html .= '<h3 class="author-name small">' . $post_author_name .'</h3>';
						$html .= '</div>';

						$html .= '<a class="button white readmore small-margin-top-small" href="' . $post_permalink . '">Read More</a>';

					$html .= '</div>';
				$html .= '</div>';
			$html .= '</article>';


		}
		//featured style card
		else if($style == 'featured'){
			$html .= '<article class="blog-wrap equal-height-item el-col-small-12 el-col-medium-4 small-margin-bottom-medium">';
				$html .= '<div class="blog">';


					//display background image / solid color
					$html .= '<a href="' . $post_permalink .'" title="' . $post_title . '">';
						if(!empty($post_background_image)){

							//$html .= '<div class="image-wrap ">';
								$html .= '<div class="background-wrap small-aspect-1-2 medium-aspect-3-4">';
									$html .= '<div class="background-image" style="background-image: url(' . $post_background_image . ');">';
								$html .= '</div>';
							//$html .= '</div>';

							//overlay color (if set)
							if(!empty($post_overlay_color)){
								$html .= '<div class="post-overlay" style="background-color: ' . $post_overlay_color . ';"></div>';
							}
							$html .= '</div>';
						}else{
							$html .= '<div class="background-color" style="background-color: ' . $post_background_color . '" ></div>';
						}
					$html .= '</a>';




					$html .= '<div class="post-info" style="">';
						$html .= '<h1 class="title small">';
							$html .= '<a href="' . $post_permalink .'" title="' . $post_title . '">' . $post_title . '</a>';
						$html .= '</h1>';

						$html .= '<h3 class="post-date small">' . $post_date->format("dS M Y") . '</h3>';
						$html .= '<div class="divider"></div>';
						if(!empty($post_excerpt)){
							$html .= '<div class="excerpt small-margin-top-bottom-small">' . $post_excerpt . '</div>';
						}

						$html .= '<a class="button gray readmore small small-margin-top-small" href="' . $post_permalink . '">Read More</a>';

					$html .= '</div>';
				$html .= '</div>';
			$html .= '</article>';

		}

		return $html;
	}


	//registers universal shortcodes for use
	/*
	 * TODO: Come back to this later and extract to a plugin, themecheck rules complain about it being in the theme
	public function register_shortcodes(){
		add_shortcode('el_row', array($this, 'render_shortcodes'));
		add_shortcode('el_col', array($this, 'render_shortcodes'));
	}

	//output for the shortcodes
	public function render_shortcodes($atts, $content = '', $tag){

		$html = '';

		//Row shortcode - [el_row][/el_row]
		if($tag == 'el_row'){
			$html .= '<div class="el-row">';
				$html .= do_shortcode(trim($content));
			$html .= '</div>';
		}

		//Column shortcode - [el_col small="12" medium="6" large="4"][/el_col]
		else if($tag == 'el_col'){
			//column arguments
			$args = shortcode_atts(array(
				'small'	 => '12',
				'medium' => '',
				'large'	 => ''
			), $atts, $tag);

			//build output
			$classes = 'el-col-small-' . $args['small'];
			$classes .= (!empty($args['medium'])) ? ' el-col-medium-' . $args['medium'] : '';
			$classes .= (!empty($args['large'])) ? ' el-col-large-' . $args['large'] : '';

			$html .= '<div class="' . $classes .'">';
				$html .= do_shortcode(trim($content));
			$html .= '</div>';
		}

		return $html;
	}*/

	//displays the comments template on single items, wrapped in the grid style
	public static function el_display_comment_template(){
		//display post navigation
		echo '<div class="el-row animation-container">';
			echo '<div class="el-col-small-12 el-col-medium-8 el-col-medium-offset-2">';
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			echo '</div>';
		echo '</div>';
	}

	//displays social media icons, simple
	public static function el_display_social_media_icons(){
		$instance = self::getInstance();
		$html = '';

		$html .= $instance::get_social_media_icons();

		echo $html;
	}

	//generates markup for social media icons
	public static function get_social_media_icons(){
		$instance = self::getInstance();
		$html = '';

		$html .= '<div class="el-row social-media">';
			$html .= '<div class="el-col-small-12">';
				$html .= '<div class="inline-block small-margin-right-small"><i class="fa fa-facebook" aria-hidden="true"></i></div>';
				$html .= '<div class="inline-block small-margin-right-small"><i class="fa fa-instagram" aria-hidden="true"></i></div>';
				$html .= '<div class="inline-block small-margin-right-small"><i class="fa fa-twitter" aria-hidden="true"></i></div>';
			$html .= '</div>';


		$html .= '</div>';

		echo $html;
	}

	//registeres widget areas for the theme
	public function register_widget_areas(){
		//Page with sidebar template
		register_sidebar( array(
			'name'          => esc_html__( 'Standard Page Sidebar', 'pacific' ),
			'id'            => 'widget-page-sidebar',
			'description'   => esc_html__( 'Displayed when selecting the \'Page with Sidebar\' page template' , 'pacific' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title small-margin-top-none small-margin-bottom-small">',
			'after_title'   => '</h3><hr class="orange small"/>',
		) );
		//Footer 1 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 1', 'pacific' ),
			'id'            => 'widget-footer-1',
			'description'   => esc_html__( 'First widget zone displayed in the footer' , 'pacific' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );
		//Footer 2 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 2', 'pacific' ),
			'id'            => 'widget-footer-2',
			'description'   => esc_html__( 'Second widget zone displayed in the footer' , 'pacific' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );

		//Footer 3 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 3', 'pacific' ),
			'id'            => 'widget-footer-3',
			'description'   => esc_html__( 'Third widget zone displayed in the footer' , 'pacific' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );

		//Bottom of post widget area
		register_sidebar(array(
			'name'          => esc_html__( 'Post Bottom Widget Area', 'pacific' ),
			'id'            => 'widget-post-bottom-sidebar',
			'description'   => esc_html__( 'Widget zone that is displayed at the bottom of the post (before comments if enabled). Displayed as a single column' , 'pacific' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title small-margin-top-none">',
			'after_title'   => '</h3>'
		));

	}

	//Set content width
	function set_theme_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'pacific_content_width', 640 );
	}

	//displays the next / prev post navigation on single posts
	public function display_post_navigation(){
		$html = '';

		$html .= '<div class="el-row animation-container">';
			$html .= '<div class="el-col-small-12 el-col-medium-8 el-col-medium-offset-2">';
				$args = array(
					'prev_text'	=> '<p class="control button black"><i class="icon fa fa-angle-left" aria-hidden="true"></i> Previous</p><div class="title">%title</div>',
					'next_text'	=> '<p class="control button black">Next <i class="icon fa fa-angle-right" aria-hidden="true"></i></p><div class="title">%title</div>'
				);
				$html .= get_the_post_navigation($args);
			$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}


	//admin only scripts / styles
	public function enqueue_admin_scripts_and_styles(){
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );
	}

	//public only scripts / styles
	public function enqueue_public_scripts_and_styles(){

		//Enqueue fonts from google
		if( get_theme_mod( 'pacific_body_font' ) ){
			wp_enqueue_style( 'pacific-body-font', '//fonts.googleapis.com/css?family=' . get_theme_mod('pacific_body_font') );
		}
		if( get_theme_mod( 'pacific_header_font' ) ){
			wp_enqueue_style( 'pacific-header-font', '//fonts.googleapis.com/css?family=' . get_theme_mod('pacific_header_font') );
		}

		wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );

		wp_enqueue_style( 'pacific-stylesheet', get_stylesheet_uri() );

		wp_enqueue_script( 'jquery-masonry' , array( 'jquery' ) ); //masonry, used for blogs /  listings
		wp_enqueue_script( 'theme-public-script' , get_stylesheet_directory_uri() . '/js/el_pacific_theme_scripts.js', array( 'jquery', 'jquery-masonry' ) );
		wp_enqueue_script( 'pacific-navigation', get_template_directory_uri() . '/js/navigation.js' );
		wp_enqueue_script( 'pacific-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js' );

		//comment reply on on singular post pages
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	//loads textdomain
	public function load_theme_textdomain(){
		load_theme_textdomain( 'pacific', get_template_directory() . '/languages' );
	}

	//adds theme support for various WP elements
	public function add_theme_support(){

		global $wp_version;

		//automatic feed links
		add_theme_support('automatic-feed-links');

		//post thumbnails
		add_theme_support('post-thumbnails');

		//HTML5 markup
		$html5_args = array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption');
		add_theme_support('html5', $html5_args);


		//Custom background
		$custom_background_args = array(
			'default-color'          => 'fffffff',
			'default-image'          => '',
			'default-repeat'         => 'no-repeat',
			'default-position-x'     => 'center',
			'default-attachment'     => 'scroll',
			'wp-head-callback'       => array( $this, 'pacific_custom_background_styles' ),
			'admin-head-callback'    => '',
			'admin-preview-callback' => ''
		);

		add_theme_support( 'custom-background', $custom_background_args );


		//post formats
		$post_formats_args = array( 'aside', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' );
		//add support for new `post-format` elements (WP 3.9)
		if( $wp_version >= '3.9.0' ){
			array_push( $post_formats_args, 'gallery' );
			array_push( $post_formats_args, 'caption' );
		}
		add_theme_support( 'post-formats', $post_formats_args );


		//Custom header
		$custom_header_args = array(
			'default-image'          => '',
			'width'                  => 1200,
			'height'                 => 675,
			'flex-height'            => false,
			'flex-width'             => false,
			'uploads'                => true,
			'random-default'         => false,
			'header-text'            => false,
			'default-text-color'     => 'CC00EE',
			'wp-head-callback'       => array( $this, 'pacific_custom_header_styles' ),
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
		);
		add_theme_support( 'custom-header' , $custom_header_args);


		//add support for `the-title` (WP 4.1)
		if($wp_version >= '4.1.0'){
			add_theme_support( 'title-tag' );
		}


		//add support for 'custom-logo' (WP 4.5)
		if($wp_version >= '4.5'){

			$logo_args = array(
				'height'      => 100,
				'width'       => 400,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text'  => array(get_bloginfo('name'), get_bloginfo('description'))
			);

			add_theme_support('custom-logo', $logo_args);

		}
	}

	//TODO: COME BACK TO THIS SOON TO ADJUST
	//outputs the styles for elements set in the theme customizer
	public function pacific_custom_header_styles() {

		/*
		 * HEADER_TEXTCOLOR
		 * NO_HEADER_TEXT
		 * HEADER_IMAGE_WIDTH
		 * HEADER_TEXTCOLOR
		 * HEADER_IMAGE_HEIGHT
		 * HEADER_IMAGE
		 * get_header_image
		 * get_header_image_tag
		 */


		$header = get_custom_header();


		//determine if site title and tagline displayed (default confusing theme mod name)

		//$header_text_color = get_theme_mod('header_textcolor', get_theme_support('custom-header', 'default-text-color'));
		$header = '';

		//if we have a custom header
		if($header){?>
		<style type="text/css" id="custom-header-styles">

			body{
				border: solid 5px red!important;
			}

			<?php
			//if we're displaying the site title and desc
			if($header_display_text){?>
				.site-title,
				.site-description {
					color: #<?php echo esc_attr( $header_text_color ); ?>;
					border: solid 1px red;
				}
			<?php }
		}?>
		</style>
		<?php
	}


	//TODO: Combine with customizer output so CSS is outputted in one style
	//outputs the styling for any custom background set in theme customizer
	public function pacific_custom_background_styles(){

		$bg_image = get_background_image();


		//if we have background image
		if($bg_image){
			$bg_color = get_theme_mod('background_color', get_theme_support('custom-background', 'default-color'));
			$bg_reapeat = get_theme_mod('background_repeat', get_theme_support('custom-background','default-repeat'));
			$bg_attachment = get_theme_mod('background_attachment', get_theme_support('custom-background','default-attachment'));
			$bg_position_x = get_theme_mod('background_position_x', get_theme_support('custom-background', 'default-position-x'));

			$style = 'background-image: url(' . $bg_image . ');';
			if(!empty($bg_attachment)){
				$style .= 'background-attachment:' . $bg_attachment . ';';
			}
			if(!empty($bg_attachment)){
				$style .= 'background-repeat:' . $bg_reapeat . ';';
			}
			if(!empty($bg_position_x)){
				$style .= 'background-position: center ' . $bg_position_x . ';';
			}

			?>
			<style type="text/css" id="custom-background-css">

				body{ <?php echo $style; ?> }

			</style>
		<?php }


	}

	//registers default theme nav areas
	public function register_navigation_menus(){

		//default nav menus
		$default_nav_menus = array(
			array(
				'nav_location'		=> 'primary',
				'nav_description'	=> esc_html__('Primary nav menu', 'pacific')
			),
			array(
				'nav_location'		=> 'footer',
				'nav_description'	=> esc_html__('Footer nav menu', 'pacific')
			)
		);

		//register nav menus
		if($default_nav_menus){
			foreach($default_nav_menus as $nav_menu){
				register_nav_menu($nav_menu['nav_location'], $nav_menu['nav_description']);
			}
		}

	}




	//returns the singleton of this class
	public static function getInstance(){

		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
 }

$pacific_init = Pacific_Init::getInstance();






/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
