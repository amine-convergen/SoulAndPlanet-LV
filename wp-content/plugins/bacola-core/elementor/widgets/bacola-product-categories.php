<?php

namespace Elementor;

class Bacola_Product_Categories_Widget extends Widget_Base {
    use Bacola_Helper;
	
    public function get_name() {
        return 'bacola-product-categories';
    }
    public function get_title() {
        return 'Product Categories (K)';
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
		
		$this->add_control( 'title',
		[
			'label' => esc_html__( 'Title', 'bacola-core' ),
			'type' => Controls_Manager::TEXT,
			'default' => 'Categories Title',
			'description'=> 'Add a title.',
			'label_block' => true,
		]
		);
		
		$this->add_control( 'subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'bacola-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => 'Do not miss to take a look on our categories.',
				'description'=> 'Add a subtitle.',
				'label_block' => true,
			]
		);
		$this->add_control( 'type',
			[
				'label' => esc_html__( 'Type', 'bacola-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'type1',
				'options' => [
					'select-type' => esc_html__( 'Select Type', 'bacola-core' ),
					'type1'	  => esc_html__( 'Type 1', 'bacola-core' ),
					'type2'	  => esc_html__( 'Type 2', 'bacola-core' ),
				],
			]
		);
		
        $this->add_control( 'cat_filter',
            [
                'label' => esc_html__( 'Exclude Category', 'bacola-core' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->bacola_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s)',
                'default' => 'all',
                'label_block' => true,
            ]
        );
		
		$this->add_control( 'column',
			[
				'label' => esc_html__( 'Column', 'shopwise' ),
				'type' => Controls_Manager::SELECT,
				'default' => '5',
				'options' => [
					'0' => esc_html__( 'Select Column', 'shopwise' ),
					'2' 	  => esc_html__( '2 Columns', 'shopwise' ),
					'3'		  => esc_html__( '3 Columns', 'shopwise' ),
					'4'		  => esc_html__( '4 Columns', 'shopwise' ),
					'5'		  => esc_html__( '5 Columns', 'shopwise' ),
					'6'		  => esc_html__( '6 Columns', 'shopwise' ),
					'7'		  => esc_html__( '7 Columns', 'shopwise' ),
				],
				'condition' => ['type' => 'type2'],
			]
		);

		$this->add_control( 'auto_play',
			[
				'label' => esc_html__( 'Auto Play', 'bacola-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'True', 'bacola-core' ),
				'label_off' => esc_html__( 'False', 'bacola-core' ),
				'return_value' => 'true',
				'default' => 'false',
				'condition' => ['type' => 'type2'],
			]
		);
		
        $this->add_control( 'auto_speed',
            [
                'label' => esc_html__( 'Auto Speed', 'chakta' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => '1600',
                'pleaceholder' => esc_html__( 'Set auto speed.', 'chakta' ),
				'condition' => ['auto_play' => 'true', 'type' => 'type2']
            ]
        );
		
		$this->add_control( 'dots',
			[
				'label' => esc_html__( 'Dots', 'bacola-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'True', 'bacola-core' ),
				'label_off' => esc_html__( 'False', 'bacola-core' ),
				'return_value' => 'true',
				'default' => 'false',
				'condition' => ['type' => 'type2'],
			]
		);
		
		$this->add_control( 'arrows',
			[
				'label' => esc_html__( 'Arrows', 'bacola-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'True', 'bacola-core' ),
				'label_off' => esc_html__( 'False', 'bacola-core' ),
				'return_value' => 'true',
				'default' => 'true',
				'condition' => ['type' => 'type2'],
			]
		);
		
        $this->add_control( 'order',
            [
                'label' => esc_html__( 'Select Order', 'bacola-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => esc_html__( 'Ascending', 'bacola-core' ),
                    'DESC' => esc_html__( 'Descending', 'bacola-core' )
                ],
                'default' => 'ASC'
            ]
        );
		
        $this->add_control( 'orderby',
            [
                'label' => esc_html__( 'Order By', 'bacola-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'id' => esc_html__( 'Post ID', 'bacola-core' ),
                    'menu_order' => esc_html__( 'Menu Order', 'bacola-core' ),
                    'rand' => esc_html__( 'Random', 'bacola-core' ),
                    'date' => esc_html__( 'Date', 'bacola-core' ),
                    'title' => esc_html__( 'Title', 'bacola-core' ),
                ],
                'default' => 'menu_order',
            ]
        );
		
		$this->end_controls_section();


	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if($settings['cat_filter']){
			$terms = get_terms( array(
				'taxonomy' => 'product_cat',
				'hide_empty' => true,
				'parent'    => 0,
				'exclude'   => $settings['cat_filter'],
				'order'          => $settings['order'],
				'orderby'        => $settings['orderby']
			) );
		} else {
			$terms = get_terms( array(
				'taxonomy' => 'product_cat',
				'hide_empty' => true,
				'parent'    => 0,
				'order'          => $settings['order'],
				'orderby'        => $settings['orderby']
			) );
		}
	
		if($settings['type'] == 'type2'){
			echo '<div class="site-module module-category style-2">';
			echo '<div class="module-header">';
			echo '<div class="column">';
			echo '<h4 class="entry-title">'.esc_html($settings['title']).'</h4>';
			echo '<div class="entry-description">'.esc_html($settings['subtitle']).'</div>';
			echo '</div>';
			echo '</div>';
			echo '<div class="module-body">';
			echo '<div class="slider-wrapper">';
			echo '<svg class="preloader" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
			echo '<div class="site-slider categories" data-slideshow="'.esc_attr($settings['column']).'" data-slidespeed="1200" data-arrows="'.esc_attr($settings['arrows']).'" data-dots="'.esc_attr($settings['dots']).'" data-autoplay="'.esc_attr($settings['auto_play']).'" data-autospeed="'.esc_attr($settings['auto_speed']).'">';
			
			foreach ( $terms as $term ) {
				$term_data = get_option('taxonomy_'.$term->term_id);
				$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );
				$term_children = get_term_children( $term->term_id, 'product_cat' );

				echo '<div class="category '.esc_html($term->slug).'">';
				if($image){
				echo '<div class="category-image">';
				echo '<a href="'.esc_url(get_term_link( $term )).'"><img src="'.esc_url($image).'" alt="'.esc_attr($term->name).'"></a>';
				echo '</div>';
				}
				echo '<div class="category-detail">';
				echo '<h3 class="entry-category"><a href="'.esc_url(get_term_link( $term )).'">'.esc_html($term->name).'</a></h3>';
				echo '<div class="category-count test">'.esc_html($term->count).' '.esc_html__('Produits','bacola-core').'</div>';
				echo '</div>';
				echo '</div>';
			}
		
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			
		} else {
			
			echo '<div class="site-module module-category style-1">';
			echo '<div class="module-header">';
			echo '<div class="column">';
			echo '<h4 class="entry-title">'.esc_html($settings['title']).'</h4>';
			echo '<div class="entry-description">'.esc_html($settings['subtitle']).'</div>';
			echo '</div>';
			echo '</div>';
			echo '<div class="module-body">';
			echo '<div class="categories">';
			
			$count = 1;
			foreach ( $terms as $term ) {
				$term_data = get_option('taxonomy_'.$term->term_id);
				$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );
				$term_children = get_term_children( $term->term_id, 'product_cat' );
				
				
				if($count == 1 ){
					echo '<div class="first">';
					echo '<div class="category">';
					if($image){
					echo '<div class="category-image">';
					echo '<a href="'.esc_url(get_term_link( $term )).'"><img src="'.esc_url($image).'" alt="'.esc_attr($term->name).'"></a>';
					echo '</div>';
					}
					echo '<div class="category-detail">';
					echo '<h3 class="entry-category"><a href="'.esc_url(get_term_link( $term )).'">'.esc_html($term->name).'</a></h3>';
					echo '<div class="category-count">'.esc_html($term->count).' '.esc_html__('Produits','bacola-core').'</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				} else {
					if($count == 2){
					echo '<div class="categories-wrapper">';
					}
					echo '<div class="category">';
					if($image){
					echo '<div class="category-image">';
					echo '<a href="'.esc_url(get_term_link( $term )).'"><img src="'.esc_url($image).'" alt="'.esc_attr($term->name).'"></a>';
					echo '</div>';
					}
					echo '<div class="category-detail">';
					echo '<h3 class="entry-category"><a href="'.esc_url(get_term_link( $term )).'">'.esc_html($term->name).'</a></h3>';
					echo '<div class="category-count">'.esc_html($term->count).' '.esc_html__('Produits','bacola-core').'</div>';
					echo '</div>';
					echo '</div>';
				}

				$count++;
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		
	}

}
