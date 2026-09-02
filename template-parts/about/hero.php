<?php
/** About-page hero. @package Alrenas */
$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
$contact_url = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );

$eyebrow         = alrenas_get_site_content( 'about_hero_eyebrow', esc_html__( 'About Alrenas', 'alrenas' ) );
$heading         = alrenas_get_site_content( 'about_hero_heading', esc_html__( 'Rehabilitation technology with recovery at the center.', 'alrenas' ) );
$lead            = alrenas_get_site_content( 'about_hero_lead', esc_html__( 'We develop rehabilitation systems to help healthcare professionals assess movement, guide therapy and support patients toward greater stability, mobility and independence.', 'alrenas' ) );
$primary_label   = alrenas_get_site_content( 'about_hero_primary_label', esc_html__( 'Explore Our Systems', 'alrenas' ) );
$secondary_label = alrenas_get_site_content( 'about_hero_secondary_label', esc_html__( 'Meet Our Team', 'alrenas' ) );

$image_id = (int) alrenas_get_site_content( 'about_hero_image_id', 0 );
$image    = $image_id
	? wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'about-main-img' ) )
	: sprintf( '<img class="about-main-img" src="%s" alt="%s">', esc_url( get_theme_file_uri( 'assets/images/blog-devices.webp' ) ), esc_attr__( 'Alrenas rehabilitation systems in a clinical environment', 'alrenas' ) );
?>
<section class="page-hero about-hero"><div class="container page-hero-grid"><div class="page-hero-copy reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => get_the_title() ) ); ?>
	<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h1><?php echo esc_html( $heading ); ?></h1><p class="lead"><?php echo esc_html( $lead ); ?></p>
	<div class="page-hero-actions"><?php if ( $shop_url ) : ?><a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-primary"><?php echo esc_html( $primary_label ); ?></a><?php endif; ?><?php if ( $contact_url ) : ?><a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-secondary"><?php echo esc_html( $secondary_label ); ?></a><?php endif; ?></div>
</div><div class="hero-panel-soft reveal"><?php echo wp_kses_post( $image ); ?></div></div></section>

