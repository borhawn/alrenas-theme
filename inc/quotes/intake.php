<?php
/**
 * Quote request intake: turns a "Get a Quote" modal submission into a
 * WooCommerce order sitting in the quote-requested status, ready for an
 * admin to price up on the native order-edit screen.
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
 * Handle the AJAX quote-request submission from the single-product modal.
 */
function alrenas_handle_quote_request() {
	check_ajax_referer( 'alrenas_quote_request', 'nonce' );

	// Honeypot: a hidden field real visitors never see or fill in. Bots
	// that fill every field get a fake success instead of a clue.
	if ( ! empty( $_POST['alrenas_quote_website'] ) ) {
		wp_send_json_success( array( 'message' => esc_html__( 'Thanks -- we will be in touch shortly.', 'alrenas' ) ) );
	}

	$name       = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email      = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone      = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$company    = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
	$notes      = isset( $_POST['notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['notes'] ) ) : '';
	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$quantity   = isset( $_POST['quantity'] ) ? min( 999, max( 1, absint( $_POST['quantity'] ) ) ) : 1;

	if ( '' === $name || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please share your name and a valid email address.', 'alrenas' ) ) );
	}

	$product = $product_id ? wc_get_product( $product_id ) : false;

	if ( ! $product instanceof WC_Product || 'publish' !== get_post_status( $product_id ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'That product could not be found.', 'alrenas' ) ) );
	}

	$name_parts = explode( ' ', $name, 2 );
	$first_name = $name_parts[0];
	$last_name  = isset( $name_parts[1] ) ? $name_parts[1] : '';

	$order = wc_create_order();

	if ( is_wp_error( $order ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Something went wrong on our end. Please try again.', 'alrenas' ) ) );
	}

	$order->set_billing_first_name( $first_name );
	$order->set_billing_last_name( $last_name );
	$order->set_billing_email( $email );
	$order->set_billing_phone( $phone );
	$order->set_billing_company( $company );
	$order->set_created_via( 'alrenas_quote' );
	$order->add_product( $product, $quantity );

	if ( $notes ) {
		$order->set_customer_note( $notes );
	}

	$order->calculate_totals();
	$order->save();

	// update_status() (not the bare set_status() setter) is what fires
	// the transition hooks the quote-requested emails are bound to.
	$order->update_status( 'quote-requested', esc_html__( 'Quote request submitted by customer.', 'alrenas' ) );

	wp_send_json_success(
		array(
			'message' => esc_html__( 'Thanks -- your request has been received. We will follow up shortly with pricing.', 'alrenas' ),
		)
	);
}
add_action( 'wp_ajax_alrenas_submit_quote_request', 'alrenas_handle_quote_request' );
add_action( 'wp_ajax_nopriv_alrenas_submit_quote_request', 'alrenas_handle_quote_request' );
