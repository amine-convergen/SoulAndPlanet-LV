<?php

namespace Elementor;

class Bacola_Product_Categories_Box extends Widget_Base {
    use Bacola_Helper;
	
    public function get_name() {
        return 'bacola-product-categories-box';
    }
    public function get_title() {
        return 'Product Categories Box (K)';
    }
    public function get_icon() {
        return 'eicon-slider-push';
    }
    public function get_categories() {
        return [ 'bacola' ];
    }

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'bacola-core' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		
        $this->add_control( 'cat_filter',
            [
                'label' => esc_html__( 'The menu category', 'bacola-core' ),
                'description' => 'The menu category is selected by default you can change on the menus generator.',
            ]
        );



		$this->end_controls_section();


	}

	protected function render() {
		$settings = $this->get_settings_for_display();
	// 	$output = ' ' ;
		
	// 		$output .= '<div class="all-categories locked">';

	// 			$output .= '<div class="dropdown-categories id="all-categories">';

	// 			$output .= 	wp_nav_menu(array(
	// 				'theme_location' => 'sidebar-menu',
	// 				'container' => '',
	// 				'fallback_cb' => 'show_top_menu',
	// 				'menu_id' => '',
	// 				'menu_class' => 'menu-list',
	// 				'echo' => true,
	// 				"walker" => '',
	// 				'link_after' => get_post_meta($item->object_id, '_menu_item_desc'),
	// 				'depth' => 0
	// 				));

	// 			$output .= '</div>';

	// 			$output .= '</div>';


	// echo $output;

	echo '<ul  class="menu-list">';
    $menu_name = 'sidebar-menu';
    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
        $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
        $menu_items = wp_get_nav_menu_items($menu->term_id);
        foreach ( (array) $menu_items as $key => $menu_item ) {
            // at this point you can get the custom meta from the page
            $image_description = get_post_meta($menu_item->object_id );
			$a_class = $menu_item->classes[0];
            // here we are getting the title and URL to the page
            $title = $menu_item->title;
            $url = $menu_item->url;
            $slug = basename($menu_item->url);
            // this allows us to add a current class
			$str = get_attachment_url_by_title($a_class);
			$delimiter = ' ';
			$words = explode($delimiter, $str);
			$i = 0;
			$str1 = '//';
			$str2 = '/';
			$all_String ='';
		   foreach ($words as $word) {
		   if ($i < 1) {
			   $all_String .=  $word . $str1;
			   $i = $i + 1;
			}
			else {
			 $all_String .=  $word . $str2;
			}
		   }
			
            if (basename($_SERVER['REQUEST_URI']) == $slug) { $current = ' current-menu-item'; } else { $current = ''; }
            $menu_list .= '<li class="menu-item menu-item-type-taxonomy menu-item-object-product_tag hover-li ' . $a_class .' go-'.get_post_meta($item->object_id, '_custom_menu_meta') .' '.$menu_item->object_id.$current.'"><a class="link-icon"href="' . $url . '">';
			if (($a_class == false)) {
				$menu_list .= ' ' ;
			}
			else {
				$menu_list .= '<img src="'. get_attachment_url_by_title($a_class) .'" class="tags-icon-col"/>' ;
			}
			  $menu_list .=   '<span class="text-icon">'. $title . '</span></a></li>';
        }
    }
    echo $menu_list;

 echo '</ul>';


	}




}
