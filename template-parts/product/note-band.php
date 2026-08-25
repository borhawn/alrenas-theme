<?php
/**
 * Single-product clinical note band: a short highlighted callout with a
 * bullet list.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id = $product->get_id();
$eyebrow    = alrenas_get_product_meta( $product_id, 'note_eyebrow' );
$heading    = alrenas_get_product_meta( $product_id, 'note_heading' );
$paragraph  = alrenas_get_product_meta( $product_id, 'note_paragraph' );
$list_raw   = alrenas_get_product_meta( $product_id, 'note_list' );
$list_items = $list_raw ? array_filter( array_map( 'trim', explode( "\n", $list_raw ) ) ) : array();

if ( ! $heading && ! $paragraph && ! $list_items ) {
	return;
}
?>
<section class="clinical-note-band">
	<div class="container clinical-note-grid">
		<div class="reveal">
			<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
			<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
		</div>
		<div class="reveal">
			<?php if ( $paragraph ) : ?><p><?php echo esc_html( $paragraph ); ?></p><?php endif; ?>
			<?php if ( $list_items ) : ?>
				<ul>
					<?php foreach ( $list_items as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
