<?php
/**
 * Single-product care strip (4 short capability items).
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$items = alrenas_get_product_meta( $product->get_id(), 'care_strip', array() );
$rows  = array();

for ( $i = 1; $i <= ALRENAS_PRODUCT_CARE_STRIP_COUNT; $i++ ) {
	$item = isset( $items[ $i ] ) ? $items[ $i ] : array();

	if ( empty( $item['label'] ) ) {
		continue;
	}

	$rows[] = $item;
}

if ( ! $rows ) {
	return;
}
?>
<section class="product-care-strip" aria-label="<?php esc_attr_e( 'Key capabilities', 'alrenas' ); ?>">
	<div class="container product-care-strip-grid">
		<?php foreach ( $rows as $row ) : ?>
			<div><strong><?php echo esc_html( $row['label'] ); ?></strong><span><?php echo esc_html( $row['description'] ?? '' ); ?></span></div>
		<?php endforeach; ?>
	</div>
</section>
