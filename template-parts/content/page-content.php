<?php
/** Optional block-editor content for designed landing pages. @package Alrenas */

if ( '' === trim( (string) get_the_content() ) ) {
	return;
}
?>
<section class="section page-editor-content">
	<div class="container entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
	</div>
</section>
