<?php
/** WooCommerce product inquiry section. @package Alrenas */

if ( ! is_active_sidebar( 'product-inquiry-form' ) ) {
	return;
}
?>
<section class="contact-section product-inquiry" id="inquiry">
	<div class="container contact-grid">
		<div class="contact-copy reveal">
			<span class="eyebrow"><?php esc_html_e( 'Request a quote or demo', 'alrenas' ); ?></span>
			<h2><?php esc_html_e( 'Discuss this system with our rehabilitation team.', 'alrenas' ); ?></h2>
			<p><?php esc_html_e( 'Tell us about your facility, patient population and intended clinical use. We will help define the appropriate configuration and next step.', 'alrenas' ); ?></p>
			<div class="inquiry-choice">
				<div><strong><?php esc_html_e( 'Request a quote', 'alrenas' ); ?></strong><span><?php esc_html_e( 'For purchasing, procurement or distributor inquiries.', 'alrenas' ); ?></span></div>
				<div><strong><?php esc_html_e( 'Request a demo', 'alrenas' ); ?></strong><span><?php esc_html_e( 'For clinical evaluation, training or research use.', 'alrenas' ); ?></span></div>
			</div>
		</div>
		<div class="contact-form reveal">
			<?php dynamic_sidebar( 'product-inquiry-form' ); ?>
		</div>
	</div>
</section>
