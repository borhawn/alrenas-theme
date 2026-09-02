<?php
/** Products landing hero. @package Alrenas */

$eyebrow        = alrenas_get_site_content( 'products_hero_eyebrow', esc_html__( 'Rehabilitation systems', 'alrenas' ) );
$heading        = alrenas_get_site_content( 'products_hero_heading', esc_html__( 'Technology shaped around the way recovery happens.', 'alrenas' ) );
$lead           = alrenas_get_site_content( 'products_hero_lead', esc_html__( 'Clinical systems for balance assessment, guided physiotherapy and supported mobility — selected according to the patient, treatment goal and level of assistance required.', 'alrenas' ) );
$primary_label  = alrenas_get_site_content( 'products_hero_primary_label', esc_html__( 'Explore Systems', 'alrenas' ) );
$secondary_label = alrenas_get_site_content( 'products_hero_secondary_label', esc_html__( 'Get Product Guidance', 'alrenas' ) );
$contact_url    = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );

$hero_image_id = (int) alrenas_get_site_content( 'products_hero_image_id', 0 );
$hero_image    = $hero_image_id ? wp_get_attachment_image( $hero_image_id, 'large' ) : '';
?>
<section class="page-hero products-hero"><div class="container page-hero-grid"><div class="page-hero-copy reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => esc_html__( 'Products', 'alrenas' ) ) ); ?>
	<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h1><?php echo esc_html( $heading ); ?></h1><p class="lead"><?php echo esc_html( $lead ); ?></p><div class="page-hero-actions"><a href="#systems" class="btn btn-primary"><?php echo esc_html( $primary_label ); ?></a><?php if ( $contact_url ) : ?><a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-secondary"><?php echo esc_html( $secondary_label ); ?></a><?php endif; ?></div>
</div><div class="hero-panel-soft reveal"><?php echo wp_kses_post( $hero_image ); ?></div></div></section>
