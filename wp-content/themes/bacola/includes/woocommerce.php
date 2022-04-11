<?php

/*************************************************
## Woocommerce 
*************************************************/

function bacola_product_image(){
	if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
		$att=get_post_thumbnail_id();
		$image_src = wp_get_attachment_image_src( $att, 'full' );
		$image_src = $image_src[0];

		$size = get_theme_mod( 'bacola_product_image_size', array( 'width' => '', 'height' => '') );

		if($size['width'] && $size['height']){
			$image = bacola_resize( $image_src, $size['width'], $size['height'], true, true, true );  
		} else {
			$image = $image_src;
		}
		
		return esc_url($image);
	} else {
		return wc_placeholder_img_src('');
	}
}

function bacola_product_second_image(){
	global $product;
	
	$product_image_ids = $product->get_gallery_image_ids();
	
	if($product_image_ids){
		$gallery_count = 1;
		foreach( $product_image_ids as $product_image_id ){
			if($gallery_count == 1){
				$image_url = wp_get_attachment_url( $product_image_id );
				return esc_url($image_url);
			}
			$gallery_count++;
		}
	}
}

function bacola_sale_percentage(){
	global $product;
	$stock_status = $product->get_stock_status();
	$managestock = $product->managing_stock();
	$stock_quantity = $product->get_stock_quantity();

	$output = '';

	$badge = get_post_meta( get_the_ID(), 'klb_product_badge', true );
	$badge_type = get_post_meta( get_the_ID(), 'klb_product_badge_type', true );

	if($badge_type == 'type5'){
		$badgestyle = 'type-5';
	} elseif($badge_type == 'type4'){
		$badgestyle = 'type-4 ';
	} elseif($badge_type == 'type3'){
		$badgestyle = 'type-3';
	} elseif($badge_type == 'type2'){
		$badgestyle = 'style-1 recommend';
	} else {
		$badgestyle = 'style-2 organic';
	}

	if ( $product->is_on_sale() && $product->is_type( 'variable' ) ) {
		$percentage = ceil(100 - ($product->get_variation_sale_price() / $product->get_variation_regular_price( 'min' )) * 100);
		$output .= '<div class="product-badges"><span class="badge style-1 onsale">'.$percentage.'%</span>';
		$output .='</div>';
	} elseif( $product->is_on_sale() && $product->get_regular_price()  && !$product->is_type( 'grouped' )) {
		$percentage = ceil(100 - ($product->get_sale_price() / $product->get_regular_price()) * 100);
		$output .= '<div class="product-badges">';
		$output .= '<span class="badge style-1 onsale">'.$percentage.'%</span>';
		if($managestock && $stock_quantity == 1) {

			$output .= '<span class="badge style-1 last">Derniers articles</span>';

		}

		if($badge){
		$output .= '<span class="badge '.esc_attr($badgestyle).'test">'.esc_html($badge).'</span>';
		if($managestock && $stock_quantity == 1) {


		}
		}
		$output .= '</div>';
	}
	
	return $output;

}


function bacola_single_product_header(){
	global $product;
	
	?>
	<div class="product-header">
		<?php do_action('bacola_single_title'); ?>

		<div class="product-meta top">
				
			<div class="product-brand">
				<?php wc_display_product_attributes( $product ); ?>
			</div>

			<?php do_action('bacola_single_rating'); ?>
				
			<?php if($product->get_sku()){ ?>
			<div class="sku-wrapper">
				<span><?php esc_html_e('SKU:','bacola'); ?></span>
				<span class="sku"><?php echo esc_html($product->get_sku()); ?></span>
			</div>
			<?php } ?>

			<?php if(bacola_vendor_name()){ ?>
				<div class="store-info">
					<span><?php esc_html_e('Store:','bacola'); ?><?php echo bacola_vendor_name(); ?></span>
				</div>
			<?php } ?>

		</div><!-- product-meta -->

	</div><!-- product-header -->
	<?php
}

if(get_theme_mod('bacola_single_type') == 'type3' || get_theme_mod('bacola_single_type') == 'type4' || bacola_get_option() == 'type3' || bacola_get_option() == 'type4'){
	add_action('bacola_single_header_side','bacola_single_product_header');
} else {
	add_action('bacola_single_header_top','bacola_single_product_header');
}


function bacola_vendor_name(){
	if (function_exists('get_wcmp_vendor_settings')) {
		global $post;
		$vendor = get_wcmp_product_vendors($post->ID);
		if (isset($vendor->page_title)) {
			$store_name = $vendor->page_title;
			return '<a href="'.esc_url($vendor->permalink).'">'.esc_html($store_name).'</a>';
		}
	}elseif(class_exists('WeDevs_Dokan')){
		// Get the author ID (the vendor ID)
		$vendor_id = get_post_field( 'post_author', get_the_id() );

		$store_info  = dokan_get_store_info( $vendor_id ); // Get the store data
		$store_name  = $store_info['store_name'];          // Get the store name
		$store_url   = dokan_get_store_url( $vendor_id );  // Get the store URL

		if (isset($store_name)) {
			return '<a href="'.esc_url($store_url).'">'.esc_html($store_name).'</a>';
		}
	}

}

if ( class_exists( 'woocommerce' ) ) {
add_theme_support( 'woocommerce' );
add_image_size('bacola-woo-product', 450, 450, true);

// Remove woocommerce defauly styles
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

// hide default shop title anasayfadaki title gizlemek için
add_filter('woocommerce_show_page_title', 'bacola_override_page_title');
function bacola_override_page_title() {
return false;
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 ); /*remove result count above products*/
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 ); //remove rating
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 ); //remove rating
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title',10);

add_action( 'woocommerce_before_shop_loop_item', 'bacola_shop_thumbnail', 10);
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 ); /*remove breadcrumb*/


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products',20);
remove_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products',10);
add_action( 'woocommerce_after_single_product_summary', 'bacola_related_products', 20);
function bacola_related_products(){
	$related_column = get_theme_mod('bacola_shop_related_post_column') ? get_theme_mod('bacola_shop_related_post_column') : '4';
    woocommerce_related_products( array('posts_per_page' => $related_column, 'columns' => $related_column));
}

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 20);
add_filter( 'woocommerce_cross_sells_columns', 'bacola_change_cross_sells_columns' );
function bacola_change_cross_sells_columns( $columns ) {
	return 4;
}

//Remove Single Title
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title',5);
add_action( 'bacola_single_title', 'woocommerce_template_single_title',5);
//Remove Single Rating
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating',10);
add_action( 'bacola_single_rating', 'woocommerce_template_single_rating',10);
//Remove Is Empty Cart Message
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'klb_cart_is_empty', 'wc_empty_cart_message', 10 );


/*************************************************
## Item Quantity 
*************************************************/
function bacola_item_quantity_in_cart($product_id) {
	if ( isset(WC()->cart)) {
		foreach( WC()->cart->get_cart() as $cart_item ) {
			
			if ( $cart_item['product_id'] === $product_id ){
				return $cart_item['quantity'];
				
			}

		}
	}
}

/*************************************************
## Cart Button with Quantity Box
*************************************************/
function bacola_cart_with_quantity($id){
	global $product;

	$step_quantity = function_exists('bacola_step_quantity') ? bacola_step_quantity($product) : '1';
	$min_quantity = function_exists('bacola_min_quantity') ? bacola_min_quantity($product) : '';
	$max_quantity = function_exists('bacola_max_quantity') ? bacola_max_quantity($product) : '';

	$in_cart_class = bacola_item_quantity_in_cart($id) ? 'product-in-cart' : '';
	$in_cart_value = bacola_item_quantity_in_cart($id) ? bacola_item_quantity_in_cart($id) : '1';
	
	$output = '';
	
	$output .= '<div class="product-button-group cart-with-quantity '.esc_attr($in_cart_class).'">';
	
	$output .= '<div class="quantity ajax-quantity">';
	$output .= '<div class="quantity-button minus"><i class="klbth-icon-minus"></i></div>';
	$output .= '<input type="text" class="input-text qty text" name="quantity" step="'.esc_attr($step_quantity).'" min="'.esc_attr($min_quantity).'" max="'.esc_attr($max_quantity).'" value="'.esc_attr($in_cart_value).'" title="Menge" size="4" inputmode="numeric">';
	$output .= '<div class="quantity-button plus"><i class="klbth-icon-plus"></i></div>';
	$output .= '</div><!-- quantity -->';
	
	$output .= bacola_add_to_cart_button();

	$output .= '</div>';
	
	return $output;
}

/*----------------------------
  Product Type 1
 ----------------------------*/
function bacola_product_type1(){
	global $product;
	global $post;
	global $woocommerce;
	
	$output = '';
	
	$id = get_the_ID();
	$allproduct = wc_get_product( get_the_ID() );

	$cart_url = wc_get_cart_url();
	$price = $allproduct->get_price_html();
	$weight = $product->get_weight();
	$stock_status = $product->get_stock_status();
	$stock_text = $product->get_availability();
	$rating = wc_get_rating_html($product->get_average_rating());
	$ratingcount = $product->get_review_count();
	$wishlist = get_theme_mod( 'bacola_wishlist_button', '0' );
	$compare = get_theme_mod( 'bacola_compare_button', '0' );
	$quickview = get_theme_mod( 'bacola_quick_view_button', '0' );

	$postview  = isset( $_POST['shop_view'] ) ? $_POST['shop_view'] : '';

	if(bacola_shop_view() == 'list_view' || $postview == 'list_view') {
		
		$output .= '<div class="klb-product-list product-content">';
		$output .= '<div class="row klb-product">';
		$output .= '<div class="col-xl-4 col-lg-4 ">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= bacola_sale_percentage();
		$output .= '<a href="'.get_permalink().'">';
		$output .= '<img src="'.bacola_product_image().'" alt="'.the_title_attribute( 'echo=0' ).'">';
		$output .= '</a>';
		$output .= '<div class="product-buttons">';
		if($quickview == '1'){
		$output .= '<a href="'.$product->get_id().'" class="detail-bnt quick-view-button">';
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100" viewBox="0 0 100 100"><image id="search-interface-symbol_1_" data-name="search-interface-symbol (1)" x="2" y="2" width="96" height="96" xlink:href="data:img/png;base64,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"/></svg>';
		$output .= '</a>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div><!--col-xl-4 col-lg-4-->';
		
		$output .= '<div class="col-xl-8 col-lg-8">';
		$output .= '<div class="content-wrapper">';
                      
		$output .= '<h3 class="product-title"><a href="'.get_permalink().'" title="'.the_title_attribute( 'echo=0' ).'">'.get_the_title().'</a></h3>';
		$output .= '<div class="product-meta">';
		// if($weight){
		// $output .= '<div class="product-unit"> '.$weight.' '.get_option('woocommerce_weight_unit').'</div>';
		// }
		if($stock_status == 'instock'){
		$output .= '<div class="product-available in-stock">'.$stock_text['availability'].'</div>';
		} else {
		$output .= '<div class="product-available outof-stock">'.$stock_text['availability'].'</div>';
		}
		$output .= '</div>';
		// if($ratingcount){
		// $output .= '<div class="product-rating">';
		// $output .= $rating;
		// $output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
		// $output .= '</div>';
		// }
		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span>';
		ob_start();
		do_action('listview-wishlist-compare');
		$output .= ob_get_clean();
		
		if(get_theme_mod('bacola_quantity_box',0) == 1){
		$output .= bacola_cart_with_quantity($id);
		} else {
		$output .= '<div class="product-button-group">';
		$output .= bacola_add_to_cart_button();
		$output .= '</div>';
		}

		$output .= '</div>';
		$output .= '</div><!--col-xl-8 col-lg-8-->';
		
		
		$output .= '</div>';
		$output .= '</div>';
	} else {
		
		$output .= '<div class="product-wrapper product-type-1">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= bacola_sale_percentage();
		$output .= '<a href="'.get_permalink().'">';
		$output .= '<img src="'.bacola_product_image().'" alt="'.the_title_attribute( 'echo=0' ).'">';
		$output .= '</a>';
		$output .= '<div class="product-buttons">';
		if($quickview == '1'){
		$output .= '<a href="'.$product->get_id().'" class="detail-bnt quick-view-button">';
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100" viewBox="0 0 100 100"><image id="search-interface-symbol_1_" data-name="search-interface-symbol (1)" x="2" y="2" width="96" height="96" xlink:href="data:img/png;base64,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"/></svg>';
		$output .= '</a>';
		}
		if($wishlist == '1'){
		$output .= do_shortcode('[ti_wishlists_addtowishlist]');
		}

		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div class="content-wrapper">';

		$output .= '<h3 class="product-title"><a href="'.get_permalink().'" title="'.the_title_attribute( 'echo=0' ).'">'.get_the_title().'</a></h3>';
		$output .= '<div class="product-meta">';
		// if($weight){
		// $output .= '<div class="product-unit"> '.$weight.' '.get_option('woocommerce_weight_unit').'</div>';
		// }
		if($stock_status == 'instock'){
		$output .= '<div class="product-available in-stock">'.$stock_text['availability'].'</div>';
		} else {
		$output .= '<div class="product-available outof-stock">'.$stock_text['availability'].'</div>';
		}
		$output .= '</div>';

		// if(bacola_vendor_name()){
		// 	$review_class = $ratingcount ? 'has-rating' : 'no-rating';

		// 	$output .= '<div class="product-switcher '.esc_attr($review_class).'">';
		// 	$output .= '<div class="switcher-wrapper">';
		// 	$output .= '<div class="store-info fade-block">';
		// 	$output .= 'Store:'.bacola_sanitize_data(bacola_vendor_name());
		// 	$output .= '</div><!-- store-info -->';
		// 	if($ratingcount){
		// 	$output .= '<div class="product-rating">';
		// 	$output .= $rating;
		// 	$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
		// 	$output .= '</div>';
		// 	}
		// 	$output .= '</div><!-- switcher-wrapper -->';
		// 	$output .= '</div><!-- product-switcher -->';
		// } else {
		// 	if($ratingcount){
		// 	$output .= '<div class="product-rating">';
		// 	$output .= $rating;
		// 	$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
		// 	$output .= '</div>';
		// 	}
		// }

		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span>';
		$output .= '<div class="product-fade-block">';
		
		if(get_theme_mod('bacola_quantity_box',0) == 1){
		$output .= bacola_cart_with_quantity($id);
		} else {
		$output .= '<div class="product-button-group">';
		$output .= bacola_add_to_cart_button();
		$output .= '</div>';
		}

		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div class="product-content-fade border-info"></div>';

	}
	
	return $output;
}

/*----------------------------
  Product Type 2
 ----------------------------*/
function bacola_product_type2(){
	global $product;
	global $post;
	global $woocommerce;
	
	$output = '';
	
	$id = get_the_ID();
	$allproduct = wc_get_product( get_the_ID() );

	$cart_url = wc_get_cart_url();
	$price = $allproduct->get_price_html();
	$weight = $product->get_weight();
	$stock_status = $product->get_stock_status();
	$stock_text = $product->get_availability();
	$rating = wc_get_rating_html($product->get_average_rating());
	$ratingcount = $product->get_review_count();
	$wishlist = get_theme_mod( 'bacola_wishlist_button', '0' );
	$compare = get_theme_mod( 'bacola_compare_button', '0' );
	$quickview = get_theme_mod( 'bacola_quick_view_button', '0' );

	
	if(bacola_shop_view() == 'list_view') {
		$output .= '<div class="klb-product-list product-content">';
		$output .= '<div class="row klb-product">';
		$output .= '<div class="col-xl-4 col-lg-4 ">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= bacola_sale_percentage();
		$output .= '<a href="'.get_permalink().'">';
		$output .= '<img src="'.bacola_product_image().'" alt="'.the_title_attribute( 'echo=0' ).'">';
		$output .= '</a>';
		$output .= '<div class="product-buttons">';
		if($quickview == '1'){
		$output .= '<a href="'.$product->get_id().'" class="detail-bnt quick-view-button">';
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100" viewBox="0 0 100 100"><image id="search-interface-symbol_1_" data-name="search-interface-symbol (1)" x="2" y="2" width="96" height="96" xlink:href="data:img/png;base64,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"/></svg>';
		$output .= '</a>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div><!--col-xl-4 col-lg-4-->';

		$output .= '<div class="col-xl-8 col-lg-8">';
		$output .= '<div class="content-wrapper">';

		$output .= '<h3 class="product-title"><a href="'.get_permalink().'" title="'.the_title_attribute( 'echo=0' ).'">'.get_the_title().'</a></h3>';
		$output .= '<div class="product-meta">';
		// if($weight){
		// $output .= '<div class="product-unit"> '.$weight.' '.get_option('woocommerce_weight_unit').'</div>';
		// }
		if($stock_status == 'instock'){
		$output .= '<div class="product-available in-stock">'.$stock_text['availability'].'</div>';
		} else {
		$output .= '<div class="product-available outof-stock">'.$stock_text['availability'].'</div>';
		}
		$output .= '</div>';
		// if($ratingcount){
		// $output .= '<div class="product-rating">';
		// $output .= $rating;
		// $output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
		// $output .= '</div>';
		// }
		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span>';
		ob_start();
		do_action('listview-wishlist-compare');
		$output .= ob_get_clean();
		
		if(get_theme_mod('bacola_quantity_box',0) == 1){
		$output .= bacola_cart_with_quantity($id);
		} else {
		$output .= '<div class="product-button-group">';
		$output .= bacola_add_to_cart_button();
		$output .= '</div>';
		}

		$output .= '</div>';
		$output .= '</div><!--col-xl-8 col-lg-8-->';


		$output .= '</div>';
		$output .= '</div>';
	} else {

		$output .= '<div class="product-wrapper product-type-2">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= bacola_sale_percentage();
		$output .= '<a href="'.get_permalink().'">';
		$output .= '<img src="'.bacola_product_image().'" alt="'.the_title_attribute( 'echo=0' ).'">';
		$output .= '</a>';
		$output .= '<div class="product-buttons">';
		if($quickview == '1'){
		$output .= '<a href="'.$product->get_id().'" class="detail-bnt quick-view-button">';
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100" viewBox="0 0 100 100"><image id="search-interface-symbol_1_" data-name="search-interface-symbol (1)" x="2" y="2" width="96" height="96" xlink:href="data:img/png;base64,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"/></svg>';
		$output .= '</a>';
		}
		if($wishlist == '1'){
		$output .= do_shortcode('[ti_wishlists_addtowishlist]');
		}

		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div class="content-wrapper">';

		$output .= '<h3 class="product-title"><a href="'.get_permalink().'" title="'.the_title_attribute( 'echo=0' ).'">'.get_the_title().'</a></h3>';
		$output .= '<div class="product-meta">';
		if($weight){
		$output .= '<div class="product-unit"> '.$weight.' '.get_option('woocommerce_weight_unit').'</div>';
		}
		if($stock_status == 'instock'){
		$output .= '<div class="product-available in-stock">'.$stock_text['availability'].'</div>';
		} else {
		$output .= '<div class="product-available outof-stock">'.$stock_text['availability'].'</div>';
		}
		$output .= '</div>';

		if(bacola_vendor_name()){
			$review_class = $ratingcount ? 'has-rating' : 'no-rating';

			$output .= '<div class="product-switcher '.esc_attr($review_class).'">';
			$output .= '<div class="switcher-wrapper">';
			$output .= '<div class="store-info fade-block">';
			$output .= 'Store:'.bacola_sanitize_data(bacola_vendor_name());
			$output .= '</div><!-- store-info -->';
			if($ratingcount){
			$output .= '<div class="product-rating">';
			$output .= $rating;
			$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
			$output .= '</div>';
			}
			$output .= '</div><!-- switcher-wrapper -->';
			$output .= '</div><!-- product-switcher -->';
		} else {
			if($ratingcount){
			$output .= '<div class="product-rating">';
			$output .= $rating;
			$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
			$output .= '</div>';
			}
		}

		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span>';
		
		if(get_theme_mod('bacola_quantity_box',0) == 1){
		$output .= bacola_cart_with_quantity($id);
		} else {
		$output .= '<div class="product-button-group">';
		$output .= bacola_add_to_cart_button();
		$output .= '</div>';
		}
		
		$output .= '</div>';
		$output .= '</div>';


	}

	
	return $output;
}

/*----------------------------
  Product Type 3
 ----------------------------*/
function bacola_product_type3(){
	global $product;
	global $post;
	global $woocommerce;
	
	$output = '';
	
	$id = get_the_ID();
	$allproduct = wc_get_product( get_the_ID() );

	$cart_url = wc_get_cart_url();
	$price = $allproduct->get_price_html();
	$weight = $product->get_weight();
	$stock_status = $product->get_stock_status();
	$stock_text = $product->get_availability();
	$rating = wc_get_rating_html($product->get_average_rating());
	$ratingcount = $product->get_review_count();
	$wishlist = get_theme_mod( 'bacola_wishlist_button', '0' );
	$compare = get_theme_mod( 'bacola_compare_button', '0' );
	$quickview = get_theme_mod( 'bacola_quick_view_button', '0' );
	$managestock = $product->managing_stock();
	$total_sales = $product->get_total_sales();
	$stock_quantity = $product->get_stock_quantity();
			
	if($managestock && $stock_quantity > 0) {
	$progress_percentage = floor($total_sales / (($total_sales + $stock_quantity) / 100)); // yuvarlama
	}
	
	if(bacola_shop_view() == 'list_view') {
		$output .= '<div class="klb-product-list product-content">';
		$output .= '<div class="row klb-product">';
		$output .= '<div class="col-xl-4 col-lg-4 ">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= bacola_sale_percentage();
		$output .= '<a href="'.get_permalink().'">';
		$output .= '<img src="'.bacola_product_image().'" alt="'.the_title_attribute( 'echo=0' ).'">';
		$output .= '</a>';
		$output .= '<div class="product-buttons">';
		if($quickview == '1'){
		$output .= '<a href="'.$product->get_id().'" class="detail-bnt quick-view-button">';
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100" viewBox="0 0 100 100"><image id="search-interface-symbol_1_" data-name="search-interface-symbol (1)" x="2" y="2" width="96" height="96" xlink:href="data:img/png;base64,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"/></svg>';
		$output .= '</a>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div><!--col-xl-4 col-lg-4-->';

		$output .= '<div class="col-xl-8 col-lg-8">';
		$output .= '<div class="content-wrapper">';

		$output .= '<h3 class="product-title"><a href="'.get_permalink().'" title="'.the_title_attribute( 'echo=0' ).'">'.get_the_title().'</a></h3>';
		$output .= '<div class="product-meta">';
		if($weight){
		$output .= '<div class="product-unit"> '.$weight.' '.get_option('woocommerce_weight_unit').'</div>';
		}
		if($stock_status == 'instock'){
		$output .= '<div class="product-available in-stock">'.$stock_text['availability'].'</div>';
		} else {
		$output .= '<div class="product-available outof-stock">'.$stock_text['availability'].'</div>';
		}
		$output .= '</div>';
		// if($ratingcount){
		// $output .= '<div class="product-rating">';
		// $output .= $rating;
		// $output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
		// $output .= '</div>';
		// }
		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span>';
		ob_start();
		do_action('listview-wishlist-compare');
		$output .= ob_get_clean();

		if(get_theme_mod('bacola_quantity_box',0) == 1){
		$output .= bacola_cart_with_quantity($id);
		} else {
		$output .= '<div class="product-button-group">';
		$output .= bacola_add_to_cart_button();
		$output .= '</div>';
		}

		$output .= '</div>';
		$output .= '</div><!--col-xl-8 col-lg-8-->';

		$output .= '</div>';
		$output .= '</div>';
	} else {

		$output .= '<div class="product-wrapper product-type-3">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= bacola_sale_percentage();
		$output .= '<a href="'.get_permalink().'">';
		$output .= '<img src="'.bacola_product_image().'" alt="'.the_title_attribute( 'echo=0' ).'">';
		$output .= '</a>';
		$output .= '<div class="product-buttons">';
		if($quickview == '1'){
		$output .= '<a href="'.$product->get_id().'" class="detail-bnt quick-view-button">';
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100" viewBox="0 0 100 100"><image id="search-interface-symbol_1_" data-name="search-interface-symbol (1)" x="2" y="2" width="96" height="96" xlink:href="data:img/png;base64,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"/></svg>';
		$output .= '</a>';
		}
		if($wishlist == '1'){
		$output .= do_shortcode('[ti_wishlists_addtowishlist]');
		}
		$output .= '</div><!-- product-buttons -->';
		$output .= '</div><!-- humbnail-wrapper -->';
		$output .= '<div class="content-wrapper">';
		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span>';
		$output .= '<h3 class="product-title"><a href="'.get_permalink().'" title="'.the_title_attribute( 'echo=0' ).'">'.get_the_title().'</a></h3>';
		$output .= '<div class="product-meta">';
		// if($weight){
		// $output .= '<div class="product-unit"> '.$weight.' '.get_option('woocommerce_weight_unit').'</div>';
		// }
		if($stock_status == 'instock'){
		$output .= '<div class="product-available in-stock">'.$stock_text['availability'].'</div>';
		} else {
		$output .= '<div class="product-available outof-stock">'.$stock_text['availability'].'</div>';
		}
		$output .= '</div><!-- product-meta -->';

		// if(bacola_vendor_name()){
		// 	$review_class = $ratingcount ? 'has-rating' : 'no-rating';
		// 	$output .= '<div class="product-switcher '.esc_attr($review_class).'">';
		// 	$output .= '<div class="switcher-wrapper">';
		// 	$output .= '<div class="store-info fade-block">';
		// 	$output .= 'Store:'.bacola_sanitize_data(bacola_vendor_name());
		// 	$output .= '</div><!-- store-info -->';
		// 	if($ratingcount){
		// 	$output .= '<div class="product-rating">';
		// 	$output .= $rating;
		// 	$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
		// 	$output .= '</div>';
		// 	}
		// 	$output .= '</div><!-- switcher-wrapper -->';
		// 	$output .= '</div><!-- product-switcher -->';
		// } else {
		// 	if($ratingcount){
		// 	$output .= '<div class="product-rating">';
		// 	$output .= $rating;
		// 	$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
		// 	$output .= '</div>';
		// 	}
		// }

		// if($managestock && $stock_quantity == 1) {
		// $output .= '<div class="product-count">';
		// // $output .= '<div class="product-progress">';
		// // $output .= '<span class="progress" style="width: '.esc_attr($progress_percentage).'%;"></span>';
		// // $output .= '</div><!-- product-progress -->';
		// $output .= '<div class="product-pcs">';
		// $output .= esc_html__('Dernier article en stock','bacola');//.' <span>'.esc_html($stock_quantity).'</span>';
		// $output .= '</div><!-- product-pcs -->';
		// $output .= '</div><!-- product-count -->';
		// }
		
		$output .= '<div class="product-fade-block">';
		
		if(get_theme_mod('bacola_quantity_box',0) == 1){
		$output .= bacola_cart_with_quantity($id);
		} else {
		$output .= '<div class="product-button-group">';
		$output .= bacola_add_to_cart_button();
		$output .= '</div>';
		}
		
		$output .= '</div><!-- product-fade-block -->';
		$output .= '</div><!-- content-wrapper -->';
		$output .= '</div><!-- product-wrapper -->';
		$output .= '<div class="product-content-fade border-info"></div>';
	}

	
	return $output;
}

/*----------------------------
  Product Type 4
 ----------------------------*/
function bacola_product_type4(){
	global $product;
	global $post;
	global $woocommerce;
	
	$output = '';
	
	$id = get_the_ID();
	$allproduct = wc_get_product( get_the_ID() );

	$cart_url = wc_get_cart_url();
	$price = $allproduct->get_price_html();
	$weight = $product->get_weight();
	$stock_status = $product->get_stock_status();
	$stock_text = $product->get_availability();
	$rating = wc_get_rating_html($product->get_average_rating());
	$ratingcount = $product->get_review_count();
	$wishlist = get_theme_mod( 'bacola_wishlist_button', '0' );
	$compare = get_theme_mod( 'bacola_compare_button', '0' );
	$quickview = get_theme_mod( 'bacola_quick_view_button', '0' );

	
	if(bacola_shop_view() == 'list_view') {
		$output .= '<div class="klb-product-list product-content">';
		$output .= '<div class="row klb-product">';
		$output .= '<div class="col-xl-4 col-lg-4 ">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= bacola_sale_percentage();
		$output .= '<a href="'.get_permalink().'">';
		$output .= '<img src="'.bacola_product_image().'" alt="'.the_title_attribute( 'echo=0' ).'">';
		$output .= '</a>';
		$output .= '<div class="product-buttons">';
		if($quickview == '1'){
		$output .= '<a href="'.$product->get_id().'" class="detail-bnt quick-view-button">';
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100" viewBox="0 0 100 100"><image id="search-interface-symbol_1_" data-name="search-interface-symbol (1)" x="2" y="2" width="96" height="96" xlink:href="data:img/png;base64,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"/></svg>';
		$output .= '</a>';
		}
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div><!--col-xl-4 col-lg-4-->';

		$output .= '<div class="col-xl-8 col-lg-8">';
		$output .= '<div class="content-wrapper">';

		$output .= '<h3 class="product-title"><a href="'.get_permalink().'" title="'.the_title_attribute( 'echo=0' ).'">'.get_the_title().'</a></h3>';
		$output .= '<div class="product-meta">';
		if($weight){
		$output .= '<div class="product-unit"> '.$weight.' '.get_option('woocommerce_weight_unit').'</div>';
		}
		if($stock_status == 'instock'){
		$output .= '<div class="product-available in-stock">'.$stock_text['availability'].'</div>';
		} else {
		$output .= '<div class="product-available outof-stock">'.$stock_text['availability'].'</div>';
		}
		$output .= '</div>';

		if(bacola_vendor_name()){
			$review_class = $ratingcount ? 'has-rating' : 'no-rating';

			$output .= '<div class="product-switcher '.esc_attr($review_class).'">';
			$output .= '<div class="switcher-wrapper">';
			$output .= '<div class="store-info fade-block">';
			$output .= 'Store:'.bacola_sanitize_data(bacola_vendor_name());
			$output .= '</div><!-- store-info -->';
			if($ratingcount){
			$output .= '<div class="product-rating">';
			$output .= $rating;
			$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
			$output .= '</div>';
			}
			$output .= '</div><!-- switcher-wrapper -->';
			$output .= '</div><!-- product-switcher -->';
		} else {
			if($ratingcount){
			$output .= '<div class="product-rating">';
			$output .= $rating;
			$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
			$output .= '</div>';
			}
		}

		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span>';
		ob_start();
		do_action('listview-wishlist-compare');
		$output .= ob_get_clean();

		if(get_theme_mod('bacola_quantity_box',0) == 1){
		$output .= bacola_cart_with_quantity($id);
		} else {
		$output .= '<div class="product-button-group">';
		$output .= bacola_add_to_cart_button();
		$output .= '</div>';
		}

		$output .= '</div>';
		$output .= '</div><!--col-xl-8 col-lg-8-->';


		$output .= '</div>';
		$output .= '</div>';
	} else {

		$output .= '<div class="product-wrapper product-type-4 test-boom">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= bacola_sale_percentage();
		$output .= '<a href="'.get_permalink().'">';
		$output .= '<img src="'.bacola_product_image().'" alt="'.the_title_attribute( 'echo=0' ).'">';
		$output .= '</a>';
		$output .= '<div class="product-buttons">';
		if($quickview == '1'){
		$output .= '<a href="'.$product->get_id().'" class="detail-bnt quick-view-button">';
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100" viewBox="0 0 100 100"><image id="search-interface-symbol_1_" data-name="search-interface-symbol (1)" x="2" y="2" width="96" height="96" xlink:href="data:img/png;base64,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"/></svg>';
		$output .= '</a>';
		}
		if($wishlist == '1'){
		$output .= do_shortcode('[ti_wishlists_addtowishlist]');
		}

		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div class="content-wrapper">';

		$output .= '<h3 class="product-title"><a href="'.get_permalink().'" title="'.the_title_attribute( 'echo=0' ).'">'.get_the_title().'</a></h3>';
		$output .= '<div class="product-meta">';
		// if($weight){
		// $output .= '<div class="product-unit"> '.$weight.' '.get_option('woocommerce_weight_unit').'</div>';
		// }
		if($stock_status == 'instock'){
		$output .= '<div class="product-available in-stock">'.$stock_text['availability'].'</div>';
		} else {
		$output .= '<div class="product-available outof-stock">'.$stock_text['availability'].'</div>';
		}
		$output .= '</div>';

		// if(bacola_vendor_name()){
		// 	$review_class = $ratingcount ? 'has-rating' : 'no-rating';

		// 	$output .= '<div class="product-switcher '.esc_attr($review_class).'">';
		// 	$output .= '<div class="switcher-wrapper">';
		// 	$output .= '<div class="store-info fade-block">';
		// 	$output .= 'Store:'.bacola_sanitize_data(bacola_vendor_name());
		// 	$output .= '</div><!-- store-info -->';
		// 	if($ratingcount){
		// 	$output .= '<div class="product-rating">';
		// 	$output .= $rating;
		// 	$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
		// 	$output .= '</div>';
		// 	}
		// 	$output .= '</div><!-- switcher-wrapper -->';
		// 	$output .= '</div><!-- product-switcher -->';
		// } else {
		// 	if($ratingcount){
		// 	$output .= '<div class="product-rating">';
		// 	$output .= $rating;
		// 	$output .= '<div class="count-rating">'.esc_html($ratingcount).' <span class="rating-text">Ratings</span></div>';
		// 	$output .= '</div>';
		// 	}
		// }

		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span>';

		if(get_theme_mod('bacola_quantity_box',0) == 1){
		$output .= bacola_cart_with_quantity($id);
		} else {
		$output .= '<div class="product-button-group">';
		$output .= bacola_add_to_cart_button();
		$output .= '</div>';
		}

		$output .= '</div>';
		$output .= '</div>';


	}


	return $output;
}

/*----------------------------
  Add my owns
 ----------------------------*/
function bacola_shop_thumbnail () {
	if(get_theme_mod('bacola_product_box_type') == 'type4'){
		echo bacola_product_type4();
	} elseif (get_theme_mod('bacola_product_box_type') == 'type2'){
		echo bacola_product_type2();
	} else {
		echo bacola_product_type1();
	}
}

/*************************************************
## Woocommerce Cart Text
*************************************************/

//add to cart button
function bacola_add_to_cart_button(){
	global $product;
	$output = '';

	ob_start();
	woocommerce_template_loop_add_to_cart();
	$output .= ob_get_clean();

	if(!empty($output)){
		$pos = strpos($output, ">");

		if ($pos !== false) {
		    $output = substr_replace($output,">", $pos , strlen(1));
		}
	}

	if($product->get_type() == 'variable' && empty($output)){
		$output = "<a class='btn btn-primary add-to-cart cart-hover' href='".get_permalink($product->id)."'>".esc_html__('Select options','bacola')."</a>";
	}

	if($product->get_type() == 'simple'){
		$output .= "";
	} else {
		$btclass  = "single_bt";
	}

	if($output) return "$output";
}



/*************************************************
## Woo Cart Ajax
*************************************************/

add_filter('woocommerce_add_to_cart_fragments', 'bacola_header_add_to_cart_fragment');
function bacola_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	?>

	<span class="cart-count"><?php echo sprintf(_n('(%d)', '(%d)', $woocommerce->cart->cart_contents_count, 'bacola'), $woocommerce->cart->cart_contents_count);?></span>


	<?php
	$fragments['span.cart-count'] = ob_get_clean();

	return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'bacola_header_add_to_cart_fragment_icon');
function bacola_header_add_to_cart_fragment_icon( $fragments ) {
	global $woocommerce;
	ob_start();
	?>

	<span class="cart-count-icon"><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'bacola'), $woocommerce->cart->cart_contents_count);?></span>


	<?php
	$fragments['span.cart-count-icon'] = ob_get_clean();

	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', function($fragments) {

    ob_start();
    ?>

    <div class="fl-mini-cart-content">
        <?php woocommerce_mini_cart(); ?>
    </div>

    <?php $fragments['div.fl-mini-cart-content'] = ob_get_clean();

    return $fragments;

} );

add_filter('woocommerce_add_to_cart_fragments', 'bacola_header_add_to_cart_fragment_subtotal');
function bacola_header_add_to_cart_fragment_subtotal( $fragments ) {
	global $woocommerce;
	ob_start();
	?>

    <div class="cart-price"><?php echo WC()->cart->get_cart_subtotal(); ?></div>

    <?php $fragments['.cart-price'] = ob_get_clean();

	return $fragments;
}


/*************************************************
## Bacola Woo Search Form
*************************************************/

add_filter( 'get_product_search_form' , 'bacola_custom_product_searchform' );

function bacola_custom_product_searchform( $form ) {

	$form = '<form class="product-search-form" action="' . esc_url( home_url( '/'  ) ) . '" role="search" method="get" id="searchform">
				<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search','bacola').'">
				<button type="submit"><i class="klb-right"></i></button>
                <input type="hidden" name="post_type" value="product" />
			</form>';

	return $form;
}

function bacola_header_product_search() {

	$form = '<form action="' . esc_url( home_url( '/'  ) ) . '" class="search-form" role="search" method="get" id="searchform">
              <input type="search" value="' . get_search_query() . '" name="s" placeholder="'.esc_attr__('Search for Products, fruit, meat, eggs .etc...','bacola').'" autocomplete="off">
              <button type="submit"><i class="klbth-icon-search"></i></button>
			  <input type="hidden" name="post_type" value="product" />
             </form>';

	return $form;
}

/*************************************************
## Bacola Gallery Thumbnail Size
*************************************************/
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
    return array(
        'width' => 90,
        'height' => 54,
        'crop' => 0,
    );
} );


/*************************************************
## Quick View Scripts
*************************************************/

function bacola_quick_view_scripts() {
  	wp_enqueue_script( 'bacola-quick-ajax', get_template_directory_uri() . '/assets/js/custom/quick_ajax.js', array('jquery'), '1.0.0', true );
  	wp_enqueue_script( 'bacola-quantity_button', get_template_directory_uri() . '/assets/js/custom/quantity_button.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'bacola-quick-ajax', 'MyAjax', array(
		'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
	));
}
add_action( 'wp_enqueue_scripts', 'bacola_quick_view_scripts' );

/*************************************************
## Quick View CallBack
*************************************************/

add_action( 'wp_ajax_nopriv_quick_view', 'bacola_quick_view_callback' );
add_action( 'wp_ajax_quick_view', 'bacola_quick_view_callback' );
function bacola_quick_view_callback() {


	$id = intval( $_POST['id'] );
	$loop = new WP_Query( array(
		'post_type' => 'product',
		'p' => $id,
	  )
	);

	while ( $loop->have_posts() ) : $loop->the_post();
	$product = new WC_Product(get_the_ID());

	$rating = wc_get_rating_html($product->get_average_rating());
	$price = $product->get_price_html();
	$rating_count = $product->get_rating_count();
	$review_count = $product->get_review_count();
	$average      = $product->get_average_rating();
	$product_image_ids = $product->get_gallery_attachment_ids();

	$output = '';

		$output .= '<div class="quickview-product single-content white-popup">';
		$output .= '<div class="quick-product-wrapper">';
		$output .= '<article class="product">';

		$output .= '<div class="product-header bordered">';
		ob_start();
		woocommerce_template_single_title();
		$output .= ob_get_clean();

		$output .= '<div class="product-meta top">';

		$output .= '<div class="product-brand">';
		ob_start();
		wc_display_product_attributes( $product );
		$output .= ob_get_clean();
		$output .= '</div><!-- product-brand -->';

		$output .= '<div class="product-rating">';
		ob_start();
		woocommerce_template_single_rating();
		$output .= ob_get_clean();
		$output .= '</div><!-- product-rating -->';


		if($product->get_sku()){
		$output .= '<div class="sku-wrapper">';
		$output .= '<span>'.esc_html__('SKU:','bacola').' </span>';
		$output .= '<span class="sku">'.esc_html($product->get_sku()).'</span>';
		$output .= '</div>';
		}

		$output .= '</div><!-- product-meta -->';

		$output .= '</div><!-- product-header -->';

		$output .= '<div class="product-wrapper">';
		if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {

			$att=get_post_thumbnail_id();
			$image_src = wp_get_attachment_image_src( $att, 'full' );
			$image_src = $image_src[0];

			$output .= '<div class="product-thumbnails">';
			$output .= '<div class="woocommerce-product-gallery">';
			$output .= bacola_sale_percentage();

			$output .= '<div class="slider-wrapper">';
			$output .= '<svg class="preloader" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
			$output .= '<figure id="images" class="woocommerce-product-gallery__wrapper site-slider" data-slideshow="1" data-arrows="false" data-asnav="#thumbnails" data-slidespeed="1200">';

			$output .= '<a href="#"><img src="'.esc_url($image_src).'"></a>';
			foreach( $product_image_ids as $product_image_id ){
			$image_url = wp_get_attachment_url( $product_image_id );
			$output .= '<a href="#0"><img src="'.esc_url($image_url).'" alt="bacola"></a>';
			}

			$output .= '</figure>';
			$output .= '<div id="thumbnails" class="product-thumbnails site-slider" data-slideshow="6" data-focusselect="true" data-asnav="#images" data-slidespeed="1200">';

			if($product_image_ids){
			$output .= '<div class="product-thumbnail"><img src="'.esc_url($image_src).'"></div>';
			foreach( $product_image_ids as $product_image_id ){
			$image_url = wp_get_attachment_url( $product_image_id );
			$output .= '<div class="product-thumbnail"><img src="'.esc_url($image_url).'"></div>';
			}
			}

			$output .= '</div><!-- product-thumbnails -->';
			$output .= '</div><!-- slider-wrapper -->';

			$output .= '</div><!-- woocommerce-product-gallery -->';
			$output .= '</div><!-- product-thumbnails -->';
		}
		$output .= '<div class="product-detail">';
		ob_start();
		woocommerce_template_single_price();
		$output .= ob_get_clean();

		$output .= '<div class="product-short-description">';
		$output .= '<p>'.get_the_excerpt().'</p>';
		$output .= '</div><!-- product-short-description -->';
		ob_start();
		woocommerce_template_single_add_to_cart();
		$output .= ob_get_clean();

		ob_start();
		do_action('quickview-wishlist-compare');
		$output .= ob_get_clean();

		$type = get_post_meta( $id, 'klb_product_type', true );
		$year = get_post_meta( $id, 'klb_product_mfg', true );
		$life = get_post_meta( $id, 'klb_product_life', true );

		if($type || $year || $life){
			$output .= '<div class="product-checklist">';
			$output .= '<ul>';
			if($type){
			$output .= '<li>'.esc_html__('Type:','bacola').' '.esc_html($type).'</li>';
			}
			if($year){
			$output .= '<li>'.esc_html__('MFG:','bacola').' '.esc_html($year).'</li>';
			}
			if($life){
			$output .= '<li>'.esc_html__('LIFE:','bacola').' '.esc_html($life).'</li>';
			}
			$output .= '</ul>';
			$output .= '</div>';
		}

		ob_start();
		woocommerce_template_single_meta();
		$output .= ob_get_clean();
		$output .= '</div><!-- product-details -->';
		$output .= '</div><!-- product-wrapper -->';
		$output .= '</article><!-- product -->';
		$output .= '</div><!-- quick-product-wrapper -->';
		$output .= '</div>';


		endwhile;
		wp_reset_postdata();

	 	$output_escaped = $output;
	 	echo $output_escaped;

		wp_die();

}

/*************************************************
## Quantity Button CallBack
*************************************************/ 

add_action( 'wp_ajax_nopriv_quantity_button', 'bacola_quantity_button_callback' );
add_action( 'wp_ajax_quantity_button', 'bacola_quantity_button_callback' );
function bacola_quantity_button_callback() {


	$id = intval( $_POST['id'] );
	$quantity = intval( $_POST['quantity'] );
	$product    = isset( $_POST['id'] ) ? wc_get_product( absint( $_POST['id'] ) ) : false;

    $specific_ids = array($id);
    $new_qty = $quantity; // New quantity

    foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product_id = $cart_item['data']->get_id();
        // Check for specific product IDs and change quantity
        if( in_array( $product_id, $specific_ids ) && $cart_item['quantity'] != $new_qty ){
            WC()->cart->set_quantity( $cart_item_key, $new_qty ); // Change quantity
        }
    }
	?>

	<?php if($product){ ?>

		<?php $cart_url   = wc_get_cart_url(); ?>
		<?php $checkout_url   = wc_get_checkout_url(); ?>

		<div class="klb-cart-notice">
			<h2><?php esc_html_e('Cart Updated','bacola'); ?></h2>
			<h3 class="product-title">
				<?php echo esc_attr($quantity).' &times; <b>' . esc_html( $product->get_title() ) . '</b>'; ?>
			</h3>
			<div class="actions">
				<a class="button go-cart" href="<?php echo esc_url( $cart_url ); ?>"><?php esc_html_e( 'View cart', 'bacola' ); ?></a>
				<a class="button checkout" href="<?php echo esc_url( $checkout_url ); ?>"><?php esc_html_e( 'Checkout', 'bacola' ); ?></a>
			</div>

			<button title="<?php esc_attr_e('Close','bacola'); ?>" type="button" class="cart-notice-close"><i class="klbth-icon-cancel"></i></button>
		</div>
	<?php } ?>

	<?php


	wp_die();

}

/*************************************************
## Bacola Filter by Attribute
*************************************************/
function bacola_woocommerce_layered_nav_term_html( $term_html, $term, $link, $count ) {

	$attribute_label_name = wc_attribute_label($term->taxonomy);;
	$attribute_id = wc_attribute_taxonomy_id_by_name($attribute_label_name);
	$attr  = wc_get_attribute($attribute_id);
	$array = json_decode(json_encode($attr), true);

	if($array['type'] == 'color'){
		$color = get_term_meta( $term->term_id, 'product_attribute_color', true );
		$term_html = '<div class="type-color"><span class="color-box" style="background-color:'.esc_attr($color).';"></span>'.$term_html.'</div>';
	}

	if($array['type'] == 'button'){
		$term_html = '<div class="type-button"><span class="button-box"></span>'.$term_html.'</div>';
	}

    return $term_html;
};

add_filter( 'woocommerce_layered_nav_term_html', 'bacola_woocommerce_layered_nav_term_html', 10, 4 );


/*************************************************
## Shop Width Body Classes
*************************************************/

function bacola_body_classes( $classes ) {

	if( get_theme_mod('bacola_shop_width') == 'wide' || bacola_get_option() == 'wide' && is_shop()) {
		$classes[] = 'shop-wide';
	} else {
		$classes[] = '';
	}

	return $classes;
}
add_filter( 'body_class', 'bacola_body_classes' );

/*************************************************
## Stock Availability Translation
*************************************************/

if(get_theme_mod('bacola_stock_quantity',0) != 1){
add_filter( 'woocommerce_get_availability', 'bacola_custom_get_availability', 1, 2);
function bacola_custom_get_availability( $availability, $_product ) {

    // Change In Stock Text
    if ( $_product->is_in_stock() ) {
        $availability['availability'] = esc_html__('In Stock', 'bacola');
    }
    // Change Out of Stock Text
    if ( ! $_product->is_in_stock() ) {
        $availability['availability'] = esc_html__('Out of stock', 'bacola');
    }
    return $availability;
}
}


/*************************************************
## Related Products with Tags
*************************************************/
if(get_theme_mod('bacola_related_by_tags',0) != 1){
	add_filter( 'woocommerce_product_related_posts_relate_by_category', '__return_false' );
}

/*************************************************
## Woo Smart Compare Disable
*************************************************/
add_filter( 'filter_wooscp_button_archive', function() {
    return '0';
} );
add_filter( 'filter_wooscp_button_single', function() {
    return '0';
} );

} // is woocommerce activated

?>