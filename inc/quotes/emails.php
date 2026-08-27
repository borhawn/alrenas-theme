<?php
/**
 * Registers the quote-lifecycle transactional emails with WooCommerce and
 * makes every WooCommerce order email (not just the quote ones) show a
 * product thumbnail in its line-item table.
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
 * Register the 5 quote WC_Email subclasses.
 *
 * @param array $email_classes Existing id => WC_Email instance map.
 * @return array
 */
function alrenas_register_quote_email_classes( $email_classes ) {
	$dir = get_theme_file_path( 'inc/quotes/emails/' );

	$classes = array(
		'alrenas_customer_quote_requested'  => 'class-wc-email-customer-quote-requested.php',
		'alrenas_admin_new_quote_request'   => 'class-wc-email-admin-new-quote-request.php',
		'alrenas_customer_quote_ready'      => 'class-wc-email-customer-quote-ready.php',
		'alrenas_admin_quote_accepted'      => 'class-wc-email-admin-quote-accepted.php',
		'alrenas_admin_quote_declined'      => 'class-wc-email-admin-quote-declined.php',
	);

	foreach ( $classes as $id => $file ) {
		$email_classes[ $id ] = include $dir . $file;
	}

	return $email_classes;
}
add_filter( 'woocommerce_email_classes', 'alrenas_register_quote_email_classes' );

/**
 * Show a product thumbnail in every WooCommerce order email's line-item
 * table (quote emails included) -- off by default in WooCommerce unless
 * the newer "email improvements" feature is active, and the user
 * explicitly wants quotes to show product images.
 *
 * @param array $args Args passed to emails/email-order-items.php.
 * @return array
 */
function alrenas_email_order_items_show_image( $args ) {
	$args['show_image'] = true;
	$args['image_size'] = array( 64, 64 );
	return $args;
}
add_filter( 'woocommerce_email_order_items_args', 'alrenas_email_order_items_show_image' );
