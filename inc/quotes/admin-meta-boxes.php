<?php
/**
 * Order-edit-screen meta box giving the admin the two actions a quote
 * order needs beyond WooCommerce's own native order-editing UI (which
 * already covers line-item pricing, shipping, tax and fees/discounts --
 * including percentage fees/discounts via a trailing "%" in the native
 * "Add fee" amount field, so no custom pricing UI is needed here):
 *
 * - "Send Quote to Customer": quote-requested -> quote-sent, firing the
 *   itemized quote email.
 * - "Resend Quote Email": re-sends that same email without a status
 *   change, for when the admin edits pricing after already sending it.
 *
 * Both link to admin-post.php rather than hooking a post-save action, so
 * they behave identically whether HPOS or the legacy post-based order
 * storage is active. They're plain nonce-protected links, not a <form> --
 * a meta box renders inside WooCommerce's own order-edit <form>, and a
 * nested <form> is invalid HTML that browsers silently fold into the
 * outer one, so a real <form> here would submit as a normal order Save
 * instead of ever reaching admin-post.php.
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
 * Register the "Quote Actions" meta box on the order-edit screen,
 * targeting whichever screen ID is correct for the active order storage.
 */
function alrenas_register_quote_meta_box() {
	$screen = 'shop_order';

	if ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) ) {
		$screen = \Automattic\WooCommerce\Utilities\OrderUtil::get_order_admin_screen();
	}

	add_meta_box(
		'alrenas-quote-actions',
		esc_html__( 'Quote Actions', 'alrenas' ),
		'alrenas_render_quote_meta_box',
		$screen,
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'alrenas_register_quote_meta_box' );

/**
 * Render the meta box. wc_get_order() normalizes the post/order object
 * WooCommerce hands to add_meta_boxes callbacks under either storage mode.
 *
 * @param WP_Post|WC_Order $post_or_order_object The screen's post or order.
 */
function alrenas_render_quote_meta_box( $post_or_order_object ) {
	$order = wc_get_order( $post_or_order_object );

	if ( ! $order instanceof WC_Order ) {
		return;
	}

	$statuses = alrenas_get_quote_statuses();
	$status   = $order->get_status();

	if ( ! array_key_exists( $status, $statuses ) ) {
		echo '<p>' . esc_html__( 'This order did not originate from a quote request.', 'alrenas' ) . '</p>';
		return;
	}

	printf( '<p><strong>%s</strong></p>', esc_html( $statuses[ $status ] ) );

	$action = 'quote-requested' === $status ? 'alrenas_send_quote' : 'alrenas_resend_quote';
	$url    = wp_nonce_url(
		add_query_arg(
			array(
				'action'   => $action,
				'order_id' => $order->get_id(),
			),
			admin_url( 'admin-post.php' )
		),
		'alrenas_quote_action_' . $order->get_id(),
		'alrenas_quote_action_nonce'
	);
	?>
	<?php if ( 'quote-requested' === $status ) : ?>
		<p class="description"><?php esc_html_e( 'Price this order using the boxes below, then send it to the customer.', 'alrenas' ); ?></p>
		<a href="<?php echo esc_url( $url ); ?>" class="button button-primary" style="width:100%;text-align:center;"><?php esc_html_e( 'Send Quote to Customer', 'alrenas' ); ?></a>
	<?php else : ?>
		<p class="description"><?php esc_html_e( 'Edited the pricing since this was sent? Resend the quote email.', 'alrenas' ); ?></p>
		<a href="<?php echo esc_url( $url ); ?>" class="button" style="width:100%;text-align:center;"><?php esc_html_e( 'Resend Quote Email', 'alrenas' ); ?></a>
	<?php endif; ?>
	<?php
}

/**
 * Transition a quote-requested order to quote-sent, firing the itemized
 * quote email via the status-transition hook chain.
 */
function alrenas_handle_send_quote() {
	$order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
	check_admin_referer( 'alrenas_quote_action_' . $order_id, 'alrenas_quote_action_nonce' );

	if ( ! current_user_can( 'edit_shop_orders' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'alrenas' ) );
	}

	$order = wc_get_order( $order_id );

	if ( $order instanceof WC_Order && 'quote-requested' === $order->get_status() ) {
		$order->update_status( 'quote-sent', esc_html__( 'Quote sent to customer.', 'alrenas' ) );
	}

	wp_safe_redirect( $order instanceof WC_Order ? $order->get_edit_order_url() : admin_url() );
	exit;
}
add_action( 'admin_post_alrenas_send_quote', 'alrenas_handle_send_quote' );

/**
 * Re-send the "quote ready" email without changing the order's status --
 * useful after editing pricing on an already-sent quote.
 */
function alrenas_handle_resend_quote() {
	$order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
	check_admin_referer( 'alrenas_quote_action_' . $order_id, 'alrenas_quote_action_nonce' );

	if ( ! current_user_can( 'edit_shop_orders' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'alrenas' ) );
	}

	$order = wc_get_order( $order_id );

	if ( $order instanceof WC_Order ) {
		do_action( 'woocommerce_order_status_quote-sent_notification', $order_id, $order );
		$order->add_order_note( esc_html__( 'Quote email resent to customer.', 'alrenas' ) );
	}

	wp_safe_redirect( $order instanceof WC_Order ? $order->get_edit_order_url() : admin_url() );
	exit;
}
add_action( 'admin_post_alrenas_resend_quote', 'alrenas_handle_resend_quote' );
