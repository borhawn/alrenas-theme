<?php
/**
 * Single-product proof strip (4 headline stats right under the hero).
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$saved = alrenas_get_product_meta( $product->get_id(), 'proof_stats', array() );
$rows  = array();

for ( $i = 1; $i <= ALRENAS_PRODUCT_PROOF_COUNT; $i++ ) {
	if ( ! empty( $saved[ $i ]['value'] ) ) {
		$rows[] = $saved[ $i ];
	}
}

if ( ! $rows ) {
	return;
}
?>
<section class="product-proof">
	<div class="container product-proof-grid">
		<?php foreach ( $rows as $row ) : ?>
			<div><strong><?php echo esc_html( $row['value'] ); ?></strong><span><?php echo esc_html( $row['label'] ?? '' ); ?></span></div>
		<?php endforeach; ?>
	</div>
</section>
