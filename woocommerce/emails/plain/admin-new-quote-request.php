<?php
/**
 * Admin new-quote-request notification email (plain text).
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

/* translators: %s: Customer billing full name. */
echo sprintf( esc_html__( '%s just submitted a quote request.', 'alrenas' ), esc_html( $order->get_formatted_billing_full_name() ) ) . "\n\n";

if ( $first_item ) {
	echo esc_html__( 'Product:', 'alrenas' ) . ' ' . esc_html( $first_item->get_name() ) . "\n";
}
echo esc_html__( 'Email:', 'alrenas' ) . ' ' . esc_html( $order->get_billing_email() ) . "\n";
if ( $order->get_billing_phone() ) {
	echo esc_html__( 'Phone:', 'alrenas' ) . ' ' . esc_html( $order->get_billing_phone() ) . "\n";
}
if ( $order->get_billing_company() ) {
	echo esc_html__( 'Company/Clinic:', 'alrenas' ) . ' ' . esc_html( $order->get_billing_company() ) . "\n";
}
echo "\n";

if ( $customer_note ) {
	echo esc_html__( 'Notes from the customer:', 'alrenas' ) . "\n";
	echo esc_html( $customer_note ) . "\n\n";
}

echo esc_html__( 'View & price this quote:', 'alrenas' ) . ' ' . esc_url( $order->get_edit_order_url() ) . "\n\n";

if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) ) . "\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
