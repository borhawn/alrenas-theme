<?php
/**
 * Frontend wiring for the quote-request modal: hands the modal script its
 * AJAX endpoint + nonce. Asset registration/enqueueing itself lives in
 * inc/enqueue.php alongside the rest of the single-product bundle.
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
 * Localize the quote-request AJAX endpoint + nonce onto the modal script.
 */
function alrenas_localize_quote_modal_script() {
	if ( ! wp_script_is( 'alrenas-quote-modal', 'enqueued' ) ) {
		return;
	}

	wp_localize_script(
		'alrenas-quote-modal',
		'alrenasQuote',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'alrenas_quote_request' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'alrenas_localize_quote_modal_script', 20 );
