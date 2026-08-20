<?php
/**
 * Single-product gallery -- reuses WooCommerce's native product image +
 * gallery images (set in the usual "Product image"/"Product gallery"
 * boxes on the product editor). No new fields needed.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$image_ids = array_filter( array_merge( array( $product->get_image_id() ), $product->get_gallery_image_ids() ) );

if ( ! $image_ids ) {
	return;
}

$images = array();
foreach ( $image_ids as $image_id ) {
	$url = wp_get_attachment_image_url( $image_id, 'large' );
	if ( $url ) {
		$images[] = array(
			'id'  => $image_id,
			'url' => $url,
			'alt' => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
		);
	}
}

if ( ! $images ) {
	return;
}

$eyebrow = alrenas_get_site_content( 'sp_gallery_eyebrow', esc_html__( 'Product gallery', 'alrenas' ) );
$heading = alrenas_get_site_content( 'sp_gallery_heading', esc_html__( 'See the system in detail.', 'alrenas' ) );
$lead    = alrenas_get_site_content( 'sp_gallery_lead', esc_html__( 'Product, clinical use and software views from the current system.', 'alrenas' ) );
$main    = $images[0];
?>
<section class="section product-gallery-section">
	<div class="container gallery-heading reveal">
		<div><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h2><?php echo esc_html( $heading ); ?></h2></div>
		<?php if ( $lead ) : ?><p><?php echo esc_html( $lead ); ?></p><?php endif; ?>
	</div>
	<div class="container product-gallery reveal" data-product-gallery>
		<button class="gallery-main" type="button" data-gallery-main aria-label="<?php esc_attr_e( 'Open selected image', 'alrenas' ); ?>">
			<img src="<?php echo esc_url( $main['url'] ); ?>" alt="<?php echo esc_attr( $main['alt'] ? $main['alt'] : $product->get_name() ); ?>">
		</button>
		<?php if ( count( $images ) > 1 ) : ?>
			<div class="gallery-thumbs" role="list">
				<?php foreach ( $images as $i => $image ) : ?>
					<button class="gallery-thumb<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" data-gallery-src="<?php echo esc_url( $image['url'] ); ?>" data-gallery-alt="<?php echo esc_attr( $image['alt'] ? $image['alt'] : $product->get_name() ); ?>"><img src="<?php echo esc_url( $image['url'] ); ?>" alt=""></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
