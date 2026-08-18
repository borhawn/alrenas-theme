<?php
/**
 * Page content.
 *
 * @package Alrenas
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header container">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>
	<div class="entry-content container">
		<?php
		the_content();
		wp_link_pages();
		?>
	</div>
</article>

