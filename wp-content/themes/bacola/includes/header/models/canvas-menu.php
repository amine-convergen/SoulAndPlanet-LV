<div class="site-canvas">
	<div class="site-scroll">
		<div class="canvas-header">
			<div class="site-brand">
				<?php if (get_theme_mod( 'bacola_logo' )) { ?>
					<?php $size = get_theme_mod( 'bacola_logo_size', array( 'width' => '127', 'height' => '34') ); ?>
					<a href="<?php echo esc_url( home_url( "/" ) ); ?>" title="<?php bloginfo("name"); ?>">
						<img src="<?php echo esc_url( wp_get_attachment_url(get_theme_mod( 'bacola_logo' )) ); ?>" width="<?php echo esc_attr( $size["width"] ); ?>" height="<?php echo esc_attr( $size["height"] ); ?>" alt="<?php bloginfo("name"); ?>">
					</a>
				<?php } elseif (get_theme_mod( 'bacola_logo_text' )) { ?>
					<a class="navbar-brand text" href="<?php echo esc_url( home_url( "/" ) ); ?>" title="<?php bloginfo("name"); ?>">
						<span class="brand-text"><?php echo esc_html(get_theme_mod( 'bacola_logo_text' )); ?></span>
					</a>
				<?php } else { ?>
					<a href="<?php echo esc_url( home_url( "/" ) ); ?>" title="<?php bloginfo("name"); ?>">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/bacola-logo.png" width="127" height="34" alt="<?php bloginfo("name"); ?>">
					</a>
				<?php } ?>
			</div><!-- site-brand -->
			<div class="close-canvas">
				<i class="klbth-icon-x"></i>
			</div><!-- close-canvas -->
		</div><!-- canvas-header -->
		
		<div class="canvas-main">

			<?php if(get_theme_mod('bacola_location_filter',0) == 1){ ?>
				<div class="site-location">
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

			<?php $sidebarmenu = get_theme_mod('bacola_header_sidebar','0'); ?>

<?php if($sidebarmenu == '1'){ ?>
	<div class="all-categories locked">
		<a href="#" data-toggle="collapse" data-target="#all-categories">
			<i class="klbth-icon-menu-thin"></i>
			<span class="text"><?php esc_html_e('CATÉGORIES','bacola'); ?></span>
		</a>
		
		<?php $menu_collapse = is_front_page() && !get_theme_mod('bacola_header_sidebar_collapse') ? 'show' : ''; ?>
		<div class="dropdown-categories collapse <?php echo esc_attr($menu_collapse); ?>" id="all-categories">
			<?php 


echo '<ul id="menu-sidebar-menu" class="menu-list">';
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


			// wp_nav_menu(array(
			// 'theme_location' => 'sidebar-menu',
			// 'container' => '',
			// 'fallback_cb' => 'show_top_menu',
			// 'menu_id' => '',
			// 'menu_class' => 'menu-list',
			// 'echo' => true,
			// "walker" => new bacola_sidebar_walker(),
			// 'depth' => 0 
			// ));
			?>
		</div>
		
	</div>
<?php } ?>

			
			<div class="canvas-title">
				<h6 class="entry-title"><?php esc_html_e('Menu','bacola'); ?></h6>
			</div><!-- canvas-title -->
			<nav class="canvas-menu canvas-primary vertical">
				<?php 
					wp_nav_menu(array(
					'theme_location' => 'main-menu-mobile',
					'container' => '',
					'fallback_cb' => 'show_top_menu',
					'menu_id' => '',
					'menu_class' => 'menu',
					'echo' => true,
					"walker" => new bacola_main_walker(),
					'depth' => 0 
					));
				?>
			</nav><!-- site-menu -->
		</div><!-- canvas-main -->
		
		<div class="canvas-footer">
			<div class="site-copyright">
			2021 © soulandplanet.tn
				<!-- < ?php if(get_theme_mod( 'bacola_copyright' )){ ?>
					< ?php echo bacola_sanitize_data(get_theme_mod( 'bacola_copyright' )); ?>
				< ?php } else { ?>
					< ?php esc_html_e('Copyright 2021.KlbTheme . All rights reserved','bacola'); ?>
				< ?php } ?> -->
			</div><!-- site-copyright -->
			<?php $not_footer = 0;  
			if ($not_footer === 1) { ?>
			<nav class="canvas-menu canvas-secondary select-language vertical">
				<?php 
					 wp_nav_menu(array(
					 'theme_location' => 'canvas-bottom',
					 'container' => '',
					 'fallback_cb' => 'show_top_menu',
					 'menu_id' => '',
					 'menu_class' => 'menu',
					 'echo' => true,
					 'depth' => 0 
					)); 
				?>
			</nav><!-- site-menu -->
			<?php  } ?>
		</div><!-- canvas-footer -->
		
	</div><!-- site-scroll -->
</div><!-- site-canvas -->