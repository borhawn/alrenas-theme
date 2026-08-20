<?php
/**
 * Single-product feature-spotlight section (image + caption, copy, and a
 * 3-item feature list) -- fully per-product.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id    = $product->get_id();
$eyebrow       = alrenas_get_product_meta( $product_id, 'care_eyebrow' );
$heading       = alrenas_get_product_meta( $product_id, 'care_heading' );
$paragraph     = alrenas_get_product_meta( $product_id, 'care_paragraph' );
$image_id      = (int) alrenas_get_product_meta( $product_id, 'care_image_id', 0 );
$caption_label = alrenas_get_product_meta( $product_id, 'care_caption_label' );
$caption_text  = alrenas_get_product_meta( $product_id, 'care_caption_text' );
$features_raw  = alrenas_get_product_meta( $product_id, 'care_features', array() );

$features = array();
for ( $i = 1; $i <= ALRENAS_PRODUCT_FEATURE_COUNT; $i++ ) {
	if ( ! empty( $features_raw[ $i ]['title'] ) ) {
		$features[] = $features_raw[ $i ];
	}
}

if ( ! $heading && ! $image_id ) {
	return;
}
?>
<section class="section dynamic-care">
	<div class="container dynamic-care-grid">
		<?php if ( $image_id ) : ?>
			<div class="dynamic-care-media reveal">
				<?php echo wp_get_attachment_image( $image_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?>
				<?php if ( $caption_text ) : ?>
					<div class="dynamic-care-caption">
						<?php if ( $caption_label ) : ?><span><?php echo esc_html( $caption_label ); ?></span><?php endif; ?>
						<strong><?php echo esc_html( $caption_text ); ?></strong>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="dynamic-care-copy reveal">
			<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
			<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
			<?php if ( $paragraph ) : ?><p><?php echo esc_html( $paragraph ); ?></p><?php endif; ?>
			<?php if ( $features ) : ?>
				<div class="care-feature-list">
					<?php foreach ( $features as $i => $feature ) : ?>
						<article><span><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span><div><h3><?php echo esc_html( $feature['title'] ); ?></h3><p><?php echo esc_html( $feature['description'] ?? '' ); ?></p></div></article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
