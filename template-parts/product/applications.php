<?php
/**
 * Single-product clinical-applications section (4 numbered cards).
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id = $product->get_id();
$eyebrow    = alrenas_get_product_meta( $product_id, 'applications_eyebrow' );
$heading    = alrenas_get_product_meta( $product_id, 'applications_heading' );
$items_raw  = alrenas_get_product_meta( $product_id, 'applications_items', array() );

$items = array();
for ( $i = 1; $i <= ALRENAS_PRODUCT_APPLICATION_COUNT; $i++ ) {
	if ( ! empty( $items_raw[ $i ]['title'] ) ) {
		$items[] = $items_raw[ $i ];
	}
}

if ( ! $items ) {
	return;
}
?>
<section class="section clinical-applications">
	<div class="container">
		<div class="application-head reveal">
			<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
			<?php if ( $heading ) : ?><h2 style="margin-top:18px"><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
		</div>
		<div class="application-grid">
			<?php foreach ( $items as $i => $item ) : ?>
				<article class="application-card reveal">
					<span><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<p><?php echo esc_html( $item['description'] ?? '' ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
