<?php
/**
 * Guest-friendly quote viewing and accept/decline handling.
 *
 * WooCommerce's own "view_order" capability only ever returns true for a
 * *logged-in* user whose ID matches the order's customer ID (see
 * wc_customer_has_capability() in WooCommerce core) -- there is no
 * built-in order-key bypass for guests on the My Account endpoints. Since
 * this is a B2B quote flow where forcing an account isn't acceptable,
 * the quote view lives on its own virtual, unauthenticated URL instead
 * of myaccount/view-order.php, secured the same way WooCommerce secures
 * its own guest order-pay/cancel links: a direct, timing-safe compare of
 * the order key via WC_Order::key_is_valid().
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
 * Build the guest-accessible URL for viewing/accepting/declining a quote.
 *
 * @param WC_Order $order The quote order.
 * @return string
 */
function alrenas_get_quote_view_url( WC_Order $order ) {
	return add_query_arg(
		array(
			'alrenas_quote' => $order->get_id(),
			'key'           => $order->get_order_key(),
		),
		home_url( '/quote-view/' )
	);
}

/**
 * Whether the current request is for the guest quote-view page.
 *
 * @return bool
 */
function alrenas_is_quote_view_request() {
	return isset( $_GET['alrenas_quote'], $_GET['key'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only, authenticated instead via WC_Order::key_is_valid() below.
}

/**
 * Resolve the current request's order + key into a validated WC_Order,
 * or null if it doesn't point at a real, matching quote order.
 *
 * @return WC_Order|null
 */
function alrenas_get_requested_quote_order() {
	if ( ! alrenas_is_quote_view_request() ) {
		return null;
	}

	$order_id = absint( $_GET['alrenas_quote'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
	$key      = sanitize_text_field( wp_unslash( $_GET['key'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$order    = wc_get_order( $order_id );

	if ( ! $order instanceof WC_Order || ! $order->key_is_valid( $key ) ) {
		return null;
	}

	if ( ! array_key_exists( $order->get_status(), alrenas_get_quote_statuses() ) ) {
		return null;
	}

	return $order;
}

/**
 * Render the guest quote-view page for a valid request, in place of
 * whatever page/post would otherwise have matched the URL.
 */
function alrenas_render_quote_view_page() {
	if ( ! alrenas_is_quote_view_request() ) {
		return;
	}

	$order = alrenas_get_requested_quote_order();

	if ( ! $order ) {
		wp_die( esc_html__( 'This quote link is invalid or has expired. Please contact us for an updated link.', 'alrenas' ), esc_html__( 'Quote not found', 'alrenas' ), array( 'response' => 404 ) );
	}

	status_header( 200 );
	nocache_headers();

	get_header();
	get_template_part( 'template-parts/quotes/view-order', null, array( 'order' => $order ) );
	get_footer();
	exit;
}
add_action( 'template_redirect', 'alrenas_render_quote_view_page' );

/**
 * Handle the customer clicking Accept or Decline on the quote-view page.
 */
function alrenas_handle_customer_quote_decision() {
	$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
	$key      = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';

	check_admin_referer( 'alrenas_quote_decision_' . $order_id, 'alrenas_quote_decision_nonce' );

	$order = wc_get_order( $order_id );

	if ( ! $order instanceof WC_Order || ! $order->key_is_valid( $key ) ) {
		wp_die( esc_html__( 'This quote link is invalid or has expired.', 'alrenas' ) );
	}

	if ( 'quote-sent' === $order->get_status() ) {
		// Not current_action() -- admin-post.php fires this callback via
		// "admin_post_{$action}"/"admin_post_nopriv_{$action}", so
		// current_action() always returns that prefixed hook name, never
		// the bare action value being compared against here.
		$submitted_action = isset( $_POST['action'] ) ? sanitize_key( wp_unslash( $_POST['action'] ) ) : '';
		$accepted         = 'alrenas_customer_accept_quote' === $submitted_action;
		$order->update_status(
			$accepted ? 'quote-accepted' : 'quote-declined',
			$accepted
				? esc_html__( 'Quote accepted by customer.', 'alrenas' )
				: esc_html__( 'Quote declined by customer.', 'alrenas' )
		);
	}

	wp_safe_redirect( alrenas_get_quote_view_url( $order ) );
	exit;
}
add_action( 'admin_post_alrenas_customer_accept_quote', 'alrenas_handle_customer_quote_decision' );
add_action( 'admin_post_nopriv_alrenas_customer_accept_quote', 'alrenas_handle_customer_quote_decision' );
add_action( 'admin_post_alrenas_customer_decline_quote', 'alrenas_handle_customer_quote_decision' );
add_action( 'admin_post_nopriv_alrenas_customer_decline_quote', 'alrenas_handle_customer_quote_decision' );
