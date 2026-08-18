<?php
/**
 * Default post summary.
 *
 * @package Alrenas
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<div class="entry-meta"><?php alrenas_posted_on(); ?></div>
	</header>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="post-thumbnail"><?php the_post_thumbnail( 'large' ); ?></a>
	<?php endif; ?>
	<div class="entry-summary"><?php the_excerpt(); ?></div>
</article>

