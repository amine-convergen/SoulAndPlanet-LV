<?php
/**
 * functions.php
 * @package WordPress
 * @subpackage Bacola
 * @since Bacola 1.0.9
 * 
 */

/*************************************************
## Admin style and scripts  
*************************************************/ 

function bacola_admin_styles() {
	wp_enqueue_style('bacola-klbtheme',   get_template_directory_uri() .'/assets/css/admin/klbtheme.css');
	wp_enqueue_script('bacola-init', 	  get_template_directory_uri() .'/assets/js/init.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('bacola-register',  get_template_directory_uri() .'/assets/js/admin/register.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'bacola_admin_styles');

 /*************************************************
## Bacola Fonts
*************************************************/
function bacola_fonts_url_inter() {
	$fonts_url = '';

	$inter = _x( 'on', 'Inter font: on or off', 'bacola' );		

	if ( 'off' !== $inter ) {
		$font_families = array();

		if ( 'off' !== $inter ) {
		$font_families[] = 'Inter:wght@100;200;300;400;500;600;700;800;900';
		}
		
		$query_args = array( 
		'family' => rawurldecode( implode( '|', $font_families ) ), 
		'subset' => rawurldecode( 'latin,latin-ext' ), 
		); 
		 
		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css2' );
	}
 
	return esc_url_raw( $fonts_url );
}

function bacola_fonts_url_dosis() {
	$fonts_url = '';

	$dosis = _x( 'on', 'Roboto font: on or off', 'bacola' );	

	if ( 'off' !== $dosis ) {
		$font_families = array();

		if ( 'off' !== $dosis ) {
		$font_families[] = 'Roboto:wght@200;300;400;500;600;700;800';
		}
		
		$query_args = array( 
		'family' => rawurldecode( implode( '|', $font_families ) ), 
		'subset' => rawurldecode( 'latin,latin-ext' ), 
		); 
		 
		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css2' );
	}
 
	return esc_url_raw( $fonts_url );
}

/*************************************************
## Styles and Scripts
*************************************************/ 
define('BACOLA_INDEX_CSS', 	  get_template_directory_uri()  . '/assets/css');
define('BACOLA_INDEX_JS', 	  get_template_directory_uri()  . '/assets/js');
define('BACOLA_INDEX_FONTS',    get_template_directory_uri()  . '/assets/fonts');

function bacola_scripts() {

	if ( is_admin_bar_showing() ) {
		wp_enqueue_style( 'bacola-klbtheme', BACOLA_INDEX_CSS . '/admin/klbtheme.css', false, '1.0');    
	}	

	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

	wp_enqueue_style( 'bootstrap', 				BACOLA_INDEX_CSS . '/bootstrap.min.css', false, '1.0');
	wp_enqueue_style( 'select2', 				BACOLA_INDEX_CSS . '/select2.min.css', false, '1.0');
	wp_enqueue_style( 'bacola-base', 			BACOLA_INDEX_CSS . '/base.css', false, '1.0');
	wp_style_add_data( 'bacola-base', 'rtl', 'replace' );
	wp_enqueue_style( 'bacola-font-dmsans',  	bacola_fonts_url_inter(), array(), null );
	wp_enqueue_style( 'bacola-font-crimson',  	bacola_fonts_url_dosis(), array(), null );
	wp_enqueue_style( 'bacola-style',         	get_stylesheet_uri() );
	wp_style_add_data( 'bacola-style', 'rtl', 'replace' );

	$mapkey = get_theme_mod('bacola_mapapi');

	wp_enqueue_script( 'imagesloaded');
	wp_enqueue_script( 'bootstrap-bundle',    	 BACOLA_INDEX_JS . '/bootstrap.bundle.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'select2-full',    	 	 BACOLA_INDEX_JS . '/select2.full.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'gsap',    	    		 BACOLA_INDEX_JS . '/vendor/gsap.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'jquery-magnific-popup',  BACOLA_INDEX_JS . '/vendor/jquery.magnific-popup.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'perfect-scrolllbar',     BACOLA_INDEX_JS . '/vendor/perfect-scrollbar.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'slick',    	    	 	 BACOLA_INDEX_JS . '/vendor/slick.min.js', array('jquery'), '1.0', true);
	wp_register_script( 'bacola-googlemap',    '//maps.googleapis.com/maps/api/js?key='. $mapkey .'', array('jquery'), '1.0', true);
	wp_enqueue_script( 'bacola-sidebarfilter',   BACOLA_INDEX_JS . '/custom/sidebarfilter.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'bacola-productsorting',  BACOLA_INDEX_JS . '/custom/productSorting.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'bacola-producthover',    BACOLA_INDEX_JS . '/custom/productHover.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'bacola-cartquantity',    BACOLA_INDEX_JS . '/custom/cartquantity.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'bacola-sitescroll',      BACOLA_INDEX_JS . '/custom/sitescroll.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'bacola-bundle',     	 BACOLA_INDEX_JS . '/bundle.js', array('jquery'), '1.0', true);

}
add_action( 'wp_enqueue_scripts', 'bacola_scripts' );

/*************************************************
## Theme Setup
*************************************************/ 

if ( ! isset( $content_width ) ) $content_width = 960;

function bacola_theme_setup() {
	
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-background' );
	add_theme_support( 'post-formats', array('gallery', 'audio', 'video'));
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'woocommerce', array('gallery_thumbnail_image_width' => 99,'thumbnail_image_width' => 90,) );
	load_theme_textdomain( 'bacola', get_template_directory() . '/languages' );
	remove_theme_support( 'widgets-block-editor' );

}
add_action( 'after_setup_theme', 'bacola_theme_setup' );


/*************************************************
## Include the TGM_Plugin_Activation class.
*************************************************/ 

require_once get_template_directory() . '/includes/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'bacola_register_required_plugins' );

function bacola_register_required_plugins() {

	$url = 'http://klbtheme.com/bacola/plugins/';
	$mainurl = 'http://klbtheme.com/plugins/';

	$plugins = array(
		
        array(
            'name'                  => esc_html__('Meta Box','bacola'),
            'slug'                  => 'meta-box',
        ),

        array(
            'name'                  => esc_html__('Contact Form 7','bacola'),
            'slug'                  => 'contact-form-7',
        ),
		
		array(
            'name'                  => esc_html__('WooCommerce Wishlist','bacola'),
            'slug'                  => 'ti-woocommerce-wishlist',
        ),
		
		array(
            'name'                  => esc_html__('WooCommerce Compare','bacola'),
            'slug'                  => 'woo-smart-compare',
        ),
		
        array(
            'name'                  => esc_html__('Kirki','bacola'),
            'slug'                  => 'kirki',
        ),
		
		array(
            'name'                  => esc_html__('MailChimp Subscribe','bacola'),
            'slug'                  => 'mailchimp-for-wp',
        ),
		
        array(
            'name'                  => esc_html__('Elementor','bacola'),
            'slug'                  => 'elementor',
            'required'              => true,
        ),
		
        array(
            'name'                  => esc_html__('WooCommerce','bacola'),
            'slug'                  => 'woocommerce',
            'required'              => true,
        ),
		
		array(
            'name'                  => esc_html__('Variation Swatches','bacola'),
            'slug'                  => 'woo-variation-swatches',
        ),

		array(
            'name'                  => esc_html__('WP Ajax Search','bacola'),
            'slug'                  => 'ajax-search-for-woocommerce',
        ),

        array(
            'name'                  => esc_html__('Bacola Core','bacola'),
            'slug'                  => 'bacola-core',
            'source'                => $url . 'bacola-core.zip',
            'required'              => true,
            'version'               => '1.1.1',
            'force_activation'      => false,
            'force_deactivation'    => false,
            'external_url'          => '',
        ),

        array(
            'name'                  => esc_html__('Envato Market','bacola'),
            'slug'                  => 'envato-market',
            'source'                => $mainurl . 'envato-market.zip',
            'required'              => true,
            'version'               => '2.0.6',
            'force_activation'      => false,
            'force_deactivation'    => false,
            'external_url'          => '',
        ),


	);

	$config = array(
		'id'           => 'bacola',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}

/*************************************************
## Bacola Register Menu 
*************************************************/

function bacola_register_menus() {
	register_nav_menus( array( 'main-menu' 	   => esc_html__('Primary Navigation Menu','bacola')) );

	if(get_theme_mod('bacola_footer_menu',0) == '1'){
		register_nav_menus( array( 'footer-menu'     => esc_html__('Footer Menu','bacola')) );
	}
	
	$topheader = get_theme_mod('bacola_top_header','0');
	$sidebarmenu = get_theme_mod('bacola_header_sidebar','0');

	if($sidebarmenu == '1'){
		register_nav_menus( array( 'sidebar-menu'     => esc_html__('Sidebar Menu','bacola')) );
	}
	
	if($topheader == '1'){
		register_nav_menus( array( 'canvas-bottom' 	   => esc_html__('Canvas Bottom','bacola')) );
		register_nav_menus( array( 'top-right-menu'    => esc_html__('Top Right Menu','bacola')) );
		register_nav_menus( array( 'top-left-menu'     => esc_html__('Top Left Menu','bacola')) );
	}
}
add_action('init', 'bacola_register_menus');

/*************************************************
## Bacola Main Menu
*************************************************/ 
class bacola_main_walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		// depth dependent classes
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'',
			( $display_depth % 2  ? '' : '' ),
			( $display_depth >=2 ? '' : '' ),
			
			);
		$class_names = implode( ' ', $classes );
	  
		// build html
		$output .= "\n" . $indent . '<ul class="sub-menu">' . "\n";
	}

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
      function start_el(&$output, $object, $depth = 0, $args = Array() , $current_object_id = 0) {
           
           global $wp_query;

           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';
		   
		   $classes = empty( $object->classes ) ? array() : (array) $object->classes;
           $icon_class = $classes[0];
		   $classes = array_slice($classes,1);
		   
		   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $object ) );
		   
		   if ( $args->has_children ) {
		   $class_names = 'class="dropdown '.esc_attr($icon_class).' '. esc_attr( $class_names ) . ' li-'. esc_attr($dataclass) .'"';
		   } else {
		   $class_names = 'class=" '. esc_attr( $class_names ) . 'li-'. esc_attr($dataclass) .'"';
		   }
			
			$output .= $indent . '<li ' . $value . $class_names .'>';

			$datahover = str_replace(' ','',$object->title);


			$attributes = ! empty( $object->url ) ? ' href="'   . esc_attr( $object->url ) .'"' : '';

				
			$object_output = $args->before;

			$object_output .= '<a'. $attributes .'  >';
			if($icon_class && $icon_class != 'mega-menu'){
			$object_output .= '<i class="'.esc_attr($icon_class).'"></i> ';
			}
			$object_output .= $args->link_before .  apply_filters( 'the_title', $object->title, $object->ID ) . '';
	        $object_output .= $args->link_after;
			$object_output .= '</a>';


			$object_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $object_output, $object, $depth, $args );            	              	
      }
}

/*************************************************
## Bacola Sidebar Menu
*************************************************/ 
class bacola_sidebar_walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		// depth dependent classes
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'',
			( $display_depth % 2  ? '' : '' ),
			( $display_depth >=2 ? '' : '' ),
			
			);
		$class_names = implode( ' ', $classes );
	  
		// build html
		$output .= "\n" . $indent . '<ul class="sub-menu">' . "\n";
	}

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
      function start_el(&$output, $object, $depth = 0, $args = Array() , $current_object_id = 0) {
           
           global $wp_query;

           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';
		   
		   $classes = empty( $object->classes ) ? array() : (array) $object->classes;
		   $myclasses = empty( $object->classes ) ? array() : (array) $object->classes;
           $icon_class = $classes[0];
		   $classes = array_slice($classes,1);
		   
		 
		   
		   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $object ) );
		   
		   if ( $args->has_children ) {
		   $class_names = 'class="category-parent parent  '. esc_attr( $class_names ) . '"';
		   }elseif(in_array('bottom',$myclasses)){
		   $class_names = 'class="link-parent  '. esc_attr( $class_names ) . '"';   
		   } else {
		   $class_names = 'class="category-parent  '. esc_attr( $class_names ) . '"';
		   }
			
			$output .= $indent . '<li ' . $value . $class_names .'>';

			$datahover = str_replace(' ','',$object->title);


			$attributes = ! empty( $object->url ) ? ' href="'   . esc_attr( $object->url ) .'"' : '';

				
			$object_output = $args->before;

			$object_output .= '<a'. $attributes .'  >';
			if($icon_class){
			$object_output .= '<i class="'.esc_attr($icon_class).'"></i> ';
			}
			$object_output .= $args->link_before .  apply_filters( 'the_title', $object->title, $object->ID ) . '';
	        $object_output .= $args->link_after;
			$object_output .= '</a>';


			$object_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $object_output, $object, $depth, $args );            	              	
      }
}

/*************************************************
## Excerpt More
*************************************************/ 

function bacola_excerpt_more($more) {
  global $post;
  return '<div class="klb-readmore entry-button"><a class="button" href="'. esc_url(get_permalink($post->ID)) . '">' . esc_html__('Lire la suite', 'bacola') . '</a></div>';
  }
 add_filter('excerpt_more', 'bacola_excerpt_more');
 
/*************************************************
## Word Limiter
*************************************************/ 
function bacola_limit_words($string, $limit) {
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $limit));
}

/*************************************************
## Widgets
*************************************************/ 

function bacola_widgets_init() {
	register_sidebar( array(
	  'name' => esc_html__( 'Blog Sidebar', 'bacola' ),
	  'id' => 'blog-sidebar',
	  'description'   => esc_html__( 'These are widgets for the Blog page.','bacola' ),
	  'before_widget' => '<div class="widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Shop Sidebar', 'bacola' ),
	  'id' => 'shop-sidebar',
	  'description'   => esc_html__( 'These are widgets for the Shop.','bacola' ),
	  'before_widget' => '<div class="widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer First Column', 'bacola' ),
	  'id' => 'footer-1',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer Second Column', 'bacola' ),
	  'id' => 'footer-2',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer Third Column', 'bacola' ),
	  'id' => 'footer-3',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer Fourth Column', 'bacola' ),
	  'id' => 'footer-4',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer Fifth Column', 'bacola' ),
	  'id' => 'footer-5',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );
	register_sidebar( array(
		'name' => esc_html__( 'Categories column', 'bacola' ),
		'id' => 'categories-col',
		'description'   => esc_html__( 'These are widgets for the Guide.','bacola' ),
		'before_widget' => '<div class="klbfooterwidget widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	  ) );
	register_sidebar( array(
		'name' => esc_html__( 'Newsletter Footer', 'bacola' ),
		'id' => 'newsletter-col',
		'description'   => esc_html__( 'These are widgets for the Newsletter.','bacola' ),
		'before_widget' => '<div class="klbfooterwidget widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	) );
}
add_action( 'widgets_init', 'bacola_widgets_init' );
 
/*************************************************
## Bacola Comment
*************************************************/

if ( ! function_exists( 'bacola_comment' ) ) :
 function bacola_comment( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
  switch ( $comment->comment_type ) :
   case 'pingback' :
   case 'trackback' :
  ?>

   <article class="post pingback">
   <p><?php esc_html_e( 'Pingback:', 'bacola' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'bacola' ), ' ' ); ?></p>
  <?php
    break;
   default :
  ?>
  
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<div class="comment-avatar">
				<div class="comment-author vcard">
					<img src="<?php echo get_avatar_url( $comment, 90 ); ?>" alt="<?php comment_author(); ?>" class="avatar">
				</div>
			</div>
			<div class="comment-content">
				<div class="comment-meta">
					<b class="fn"><a class="url"><?php comment_author(); ?></a></b>
					<div class="comment-metadata">
						<time><?php comment_date(); ?></time>
					</div>
				</div>
				<div class="klb-post">
					<?php comment_text(); ?>
					<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'bacola' ); ?></em>
					<?php endif; ?>
				</div>
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
			</div>

		</div>
	</li>


  <?php
    break;
  endswitch;
 }
endif;

/*************************************************
## Bacola Widget Count Filter
 *************************************************/

function bacola_cat_count_span($links) {
  $links = str_replace('</a> (', '</a> <span class="catcount">(', $links);
  $links = str_replace(')', ')</span>', $links);
  return bacola_sanitize_data($links);
}
add_filter('wp_list_categories', 'bacola_cat_count_span');
 
function bacola_archive_count_span( $links ) {
	$links = str_replace( '</a>&nbsp;(', '</a><span class="catcount">(', $links );
	$links = str_replace( ')', ')</span>', $links );
	return bacola_sanitize_data($links);
}
add_filter( 'get_archives_link', 'bacola_archive_count_span' );


/*************************************************
## Pingback url auto-discovery header for single posts, pages, or attachments
 *************************************************/
function bacola_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'bacola_pingback_header' );

/************************************************************
## DATA CONTROL FROM PAGE METABOX OR ELEMENTOR PAGE SETTINGS
*************************************************************/
function bacola_page_settings( $opt_id){
	
	if ( class_exists( '\Elementor\Core\Settings\Manager' ) ) {
		// Get the current post id
		$post_id = get_the_ID();

		// Get the page settings manager
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

		// Get the settings model for current post
		$page_settings_model = $page_settings_manager->get_model( $post_id );

		// Retrieve the color we added before
		$output = $page_settings_model->get_settings( 'bacola_elementor_'.$opt_id );
		
		return $output;
	}
}

/************************************************************
## Elementor Register Location
*************************************************************/
function bacola_register_elementor_locations( $elementor_theme_manager ) {

    $elementor_theme_manager->register_location( 'header' );
    $elementor_theme_manager->register_location( 'footer' );
    $elementor_theme_manager->register_location( 'single' );

}
add_action( 'elementor/theme/register_locations', 'bacola_register_elementor_locations' );

/*************************************************
## Bacola Get options
*************************************************/
function bacola_get_option(){	
	$getopt  = isset( $_GET['opt'] ) ? $_GET['opt'] : '';

	return esc_html($getopt);
}

/*************************************************
## Bacola Theme options
*************************************************/

	require_once get_template_directory() . '/includes/metaboxes.php';
	require_once get_template_directory() . '/includes/woocommerce.php';
	require_once get_template_directory() . '/includes/woocommerce-filter.php';
	require_once get_template_directory() . '/includes/sanitize.php';
	require_once get_template_directory() . '/includes/merlin/theme-register.php';
	require_once get_template_directory() . '/includes/merlin/setup-wizard.php';
	require_once get_template_directory() . '/includes/pjax/filter-functions.php';
    
/*************************************************
## Bacola Theme checkout page products thumbnails
*************************************************/
function bacola_product_image_review_order_checkout( $name, $cart_item, $cart_item_key ) {
    if ( ! is_checkout() ) return $name;
    $product = $cart_item['data'];
    $thumbnail = $product->get_image( array( '50', '50' ), array( 'class' => 'alignleft' ) );
    return $thumbnail . $name;
}
add_filter( 'woocommerce_cart_item_name', 'bacola_product_image_review_order_checkout', 9999, 3 );

/**
 * Change a currency symbol
 */
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case 'TND': $currency_symbol = 'TD'; break;
     }
     return $currency_symbol;
}

//this code for adding field in product backend
// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields1');
// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields1()
{

    global $woocommerce, $post;

    echo '<div class="product_custom_field">';
	echo '<h2> Guide Header</h2>';
		// Checkbox
	woocommerce_wp_checkbox( array(
		'id'            => '_checkbox',
		'wrapper_class' => 'show_if_simple',
		'label'         => __('Show Guide template', 'woocommerce' ),
		'description'   => __( 'To display the guide', 'woocommerce' )
		)
	);
    // Custom Product Text Field
    woocommerce_wp_text_input(array(
        'id' => '_custom_product_text_field',
        'placeholder' => 'Enter your guide title',
        'label' => __('Guide Title', 'woocommerce'),
        'desc_tip' => 'true'
    ));
	//Custom Product Image Url Header
	woocommerce_wp_text_input(array(
		'id' => '_custom_product_image_field_header',
		'placeholder' => 'Custom Image Url',
		'label' => __('Guide Banner Url', 'woocommerce'),
		'type' => 'text'
	));
	//Custom Product Image Url Header Mobile
	woocommerce_wp_text_input(array(
		'id' => '_custom_product_image_field_header_mb',
		'placeholder' => 'Custom Image Url Mobile',
		'label' => __('Guide Mobile Banner Url', 'woocommerce'),
		'type' => 'text'
	));
	echo '</div>';
	echo '<div class="product_custom_field">';
	echo '<h2> Guide Body</h2>';
	// Checkbox
	woocommerce_wp_checkbox( array(
		'id'            => '_checkbox_b',
		'wrapper_class' => 'show_if_simple',
		'label'         => __('Show Body Guide template', 'woocommerce' ),
		'description'   => __( 'To display Body the guide', 'woocommerce' )
		)
	);
    //Custom Product Number Field
    woocommerce_wp_text_input(array(
        'id' => '_custom_product_number_field',
        'placeholder' => 'Enter your Body title',
        'label' => __('Body Title', 'woocommerce'),
        'type' => 'text',
        'custom_attributes' => array(
            // 'step' => 'any',
            // 'min' => '0'
        )
    ));
    //Custom Product  Textarea
    woocommerce_wp_textarea_input(array(
        'id' => '_custom_product_textarea',
        'placeholder' => 'Enter your guide description',
        'label' => __('Body Description', 'woocommerce'),
		'description' => __( 'Custom description Guide Body.', 'woocommerce' )
    ));
	//Custom Product Image Url
	woocommerce_wp_text_input(array(
		'id' => '_custom_product_image_field',
		'placeholder' => 'Custom Image Url',
		'label' => __('Image Url', 'woocommerce'),
		'type' => 'text'
	));
    echo '</div>';
}
function woocommerce_product_custom_fields_save($post_id)
{
	// Checkbox
	$woocommerce_checkbox = isset( $_POST['_checkbox'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_checkbox', $woocommerce_checkbox );
    // Custom Product Text Field
    $woocommerce_custom_product_text_field = $_POST['_custom_product_text_field'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, '_custom_product_text_field', esc_attr($woocommerce_custom_product_text_field));
	//Custom Product Image Header
	$woocommerce_custom_product_text_field_image = $_POST['_custom_product_image_field_header'];
    if (!empty($woocommerce_custom_product_text_field_image))
        update_post_meta($post_id, '_custom_product_image_field_header', esc_attr($woocommerce_custom_product_text_field_image));
	//Custom Product Image Header Mobile
	$woocommerce_custom_product_text_field_image_mb = $_POST['_custom_product_image_field_header_mb'];
    if (!empty($woocommerce_custom_product_text_field_image_mb))
        update_post_meta($post_id, '_custom_product_image_field_header_mb', esc_attr($woocommerce_custom_product_text_field_image_mb));
	// Checkbox body
	$woocommerce_checkbox_b = isset( $_POST['_checkbox_b'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_checkbox_b', $woocommerce_checkbox_b );
    // Custom Product Number Field
    $woocommerce_custom_product_number_field = $_POST['_custom_product_number_field'];
    if (!empty($woocommerce_custom_product_number_field))
        update_post_meta($post_id, '_custom_product_number_field', esc_attr($woocommerce_custom_product_number_field));
    // Custom Product Textarea Field
    $woocommerce_custom_procut_textarea = $_POST['_custom_product_textarea'];
    if (!empty($woocommerce_custom_procut_textarea))
        update_post_meta($post_id, '_custom_product_textarea', esc_html($woocommerce_custom_procut_textarea));
	//Custom Product Image Url
	$woocommerce_custom_product_text_field = $_POST['_custom_product_image_field'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, '_custom_product_image_field', esc_attr($woocommerce_custom_product_text_field));
}


add_action( 'woocommerce_before_single_product', 'custom_banner_before_single_product', 6 );
function custom_banner_before_single_product() {
    global $product;

    $id = $product->get_id(); // The product ID
	$checked = get_post_meta( $product->id, '_checkbox', true );
	if($checked == "yes") {
		echo '<div class="">';
			// Your custom field "Book author"
			$book_author = get_post_meta($id, "_custom_product_text_field", true);
			if ("" != get_post_meta($id, '_custom_product_image_field_header', true)) {
				echo '<picture>
				<source media="(min-width: 650px)" srcset="' . get_post_meta($id, "_custom_product_image_field_header", true) . '">
				<img src="' . get_post_meta($id, "_custom_product_image_field_header_mb", true) . '" alt="Flowers" style="width:auto;">
				</picture>';

			}
			// Displaying your custom field under the title
			echo '<p class="book-author">test' . $book_author . '</p>';
		echo '</div>';
	}
}
add_action( 'woocommerce_after_single_product', 'custom_content_after_single_product', 12 );
function custom_content_after_single_product() {
    global $product;

    $id = $product->get_id(); // The product ID

				$checked_b = get_post_meta( $id, '_checkbox_b', true );
				if($checked_b == "yes") {
				echo '<div class="woocommerce-tabs">';
				// dynamic_sidebar( 'guide-1' );
				// echo $product->get_type();
				echo '<h3>' . get_post_meta($id, '_custom_product_number_field', true) . '</h3>';
				echo get_post_meta($id, '_custom_product_textarea', true);

				if ("" != get_post_meta($id, '_custom_product_image_field', true)) {
					echo "<img src=". get_post_meta($id, '_custom_product_image_field', true) . " >";
				}
					echo "<h1>test</h1>";
				echo '</div>';
				}
}

add_action('admin_head', 'my_custom_fonts'); // admin_head is a hook my_custom_fonts is a function we are adding it to the hook

function my_custom_fonts() {
  echo '<style>
    .product_custom_field .show_if_simple {
        display: block !important;
    }
  </style>';
}

function wpc_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'wpc_mime_types');

function get_attachment_url_by_title( $title ) {
    global $wpdb;

    $attachments = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_title = '$title' AND post_type = 'attachment' ", OBJECT );
    //print_r($attachments);
    if ( $attachments ){

        $attachment_url = $attachments[0]->guid;

    }else{
        return false;
    }

    return $attachment_url;
}
add_filter('woocommerce_get_endpoint_url', 'change_my_account_endpoint_urls', 10, 4);
function change_my_account_endpoint_urls( $url, $endpoint, $value, $permalink ) {
    switch($endpoint){
        case 'orders':
            $url = home_url('/myaccount/orders/');
            break;
        case 'downloads':
            $url = home_url('/myaccount/downloads/');
            break;
        case 'edit-address':
            $url = home_url('/myaccount/edit-address/');
            break;
        case 'edit-account':
            $url = home_url('/myaccount/edit-account/');
            break;
        case 'customer-logout':
            $url = home_url('/myaccount/customer-logout/?_wpnonce=378cccde0/');
            break;
		case 'order-tracking':
			$url = home_url('order-tracking');
			break;
		case 'view-order':
			$url = home_url('/myaccount/view-order/') . $value;
			break;
    }
    return $url;
}

add_filter ( 'woocommerce_account_menu_items', 'misha_one_more_link' );
function misha_one_more_link( $menu_links ){
	unset( $menu_links['downloads'] ); // Addresses

	$new = array( '/order-tracking/' => 'suivi de commande' );

	// array_slice() is good when you want to add an element between the other ones
	$menu_links = array_slice( $menu_links, 0, 1, true )
	+ $new
	+ array_slice( $menu_links, 1, NULL, true );

	return $menu_links;
}
add_filter('woocommerce_catalog_orderby', 'wc_customize_product_sorting');

function wc_customize_product_sorting($sorting_options){
    $sorting_options = array(
        'menu_order' => __( 'Sorting', 'woocommerce' ),
        'popularity' => __( 'Tri par popularité', 'woocommerce' ),
        'rating'     => __( 'Tri par avis des clients', 'woocommerce' ),
        'price'      => __( 'Tri par prix croissant', 'woocommerce' ),
        'price-desc' => __( 'Tri par prix décroissant', 'woocommerce' ),
    );
    // 'date'       => __( 'Tri du plus récent au plus ancien', 'woocommerce' ),
    return $sorting_options;
}

add_filter( 'woocommerce_variable_price_html', 'custom_variable_displayed_price', 10, 2 );
function custom_variable_displayed_price( $price_html, $product ) {
    // Only for archives pages
    if ( ! ( is_shop() || is_product_category() || is_product_tag() ) )
        return $price_html;

    // Searching for the default variation
    $default_attributes = $product->get_default_attributes();
    // Loop through available variations
    foreach($product->get_available_variations() as $variation){
        $found = true; // Initializing
        // Loop through variation attributes
        foreach( $variation['attributes'] as $key => $value ){
            $taxonomy = str_replace( 'attribute_', '', $key );
            // Searching for a matching variation as default
            if( isset($default_attributes[$taxonomy]) && $default_attributes[$taxonomy] != $value ){
                $found = false;
                break;
            }
        }
        // When it's found we set it and we stop the main loop
        if( $found ) {
            $default_variaton = $variation;
            break;
        } // If not we continue
        else {
            continue;
        }
    }

    // If no default variation is found we exit.
    if( ! isset($default_variaton) )
        $price_html;

    // Formatting the price
    if ( $default_variaton['display_price'] !== $default_variaton['display_regular_price'] && $product->is_on_sale()) {
        $price_html = '<del>' . wc_price($default_variaton['display_regular_price']) . '</del> <ins>' . wc_price($default_variaton['display_price']) . '</ins>';
    } else {
        $price_html = wc_price($default_variaton['display_price']);
    }
    return $price_html;
}

add_filter( 'woocommerce_get_availability', 'custom_get_availability', 1, 2);

function custom_get_availability( $availability, $_product ) {
  global $product;
  $stock = $product->get_stock_quantity();

  if ( $_product->is_in_stock() ) $availability['availability'] = __('En stock', 'woocommerce');
  if ( !$_product->is_in_stock() ) $availability['availability'] = __('En rupture de stock', 'woocommerce');

  return $availability;
}

/*************************************************
## Bacola Theme search only posts
*************************************************/
	// function SearchFilter($query) {
	// 	if ($query->is_search) {
	// 		$query->set('post_type', 'post');
	// 	}
	// 	return $query;
	// }
	// add_filter('pre_get_posts','SearchFilter');

/**
 * Add or modify States
 */
add_filter( 'woocommerce_states', 'custom_woocommerce_states' );

function custom_woocommerce_states( $states ) {


  $states['TN'] = array(
    'TN001' => 'Ariana',
    'TN002' => 'Béja',
    'TN003' => 'Ben Arous',
    'TN004' => 'Bizerte',
    'TN005' => 'Gabès',
    'TN006' => 'Gafsa',
    'TN007' => 'Jendouba',
    'TN008' => 'Kairouan',
    'TN009' => 'Kasserine',
    'TN010' => 'Kebili',
    'TN011' => 'Kef',
    'TN012' => 'Mahdia',
    'TN013' => 'Manouba',
    'TN014' => 'Medenine',
    'TN015' => 'Monastir',
    'TN016' => 'Nabeul',
    'TN017' => 'Sfax',
    'TN018' => 'Sidi Bouzid',
    'TN019' => 'Siliana',
    'TN020' => 'Sousse',
    'TN021' => 'Tataouine',
    'TN022' => 'Tozeur',
    'TN023' => 'Tunis',
    'TN024' => 'Zaghouan'

  );

  return $states;
}

/**
 * Proceed to checkout button
 */
function woocommerce_button_proceed_to_checkout() { ?>
    <a href="<?php echo esc_url( home_url() ); ?>/checkout/" class="checkout-button button alt wc-forward">
    <?php esc_html_e( '	Valider la commande', 'woocommerce' ); ?>
    </a>
    <?php
}
/**
 * Redirection to home
 */

function auto_redirect_external_after_logout(){
    // check if user is leaving from admin
    // is_admin() check would not work here probably as we left the admin already
    if ( false !== strpos( $_SERVER['HTTP_REFERER'], 'wp-admin' ) ){
        wp_redirect( home_url() );
    } else {
        wp_redirect( home_url().'/myaccount/'  );
    }
    //make sure to call exit after redirect
    exit;
}

//execute the code above on logout
add_action( 'wp_logout', 'auto_redirect_external_after_logout');
  /**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function my_hide_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 );