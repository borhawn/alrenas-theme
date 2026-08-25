<?php
/**
 * Single-product related-products section. Reuses the same 3 product
 * slots already configured under Site Content > Home — Products (product,
 * badge/kicker, description) rather than duplicating that data here --
 * just shows whichever of those slots isn't the product being viewed.
 *
 * @package Alrenas
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$current_id = $product->get_id();
$slots      = alrenas_get_site_content( 'home_products', array() );
$eyebrow    = alrenas_get_site_content( 'sp_related_eyebrow', esc_html__( 'Related rehabilitation systems', 'alrenas' ) );
$heading    = alrenas_get_product_meta( $current_id, 'related_heading' );
$link_label = alrenas_get_site_content( 'sp_related_link_label', esc_html__( 'Compare all systems', 'alrenas' ) );
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';

$related = array();

foreach ( $slots as $slot ) {
	$slot_id = isset( $slot['product_id'] ) ? (int) $slot['product_id'] : 0;

	if ( ! $slot_id || $slot_id === $current_id || 'publish' !== get_post_status( $slot_id ) ) {
		continue;
	}

	$related[] = array(
		'permalink'   => get_permalink( $slot_id ),
		'thumbnail'   => get_the_post_thumbnail( $slot_id, 'medium' ),
		'title'       => get_the_title( $slot_id ),
		'kicker'      => isset( $slot['kicker'] ) ? $slot['kicker'] : '',
		'description' => isset( $slot['description'] ) ? $slot['description'] : '',
	);

	if ( count( $related ) >= 2 ) {
		break;
	}
}

if ( ! $related ) {
	return;
}
?>
<section class="section related-products">
	<div class="container">
		<div class="related-head reveal">
			<div>
				<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<?php if ( $heading ) : ?><h2 style="margin-top:18px"><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
			</div>
			<?php if ( $shop_url ) : ?><a href="<?php echo esc_url( $shop_url ); ?>" class="text-link"><?php echo esc_html( $link_label ); ?> <span aria-hidden="true">→</span></a><?php endif; ?>
		</div>
		<div class="related-grid">
			<?php foreach ( $related as $item ) : ?>
				<article class="related-card reveal">
					<div class="related-card-media"><?php echo $item['thumbnail']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- from get_the_post_thumbnail(). ?></div>
					<div>
						<?php if ( $item['kicker'] ) : ?><span class="product-kicker"><?php echo esc_html( $item['kicker'] ); ?></span><?php endif; ?>
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<?php if ( $item['description'] ) : ?><p><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
						<a class="text-link" href="<?php echo esc_url( $item['permalink'] ); ?>"><?php esc_html_e( 'View system', 'alrenas' ); ?> <span aria-hidden="true">→</span></a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
