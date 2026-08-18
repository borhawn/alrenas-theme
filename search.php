<?php
/**
 * Search-results template.
 *
 * @package Alrenas
 */

get_header();
?>
<main id="primary" class="site-main section">
	<div class="container">
		<header class="page-header">
			<h1><?php printf( esc_html__( 'Search results for: %s', 'alrenas' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
		</header>
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php get_template_part( 'template-parts/content/content', 'search' ); ?>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();

