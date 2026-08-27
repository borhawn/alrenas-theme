<?php
/**
 * Customer quote-ready email (plain text).
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

if ( $order->get_billing_first_name() ) {
	/* translators: %s: Customer first name. */
	echo sprintf( esc_html__( 'Hi %s,', 'alrenas' ), esc_html( $order->get_billing_first_name() ) ) . "\n\n";
} else {
	echo esc_html__( 'Hi,', 'alrenas' ) . "\n\n";
}

echo esc_html__( 'Here\'s the quote you requested, itemized below.', 'alrenas' ) . "\n\n";

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

echo "\n----------------------------------------\n\n";

if ( ! empty( $quote_url ) ) {
	echo esc_html__( 'View and accept your quote here:', 'alrenas' ) . ' ' . esc_url( $quote_url ) . "\n\n";
}

if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) ) . "\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
