<?php
/**
 * Single-product documentation download section.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$document_id = (int) alrenas_get_product_meta( $product->get_id(), 'document_id', 0 );

if ( ! $document_id ) {
	return;
}

$document_url = wp_get_attachment_url( $document_id );

if ( ! $document_url ) {
	return;
}

$kicker  = alrenas_get_site_content( 'sp_documentation_kicker', esc_html__( 'Product documentation', 'alrenas' ) );
$heading = alrenas_get_site_content( 'sp_documentation_heading', esc_html__( 'Need the complete product information?', 'alrenas' ) );
$lead    = alrenas_get_site_content( 'sp_documentation_lead', esc_html__( 'Download the current product document for technical review, internal evaluation or procurement discussions.', 'alrenas' ) );
$button  = alrenas_get_site_content( 'sp_documentation_button', esc_html__( 'Download Documentation', 'alrenas' ) );
?>
<section class="documentation-section">
	<div class="container documentation-card reveal">
		<div class="documentation-icon" aria-hidden="true">
			<svg viewBox="0 0 32 32"><path d="M9 4h10l5 5v19H9z"/><path d="M19 4v6h5M13 16h7M13 20h7"/></svg>
		</div>
		<div class="documentation-copy">
			<span class="product-kicker"><?php echo esc_html( $kicker ); ?></span>
			<h2><?php echo esc_html( $heading ); ?></h2>
			<p><?php echo esc_html( $lead ); ?></p>
		</div>
		<a class="btn btn-secondary" href="<?php echo esc_url( $document_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $button ); ?></a>
	</div>
</section>
