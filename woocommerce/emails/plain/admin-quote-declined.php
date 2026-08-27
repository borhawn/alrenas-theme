<?php
/**
 * Admin quote-declined notification email (plain text).
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items      = $order->get_items();
$first_item = $items ? reset( $items ) : null;

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/* translators: 1: Customer billing full name, 2: order number. */
echo sprintf(
	esc_html__( '%1$s declined quote #%2$s.', 'alrenas' ),
	esc_html( $order->get_formatted_billing_full_name() ),
	esc_html( $order->get_order_number() )
) . "\n\n";

if ( $first_item ) {
	echo esc_html__( 'Product:', 'alrenas' ) . ' ' . esc_html( $first_item->get_name() ) . "\n\n";
}

echo esc_html__( 'View order:', 'alrenas' ) . ' ' . esc_url( $order->get_edit_order_url() ) . "\n\n";

if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) ) . "\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
