<header id="masthead" class="site-header desktop-shadow-disable mobile-shadow-enable mobile-nav-enable" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
	<?php if(get_theme_mod('bacola_top_header',0) == 1){ ?>
		<div class="header-top header-wrapper hide-mobile">
			<div class="container">
				<div class="column column-left">
					<nav class="site-menu horizontal">
						<?php 
							wp_nav_menu(array(
							'theme_location' => 'top-left-menu',
							'container' => '',
							'fallback_cb' => 'show_top_menu',
							'menu_id' => '',
							'menu_class' => 'menu',
							'echo' => true,
							"walker" => '',
							'depth' => 0 
							));
						?>
					</nav><!-- site-menu -->
				</div><!-- column-left -->
				
				<div class="column column-right">

					<div class="topbar-notice">
						<i class="klbth-icon-<?php echo esc_attr(get_theme_mod('bacola_top_header_text_icon')); ?>"></i>
						<span><?php echo bacola_sanitize_data(get_theme_mod('bacola_top_header_text')); ?></span>
					</div>

					<div class="text-content">
						<?php echo bacola_sanitize_data(get_theme_mod('bacola_top_header_content_text')); ?>
					</div>

					<div class="header-switchers">
						<nav class="store-language site-menu horizontal">
							<?php 
								wp_nav_menu(array(
								'theme_location' => 'top-right-menu',
								'container' => '',
								'fallback_cb' => 'show_top_menu',
								'menu_id' => '',
								'menu_class' => 'menu',
								'echo' => true,
								"walker" => '',
								'depth' => 0 
								));
							?>
						</nav><!-- site-menu -->
					</div><!-- header-switchers -->

				</div><!-- column-right -->
			</div><!-- container -->
		</div><!-- header-top -->
	<?php } ?>
	
	<div class="header-main header-wrapper">
		<div class="container box-wrapper">
			<div class="column column-left">
				<div class="header-buttons hide-desktop">
					<div class="header-canvas button-item">
						<a href="#">
							<i class="klbth-icon-menu-thin"></i>
						</a>
					</div><!-- button-item -->
				</div><!-- header-buttons -->
				<div class="site-brand">
					<a href="<?php echo esc_url( home_url( "/" ) ); ?>" title="<?php bloginfo("name"); ?>">
						<?php if (get_theme_mod( 'bacola_logo' )) { ?>
							<?php $size = get_theme_mod( 'bacola_logo_size', array( 'width' => '164', 'height' => '44') ); ?>
							<img class="desktop-logo hide-mobile" src="<?php echo esc_url( wp_get_attachment_url(get_theme_mod( 'bacola_logo' )) ); ?>" width="<?php echo esc_attr( $size["width"] ); ?>" height="<?php echo esc_attr( $size["height"] ); ?>" alt="<?php bloginfo("name"); ?>">
						<?php } elseif (get_theme_mod( 'bacola_logo_text' )) { ?>
							<span class="brand-text hide-mobile"><?php echo esc_html(get_theme_mod( 'bacola_logo_text' )); ?></span>
						<?php } else { ?>
							<img class="desktop-logo hide-mobile" src="<?php echo get_template_directory_uri(); ?>/assets/images/bacola-logo.png" width="164" height="44" alt="<?php bloginfo("name"); ?>">
						<?php } ?>

						<?php if (get_theme_mod( 'bacola_mobile_logo' )) { ?>
							<img class="mobile-logo hide-desktop" src="<?php echo esc_url( wp_get_attachment_url(get_theme_mod( 'bacola_mobile_logo' )) ); ?>" alt="<?php bloginfo("name"); ?>">
						<?php } else { ?>
							<img class="mobile-logo hide-desktop" src="<?php echo get_template_directory_uri(); ?>/assets/images/bacola-logo-mobile.png" alt="<?php bloginfo("name"); ?>">   
						<?php } ?>
						<?php if(get_theme_mod('bacola_logo_desc')){ ?>
							<span class="brand-description"><?php echo esc_html(get_theme_mod('bacola_logo_desc')); ?></span>
						<?php } ?>
					</a>
				</div><!-- site-brand -->
			</div><!-- column -->
			<div class="column column-center">
				<?php if(get_theme_mod('bacola_location_filter',0) == 1){ ?>
					<div class="header-location site-location hide-mobile">
						<a href="#">
							<span class="location-description"><?php esc_html_e('Your Location','bacola'); ?></span>
							<?php if(bacola_location() == 'all'){ ?>
								<div class="current-location"><?php esc_html_e('Select a Location','bacola'); ?></div>
							<?php } else { ?>
								<div class="current-location activated"><?php echo esc_html(bacola_location()); ?></div>
							<?php } ?>
						</a>
					</div>
				<?php } ?>

				<?php if(get_theme_mod('bacola_header_search',0) == 1){ ?>
					<div class="header-search">
						<?php if(get_theme_mod('bacola_ajax_search_form',0) == 1 && class_exists( 'DGWT_WC_Ajax_Search' )){ ?>
							<?php echo do_shortcode('[wcas-search-form]'); ?>
						<?php } else { ?>
							<?php echo bacola_header_product_search(); ?>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<div class="column column-right">
				<div class="header-buttons">
					<?php $wishlist = get_theme_mod( 'bacola_wishlist_button', '0' );
						if($wishlist == '1'){ ?>
						<div class="header-login button-item bordered">
							<a href="<?php echo esc_url( home_url( "/wishlist/" ) ); ?>">
								<div class="button-icon">
									<i class="ftinvwl ftinvwl-heart-o"></i>
									<?php echo do_shortcode('[ti_wishlist_products_counter]'); ?>
								</div>
							</a>
						</div>
					<?php } ?>
					<?php $headeraccounticon  = get_theme_mod('bacola_header_account','0'); ?>
					<?php if($headeraccounticon){ ?>
						<div class="header-login button-item bordered">
							<a href="<?php echo esc_url( home_url( "/myaccount/" ) ); ?>">
								<div class="button-icon">
									<?php if (get_theme_mod( 'bacola_icon_admin' )) { ?>
									<img class="mobile-logo" src="<?php echo esc_url( wp_get_attachment_url(get_theme_mod( 'bacola_icon_admin' )) ); ?>" alt="<?php bloginfo("name"); ?>">
									<?php } else { ?>
										<i class="klbth-icon-user"></i>
									<?php }  ?>
								</div>
							</a>
						</div>
					<?php } ?>

					<?php $headercart = get_theme_mod('bacola_header_cart','0'); ?>
					<?php if($headercart == '1'){ ?>
						<?php global $woocommerce; ?>
						<?php $carturl = wc_get_cart_url(); ?>
						<div class="header-cart button-item bordered">
							<a href="<?php echo esc_url($carturl); ?>">
								<!-- <div class="cart-price"><?php echo WC()->cart->get_cart_subtotal(); ?></div> -->
								<div class="button-icon">
									<?php if (get_theme_mod( 'bacola_icon_cart' )) { ?>
									<img class="" src="<?php echo esc_url( wp_get_attachment_url(get_theme_mod( 'bacola_icon_cart' )) ); ?>" alt="<?php bloginfo("name"); ?>">
									<?php } else { ?>
									<svg xmlns="http://www.w3.org/2000/svg" width="27.725" height="20.815" viewBox="0 0 35.725 29.815">
									<g id="Groupe_49" data-name="Groupe 49" transform="translate(-1670.865 -160.79)">
										<g id="panier" transform="translate(1309.249 -254.091)">
										<path id="Tracé_42" data-name="Tracé 42" d="M370.483,426.751h17.93c-.14-.224-.237-.392-.347-.554l-6.71-9.764c-.466-.679-.49-1.132-.073-1.418s.829-.115,1.3.568q3.69,5.364,7.371,10.735a.956.956,0,0,0,.907.485c1.751-.025,3.5-.012,5.256-.008a1.007,1.007,0,0,1,1.209.728c.084.358-.173.623-.548.72a.4.4,0,0,1-.163.03c-.912-.2-1.082.41-1.3,1.092-1.352,4.316-2.735,8.622-4.107,12.933A3.02,3.02,0,0,1,388,444.691q-8.526.01-17.051,0a3.023,3.023,0,0,1-3.227-2.373c-1.406-4.417-2.82-8.83-4.2-13.254-.172-.551-.373-.876-1.019-.785a.707.707,0,0,1-.87-.526.891.891,0,0,1,.221-.732,1.4,1.4,0,0,1,.83-.219c1.77-.018,3.541-.025,5.311,0a1.046,1.046,0,0,0,1-.514q3.65-5.357,7.338-10.689c.053-.077.1-.155.158-.23.351-.476.763-.608,1.136-.366s.428.721.079,1.23q-2.955,4.309-5.92,8.612C371.363,425.453,370.95,426.064,370.483,426.751Zm-5.617,1.572c.077.291.123.505.19.715q2.033,6.387,4.069,12.773c.352,1.106.741,1.389,1.914,1.389q8.417,0,16.832,0c1.179,0,1.567-.281,1.919-1.384q2.036-6.385,4.069-12.773c.07-.22.113-.448.18-.719Z" transform="translate(0)" fill="#282a28"/>
										</g>
									</g>
									</svg>
									<?php }  ?>
								</div>
								<span class="cart-count-icon"><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'bacola'), $woocommerce->cart->cart_contents_count);?></span>
							</a>
							<div class="cart-dropdown hide">
								<div class="cart-dropdown-wrapper">
									<div class="fl-mini-cart-content">
										<?php woocommerce_mini_cart(); ?>
									</div>

									<?php if(get_theme_mod('bacola_header_mini_cart_notice')){ ?>
										<div class="cart-noticy">
											<?php echo esc_html(get_theme_mod('bacola_header_mini_cart_notice')); ?>
										</div><!-- cart-noticy -->
									<?php } ?>
								</div><!-- cart-dropdown-wrapper -->
							</div><!-- cart-dropdown -->
						</div><!-- button-item -->
					<?php } ?>
				</div><!-- header-buttons -->
			</div><!-- column -->
		</div><!-- container -->
	



	<div class="header-nav header-wrapper hide-mobile">
		<!-- <div class="container "></div>container -->
			<nav class="container site-menu primary-menu horizontal header-one-box">
				<?php get_template_part( 'includes/header/models/sidebar-menu' ); ?>

				<!-- < ?php
					wp_nav_menu(array(
					'theme_location' => 'main-menu',
					'container' => '',
					'fallback_cb' => 'show_top_menu',
					'menu_id' => '',
					'menu_class' => 'menu',
					'echo' => true,
					"walker" => new bacola_main_walker(),
					'depth' => 0 
					));
				?> -->
			</nav><!-- site-menu -->
		
	</div><!-- header-nav -->
	</div><!-- header-main -->
	<?php do_action('bacola_mobile_bottom_menu'); ?>
</header><!-- site-header -->