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
 * Count orders still sitting in quote-requested -- submitted by a
 * customer but not yet priced/sent, i.e. the ones actually waiting on
 * the admin. wc_get_orders() is used (rather than the newer
 * wc_orders_count()) since it's the longest-standing, most reliably
 * available API for this and already works transparently whether or
 * not HPOS is enabled.
 *
 * @return int
 */
function alrenas_count_unattended_quotes() {
	static $count = null;

	if ( null === $count ) {
		$order_ids = wc_get_orders(
			array(
				'status' => 'quote-requested',
				'type'   => 'shop_order',
				'limit'  => -1,
				'return' => 'ids',
			)
		);

		$count = is_array( $order_ids ) ? count( $order_ids ) : 0;
	}

	return $count;
}

/**
 * Build the little red "count" bubble WordPress core uses for pending
 * plugin/theme updates, so an unattended-quote count reads the same way.
 *
 * @param int $count Number to display.
 * @return string
 */
function alrenas_quote_count_bubble( $count ) {
	return sprintf(
		' <span class="update-plugins count-%1$d"><span class="update-count">%2$s</span></span>',
		absint( $count ),
		esc_html( number_format_i18n( $count ) )
	);
}

/**
 * Add the "Quotes" entry under the WooCommerce admin menu, with a count
 * bubble on its label when there are unattended requests.
 */
function alrenas_register_quotes_menu() {
	$count      = alrenas_count_unattended_quotes();
	$menu_title = esc_html__( 'Quotes', 'alrenas' );

	if ( $count > 0 ) {
		$menu_title .= alrenas_quote_count_bubble( $count );
	}

	add_submenu_page(
		'woocommerce',
		esc_html__( 'Quotes', 'alrenas' ),
		$menu_title,
		'edit_shop_orders',
		'alrenas-quotes',
		'alrenas_redirect_to_quotes_list'
	);
}
add_action( 'admin_menu', 'alrenas_register_quotes_menu', 20 );

/**
 * Also bubble the same count onto the top-level WooCommerce menu item,
 * matching how core bubbles update counts onto "Plugins" -- visible
 * without expanding the submenu. Runs late so WooCommerce has already
 * registered its top-level menu entry by the time this looks for it.
 */
function alrenas_bubble_quotes_count_on_woocommerce_menu() {
	global $menu;

	if ( ! is_array( $menu ) ) {
		return;
	}

	$count = alrenas_count_unattended_quotes();

	if ( ! $count ) {
		return;
	}

	foreach ( $menu as $key => $item ) {
		if ( isset( $item[2] ) && 'woocommerce' === $item[2] ) {
			$menu[ $key ][0] .= alrenas_quote_count_bubble( $count );
			break;
		}
	}
}
add_action( 'admin_menu', 'alrenas_bubble_quotes_count_on_woocommerce_menu', 999 );

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
