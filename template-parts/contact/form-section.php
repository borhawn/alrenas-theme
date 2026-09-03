<?php
/** Contact details and plugin form slot. @package Alrenas */

$contact_entries = alrenas_get_contact_menu_entries();

$details_eyebrow = alrenas_get_site_content( 'contact_details_eyebrow', esc_html__( 'Talk to our team', 'alrenas' ) );
$details_heading = alrenas_get_site_content( 'contact_details_heading', esc_html__( 'We\'ll route your request to the right person.', 'alrenas' ) );
$details_lead     = alrenas_get_site_content( 'contact_details_lead', esc_html__( 'Share the clinical or practical context rather than trying to fit your question into a generic sales form.', 'alrenas' ) );
$map_embed_url    = alrenas_get_site_content( 'contact_map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3007.8593075641065!2d28.79895727665433!3d41.07206791549381!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14caaffeeb36df9b%3A0x9d9cfbe2c8d62259!2sAlrenas%20Teknoloji%20A.%C5%9E.!5e0!3m2!1sen!2str!4v1788445118996!5m2!1sen!2str' );
$form_heading      = alrenas_get_site_content( 'contact_form_heading', esc_html__( 'Tell us how we can help.', 'alrenas' ) );
$form_lead         = alrenas_get_site_content( 'contact_form_lead', esc_html__( 'For quotations and demos, include the facility type, product of interest and intended clinical use where possible.', 'alrenas' ) );

$first_option        = alrenas_get_site_content( 'contact_options_items', array() );
$default_intent_badge = ! empty( $first_option[1]['title'] ) ? $first_option[1]['title'] : esc_html__( 'Product guidance', 'alrenas' );
?>
<section class="section contact-main" id="inquiry"><div class="container contact-main-grid"><aside class="contact-details reveal"><span class="eyebrow"><?php echo esc_html( $details_eyebrow ); ?></span><h2><?php echo esc_html( $details_heading ); ?></h2><p><?php echo esc_html( $details_lead ); ?></p>
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
	<?php if ( $map_embed_url ) : ?><div class="contact-map"><iframe src="<?php echo esc_url( $map_embed_url ); ?>" title="<?php esc_attr_e( 'Alrenas location map', 'alrenas' ); ?>" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div><?php endif; ?>
	</aside>
	<?php if ( is_active_sidebar( 'contact-page-form' ) ) : ?><div class="contact-form-card reveal"><div class="contact-form-head"><div><h3><?php echo esc_html( $form_heading ); ?></h3><p><?php echo esc_html( $form_lead ); ?></p></div><span class="intent-badge" data-intent-badge><?php echo esc_html( $default_intent_badge ); ?></span></div><?php dynamic_sidebar( 'contact-page-form' ); ?></div><?php endif; ?>
</div></section>
