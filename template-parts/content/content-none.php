<?php
/**
 * Empty-result state.
 *
 * @package Alrenas
 */
?>
<section class="no-results not-found container">
	<header class="page-header"><h1><?php esc_html_e( 'Nothing found', 'alrenas' ); ?></h1></header>
	<div class="page-content">
		<p><?php esc_html_e( 'No content matched your request.', 'alrenas' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</section>

