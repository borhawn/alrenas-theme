<?php
/**
 * Disable comments (and WooCommerce product reviews, which use the same
 * comment system) sitewide for posts and products.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Force comments/pings closed for posts and products, regardless of each
 * post's own Discussion setting.
 */
function alrenas_force_comments_closed( $open, $post_id ) {
	$post_type = get_post_type( $post_id );

	if ( in_array( $post_type, array( 'post', 'product' ), true ) ) {
		return false;
	}

	return $open;
}
add_filter( 'comments_open', 'alrenas_force_comments_closed', 20, 2 );
add_filter( 'pings_open', 'alrenas_force_comments_closed', 20, 2 );

/**
 * Drop comment/trackback support from the post types themselves, so new
 * posts/products default to closed and the Discussion meta box on new
 * drafts isn't misleading.
 */
function alrenas_remove_comment_support() {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'post', 'trackbacks' );
	remove_post_type_support( 'product', 'comments' );
	remove_post_type_support( 'product', 'trackbacks' );
}
add_action( 'init', 'alrenas_remove_comment_support', 20 );

/**
 * Remove the WooCommerce Reviews tab outright (belt and suspenders on top
 * of comments_open() being forced false above).
 */
function alrenas_remove_product_reviews_tab( $tabs ) {
	unset( $tabs['reviews'] );

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'alrenas_remove_product_reviews_tab', 98 );

/**
 * Hide the Comments admin menu and dashboard widget.
 */
function alrenas_hide_comments_admin_ui() {
	remove_menu_page( 'edit-comments.php' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}
add_action( 'admin_menu', 'alrenas_hide_comments_admin_ui' );

/**
 * Remove the Comments meta box from the post/product editor.
 */
function alrenas_remove_comments_meta_box() {
	remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
	remove_meta_box( 'commentsdiv', 'post', 'normal' );
	remove_meta_box( 'commentstatusdiv', 'product', 'normal' );
	remove_meta_box( 'commentsdiv', 'product', 'normal' );
}
add_action( 'admin_menu', 'alrenas_remove_comments_meta_box' );

/**
 * Remove the Comments bubble from the admin bar.
 */
function alrenas_remove_admin_bar_comments( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'comments' );
}
add_action( 'admin_bar_menu', 'alrenas_remove_admin_bar_comments', 999 );
