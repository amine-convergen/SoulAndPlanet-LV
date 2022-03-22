<?php
/**
 * 404.php
 * @package WordPress
 * @subpackage Bacola
 * @since Bacola 1.0
 */
?>

<?php get_header(); ?>

<div class="module-border hide-mobile">
	<div class="container">
		<div class="module-border--inner"></div>
	</div>
</div>
<div class="page-not-found">
	<div class="page-not-found--inner">
		<h1 class="entry-title"><?php esc_html_e('404','bacola'); ?></h1>
		<h2 class="entry-subtitle"><?php esc_html_e('OUPS!','bacola'); ?></h2>
		<div class="entry-description">
			<p><?php esc_html_e('La page que vous recherchez semble introuvable.','bacola'); ?></p>
		</div>

		<?php get_search_form(); ?>
		
		<a href="<?php echo esc_url( home_url('/') ); ?>" class="button button-primary"><?php esc_html_e('RETOUR À L’ACCUEIL','bacola'); ?></a>
	</div>
</div>

<?php get_footer(); ?>