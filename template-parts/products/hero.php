<?php
/** Products landing hero. @package Alrenas */

$eyebrow        = alrenas_get_site_content( 'products_hero_eyebrow', esc_html__( 'Rehabilitation systems', 'alrenas' ) );
$heading        = alrenas_get_site_content( 'products_hero_heading', esc_html__( 'Technology shaped around the way recovery happens.', 'alrenas' ) );
$lead           = alrenas_get_site_content( 'products_hero_lead', esc_html__( 'Clinical systems for balance assessment, guided physiotherapy and supported mobility — selected according to the patient, treatment goal and level of assistance required.', 'alrenas' ) );
$primary_label  = alrenas_get_site_content( 'products_hero_primary_label', esc_html__( 'Explore Systems', 'alrenas' ) );
$secondary_label = alrenas_get_site_content( 'products_hero_secondary_label', esc_html__( 'Get Product Guidance', 'alrenas' ) );
$contact_url    = get_theme_mod( 'alrenas_header_cta_url', home_url( '/' ) );

$main_product_id = (int) alrenas_get_site_content( 'products_hero_main_product_id', 0 );
$mini_product_id = (int) alrenas_get_site_content( 'products_hero_mini_product_id', 0 );

$main_image = '';
$mini_image = '';

if ( function_exists( 'wc_get_product' ) ) {
	$main_product = $main_product_id ? wc_get_product( $main_product_id ) : null;
	$mini_product = $mini_product_id ? wc_get_product( $mini_product_id ) : null;

	if ( $main_product ) {
		$main_image = $main_product->get_image( 'woocommerce_single' );
	}

	if ( $mini_product ) {
		$mini_image = $mini_product->get_image( 'woocommerce_single', array( 'class' => 'mini-device' ) );
	}

	// Fall back to the 2 most recent products for whichever slot isn't configured.
	if ( ! $main_image || ! $mini_image ) {
		$fallback = wc_get_products(
			array(
				'limit'      => 2,
				'status'     => 'publish',
				'visibility' => 'catalog',
				'orderby'    => 'date',
				'order'      => 'DESC',
				'exclude'    => array_filter( array( $main_product_id, $mini_product_id ) ),
			)
		);

		foreach ( $fallback as $product ) {
			if ( ! $main_image ) {
				$main_image = $product->get_image( 'woocommerce_single' );
				continue;
			}

			if ( ! $mini_image ) {
				$mini_image = $product->get_image( 'woocommerce_single', array( 'class' => 'mini-device' ) );
			}
		}
	}
}
?>
<section class="page-hero products-hero"><div class="container page-hero-grid"><div class="page-hero-copy reveal">
	<?php get_template_part( 'template-parts/global/breadcrumbs', null, array( 'current_label' => esc_html__( 'Products', 'alrenas' ) ) ); ?>
	<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h1><?php echo esc_html( $heading ); ?></h1><p class="lead"><?php echo esc_html( $lead ); ?></p><div class="page-hero-actions"><a href="#systems" class="btn btn-primary"><?php echo esc_html( $primary_label ); ?></a><?php if ( $contact_url ) : ?><a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-secondary"><?php echo esc_html( $secondary_label ); ?></a><?php endif; ?></div>
</div><div class="hero-panel-soft reveal"><?php echo wp_kses_post( $mini_image ); ?><?php echo wp_kses_post( $main_image ); ?></div></div></section>
