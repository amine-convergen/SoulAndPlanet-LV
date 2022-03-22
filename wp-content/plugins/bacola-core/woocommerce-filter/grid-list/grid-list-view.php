<?php 
/*************************************************
* Catalog Ordering
*************************************************/
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 ); 
add_action( 'klb_catalog_ordering', 'woocommerce_catalog_ordering', 30 ); 

add_action( 'woocommerce_before_shop_loop', 'bacola_catalog_ordering_start', 30 );
function bacola_catalog_ordering_start(){
?>

	<div class="before-shop-loop">
		<div class="shop-view-selector">
		<?php if(get_theme_mod('bacola_grid_list_view','0') == '1'){ ?>
		
			<?php if(bacola_shop_view() == 'list_view') { ?>
				<a href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
					<i class="klbth-icon-3-grid"></i>
				</a>
				<a href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
					<i class="klbth-icon-4-grid"></i>
				</a>
			<?php } else { ?>
				<?php if(bacola_get_column_option() == 2){ ?>
					<a href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
						<i class="klbth-icon-3-grid"></i>
					</a>
					<a href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
						<i class="klbth-icon-4-grid"></i>
					</a>
				<?php } elseif(bacola_get_column_option() == 3){ ?>
					<a href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view active">
						<i class="klbth-icon-3-grid"></i>
					</a>
					<a href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
						<i class="klbth-icon-4-grid"></i>
					</a>
				<?php } else { ?>
					<a href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
						<i class="klbth-icon-3-grid"></i>
					</a>
					<a href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view active">
						<i class="klbth-icon-4-grid"></i>
					</a>
				<?php } ?>

			<?php } ?>
		<?php } ?>
		</div>
		
		<div class="mobile-filter">
			<a href="#" class="filter-toggle">
				<i class="klbth-icon-filter"></i>
				<span><?php esc_html_e('Filtrer les produits','bacola-core'); ?></span>
			</a>
		</div>
		
		<!-- For get orderby from loop -->
		<?php do_action('klb_catalog_ordering'); ?>
		
		

	</div>


	<?php echo bacola_remove_klb_filter(); ?>
	<?php wp_enqueue_style( 'klb-remove-filter'); ?>
<?php

}