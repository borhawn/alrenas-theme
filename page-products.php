<?php
/**
 * Template Name: Products Landing
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part( 'template-parts/products/hero' ); ?>
		<?php get_template_part( 'template-parts/products/introduction' ); ?>
		<?php get_template_part( 'template-parts/products/systems' ); ?>
		<?php get_template_part( 'template-parts/products/selector' ); ?>
		<?php get_template_part( 'template-parts/products/quote-process' ); ?>
		<?php get_template_part( 'template-parts/content/page-content' ); ?>
		<?php get_template_part( 'template-parts/global/site-cta' ); ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
