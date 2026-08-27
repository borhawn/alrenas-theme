<?php
/**
 * Custom WooCommerce order statuses backing the quote lifecycle:
 * quote-requested -> quote-sent -> quote-accepted / quote-declined.
 *
 * Built on WC_Order rather than a bespoke CPT so line items, taxes,
 * fees/discounts, guest access and the native order-edit screen all
 * come for free. These statuses never represent a paid/fulfilled order,
 * so they're explicitly kept out of every "paid"/"valid for payment"
 * status list WooCommerce exposes.
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
 * Canonical slug => label map for the quote statuses, reused by every
 * other quotes/*.php file instead of repeating the list.
 *
 * @return array<string,string>
 */
function alrenas_get_quote_statuses() {
	return array(
		'quote-requested' => esc_html__( 'Quote Requested', 'alrenas' ),
		'quote-sent'      => esc_html__( 'Quote Sent', 'alrenas' ),
		'quote-accepted'  => esc_html__( 'Quote Accepted', 'alrenas' ),
		'quote-declined'  => esc_html__( 'Quote Declined', 'alrenas' ),
	);
}

/**
 * Register each quote status as a first-class order post status.
 */
function alrenas_register_quote_order_statuses() {
	foreach ( alrenas_get_quote_statuses() as $slug => $label ) {
		register_post_status(
			'wc-' . $slug,
			array(
				'label'                     => $label,
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: number of orders in this status. */
				'label_count'               => _n_noop( $label . ' <span class="count">(%s)</span>', $label . ' <span class="count">(%s)</span>', 'alrenas' ),
			)
		);
	}
}
add_action( 'init', 'alrenas_register_quote_order_statuses' );

/**
 * Surface the quote statuses in WooCommerce's own status list so they
 * appear in the order-edit status dropdown, order list filters, etc.
 *
 * @param array $order_statuses Existing wc- prefixed status => label map.
 * @return array
 */
function alrenas_add_quote_order_statuses( $order_statuses ) {
	foreach ( alrenas_get_quote_statuses() as $slug => $label ) {
		$order_statuses[ 'wc-' . $slug ] = $label;
	}
	return $order_statuses;
}
add_filter( 'wc_order_statuses', 'alrenas_add_quote_order_statuses' );

/**
 * Quote statuses are never "paid" or valid targets for payment -- a
 * quote is a pre-sale conversation, not a fulfilled order. Stripping
 * them here means nothing in WooCommerce core or another plugin will
 * ever treat reaching one of these statuses as a completed payment.
 *
 * @param array $statuses Unprefixed status slugs.
 * @return array
 */
function alrenas_exclude_quote_statuses_from_payment( $statuses ) {
	return array_values( array_diff( $statuses, array_keys( alrenas_get_quote_statuses() ) ) );
}
add_filter( 'woocommerce_valid_order_statuses_for_payment', 'alrenas_exclude_quote_statuses_from_payment' );
add_filter( 'woocommerce_order_is_paid_statuses', 'alrenas_exclude_quote_statuses_from_payment' );

/**
 * Bridge the quote status transitions into WooCommerce's transactional
 * email pipeline. WC_Emails only rebroadcasts a plain
 * "woocommerce_order_status_{to}" hook as the "_notification" suffixed
 * hook that WC_Email subclasses actually bind to (see
 * WC_Emails::init_transactional_emails()) for hook names it already
 * knows about -- so without this filter, none of the quote emails
 * registered in inc/quotes/emails.php would ever fire.
 *
 * @param array $actions Plain hook names that trigger transactional emails.
 * @return array
 */
function alrenas_register_quote_email_actions( $actions ) {
	foreach ( array_keys( alrenas_get_quote_statuses() ) as $slug ) {
		$actions[] = 'woocommerce_order_status_' . $slug;
	}
	return $actions;
}
add_filter( 'woocommerce_email_actions', 'alrenas_register_quote_email_actions' );
