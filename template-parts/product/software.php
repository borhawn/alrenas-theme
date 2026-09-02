<?php
/**
 * Single-product software showcase: an admin-managed, add/remove-able set
 * of software screens (title, description, 16:9 screenshot), shown as a
 * pinned-scroll narrative on desktop -- a single sticky image on the
 * right while the titles/descriptions scroll past on the left, swapping
 * to match whichever step is centered in the viewport (IntersectionObserver
 * in assets/js/product.js, no scroll-position math, no added library). On
 * narrower screens the pin is dropped in favor of a plain stacked list --
 * each screenshot directly above its own title/description.
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
	</div>

	<div class="container software-scroll-grid" data-software-scroll>
		<div class="software-scroll-copy">
			<?php foreach ( $items as $i => $item ) : ?>
				<?php $image_id = ! empty( $item['image_id'] ) ? (int) $item['image_id'] : 0; ?>
				<div class="software-step<?php echo 0 === $i ? ' is-active' : ''; ?>" data-software-step data-step-index="<?php echo esc_attr( $i ); ?>">
					<?php if ( $image_id ) : ?>
						<div class="software-step-media"><?php echo wp_get_attachment_image( $image_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?></div>
					<?php endif; ?>
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<?php if ( ! empty( $item['description'] ) ) : ?><p><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( count( $items ) > 1 ) : ?>
			<div class="software-scroll-visual">
				<div class="software-visual-sticky">
					<?php foreach ( $items as $i => $item ) : ?>
						<?php $image_id = ! empty( $item['image_id'] ) ? (int) $item['image_id'] : 0; ?>
						<?php if ( $image_id ) : ?>
							<div class="software-visual-frame<?php echo 0 === $i ? ' is-active' : ''; ?>" data-step-index="<?php echo esc_attr( $i ); ?>">
								<?php echo wp_get_attachment_image( $image_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
