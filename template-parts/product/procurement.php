<?php
/**
 * Single-product "before you quote" section (4 questions to prompt the
 * visitor to think through before requesting a quote).
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id = $product->get_id();
$eyebrow    = alrenas_get_site_content( 'sp_procurement_eyebrow', esc_html__( 'Before requesting a quote', 'alrenas' ) );
$heading    = alrenas_get_product_meta( $product_id, 'procurement_heading' );
$lead       = alrenas_get_product_meta( $product_id, 'procurement_lead' );
$items_raw  = alrenas_get_product_meta( $product_id, 'procurement_items', array() );

$items = array();
for ( $i = 1; $i <= ALRENAS_PRODUCT_PROCUREMENT_COUNT; $i++ ) {
	if ( ! empty( $items_raw[ $i ]['title'] ) ) {
		$items[] = $items_raw[ $i ];
	}
}

if ( ! $heading && ! $items ) {
	return;
}
?>
<section class="section procurement-section">
	<div class="container procurement-grid">
		<div class="procurement-copy reveal">
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
			<?php if ( $lead ) : ?><p><?php echo esc_html( $lead ); ?></p><?php endif; ?>
		</div>
		<?php if ( $items ) : ?>
			<div class="procurement-list reveal">
				<?php foreach ( $items as $item ) : ?>
					<article class="procurement-item"><strong><?php echo esc_html( $item['title'] ); ?></strong><p><?php echo esc_html( $item['description'] ?? '' ); ?></p></article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
