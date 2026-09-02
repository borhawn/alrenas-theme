<?php
/**
 * Single-product software showcase: an admin-managed, add/remove-able set
 * of software screens (title, description, 16:9 screenshot), shown as
 * alternating full-size image/copy rows -- the same "big focused image"
 * pattern used for the homepage's product rows and the /products systems
 * list -- rather than a tag/tab switcher. Needs no JS: every screen is
 * simply on the page, in order.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id = $product->get_id();
$eyebrow    = alrenas_get_product_meta( $product_id, 'software_eyebrow' );
$heading    = alrenas_get_product_meta( $product_id, 'software_heading' );
$lead       = alrenas_get_product_meta( $product_id, 'software_lead' );
$raw_items  = alrenas_get_product_meta( $product_id, 'software_items', array() );

$items = array();
foreach ( $raw_items as $item ) {
	if ( ! empty( $item['title'] ) ) {
		$items[] = $item;
	}
}

if ( ! $items ) {
	return;
}
?>
<section class="section product-software" id="software">
	<div class="container">
		<div class="workflow-heading reveal">
			<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
			<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
			<?php if ( $lead ) : ?><p><?php echo esc_html( $lead ); ?></p><?php endif; ?>
		</div>

		<div class="software-showcase">
			<?php foreach ( $items as $i => $item ) : ?>
				<?php $image_id = ! empty( $item['image_id'] ) ? (int) $item['image_id'] : 0; ?>
				<article class="software-row<?php echo 1 === $i % 2 ? ' software-row--reverse' : ''; ?> reveal">
					<div class="software-row-media">
						<?php if ( $image_id ) : ?><?php echo wp_get_attachment_image( $image_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?><?php endif; ?>
					</div>
					<div class="software-row-copy">
						<span class="software-row-index"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<?php if ( ! empty( $item['description'] ) ) : ?><p><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
