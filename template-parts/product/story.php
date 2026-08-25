<?php
/**
 * Single-product feature-story section: image + eyebrow/heading/paragraph
 * and a 4-item feature checklist.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id = $product->get_id();
$eyebrow    = alrenas_get_product_meta( $product_id, 'story_eyebrow' );
$heading    = alrenas_get_product_meta( $product_id, 'story_heading' );
$paragraph  = alrenas_get_product_meta( $product_id, 'story_paragraph' );
$image_id   = (int) alrenas_get_product_meta( $product_id, 'story_image_id', 0 );
$checks_raw = alrenas_get_product_meta( $product_id, 'feature_checks', array() );

$checks = array();
for ( $i = 1; $i <= ALRENAS_PRODUCT_FEATURE_COUNT; $i++ ) {
	if ( ! empty( $checks_raw[ $i ]['title'] ) ) {
		$checks[] = $checks_raw[ $i ];
	}
}

if ( ! $heading && ! $image_id ) {
	return;
}
?>
<section class="section software-story">
	<div class="container software-story-grid">
		<?php if ( $image_id ) : ?>
			<div class="software-media software-media--contain reveal">
				<?php echo wp_get_attachment_image( $image_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- self-escaping. ?>
			</div>
		<?php endif; ?>
		<div class="software-copy reveal">
			<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
			<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
			<?php if ( $paragraph ) : ?><p><?php echo esc_html( $paragraph ); ?></p><?php endif; ?>
			<?php if ( $checks ) : ?>
				<div class="feature-checks">
					<?php foreach ( $checks as $check ) : ?>
						<div class="feature-check"><span>✓</span><div><strong><?php echo esc_html( $check['title'] ); ?></strong><p><?php echo esc_html( $check['description'] ?? '' ); ?></p></div></div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
