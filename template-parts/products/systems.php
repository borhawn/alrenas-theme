<?php
/**
 * Products-page "systems" list — reuses the same 3 product slots configured
 * under Site Content > Home — Products (product, badge, kicker,
 * description, tags), plus page-specific capabilities configured under
 * Site Content > Products Page.
 *
 * @package Alrenas
 */

if ( ! function_exists( 'wc_get_product' ) ) {
	return;
}

$eyebrow = alrenas_get_site_content( 'products_systems_eyebrow', esc_html__( 'Rehabilitation pathways', 'alrenas' ) );
$heading = alrenas_get_site_content( 'products_systems_heading', esc_html__( 'From objective assessment to supported standing.', 'alrenas' ) );
$lead    = alrenas_get_site_content( 'products_systems_lead', esc_html__( 'Each system answers a different clinical need while sharing a focus on safe progression, feedback and measurable rehabilitation.', 'alrenas' ) );

$product_slots = alrenas_get_site_content( 'home_products', array() );
$capabilities_by_slot = alrenas_get_site_content( 'product_capabilities', array() );

$rows = array();

for ( $index = 1; $index <= ALRENAS_PRODUCTS_SYSTEM_COUNT; $index++ ) {
	$slot       = isset( $product_slots[ $index ] ) ? $product_slots[ $index ] : array();
	$product_id = isset( $slot['product_id'] ) ? (int) $slot['product_id'] : 0;

	if ( ! $product_id || 'publish' !== get_post_status( $product_id ) ) {
		continue;
	}

	$capabilities = isset( $capabilities_by_slot[ $index ] )
		? array_filter( array_map( 'trim', explode( ',', $capabilities_by_slot[ $index ] ) ) )
		: array();

	$rows[] = array(
		'permalink'    => get_permalink( $product_id ),
		'thumbnail'    => get_the_post_thumbnail( $product_id, 'large' ),
		'title'        => get_the_title( $product_id ),
		'badge'        => isset( $slot['badge'] ) ? $slot['badge'] : '',
		'kicker'       => isset( $slot['kicker'] ) ? $slot['kicker'] : '',
		'description'  => isset( $slot['description'] ) ? $slot['description'] : '',
		'tags'         => isset( $slot['tags'] ) ? array_filter( array_map( 'trim', explode( ',', $slot['tags'] ) ) ) : array(),
		'capabilities' => $capabilities,
	);
}

if ( ! $rows ) {
	return;
}
?>
<section class="section solutions" id="systems"><div class="container"><div class="solutions-head reveal"><div><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><h2><?php echo esc_html( $heading ); ?></h2></div><p><?php echo esc_html( $lead ); ?></p></div><div class="solution-list">
	<?php foreach ( $rows as $row ) : ?>
		<article class="solution-card reveal"><div class="solution-media"><?php if ( $row['badge'] ) : ?><span class="solution-label"><?php echo esc_html( $row['badge'] ); ?></span><?php endif; ?><?php echo $row['thumbnail']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- from get_the_post_thumbnail(). ?></div><div class="solution-copy"><?php if ( $row['kicker'] ) : ?><span class="kicker"><?php echo esc_html( $row['kicker'] ); ?></span><?php endif; ?><h3><?php echo esc_html( $row['title'] ); ?></h3><?php if ( $row['description'] ) : ?><p><?php echo esc_html( $row['description'] ); ?></p><?php endif; ?><?php if ( $row['capabilities'] ) : ?><div class="solution-capabilities"><?php foreach ( $row['capabilities'] as $capability ) : ?><span><?php echo esc_html( $capability ); ?></span><?php endforeach; ?></div><?php endif; ?><?php if ( $row['tags'] ) : ?><div class="chips" style="margin-top:18px"><?php foreach ( $row['tags'] as $tag ) : ?><span class="chip"><?php echo esc_html( $tag ); ?></span><?php endforeach; ?></div><?php endif; ?><div class="solution-actions"><a href="<?php echo esc_url( $row['permalink'] ); ?>" class="btn btn-primary stretched-link"><?php esc_html_e( 'View System', 'alrenas' ); ?></a><a href="<?php echo esc_url( $row['permalink'] . '#get-a-quote' ); ?>" class="text-link solution-quote-link"><?php esc_html_e( 'Request a quote', 'alrenas' ); ?> <span aria-hidden="true">→</span></a></div></div></article>
	<?php endforeach; ?>
</div></div></section>
