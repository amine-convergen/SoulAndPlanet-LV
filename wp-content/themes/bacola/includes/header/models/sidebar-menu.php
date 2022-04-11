<?php $sidebarmenu = get_theme_mod('bacola_header_sidebar','0'); ?>

<?php if($sidebarmenu == '1'){ ?>
	<!-- <div class="all-categories locked"></div> -->

		<!--<nav class="site-menu primary-menu horizontal"></nav> site-menu -->
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


<?php } ?>