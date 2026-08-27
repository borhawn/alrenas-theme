<?php
/**
 * Customer quote-requested email (plain text).
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items         = $order->get_items();
$first_item    = $items ? reset( $items ) : null;
$customer_note = $order->get_customer_note();

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

if ( $order->get_billing_first_name() ) {
	/* translators: %s: Customer first name. */
	echo sprintf( esc_html__( 'Hi %s,', 'alrenas' ), esc_html( $order->get_billing_first_name() ) ) . "\n\n";
} else {
	echo esc_html__( 'Hi,', 'alrenas' ) . "\n\n";
}

if ( $first_item ) {
	/* translators: %s: Product name. */
	echo sprintf( esc_html__( 'Thanks for your interest in %s. We\'ve received your quote request and will follow up shortly with pricing tailored to your needs.', 'alrenas' ), esc_html( $first_item->get_name() ) ) . "\n\n";
} else {
	echo esc_html__( 'We\'ve received your quote request and will follow up shortly with pricing tailored to your needs.', 'alrenas' ) . "\n\n";
}

if ( $customer_note ) {
	echo esc_html__( 'What you told us:', 'alrenas' ) . "\n";
	echo esc_html( $customer_note ) . "\n\n";
}

if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) ) . "\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
