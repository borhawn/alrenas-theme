<?php
/** About-page hero. @package Alrenas */
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
$contact_url = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );
$image_id   = get_post_thumbnail_id();
?>
<section class="page-hero about-hero"><div class="container page-hero-grid"><div class="page-hero-copy reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => get_the_title() ) ); ?>
	<span class="eyebrow"><?php esc_html_e( 'About Alrenas', 'alrenas' ); ?></span><h1><?php esc_html_e( 'Rehabilitation technology with recovery at the center.', 'alrenas' ); ?></h1><p class="lead"><?php esc_html_e( 'We develop rehabilitation systems to help healthcare professionals assess movement, guide therapy and support patients toward greater stability, mobility and independence.', 'alrenas' ); ?></p>
	<div class="page-hero-actions"><?php if ( $shop_url ) : ?><a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'Explore Our Systems', 'alrenas' ); ?></a><?php endif; ?><?php if ( $contact_url ) : ?><a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Meet Our Team', 'alrenas' ); ?></a><?php endif; ?></div>
</div><div class="hero-panel-soft reveal">
	<?php if ( $image_id ) : ?><?php echo wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'about-main-img' ) ); ?><?php else : ?><img class="about-main-img" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/blog-devices.webp' ) ); ?>" alt="<?php esc_attr_e( 'Alrenas rehabilitation systems in a clinical environment', 'alrenas' ); ?>"><?php endif; ?>
	<img class="about-small-img" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/rehab-session.webp' ) ); ?>" alt="<?php esc_attr_e( 'Patient using Alrenas rehabilitation equipment', 'alrenas' ); ?>">
</div></div></section>

