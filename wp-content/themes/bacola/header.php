<?php
/**
 * header.php
 * @package WordPress
 * @subpackage Bacola
 * @since Bacola 1.0
 * 
 */
 ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( "charset" ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>


	<?php if (get_theme_mod( 'bacola_preloader' )) { ?>
		<div class="site-loading">
			<div class="preloading">
				<svg class="circular" viewBox="25 25 50 50">
					<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
				</svg>
			</div>
		</div>
	<?php } ?>

	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { ?>
		<?php get_template_part( 'includes/header/models/canvas-menu' ); ?>
	
		<?php get_template_part( 'includes/header/models/top-notification' ); ?>
	
		<?php
		if(get_theme_mod('bacola_header_type') == 'type1'){
			get_template_part( 'includes/header/header-type1' );
		} else {
			get_template_part( 'includes/header/header-type2' );
		}
		?>
	<?php } ?>

	<main id="main" class="site-primary">
		<div class="site-content">
			<div class="homepage-content">