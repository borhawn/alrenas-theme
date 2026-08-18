<?php
/**
 * Site header.
 *
 * @package Alrenas
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'alrenas' ); ?></a>
<header class="site-header" data-header>
	<div class="container header-inner">
		<?php get_template_part( 'template-parts/header/branding' ); ?>

		<button class="nav-toggle" type="button" aria-label="<?php esc_attr_e( 'Open navigation', 'alrenas' ); ?>" aria-expanded="false" aria-controls="primary-menu" data-nav-toggle>
			<span></span><span></span>
		</button>

		<?php get_template_part( 'template-parts/header/navigation' ); ?>
		<?php get_template_part( 'template-parts/header/header-cta' ); ?>
	</div>
</header>
