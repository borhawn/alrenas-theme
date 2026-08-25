<?php
/**
 * Product + FAQPage structured data for the single-product template,
 * built entirely from data already entered elsewhere (title, short
 * description, image, WooCommerce attributes, FAQ items) -- nothing new
 * to fill in. No price/Offer is included since these products are quoted,
 * not sold online.
 *
 * @package Alrenas
 */

// Runs from wp_head, before the main Loop populates the global $product
// via WooCommerce's the_post hook -- resolve it independently instead.
$product = wc_get_product( get_queried_object_id() );

if ( ! $product instanceof WC_Product ) {
	return;
}

$product_id = $product->get_id();
$image_id   = $product->get_image_id();

$schema = array(
	'@context'    => 'https://schema.org',
	'@type'       => 'Product',
	'name'        => $product->get_name(),
	'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
	'url'         => get_permalink( $product_id ),
	'brand'       => array(
		'@type' => 'Brand',
		'name'  => 'Alrenas',
	),
);

if ( $image_id ) {
	$image_url = wp_get_attachment_image_url( $image_id, 'large' );
	if ( $image_url ) {
		$schema['image'] = array( $image_url );
	}
}

$properties = array();
foreach ( alrenas_get_grouped_product_attributes( $product ) as $group ) {
	foreach ( $group['specs'] as $spec ) {
		$properties[] = array(
			'@type' => 'PropertyValue',
			'name'  => $spec['label'],
			'value' => $spec['value'],
		);
	}
}

if ( $properties ) {
	$schema['additionalProperty'] = $properties;
}

$faq_items = alrenas_get_product_faq_items( $product_id );
$faq_schema = null;

if ( $faq_items ) {
	$faq_schema = array(
		'@context'  => 'https://schema.org',
		'@type'     => 'FAQPage',
		'mainEntity' => array_map(
			function ( $item ) {
				return array(
					'@type'          => 'Question',
					'name'           => $item['question'],
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $item['answer'],
					),
				);
			},
			$faq_items
		),
	);
}
?>
<script type="application/ld+json"><?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON, not HTML. ?></script>
<?php if ( $faq_schema ) : ?>
<script type="application/ld+json"><?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON, not HTML. ?></script>
<?php endif; ?>
