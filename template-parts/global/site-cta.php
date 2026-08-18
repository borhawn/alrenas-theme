<?php
/**
 * Shared rehabilitation call to action.
 *
 * @package Alrenas
 */

$cta_url = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );
?>
<section class="site-cta">
	<div class="container">
		<div class="site-cta-card reveal">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Talk to our rehabilitation team', 'alrenas' ); ?></span>
				<h2><?php esc_html_e( 'Find the right system for your patients and clinical workflow.', 'alrenas' ); ?></h2>
				<p><?php esc_html_e( 'Tell us about your patient groups, treatment goals and facility. We can help you evaluate the appropriate Alrenas system and prepare a tailored quotation.', 'alrenas' ); ?></p>
			</div>
			<?php if ( $cta_url ) : ?>
				<div class="site-cta-actions">
					<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Request a Demo', 'alrenas' ); ?></a>
					<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Request a Quote', 'alrenas' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
