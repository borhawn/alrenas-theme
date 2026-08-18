<?php
/** Products landing hero. @package Alrenas */
$hero_products = function_exists( 'wc_get_products' ) ? wc_get_products( array( 'limit' => 2, 'status' => 'publish', 'visibility' => 'catalog', 'orderby' => 'date', 'order' => 'DESC' ) ) : array();
$contact_url   = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );
?>
<section class="page-hero products-hero"><div class="container page-hero-grid"><div class="page-hero-copy reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => get_the_title() ) ); ?>
	<span class="eyebrow"><?php esc_html_e( 'Rehabilitation systems', 'alrenas' ); ?></span><h1><?php esc_html_e( 'Technology shaped around the way recovery happens.', 'alrenas' ); ?></h1><p class="lead"><?php esc_html_e( 'Clinical systems for balance assessment, guided physiotherapy and supported mobility — selected according to the patient, treatment goal and level of assistance required.', 'alrenas' ); ?></p><div class="page-hero-actions"><a href="#systems" class="btn btn-primary"><?php esc_html_e( 'Explore Systems', 'alrenas' ); ?></a><?php if ( $contact_url ) : ?><a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Get Product Guidance', 'alrenas' ); ?></a><?php endif; ?></div>
</div><div class="hero-panel-soft reveal"><?php foreach ( $hero_products as $index => $product ) : ?><?php echo wp_kses_post( $product->get_image( 'woocommerce_single', array( 'class' => 0 === $index ? 'mini-device' : '' ) ) ); ?><?php endforeach; ?></div></div></section>

