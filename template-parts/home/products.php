<?php
/**
 * Homepage "Rehabilitation systems" section.
 *
 * Three admin-configured slots (Site Content > Home — Products Section),
 * each pointing at a WooCommerce product for its link + thumbnail, with
 * editable badge/kicker/description/tags to match the reference design.
 *
 * @package Alrenas
 */

$eyebrow      = alrenas_get_site_content( 'home_products_eyebrow', esc_html__( 'Rehabilitation systems', 'alrenas' ) );
$product_slots = alrenas_get_site_content( 'home_products', array() );

$variants = array(
	1 => array(
		'image_class' => 'product-image--blue',
		'reverse'     => false,
	),
	2 => array(
		'image_class' => 'product-image--mint',
		'reverse'     => true,
	),
	3 => array(
		'image_class' => 'product-image--warm',
		'reverse'     => false,
	),
);

$rows = array();

foreach ( $variants as $index => $variant ) {
	$slot = isset( $product_slots[ $index ] ) ? $product_slots[ $index ] : array();
	$product_id = isset( $slot['product_id'] ) ? (int) $slot['product_id'] : 0;

	if ( ! $product_id || 'publish' !== get_post_status( $product_id ) ) {
		continue;
	}

	$tags = isset( $slot['tags'] ) ? array_filter( array_map( 'trim', explode( ',', $slot['tags'] ) ) ) : array();

	$rows[] = array(
		'permalink'   => get_permalink( $product_id ),
		'thumbnail'   => get_the_post_thumbnail( $product_id, 'large' ),
		'title'       => get_the_title( $product_id ),
		'badge'       => isset( $slot['badge'] ) ? $slot['badge'] : '',
		'kicker'      => isset( $slot['kicker'] ) ? $slot['kicker'] : '',
		'description' => isset( $slot['description'] ) ? $slot['description'] : '',
		'tags'        => $tags,
		'image_class' => $variant['image_class'],
		'reverse'     => $variant['reverse'],
	);
}

if ( ! $rows ) {
	return;
}
?>
<section class="section products" id="products" aria-labelledby="home-products-title">
	<div class="container products-head reveal">
		<div>
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<h2 id="home-products-title"><?php esc_html_e( 'Purpose-built devices for balance, mobility and recovery.', 'alrenas' ); ?></h2>
		</div>
		<p><?php esc_html_e( 'Choose a system based on the patient group, treatment goals and level of support required.', 'alrenas' ); ?></p>
	</div>

	<div class="container product-stack">
		<?php foreach ( $rows as $row ) : ?>
			<article class="product-row<?php echo $row['reverse'] ? ' product-row--reverse' : ''; ?> reveal">
				<div class="product-image <?php echo esc_attr( $row['image_class'] ); ?>">
					<?php if ( $row['thumbnail'] ) : ?>
						<?php echo $row['thumbnail']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- from get_the_post_thumbnail(). ?>
					<?php endif; ?>
					<?php if ( $row['badge'] ) : ?>
						<span class="product-badge"><?php echo esc_html( $row['badge'] ); ?></span>
					<?php endif; ?>
				</div>
				<div class="product-content">
					<?php if ( $row['kicker'] ) : ?>
						<span class="product-kicker"><?php echo esc_html( $row['kicker'] ); ?></span>
					<?php endif; ?>
					<h3><?php echo esc_html( $row['title'] ); ?></h3>
					<?php if ( $row['description'] ) : ?>
						<p><?php echo esc_html( $row['description'] ); ?></p>
					<?php endif; ?>
					<?php if ( $row['tags'] ) : ?>
						<div class="product-tags">
							<?php foreach ( $row['tags'] as $tag ) : ?>
								<span><?php echo esc_html( $tag ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<a class="text-link" href="<?php echo esc_url( $row['permalink'] ); ?>">
						<?php esc_html_e( 'View product', 'alrenas' ); ?> <span aria-hidden="true">→</span>
					</a>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
