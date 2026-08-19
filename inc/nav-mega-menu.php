<?php
/**
 * Product mega-menu for the primary navigation.
 *
 * No separate admin screen needed: an editor just adds normal sub-menu
 * items under "Products" (or any top-level item) in Appearance > Menus,
 * linking each to an actual product page. Any sub-item whose URL resolves
 * to a published WooCommerce product automatically gets that product's
 * thumbnail prepended to its title and a marker class, so CSS can render
 * the dropdown as an image grid instead of a plain text list.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve a nav menu item to a WooCommerce product, if its URL points to one.
 *
 * @param WP_Post $item Menu item.
 * @return WC_Product|null
 */
function alrenas_resolve_menu_item_product( $item ) {
	static $cache = array();

	if ( isset( $cache[ $item->ID ] ) ) {
		return $cache[ $item->ID ];
	}

	if ( ! function_exists( 'wc_get_product' ) || empty( $item->url ) ) {
		$cache[ $item->ID ] = null;
		return null;
	}

	$post_id = url_to_postid( $item->url );

	if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
		$cache[ $item->ID ] = null;
		return null;
	}

	$product = wc_get_product( $post_id );

	$cache[ $item->ID ] = ( $product instanceof WC_Product ) ? $product : null;

	return $cache[ $item->ID ];
}

/**
 * Prepend a product thumbnail to sub-menu items that link to a product.
 *
 * @param string   $title Menu item title (HTML).
 * @param WP_Post  $item  Menu item.
 * @param stdClass $args  Menu args.
 * @param int      $depth Current depth (0 = top level).
 * @return string
 */
function alrenas_nav_menu_item_title( $title, $item, $args, $depth ) {
	if ( 'primary' !== $args->theme_location || 0 === $depth ) {
		return $title;
	}

	$product = alrenas_resolve_menu_item_product( $item );

	if ( ! $product ) {
		return $title;
	}

	$image = $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'menu-product-thumb-img' ) );

	return '<span class="menu-product-thumb">' . wp_kses_post( $image ) . '</span><span class="menu-product-name">' . esc_html( $title ) . '</span>';
}
add_filter( 'nav_menu_item_title', 'alrenas_nav_menu_item_title', 10, 4 );

/**
 * Mark sub-menu items that resolve to a product, so CSS can grid/image-style
 * just those dropdowns instead of every submenu.
 *
 * @param array    $classes CSS classes.
 * @param WP_Post  $item    Menu item.
 * @param stdClass $args    Menu args.
 * @param int      $depth   Current depth.
 * @return array
 */
function alrenas_nav_menu_css_class( $classes, $item, $args, $depth ) {
	if ( 'primary' === $args->theme_location && $depth > 0 && alrenas_resolve_menu_item_product( $item ) ) {
		$classes[] = 'menu-item-product';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'alrenas_nav_menu_css_class', 10, 4 );
