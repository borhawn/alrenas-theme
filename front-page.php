<?php
/**
 * Front-page template.
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main">
	<?php if ( have_posts() ) { the_post(); } ?>
	<?php get_template_part( 'template-parts/home/hero' ); ?>
	<?php get_template_part( 'template-parts/home/care-strip' ); ?>
	<?php get_template_part( 'template-parts/home/rehabilitation-intro' ); ?>
	<?php get_template_part( 'template-parts/home/products' ); ?>
	<?php get_template_part( 'template-parts/home/patient-story' ); ?>
	<?php get_template_part( 'template-parts/home/disciplines' ); ?>
	<?php get_template_part( 'template-parts/home/why-alrenas' ); ?>
	<?php if ( is_page() ) : ?>
		<?php get_template_part( 'template-parts/content/page-content' ); ?>
	<?php endif; ?>
	<?php get_template_part( 'template-parts/home/latest-posts' ); ?>
	<?php get_template_part( 'template-parts/home/contact' ); ?>
</main>
<?php
get_footer();
