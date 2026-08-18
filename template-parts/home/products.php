<?php
/**
 * Homepage WooCommerce products.
 *
 * Product markup comes from WooCommerce's content-product template so core and
 * extension hooks continue to control images, titles, prices, ratings, sales,
 * add-to-cart behavior, and product visibility.
 *
 * @package Alrenas
 */

if ( ! function_exists( 'wc_get_products' ) || ! function_exists( 'wc_get_template_part' ) ) {
	return;
}

$product_ids = wc_get_products(
	array(
		'limit'      => 3,
		'status'     => 'publish',
		'visibility' => 'catalog',
		'orderby'    => 'date',
		'order'      => 'DESC',
		'return'     => 'ids',
	)
);

if ( ! $product_ids ) {
	return;
}

$products = new WP_Query(
	array(
		'post_type'              => 'product',
		'post_status'            => 'publish',
		'post__in'               => array_map( 'absint', $product_ids ),
		'orderby'                => 'post__in',
		'posts_per_page'         => 3,
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => true,
	)
);

if ( ! $products->have_posts() ) {
	return;
}

$shop_url = wc_get_page_permalink( 'shop' );

wc_setup_loop(
	array(
		'name'         => 'home-products',
		'columns'      => 3,
		'is_shortcode' => false,
		'is_paginated' => false,
		'total'        => $products->post_count,
		'total_pages'  => 1,
		'per_page'     => 3,
		'current_page' => 1,
	)
);
?>
<section class="section products home-products" id="products" aria-labelledby="home-products-title">
	<div class="container products-head reveal">
		<div>
			<span class="eyebrow"><?php esc_html_e( 'Rehabilitation systems', 'alrenas' ); ?></span>
			<h2 id="home-products-title"><?php esc_html_e( 'Purpose-built devices for balance, mobility and recovery.', 'alrenas' ); ?></h2>
		</div>
		<?php if ( $shop_url ) : ?>
			<a class="text-link" href="<?php echo esc_url( $shop_url ); ?>">
				<?php esc_html_e( 'View all products', 'alrenas' ); ?> <span aria-hidden="true">→</span>
			</a>
		<?php endif; ?>
	</div>

	<div class="container woocommerce">
		<?php woocommerce_product_loop_start(); ?>

		<?php while ( $products->have_posts() ) : ?>
			<?php $products->the_post(); ?>
			<?php do_action( 'woocommerce_shop_loop' ); ?>
			<?php wc_get_template_part( 'content', 'product' ); ?>
		<?php endwhile; ?>

		<?php woocommerce_product_loop_end(); ?>
	</div>
</section>
<?php
wp_reset_postdata();
woocommerce_reset_loop();

