<?php
/**
 * Single-product YouTube video section.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$embed_url = alrenas_get_youtube_embed_url( alrenas_get_product_meta( $product->get_id(), 'video_url' ) );

if ( ! $embed_url ) {
	return;
}

$eyebrow = alrenas_get_site_content( 'sp_video_eyebrow', esc_html__( 'See it in action', 'alrenas' ) );
$heading = alrenas_get_site_content( 'sp_video_heading', esc_html__( 'Watch the system in a clinical setting.', 'alrenas' ) );
?>
<section class="section product-video">
	<div class="container">
		<div class="video-heading reveal">
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<h2><?php echo esc_html( $heading ); ?></h2>
		</div>
		<div class="video-embed reveal">
			<iframe src="<?php echo esc_url( $embed_url ); ?>" title="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
		</div>
	</div>
</section>
