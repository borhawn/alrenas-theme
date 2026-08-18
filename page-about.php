<?php
/**
 * Template Name: About
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part( 'template-parts/about/hero' ); ?>
		<?php get_template_part( 'template-parts/about/mission' ); ?>
		<?php get_template_part( 'template-parts/about/values' ); ?>
		<?php get_template_part( 'template-parts/about/story' ); ?>
		<?php get_template_part( 'template-parts/about/quality' ); ?>
		<?php get_template_part( 'template-parts/content/page-content' ); ?>
		<?php get_template_part( 'template-parts/global/site-cta' ); ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
