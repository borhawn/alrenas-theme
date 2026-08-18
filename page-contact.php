<?php
/**
 * Template Name: Contact
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part( 'template-parts/contact/hero' ); ?>
		<?php get_template_part( 'template-parts/contact/options' ); ?>
		<?php get_template_part( 'template-parts/content/page-content' ); ?>
		<?php get_template_part( 'template-parts/contact/form-section' ); ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
