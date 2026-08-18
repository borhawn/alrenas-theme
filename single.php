<?php
/** Single-post template. @package Alrenas */

get_header();
?>
<main id="primary" class="site-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part( 'template-parts/article/hero' ); ?>
		<?php get_template_part( 'template-parts/article/content' ); ?>
		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template(); ?>
		<?php endif; ?>
		<?php get_template_part( 'template-parts/article/related-posts' ); ?>
		<?php get_template_part( 'template-parts/global/site-cta' ); ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
