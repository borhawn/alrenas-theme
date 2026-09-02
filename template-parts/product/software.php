<?php
/**
 * Single-product software showcase: an admin-managed, add/remove-able set
 * of software screens (title, description, 16:9 screenshot). Only the
 * section's own eyebrow is fixed; its title and description come from
 * whichever screen is currently selected. Title/description sit on the
 * left, with the image, a thumbnail strip and dot navigation on the
 * right -- switching via the theme's existing generic [data-tabs]
 * handler (same one driving the homepage specialties and Clinical
 * Workflow tabs), so no bespoke JS is needed here. Renders fine with
 * just one screen and no thumbnails/dots at all.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id = $product->get_id();
$eyebrow    = alrenas_get_product_meta( $product_id, 'software_eyebrow' );
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
		<div class="showcase-tabs" data-tabs data-software-autoplay="5000">
			<div class="showcase-layout reveal">
				<div class="showcase-copy">
					<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
					<?php foreach ( $items as $i => $item ) : ?>
						<div class="showcase-fade" data-panel="sw-<?php echo esc_attr( $i ); ?>" <?php echo 0 === $i ? '' : 'hidden'; ?>>
							<h2><?php echo esc_html( $item['title'] ); ?></h2>
							<?php if ( ! empty( $item['description'] ) ) : ?><p><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="showcase-visual">
					<div class="showcase-media-stack">
						<?php foreach ( $items as $i => $item ) : ?>
							<?php $image_id = ! empty( $item['image_id'] ) ? (int) $item['image_id'] : 0; ?>
							<?php if ( $image_id ) : ?>
								<div class="showcase-fade showcase-media-panel" data-panel="sw-<?php echo esc_attr( $i ); ?>" <?php echo 0 === $i ? '' : 'hidden'; ?>>
									<?php echo wp_get_attachment_image( $image_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>

					<?php if ( count( $items ) > 1 ) : ?>
						<div class="showcase-thumb-list" role="tablist" aria-label="<?php echo esc_attr( $product->get_name() ); ?> software">
							<?php foreach ( $items as $i => $item ) : ?>
								<?php $image_id = ! empty( $item['image_id'] ) ? (int) $item['image_id'] : 0; ?>
								<button class="showcase-thumb<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( $item['title'] ); ?>" data-tab="sw-<?php echo esc_attr( $i ); ?>">
									<span class="showcase-thumb-media"><?php echo $image_id ? wp_get_attachment_image( $image_id, 'thumbnail' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?></span>
								</button>
							<?php endforeach; ?>
						</div>

						<div class="showcase-dots" role="tablist" aria-label="<?php esc_attr_e( 'Jump to software screen', 'alrenas' ); ?>">
							<?php foreach ( $items as $i => $item ) : ?>
								<button class="showcase-dot<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( $item['title'] ); ?>" data-tab="sw-<?php echo esc_attr( $i ); ?>"></button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
