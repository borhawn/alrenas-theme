<?php
/**
 * Main fallback template.
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php get_template_part( 'template-parts/content/content', get_post_type() ); ?>
			<?php endwhile; ?>
			<?php the_posts_navigation(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();

