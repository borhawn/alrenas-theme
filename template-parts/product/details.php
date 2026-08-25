<?php
/**
 * Single-product technical-information accordion -- grouped automatically
 * from this product's WooCommerce attributes (Product data > Attributes).
 * Nothing here needs separate admin configuration.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id      = $product->get_id();
$groups          = alrenas_get_grouped_product_attributes( $product );
$eyebrow         = alrenas_get_site_content( 'sp_details_eyebrow', esc_html__( 'Technical information', 'alrenas' ) );
$heading         = alrenas_get_product_meta( $product_id, 'details_heading', esc_html__( 'Key specifications for clinical and procurement review.', 'alrenas' ) );
$lead            = alrenas_get_product_meta( $product_id, 'details_lead', esc_html__( 'Technical information stays available without dominating the rehabilitation story. Open the sections you need.', 'alrenas' ) );
$certifications_raw = alrenas_get_product_meta( $product_id, 'certifications', alrenas_get_site_content( 'sp_certifications', esc_html__( 'CE, UKCA, 2-year warranty', 'alrenas' ) ) );
$certifications  = array_filter( array_map( 'trim', explode( ',', $certifications_raw ) ) );
$empty_note      = alrenas_get_site_content( 'sp_details_note', esc_html__( 'Detailed specifications for this system are coming soon.', 'alrenas' ) );
?>
<section class="section product-details" id="details">
	<div class="container details-grid">
		<div class="details-copy reveal">
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<h2><?php echo esc_html( $heading ); ?></h2>
			<p><?php echo esc_html( $lead ); ?></p>
			<?php if ( $certifications ) : ?>
				<div class="certification-row">
					<?php foreach ( $certifications as $certification ) : ?><span><?php echo esc_html( $certification ); ?></span><?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $groups ) : ?>
			<div class="details-accordions reveal" data-accordions>
				<?php foreach ( $groups as $i => $group ) : ?>
					<article class="detail-item<?php echo 0 === $i ? ' is-open' : ''; ?>">
						<button type="button" class="detail-trigger" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>"><span><?php echo esc_html( $group['label'] ); ?></span><i></i></button>
						<div class="detail-content" <?php echo 0 === $i ? '' : 'hidden'; ?>>
							<dl class="spec-list">
								<?php foreach ( $group['specs'] as $spec ) : ?>
									<div><dt><?php echo esc_html( $spec['label'] ); ?></dt><dd><?php echo esc_html( $spec['value'] ); ?></dd></div>
								<?php endforeach; ?>
							</dl>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="details-empty-note"><?php echo esc_html( $empty_note ); ?></p>
		<?php endif; ?>
	</div>
</section>
