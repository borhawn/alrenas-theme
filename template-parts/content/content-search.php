<?php
/**
 * Search-result summary.
 *
 * @package Alrenas
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h2><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
	</header>
	<div class="entry-summary"><?php the_excerpt(); ?></div>
</article>

