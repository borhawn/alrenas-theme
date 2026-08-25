<?php
/**
 * Single-product FAQ section. Question/answer pairs are admin-managed,
 * add/remove-able, per product. The same data also powers the FAQPage
 * structured data printed in <head> (see template-parts/product/schema.php)
 * so there's only one place to maintain the answers.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id = $product->get_id();
$heading    = alrenas_get_product_meta( $product_id, 'faq_heading' );
$eyebrow    = alrenas_get_site_content( 'sp_faq_eyebrow', esc_html__( 'Frequently asked questions', 'alrenas' ) );
$items      = alrenas_get_product_faq_items( $product_id );

if ( ! $items ) {
	return;
}
?>
<section class="section faq-section">
	<div class="container faq-grid">
		<div class="faq-copy reveal">
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<?php if ( $heading ) : ?><h2><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
		</div>
		<div class="faq-list reveal" data-faq>
			<?php foreach ( $items as $i => $item ) : ?>
				<article class="faq-item<?php echo 0 === $i ? ' is-open' : ''; ?>">
					<button type="button" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>"><span><?php echo esc_html( $item['question'] ); ?></span><i></i></button>
					<div class="faq-answer" <?php echo 0 === $i ? '' : 'hidden'; ?>><p><?php echo esc_html( $item['answer'] ); ?></p></div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
