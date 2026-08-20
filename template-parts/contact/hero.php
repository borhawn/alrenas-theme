<?php
/** Contact-page hero. @package Alrenas */
$image_id = get_post_thumbnail_id();
$phone    = alrenas_get_contact_menu_entry( 'tel' );

$eyebrow       = alrenas_get_site_content( 'contact_hero_eyebrow', esc_html__( 'Contact Alrenas', 'alrenas' ) );
$heading       = alrenas_get_site_content( 'contact_hero_heading', esc_html__( 'Start with the rehabilitation need.', 'alrenas' ) );
$lead          = alrenas_get_site_content( 'contact_hero_lead', esc_html__( 'Whether you are evaluating a device for a hospital, physiotherapy clinic, rehabilitation center or research project, tell us what you need to achieve. We\'ll help you take the next step.', 'alrenas' ) );
$primary_label = alrenas_get_site_content( 'contact_hero_primary_label', esc_html__( 'Send an Inquiry', 'alrenas' ) );
?>
<section class="page-hero contact-hero"><div class="container page-hero-grid"><div class="page-hero-copy reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => get_the_title() ) ); ?>
	<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h1><?php echo esc_html( $heading ); ?></h1><p class="lead"><?php echo esc_html( $lead ); ?></p><div class="page-hero-actions"><a href="#inquiry" class="btn btn-primary"><?php echo esc_html( $primary_label ); ?></a><?php if ( $phone ) : ?><a href="<?php echo esc_url( $phone['url'] ); ?>" class="btn btn-secondary"><?php echo esc_html( sprintf( /* translators: %s: phone number. */ __( 'Call %s', 'alrenas' ), $phone['value'] ) ); ?></a><?php endif; ?></div>
</div><div class="hero-panel-soft reveal"><?php if ( $image_id ) : ?><?php echo wp_get_attachment_image( $image_id, 'full' ); ?><?php else : ?><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/contact-device.webp' ) ); ?>" alt="<?php esc_attr_e( 'Patient using Alrenas rehabilitation equipment', 'alrenas' ); ?>"><?php endif; ?></div></div></section>

