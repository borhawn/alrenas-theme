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
 * Print Product + FAQPage structured data for single-product pages.
 */
function alrenas_output_product_schema() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	get_template_part( 'template-parts/product/schema' );
}
add_action( 'wp_head', 'alrenas_output_product_schema' );

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
