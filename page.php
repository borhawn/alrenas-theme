<?php
/**
 * Default page template.
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main section">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part( 'template-parts/content/content', 'page' ); ?>
		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template(); ?>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();

