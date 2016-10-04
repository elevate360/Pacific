<?php
/*
 * Universal theme elements. Functionality shared between modules is handled here
 * - Adds Custom Header metabox to several post types (To allow output of an image, logo, text, subtitle etc)
 * - Adds Call to Action metabox (Letting pages, posts, ccts output applicable call to action elements)
 * - Adds SVG support for image uploads 
 * - Registers widget areas for the footer (3 columns)
 */

 
 class el_universal{
 
 	private static $instance = null;

 	public function __construct(){
 	
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts_and_styles'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles'));
		add_filter('upload_mimes', array($this, 'add_file_types_to_uploads'));
		add_action('edit_form_after_title', array($this, 'move_advanced_metaboxes_above_editor')); //output 'advanced' metaboxes just below the title
		add_action('widgets_init', array($this, 'register_widget_areas'));
		add_action('after_setup_theme', array($this, 'set_theme_content_width'));
		add_action('wp_ajax_getattachmenturl', array($this, 'get_attachment_url_media_library')); //custom ajax function to be used for the media library
		
	
		add_action('add_meta_boxes', array($this, 'register_header_metabox')); //register header metabox
		add_action('add_meta_boxes', array($this, 'register_call_to_action_metabox')); //call to action metabox
		add_action('add_meta_boxes', array($this, 'register_showcase_page_meta_box')); //showcase page template metabox
		
		//saving 
		add_action('save_post', array($this, 'save_post_header_metabox')); //saves metadata for header metabox
		add_action('save_post', array($this, 'save_post_call_to_action_metabox')); //saves metadata for call to action metabox
		add_action('save_post', array($this, 'save_post_showcase_metabox')); //saves metadata for the showcase metabox
	
		//universal shortcodes
		add_action('init', array($this, 'register_shortcodes'),10, 1);
	
		//universal action hooks
		add_action('el_display_post_navigation', array($this, 'display_post_navigation'));
		add_action('el_display_social_media_icons', array($this,'el_display_social_media_icons' ));
		add_action('el_display_comment_template', array($this, 'el_display_comment_template'));
		
		//action hooks for showcase template
		add_action('el_display_showcase_main_content', array($this, 'el_display_showcase_main_content'), 10, 1);
		add_action('el_display_showcase_secondary_content', array($this, 'el_display_showcase_secondary_content'), 10, 1);
		add_action('el_displau_showcase_bottom_content', array($this, 'el_displau_showcase_bottom_content'), 10, 1); //bottom content (sits negatively margined into the secondary content area)
		add_action('el_display_showcase_data_summaries', array($this, 'el_display_showcase_data_summaries'), 10, 1); //Data summaries for this page
 	}

	public function get_attachment_url_media_library(){
		
		$url = '';
		$attachmentID = isset($_REQUEST['attachmentID']) ? $_REQUEST['attachmentID'] : '';
		if($attachmentID){
			$url = wp_get_attachment_url($attachmentID);
		}
		
		echo $url;
		
		die();
	}

	//registers universal shortcodes for use
	public function register_shortcodes(){
		add_shortcode('el_row', array($this, 'render_shortcodes'));
		add_shortcode('el_col', array($this, 'render_shortcodes'));
		add_shortcode('el_showcase_block', array($this, 'render_shortcodes'));
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
		
		//Showcase block (white background section with title, content and border)
		//[el_showcase_section title="" content=""][/el_showcase_section]
		else if($tag == 'el_showcase_block'){
			
			$args = shortcode_atts(array(
				'title'		=> '',
				'content'	=> ''
			), $atts, $tag);
			
			$html .= '<div class="showcase-block">';
				if(!empty($args['title'])){
					$html .= '<h3 class="title fat small-align-center">' . $args['title'] . '</h3>';
				}
				if(!empty($args['content'])){
					$html .= '<div class="content small-margin-bottom-medium">' . esc_html($args['content']) . '</div>';
				}
			$html .= '</div>';
		}
		
		
		return $html;
	}

	//displays the main content for a page using the showcase template
	public static function el_display_showcase_main_content($post){
			
		$instance = self::getInstance();
		$html .= '';
		
		
		$showcase_main_content = get_post_meta($post->ID, 'showcase_main_content', true);
		
		//output
		$html .= '<article class="el-row animation-container">';
			$html .= '<div class="showcase-main-content el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center small-margin-bottom-medium ">';
				if(!empty($showcase_main_content)){
					$html .= '<div class="content">' . apply_filters('the_content', $showcase_main_content) . '</div>';
				}
			$html .= '</div>';
		$html .= '</article>';
		
		
		echo $html;
		
	}
	//displays the secondary content for a page using showcase template (left image + right content block)
	public static function el_display_showcase_secondary_content($post){
		
		$instance = self::getInstance();
		$html .= '';
		
		$showcase_right_image = get_post_meta($post->ID, 'showcase_right_image', true);
		$showcase_left_content = get_post_meta($post->ID, 'showcase_left_content', true);
		$image_url = '';
		if(!empty($showcase_right_image)){
			$post_attachment = get_post($showcase_right_image);
			if($post_attachment->post_mime_type == 'image/svg+xml'){
				$image_url = $post_attachment->guid;
			}else{
				$image_url = wp_get_attachment_image_src($showcase_right_image, 'large', false)[0];
			}
		}
		
		
		
		//$style = (!empty($image_url)) ? 'background-image: url(' . $image_url . ');' : '';
		$style = '';
		$html .= '<article class="el-row animation-container">';
		
			$html .= '<div class="showcase-secondary-content el-col-small-12  small-margin-top-medium" style="' . $style .'">';
				
				$html .= '<div class="content el-row nested">';
					
					//left content
					if(!empty($showcase_left_content)){
						$html .= '<div class="el-col-small-12 el-col-medium-6 el-col-large-4">';
							$html .= apply_filters('the_content', $showcase_left_content);
						$html .= '</div>';
					}
					
					//right image
					if(!empty($showcase_right_image) && !empty($image_url)){
						$html .= '<div class="el-col-small-12 el-col-medium-6 el-col-large-offset-2">';
							$html .= '<img src="' . $image_url . '"/>';
						$html .= '</div>';
					}
					
					
					
				$html .= '</div>';
		
			$html .= '</div>';
		
		$html .= '</article>';
		
		echo $html;
		
	}

	//displays the bottom content section for the showcase template page
	public static function el_displau_showcase_bottom_content($post){
		
		$instance = self::getInstance();
		$html .= '';
		
		$showcase_bottom_content = get_post_meta($post->ID, 'showcase_bottom_content', true);
		
		if(!empty($showcase_bottom_content)){
			$html .= '<article class="el-row animation-container">';
				$html .= '<div class="showcase-bottom-content el-col-small-12 small-margin-bottom-medium">';
					$html .= apply_filters('the_content', $showcase_bottom_content);
				$html .= '</div>';
			$html .= '</article>';
		}

		echo $html;
	}


	//displays the data summaries associated with this showcase page
	public static function el_display_showcase_data_summaries($post){
		
		$instance = self::getInstance();
		$html .= '';
		
		$showcase_data_summaries = get_post_meta($post->ID,'showcase_data_summaries', true);
		if(!empty($showcase_data_summaries)){
			//get our summary data class
			$el_data_summary = el_data_summary::getInstance();
			//get summaries
			$showcase_data_summaries = json_decode($showcase_data_summaries);
			
			foreach($showcase_data_summaries as $summary_id){
				$html .= $el_data_summary::get_data_summary_html($summary_id);
			}
		}

		echo $html;
		
	}
	
	
	
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
			'name'          => esc_html__( 'Standard Page Sidebar', 'retail-motion' ),
			'id'            => 'widget-page-sidebar',
			'description'   => esc_html__( 'Displayed when selecting the \'Page with Sidebar\' page template' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title small-margin-top-none small-margin-bottom-small">',
			'after_title'   => '</h3><hr class="orange medium"/>',
		) );
		//Footer 1 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 1', 'retail-motion' ),
			'id'            => 'widget-footer-1',
			'description'   => esc_html__( 'First widget zone displayed in the footer' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		//Footer 2 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 2', 'retail-motion' ),
			'id'            => 'widget-footer-2',
			'description'   => esc_html__( 'Second widget zone displayed in the footer' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		
		//Footer 3 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 3', 'retail-motion' ),
			'id'            => 'widget-footer-3',
			'description'   => esc_html__( 'Third widget zone displayed in the footer' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
	
	//Set content width
	function set_theme_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'retail_motion_content_width', 640 );
	}
	
	//displays the next / prev post navigation on single posts
	public function display_post_navigation(){
		$html .= '';
		
		$html .= '<div class="el-row animation-container">';
			$html .= '<div class="el-col-small-12">';
				$args = array(
					'prev_text'	=> '<p class="control"><i class="icon fa fa-angle-left" aria-hidden="true"></i> Previous</p><div class="title">%title</div>',
					'next_text'	=> '<p class="control">Next <i class="icon fa fa-angle-right" aria-hidden="true"></i></p><div class="title">%title</div>'
				);
				$html .= get_the_post_navigation($args);
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}
	
	//register metaboxes for applicable post types
	public function register_header_metabox(){
		
		$post_types = array('post','page','portfolio','services');
		
		foreach($post_types as $post_type){
			
			add_meta_box(
				'header-meta-box',
				'Header Info',
				array($this, 'render_header_metabox'),
				$post_type,
				'normal',
				'default'
			);	
		}
		
	}
	
	//call to action metabox
	public function register_call_to_action_metabox(){
		
		$post_types = array('post','page','portfolio','services');
		
		foreach($post_types as $post_type){
			add_meta_box(
				'call-to-action-meta-box',
				'Call to Action Elements',
				array($this, 'render_call_to_action_metabox'),
				$post_type,
				'normal',
				'default'
			);
		}	
	}

	//registers a metabox that will be displayed only on pages using the 'Showcase Page' template
	public function register_showcase_page_meta_box(){
			
		add_meta_box(
			'showcase-page-meta-box',
			'Showcase Page Template Options',
			array($this, 'render_showcase_template_metabox'),
			'page',
			'advanced',
			'default'
		);
		
	}
	
	//renders the showcase metabox output
	//Uses the `el_content_type` class to output our fields for simplicity
	//Content of metabox will be dynamically hidden or shown with JS when editing single pages
	public function render_showcase_template_metabox($post){
			
		$html = '';
		$html .= '<p>Enter the following information for this page. The following settings will determine the visual layout of your page</p>';
		
		$fields = array(
			array(
				 'id'			=> 'showcase_main_content',
				 'title'		=> 'Main Content',
				 'description'	=> 'Content area displayed at the very top of the showcase page, used as an introduction / full width content',
				 'type'			=> 'editor',
			),
			array(
				 'id'			=> 'showcase_left_content',
				 'title'		=> 'Right Content',
				 'description'	=> 'Content area displayed under the primary content area. Displayed on the left hand side of the showcase image',
				 'type'			=> 'editor',
			),
			array(
				 'id'			=> 'showcase_right_image',
				 'title'		=> 'Left Image',
				 'description'	=> 'Left image used under the primary content area. Displayed as a long background image on the left',
				 'type'			=> 'upload-image',
			),
			
			array(
				 'id'			=> 'showcase_bottom_content',
				 'title'		=> 'Bottom Content',
				 'description'	=> 'Content area displayed below the secondary content area. It\'s position will be negatively margined so it will slightly overlap the secondary area',
				 'type'			=> 'editor',
			),
			array(
				 'id'			=> 'showcase_data_summaries',
				 'title'		=> 'Data Summaries',
				 'description'	=> 'Optional data summaries that should be displayed on this page',
				 'type'			=> 'related-posts',
				 'args'			=> array(
				 	'related_post_type_name'	=> 'data_summary'
				 )
			),
		);
		
		foreach($fields as $field){
			$html .= el_content_type::render_metafield_element($field, $post);
		}

		echo $html;
		
	}
	
	//renders the header metabox output
	//Uses the `el_content_type` to output our fields for simplicity 
	public function render_header_metabox($post){
		
		$html = '';
		$html .= '<p>Enter the following information for the header</p>';
		
		$fields = array(
			array(
				 'id'			=> 'header_background_image',
				 'title'		=> 'Background Image',
				 'description'	=> 'Select a background image for use in the header',
				 'type'			=> 'upload-image',
			 ),
			 array(
				 'id'			=> 'header_background_color',
				 'title'		=> 'Background Color',
				 'description'	=> 'Select a solid color to be used in the background if you don\'t want to use an image',
				 'type'			=> 'color',
				 'args'			=> array(
					'default-color'		=> '#eee' 
			 	 )
			 ),
			  array(
				 'id'			=> 'header_overlay_color',
				 'title'		=> 'Overlay Color',
				 'description'	=> 'Select a color that will be displayed as an overlay on top of the image. This is optional',
				 'type'			=> 'color'
			 ),
			 array(
			 	'id'			=> 'header_overlay_opacity',
			 	'title'			=> 'Header Overlay Opacity',
			 	'description'	=> 'Opacity of the overlay element (when the overlay color has been set), lower values are more transparent',
			 	'type'			=> 'select',
			 	'args'			=> array(
			 		'options'		=> array(
			 			array('id'	=> '0.0',  'title'	=> '0%'),
						array('id'	=> '0.1', 'title'	=> '10%'),
						array('id'	=> '0.2', 'title'	=> '20%'),
						array('id'	=> '0.3', 'title'	=> '30%'),
						array('id'	=> '0.4', 'title'	=> '40%'),
						array('id'	=> '0.5', 'title'	=> '50%'),
						array('id'	=> '0.6', 'title'	=> '60%'),
						array('id'	=> '0.7', 'title'	=> '70%'),
						array('id'	=> '0.8', 'title'	=> '80%'),
						array('id'	=> '0.9', 'title'	=> '90%'),
						array('id'	=> '100', 'title'	=> '100%')
					)
				)
			 ),
			  array(
				 'id'			=> 'header_text_color',
				 'title'		=> 'Text Color',
				 'description'	=> 'Color of the text when superimposed over your image / ',
				 'type'			=> 'color',
				 'args'			=> array(
					'default-color'		=> '#333' 
			 	 )
			 ),
			 array(
			  	'id'			=> 'header_logo',
				 'title'		=> 'Header Logo',
				 'description'	=> '(Optional) - Select a logo to be used in the header',
				 'type'			=> 'upload-image',
			 ),
			 array(
			 	'id'			=> 'header_title',
			 	'title'			=> 'Header Title',
			 	'description'	=> 'Primary call to action text displayed in the header',
			 	'type'			=> 'text'
			 ),
			 array(
			 	'id'			=> 'header_subtitle',
			 	'title'			=> 'Header Subtitle',
			 	'description'	=> 'Secondary call to action text displayed in the header, below the primary title',
			 	'type'			=> 'text'
			 ),
			 array(
			 	'type'			=> 'line-break-title',
			 	'title'			=> 'Video Options',
			 	'description'	=> 'These settings relate to your video options from Wistia' 
			 ),
			 array(
			 	'id'			=> 'header_video_url',
			 	'title'			=> 'Video Embed URL',
			 	'description'	=> 'ULR of the iFrame element. This is found when clicking the \'Embed & Share\' option while managing your upload on Wistia.',   
			 	'type'			=> 'text',
			 	'args'			=> array(
					'placeholder'		=> 'E.g //fast.wistia.net/embed/iframe/qznuwjoj98'
				)
			 ),

		);
		
		foreach($fields as $field){
			$html .= el_content_type::render_metafield_element($field, $post);
		}

		echo $html;
	}

	//Displays the call to action metabox on post types, lets users assign CTA's on a post by post basis
	public function render_call_to_action_metabox($post){
			
		$html = '';
		
		$fields = array(
			array(
				'id'			=> 'call_to_action_elements',
				'title'			=> 'Call to Actions',
				'description'	=> 'Select the CTA\'s that will be displayed on this post type.',
				'type'			=> 'related-posts',
				'meta_box_id'	=> 'call-to-action-meta-box',
				'args'			=> array(
					'related_post_type_name'	=> 'call_to_action'
				)
			)
		);
		
		foreach($fields as $field){
			$html .= el_content_type::render_metafield_element($field, $post);
		}
		
		echo $html;
		
	}
	
	//save metadata from our header metabox
	public function save_post_header_metabox($post_id){
		
		$post_types = array('post','page','portfolio','services');
		$post_type = get_post_type($post_id);
		
		if(in_array($post_type, $post_types)){
			
			//Header Metabox
			$header_background_image = isset($_POST['header_background_image']) ? sanitize_text_field($_POST['header_background_image']) : '';
			$header_background_color = isset($_POST['header_background_color']) ? $_POST['header_background_color'] : '';
			$header_overlay_color = isset($_POST['header_overlay_color']) ? $_POST['header_overlay_color'] : '';
			$header_overlay_opacity = isset($_POST['header_overlay_opacity'])? $_POST['header_overlay_opacity'] : '';
			$header_text_color = isset($_POST['header_text_color']) ? $_POST['header_text_color'] : '';
			$header_logo = isset($_POST['header_logo']) ? sanitize_text_field($_POST['header_logo']) : '';
			$header_title = isset($_POST['header_title']) ? sanitize_text_field($_POST['header_title']) : '';
			$header_subtitle = isset($_POST['header_subtitle']) ? sanitize_text_field($_POST['header_subtitle']) : '';
			$header_video_url = isset($_POST['header_video_url']) ? esc_url($_POST['header_video_url']) : '';
			
			
			update_post_meta($post_id, 'header_background_image', $header_background_image);
			update_post_meta($post_id, 'header_background_color', $header_background_color);
			update_post_meta($post_id, 'header_overlay_color', $header_overlay_color);
			update_post_meta($post_id, 'header_overlay_opacity', $header_overlay_opacity);
			update_post_meta($post_id, 'header_text_color', $header_text_color);
			update_post_meta($post_id, 'header_logo', $header_logo);
			update_post_meta($post_id, 'header_title', $header_title);
			update_post_meta($post_id, 'header_subtitle', $header_subtitle);
			update_post_meta($post_id, 'header_video_url', $header_video_url);
			
		}
		
	}

	//save metadata from our call to action metabox
	public function save_post_call_to_action_metabox($post_id){
		
		$post_types = array('post','page','portfolio','services');
		$post_type = get_post_type($post_id);
		
		if(in_array($post_type, $post_types)){
				
			$call_to_action_elements = isset($_POST['call_to_action_elements']) ? $_POST['call_to_action_elements'] : '';
			if(!empty($call_to_action_elements)){
				$call_to_action_elements = json_encode($call_to_action_elements);
			}
			update_post_meta($post_id, 'call_to_action_elements', $call_to_action_elements);

		}
	}

	//save metabox for the showcase metabox 
	//Only want to save values if we're using the right template, else delete values
	public function save_post_showcase_metabox($post_id){
		
		$post_type = get_post_type($post_id);
		if($post_type === 'page'){
			
			if(isset($_POST['page_template'])){
					
				//if page is using the showcase template, update values
				if($_POST['page_template'] == 'page-showcase.php'){
					
					$showcase_main_content = isset($_POST['showcase_main_content']) ? $_POST['showcase_main_content'] : '';
					$showcase_right_image = isset($_POST['showcase_right_image']) ? $_POST['showcase_right_image'] : '';
					$showcase_left_content = isset($_POST['showcase_left_content']) ? $_POST['showcase_left_content'] : '';
					$showcase_data_summaries = isset($_POST['showcase_data_summaries']) ? json_encode($_POST['showcase_data_summaries']) : '';
					$showcase_bottom_content = isset($_POST['showcase_bottom_content']) ? $_POST['showcase_bottom_content'] : '';
					
					update_post_meta($post_id, 'showcase_main_content', $showcase_main_content);
					update_post_meta($post_id, 'showcase_right_image', $showcase_right_image);
					update_post_meta($post_id, 'showcase_left_content', $showcase_left_content);
					update_post_meta($post_id, 'showcase_bottom_content', $showcase_bottom_content);
					update_post_meta($post_id, 'showcase_data_summaries', $showcase_data_summaries);
					
				}
				//else remove set value if exist
				else{
					delete_post_meta($post_id, 'showcase_main_content');
					delete_post_meta($post_id, 'showcase_right_image');
					delete_post_meta($post_id, 'showcase_left_content');
					delete_post_meta($post_id, 'showcase_bottom_content');
					delete_post_meta($post_id, 'showcase_data_summaries');
				}
				
			}
				
				
				
			
			
		}
		
	}
	
	//admin only scripts / styles
	public function enqueue_admin_scripts_and_styles(){
		
		wp_enqueue_style('theme-admin-style', get_stylesheet_directory_uri() . '/inc/modules/universal/css/theme_universal_admin_styles.css');
		wp_enqueue_style('theme-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('theme-admin-script', get_stylesheet_directory_uri() . '/inc/modules/universal/js/theme_universal_admin_scripts.js', array('jquery','jquery-ui-sortable','wp-color-picker'));
	}
	
	//public only scripts / styles
	public function enqueue_public_scripts_and_styles(){
		wp_enqueue_style('theme-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700');
		wp_enqueue_script('theme-masonry-script', get_stylesheet_directory_uri() . '/inc/modules/universal/js/jquery-masonry.js', array('jquery'));
		wp_enqueue_style('theme-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		wp_enqueue_script('theme-flexslider-script', get_stylesheet_directory_uri() . '/inc/modules/universal/js/jquery.flexslider-min.js' , array('jquery'));
		wp_enqueue_style('theme-flexslider-styles', get_stylesheet_directory_uri() . '/inc/modules/universal/css/flexslider.css');
		wp_enqueue_script('theme-public-script', get_stylesheet_directory_uri() . '/inc/modules/universal/js/theme_universal_public_scripts.js', array('jquery', 'theme-masonry-script','theme-flexslider-script'));
		wp_enqueue_script('theme-wistia-script-primary', '//fast.wistia.com/assets/external/E-v1.js');	
	}
	
	//'advanced' metaboxes should be moved above the main post editor
	public function move_advanced_metaboxes_above_editor($post){
		
		global $wp_meta_boxes;
		
		//output all advanced metaboxes
		do_meta_boxes(get_current_screen(), 'advanced', $post);		
		
		//remove these metaboxes so they don't get outputted again
		unset($wp_meta_boxes[get_post_type($post)]['advanced']);
		
	}
	
	//add SVG to allowed file uploads
	public function add_file_types_to_uploads($file_types){
			
		$new_filetypes = array();
		$new_filetypes['svg'] = 'image/svg+xml';
		$file_types = array_merge($file_types, $new_filetypes );

		return $file_types;
	}
	
	//returns the singleton of this class
	public static function getInstance(){
		
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
 }
$el_universal = el_universal::getInstance();



?>