<?php
/**
 * Single-product intro (eyebrow/heading/paragraph, per-product, reusing
 * the workflow section's own text since it plays the same "orient the
 * reader" role immediately before the workflow tabs).
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$eyebrow = alrenas_get_product_meta( $product->get_id(), 'workflow_eyebrow' );
$heading = alrenas_get_product_meta( $product->get_id(), 'workflow_heading' );
$lead    = $product->get_description();
$link_label = alrenas_get_site_content( 'sp_intro_link_label', esc_html__( 'Explore the clinical workflow', 'alrenas' ) );

if ( ! $eyebrow && ! $heading && ! $lead ) {
	return;
}
?>
<section class="section product-intro">
	<div class="container product-intro-grid">
		<div class="section-heading reveal">
			<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
			<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
		</div>
		<div class="product-intro-copy reveal">
			<?php if ( $lead ) : ?><p><?php echo wp_kses_post( wpautop( $lead ) ); ?></p><?php endif; ?>
			<a href="#clinical-workflow" class="text-link"><?php echo esc_html( $link_label ); ?> <span aria-hidden="true">→</span></a>
		</div>
	</div>
</section>
