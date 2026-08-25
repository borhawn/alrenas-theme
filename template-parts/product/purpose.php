<?php
/**
 * Single-product "purpose" section: narrative paragraph (reuses this
 * product's native Description field) plus a numbered 4-step breakdown.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id = $product->get_id();
$eyebrow    = alrenas_get_product_meta( $product_id, 'purpose_eyebrow' );
$heading    = alrenas_get_product_meta( $product_id, 'purpose_heading' );
$paragraph  = $product->get_description();
$items_raw  = alrenas_get_product_meta( $product_id, 'purpose_items', array() );

$items = array();
for ( $i = 1; $i <= ALRENAS_PRODUCT_PURPOSE_COUNT; $i++ ) {
	if ( ! empty( $items_raw[ $i ]['title'] ) ) {
		$items[] = $items_raw[ $i ];
	}
}

if ( ! $heading && ! $paragraph && ! $items ) {
	return;
}
?>
<section class="section product-purpose">
	<div class="container product-purpose-grid">
		<div class="section-heading reveal">
			<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
			<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
		</div>
		<div class="product-purpose-copy reveal">
			<?php if ( $paragraph ) : ?><p><?php echo wp_kses_post( wpautop( $paragraph ) ); ?></p><?php endif; ?>
			<?php if ( $items ) : ?>
				<div class="purpose-list">
					<?php foreach ( $items as $i => $item ) : ?>
						<article class="purpose-item">
							<span><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) . ( ! empty( $item['step'] ) ? ' / ' . $item['step'] : '' ) ); ?></span>
							<h3><?php echo esc_html( $item['title'] ); ?></h3>
							<p><?php echo esc_html( $item['description'] ?? '' ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
