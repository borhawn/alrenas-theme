<?php
/**
 * Single-product documentation section. Shows a download button when a
 * document is uploaded, or a "Request Documentation" button linking to
 * the inquiry form when the section has copy but no file yet (e.g. when
 * the current file doesn't actually match this product -- see the Al
 * Balance Dyn note in the reference source notes).
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id  = $product->get_id();
$document_id = (int) alrenas_get_product_meta( $product_id, 'document_id', 0 );
$kicker      = alrenas_get_product_meta( $product_id, 'documentation_kicker' );
$heading     = alrenas_get_product_meta( $product_id, 'documentation_heading' );
$lead        = alrenas_get_product_meta( $product_id, 'documentation_lead' );

if ( ! $document_id && ! $heading ) {
	return;
}

$document_url = $document_id ? wp_get_attachment_url( $document_id ) : '';

if ( $document_url ) {
	$button_label = alrenas_get_site_content( 'sp_documentation_download_label', esc_html__( 'Download Documentation', 'alrenas' ) );
	$button_url   = $document_url;
	$button_attrs = 'target="_blank" rel="noopener"';
} else {
	$button_label = alrenas_get_site_content( 'sp_documentation_request_label', esc_html__( 'Request Documentation', 'alrenas' ) );
	$button_url   = '#inquiry';
	$button_attrs = 'data-inquiry-intent="quote"';
}
?>
<section class="documentation-section">
	<div class="container documentation-card reveal">
		<div class="documentation-icon" aria-hidden="true">
			<svg viewBox="0 0 32 32"><path d="M9 4h10l5 5v19H9z"/><path d="M19 4v6h5M13 16h7M13 20h7"/></svg>
		</div>
		<div class="documentation-copy">
			<?php if ( $kicker ) : ?><span class="product-kicker"><?php echo esc_html( $kicker ); ?></span><?php endif; ?>
			<h2><?php echo esc_html( $heading ); ?></h2>
			<?php if ( $lead ) : ?><p><?php echo esc_html( $lead ); ?></p><?php endif; ?>
		</div>
		<a class="btn btn-secondary" href="<?php echo esc_url( $button_url ); ?>" <?php echo $button_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute string, not user input. ?>><?php echo esc_html( $button_label ); ?></a>
	</div>
</section>
