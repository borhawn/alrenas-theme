<?php
/**
 * Single-product software showcase: an admin-managed, add/remove-able set
 * of software screens (title, description, 16:9 screenshot). Switches
 * between screens via the theme's existing generic [data-tabs] handler
 * (same one driving the Clinical Workflow tabs), so no extra JS is
 * needed here -- and it renders fine with just one screen and no tab
 * list at all.
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

		<div class="software-tabs" data-tabs>
			<?php if ( count( $items ) > 1 ) : ?>
				<div class="software-tab-list reveal" role="tablist" aria-label="<?php echo esc_attr( $product->get_name() ); ?> software">
					<?php foreach ( $items as $i => $item ) : ?>
						<button class="software-tab<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" data-tab="sw-<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $item['title'] ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="software-panels reveal">
				<?php foreach ( $items as $i => $item ) : ?>
					<?php $image_id = ! empty( $item['image_id'] ) ? (int) $item['image_id'] : 0; ?>
					<article class="software-panel" data-panel="sw-<?php echo esc_attr( $i ); ?>" <?php echo 0 === $i ? '' : 'hidden'; ?>>
						<div class="software-panel-copy">
							<h3><?php echo esc_html( $item['title'] ); ?></h3>
							<?php if ( ! empty( $item['description'] ) ) : ?><p><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
						</div>
						<?php if ( $image_id ) : ?>
							<div class="software-panel-media"><?php echo wp_get_attachment_image( $image_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?></div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
