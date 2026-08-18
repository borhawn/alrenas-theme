<?php
/** Posts index. @package Alrenas */

get_header();
?>
<main id="primary" class="site-main">
	<?php get_template_part( 'template-parts/blog/archive-hero' ); ?>
	<section class="section resources"><div class="container">
		<?php get_template_part( 'template-parts/blog/archive-header' ); ?>
		<?php if ( have_posts() ) : ?>
			<?php if ( ! is_paged() ) : ?><?php the_post(); ?><?php get_template_part( 'template-parts/blog/featured-card' ); ?><?php endif; ?>
			<div class="article-grid"><?php while ( have_posts() ) : ?><?php the_post(); ?><?php get_template_part( 'template-parts/content/blog-card' ); ?><?php endwhile; ?></div>
			<?php the_posts_pagination(); ?>
		<?php else : ?><?php get_template_part( 'template-parts/content/content', 'none' ); ?><?php endif; ?>
	</div></section>
	<?php get_template_part( 'template-parts/global/site-cta' ); ?>
</main>
<?php
get_footer();
