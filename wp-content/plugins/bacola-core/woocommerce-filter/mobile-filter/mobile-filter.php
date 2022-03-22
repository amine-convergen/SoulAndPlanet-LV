<?php
/*************************************************
* Mobile Filter
*************************************************/
add_action('bacola_mobile_bottom_menu', 'bacola_mobile_filter');
function bacola_mobile_filter() {

	$mobilebottommenu = get_theme_mod('bacola_mobile_bottom_menu','0');
	if($mobilebottommenu == '1'){

?>

	<?php $edittoggle = get_theme_mod('bacola_mobile_bottom_menu_edit_toggle','0'); ?>
	<?php if($edittoggle == '1'){ ?>
		<nav class="header-mobile-nav">
			<div class="mobile-nav-wrapper">
				<ul>
					<?php $editrepeater = get_theme_mod('bacola_mobile_bottom_menu_edit'); ?>

					<?php foreach($editrepeater as $e){ ?>
						<?php if($e['mobile_menu_type'] == 'filter'){ ?>
							<?php if(is_shop()){ ?>
								<li class="menu-item">
									<a href="#" class="filter-toggle">
										<i class="klbth-icon-<?php echo esc_attr($e['mobile_menu_icon']); ?>"></i>
										<span><?php echo esc_html($e['mobile_menu_text']); ?></span>
									</a>
								</li>
							<?php } ?>
						<?php } elseif($e['mobile_menu_type'] == 'search'){ ?>
							<li class="menu-item">
								<a href="#" class="search">
									<i class="klbth-icon-<?php echo esc_attr($e['mobile_menu_icon']); ?>"></i>
									<span><?php echo esc_html($e['mobile_menu_text']); ?></span>
								</a>
							</li>
						<?php } else { ?>
							<li class="menu-item">
								<a href="<?php echo esc_url($e['mobile_menu_url']); ?>" class="user">
									<i class="klbth-icon-<?php echo esc_attr($e['mobile_menu_icon']); ?>"></i>
									<span><?php echo esc_html($e['mobile_menu_text']); ?></span>
								</a>
							</li>
						<?php } ?>
					<?php } ?>

				</ul>
			</div>
		</div>
	<?php } else { ?>
		<nav class="header-mobile-nav">
			<div class="mobile-nav-wrapper">
				<ul>
					<li class="menu-item">
						<?php if(!is_shop()){ ?>
							<a href="<?php echo wc_get_page_permalink( 'shop' ); ?>" class="store">
								<i class="klbth-icon-store"></i>
								<span><?php esc_html_e('Boutique','bacola-core'); ?></span>
							</a>
						<?php } else { ?>
							<a href="<?php echo esc_url( home_url( "/" ) ); ?>" class="store">
								<i class="klbth-icon-home"></i>
								<span><?php esc_html_e('Accueil','bacola-core'); ?></span>
							</a>
						<?php } ?>
					</li>

					<?php if(is_shop()){ ?>
						<li class="menu-item">
							<a href="#" class="filter-toggle">
								<i class="klbth-icon-filter"></i>
								<span><?php esc_html_e('Filter','bacola-core'); ?></span>
							</a>
						</li>
					<?php } ?>

					<li class="menu-item">
						<a href="#" class="search">
							<i class="klbth-icon-search"></i>
							<span><?php esc_html_e('Rechercher','bacola-core'); ?></span>
						</a>
					</li>

					<?php $wishlist = get_theme_mod( 'bacola_wishlist_button', '0' );
					if ( function_exists( 'tinv_url_wishlist_default' ) && $wishlist == '1' ) { ?>
						<li class="menu-item">
							<a href="<?php echo tinv_url_wishlist_default(); ?>" class="wishlist">
								<i class="klbth-icon-heart-1"></i>
								<span><?php esc_html_e('Wishlist','bacola-core'); ?></span>
								<?php echo do_shortcode('[ti_wishlist_products_counter]'); ?>
							</a>
						</li>
					<?php } ?>

					<li class="menu-item">
						<a href="<?php echo esc_url( home_url( "/myaccount/" ) ); ?>" class="user">
							<i class="klbth-icon-user"></i>
							<span><?php esc_html_e('Compte','bacola-core'); ?></span>
						</a>
					</li>
				</ul>
			</div><!-- mobile-nav-wrapper -->
		</nav><!-- header-mobile-nav -->
	<?php } ?>

<?php }

}