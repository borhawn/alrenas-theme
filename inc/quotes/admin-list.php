<?php
/**
 * A "WooCommerce -> Quotes" submenu entry. Rather than a separate admin
 * screen, it simply redirects to the native order list pre-filtered to
 * quote-requested orders -- the status that actually needs the admin's
 * attention. The other quote statuses remain one click away via the
 * order list's own status tabs, which already include them (see
 * inc/quotes/statuses.php's show_in_admin_status_list registration).
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Add the "Quotes" entry under the WooCommerce admin menu.
 */
function alrenas_register_quotes_menu() {
	add_submenu_page(
		'woocommerce',
		esc_html__( 'Quotes', 'alrenas' ),
		esc_html__( 'Quotes', 'alrenas' ),
		'edit_shop_orders',
		'alrenas-quotes',
		'alrenas_redirect_to_quotes_list'
	);
}
add_action( 'admin_menu', 'alrenas_register_quotes_menu', 20 );

/**
 * Redirect to the order list, filtered to quote-requested orders, using
 * whichever URL shape matches the active order storage mode.
 */
function alrenas_redirect_to_quotes_list() {
	$hpos = class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' )
		&& \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();

	$url = $hpos
		? admin_url( 'admin.php?page=wc-orders&status=wc-quote-requested' )
		: admin_url( 'edit.php?post_type=shop_order&post_status=wc-quote-requested' );

	wp_safe_redirect( $url );
	exit;
}
