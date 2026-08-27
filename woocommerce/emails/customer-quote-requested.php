<?php
/**
 * Customer quote-requested email.
 *
 * No pricing exists yet at this stage, so this deliberately doesn't show
 * WooCommerce's line-item/price table -- just a confirmation of what was
 * asked for, to avoid implying a price before the admin has set one.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items           = $order->get_items();
$first_item      = $items ? reset( $items ) : null;
$customer_note   = $order->get_customer_note();
$product_summary = '';

if ( $first_item ) {
	$qty             = $first_item->get_quantity();
	$product_summary = $qty > 1 ? $qty . ' × ' . $first_item->get_name() : $first_item->get_name();
}

/* This action is documented in woocommerce/templates/emails/customer-processing-order.php */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<p>
<?php
if ( $order->get_billing_first_name() ) {
	/* translators: %s: Customer first name. */
	printf( esc_html__( 'Hi %s,', 'alrenas' ), esc_html( $order->get_billing_first_name() ) );
} else {
	esc_html_e( 'Hi,', 'alrenas' );
}
?>
</p>

<p>
<?php
if ( $product_summary ) {
	/* translators: %s: Product name, optionally prefixed with a quantity. */
	printf( esc_html__( 'Thanks for your interest in %s. We\'ve received your quote request and will follow up shortly with pricing tailored to your needs.', 'alrenas' ), esc_html( $product_summary ) );
} else {
	esc_html_e( 'We\'ve received your quote request and will follow up shortly with pricing tailored to your needs.', 'alrenas' );
}
?>
</p>

<?php if ( $customer_note ) : ?>
	<div class="email-note-box">
		<strong><?php esc_html_e( 'What you told us:', 'alrenas' ); ?></strong>
		<p style="margin:8px 0 0;"><?php echo wp_kses_post( nl2br( esc_html( $customer_note ) ) ); ?></p>
	</div>
<?php endif; ?>

<?php if ( $additional_content ) : ?>
	<p><?php echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) ); ?></p>
<?php endif; ?>

<?php
/* This action is documented in woocommerce/templates/emails/customer-processing-order.php */
do_action( 'woocommerce_email_footer', $email );
