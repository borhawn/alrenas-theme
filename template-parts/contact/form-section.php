<?php
/** Contact details and plugin form slot. @package Alrenas */
$contact_entries = alrenas_get_contact_menu_entries();
?>
<section class="section contact-main" id="inquiry"><div class="container contact-main-grid"><aside class="contact-details reveal"><span class="eyebrow"><?php esc_html_e( 'Talk to our team', 'alrenas' ); ?></span><h2><?php esc_html_e( 'We’ll route your request to the right person.', 'alrenas' ); ?></h2><p><?php esc_html_e( 'Share the clinical or practical context rather than trying to fit your question into a generic sales form.', 'alrenas' ); ?></p>
	<?php if ( $contact_entries ) : ?>
		<div class="contact-detail-list">
			<?php foreach ( $contact_entries as $entry ) : ?>
				<div class="contact-detail-item">
					<span><?php echo esc_html( $entry['label'] ); ?></span>
					<?php if ( 'address' === $entry['type'] ) : ?>
						<strong><?php echo esc_html( $entry['value'] ); ?></strong>
					<?php else : ?>
						<a href="<?php echo esc_url( $entry['url'] ); ?>"><?php echo esc_html( $entry['value'] ); ?></a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="contact-map-note"><?php esc_html_e( 'For urgent technical assistance, calling the team directly is the fastest route.', 'alrenas' ); ?></div></aside>
	<?php if ( is_active_sidebar( 'contact-page-form' ) ) : ?><div class="contact-form-card reveal"><div class="contact-form-head"><div><h3><?php esc_html_e( 'Tell us how we can help.', 'alrenas' ); ?></h3><p><?php esc_html_e( 'For quotations and demos, include the facility type, product of interest and intended clinical use where possible.', 'alrenas' ); ?></p></div><span class="intent-badge" data-intent-badge><?php esc_html_e( 'Product guidance', 'alrenas' ); ?></span></div><?php dynamic_sidebar( 'contact-page-form' ); ?></div><?php endif; ?>
</div></section>

