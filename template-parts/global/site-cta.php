<?php
/**
 * Shared rehabilitation call to action -- reused on the homepage, blog
 * index, single posts, the Products page, and the About page. Content and
 * both button links are editable in one place: Site Content > Site CTA.
 *
 * @package Alrenas
 */

$contact_url = alrenas_get_contact_page_url();

$eyebrow         = alrenas_get_site_content( 'site_cta_eyebrow', esc_html__( 'Talk to our rehabilitation team', 'alrenas' ) );
$heading         = alrenas_get_site_content( 'site_cta_heading', esc_html__( 'Find the right system for your patients and clinical workflow.', 'alrenas' ) );
$lead            = alrenas_get_site_content( 'site_cta_lead', esc_html__( 'Tell us about your patient groups, treatment goals and facility. We can help you evaluate the appropriate Alrenas system and prepare a tailored quotation.', 'alrenas' ) );
$primary_label   = alrenas_get_site_content( 'site_cta_primary_label', esc_html__( 'Request a Demo', 'alrenas' ) );
$primary_url     = alrenas_get_site_content( 'site_cta_primary_url', trailingslashit( $contact_url ) . '#inquiry' );
$secondary_label = alrenas_get_site_content( 'site_cta_secondary_label', esc_html__( 'Contact Us', 'alrenas' ) );
$secondary_url   = alrenas_get_site_content( 'site_cta_secondary_url', $contact_url );
?>
<section class="site-cta">
	<div class="container">
		<div class="site-cta-card reveal">
			<div>
				<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<h2><?php echo esc_html( $heading ); ?></h2>
				<p><?php echo esc_html( $lead ); ?></p>
			</div>
			<?php if ( $primary_url || $secondary_url ) : ?>
				<div class="site-cta-actions">
					<?php if ( $primary_url && $primary_label ) : ?>
						<a href="<?php echo esc_url( $primary_url ); ?>" class="btn btn-secondary"><?php echo esc_html( $primary_label ); ?></a>
					<?php endif; ?>
					<?php if ( $secondary_url && $secondary_label ) : ?>
						<a href="<?php echo esc_url( $secondary_url ); ?>" class="btn btn-secondary"><?php echo esc_html( $secondary_label ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
