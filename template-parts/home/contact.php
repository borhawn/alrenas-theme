<?php
/**
 * Homepage contact section.
 *
 * Form markup and submission behavior belong to the plugin/widget selected by
 * the site administrator.
 *
 * @package Alrenas
 */
?>
<section class="contact-section" id="contact">
	<div class="container contact-grid">
		<div class="contact-copy reveal">
			<span class="eyebrow"><?php esc_html_e( 'Request a demo', 'alrenas' ); ?></span>
			<h2><?php esc_html_e( 'See how Alrenas can fit your rehabilitation center.', 'alrenas' ); ?></h2>
			<p><?php esc_html_e( 'Tell us about your clinic, hospital or rehabilitation program. Our team can help you identify the right system for your patients and workflow.', 'alrenas' ); ?></p>
			<?php if ( has_nav_menu( 'contact' ) ) : ?>
				<div class="contact-details">
					<?php wp_nav_menu( array( 'theme_location' => 'contact', 'container' => false, 'fallback_cb' => false, 'depth' => 1 ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( is_active_sidebar( 'home-contact-form' ) ) : ?>
			<div class="contact-form reveal">
				<?php dynamic_sidebar( 'home-contact-form' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>

