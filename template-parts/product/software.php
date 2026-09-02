<?php
/**
 * Single-product software showcase. Desktop: the section locks in place
 * (GSAP ScrollTrigger pin) once it reaches the center of the viewport --
 * eyebrow + title + description on the left, one big image on the right.
 * The title/description start out as this section's own intro copy, then
 * each scroll step replaces them with that software screen's own title
 * and description, in sync with the image swapping on the right (see
 * assets/js/product.js). Below ~900px the pin is dropped in favor of a
 * plain stacked list -- see the CSS breakpoint in product.css.
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

$first_image_id  = ! empty( $items[0]['image_id'] ) ? (int) $items[0]['image_id'] : 0;
$first_image_url = $first_image_id ? wp_get_attachment_image_url( $first_image_id, 'large' ) : wc_placeholder_img_src( 'large' );
$first_image_alt = $first_image_id ? get_post_meta( $first_image_id, '_wp_attachment_image_alt', true ) : $items[0]['title'];

// The <img> swapped by JS as the visitor scrolls has to always exist in
// the DOM (so product.js has a stable element to update), so this can
// never fall back to an empty src -- unlike the rest of this theme's
// per-item images, which simply aren't rendered at all when unset.
$steps = array();
foreach ( $items as $item ) {
	$image_id = ! empty( $item['image_id'] ) ? (int) $item['image_id'] : 0;
	$steps[]  = array(
		'title'       => $item['title'],
		'description' => ! empty( $item['description'] ) ? $item['description'] : '',
		'image'       => $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : wc_placeholder_img_src( 'large' ),
		'alt'         => $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : $item['title'],
	);
}
?>
<section class="section product-software" id="software">
	<div class="container software-pin-wrap" data-software-pin>
		<div class="software-pin-grid">
			<div class="software-pin-copy">
				<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
				<h2 data-software-title><?php echo esc_html( $heading ); ?></h2>
				<p data-software-description><?php echo esc_html( $lead ); ?></p>
			</div>
			<div class="software-pin-visual">
				<img data-software-image src="<?php echo esc_url( $first_image_url ); ?>" alt="<?php echo esc_attr( $first_image_alt ); ?>">
			</div>
		</div>
	</div>

	<div class="software-stack">
		<div class="container">
			<?php foreach ( $items as $item ) : ?>
				<?php $image_id = ! empty( $item['image_id'] ) ? (int) $item['image_id'] : 0; ?>
				<div class="software-stack-item">
					<?php if ( $image_id ) : ?>
						<div class="software-stack-media"><?php echo wp_get_attachment_image( $image_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?></div>
					<?php endif; ?>
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<?php if ( ! empty( $item['description'] ) ) : ?><p><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( count( $items ) > 1 ) : ?>
		<script type="application/json" data-software-steps><?php echo wp_json_encode( $steps ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode() escapes forward slashes by default, so a "</script>" substring in any title/description can't break out of this tag. ?></script>
	<?php endif; ?>
</section>
