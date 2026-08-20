<?php
/**
 * Minimal WooCommerce theme support.
 *
 * Business and transactional behavior remains owned by WooCommerce.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function alrenas_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 480,
			'single_image_width'    => 900,
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'alrenas_woocommerce_setup' );

/**
 * Replace only WooCommerce's outer content wrappers with theme markup.
 *
 * All catalog, product, notice, breadcrumb, structured-data, sidebar, and
 * extension hooks remain in WooCommerce's own templates.
 */
function alrenas_woocommerce_wrappers() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

	add_action( 'woocommerce_before_main_content', 'alrenas_woocommerce_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'alrenas_woocommerce_wrapper_end', 10 );

	// The reference design has no shop sidebar. Other callbacks on this hook remain intact.
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}
add_action( 'wp', 'alrenas_woocommerce_wrappers' );

/**
 * Add the theme's plugin-controlled inquiry section after WooCommerce's own
 * tabs, upsells and related-product callbacks.
 */
function alrenas_product_inquiry() {
	get_template_part( 'template-parts/product/product-inquiry' );
}
add_action( 'woocommerce_after_single_product_summary', 'alrenas_product_inquiry', 30 );

/**
 * Open the theme's WooCommerce content wrapper.
 */
function alrenas_woocommerce_wrapper_start() {
	$classes = array( 'site-main', 'alrenas-woocommerce-main' );

	if ( function_exists( 'is_product' ) && is_product() ) {
		$classes[] = 'product-page';
	}

	printf(
		'<main id="primary" class="%s"><div class="container">',
		esc_attr( implode( ' ', $classes ) )
	);
}

/**
 * Close the theme's WooCommerce content wrapper.
 */
function alrenas_woocommerce_wrapper_end() {
	echo '</div></main>';
}

/**
 * WooCommerce forces its own archive-product.php for whatever page is set
 * as the Shop page in WooCommerce settings, overriding any custom page
 * template that page has assigned -- regardless of intent. Our Products
 * Landing page (page-products.php) happens to also be the configured Shop
 * page, so without this it silently gets replaced by the plain WooCommerce
 * product grid. Put our template back if that's the page being requested.
 *
 * @param string $template Template path WooCommerce/WordPress resolved.
 * @return string
 */
function alrenas_restore_products_landing_template( $template ) {
	if ( is_page() && 'page-products.php' === get_page_template_slug() ) {
		$theme_template = locate_template( 'page-products.php' );

		if ( $theme_template ) {
			return $theme_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'alrenas_restore_products_landing_template', 100 );
