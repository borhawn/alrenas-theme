<?php
/**
 * Comments template.
 *
 * @package Alrenas
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area container">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title"><?php esc_html_e( 'Comments', 'alrenas' ); ?></h2>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true ) ); ?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</section>

